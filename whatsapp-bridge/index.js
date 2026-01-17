const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason
} = require('@whiskeysockets/baileys');
const express = require('express');
const QRCode = require('qrcode');
const path = require('path');
const pino = require('pino');
const fs = require('fs');

const app = express();
app.use(express.json());

const PORT = process.env.PORT || 3000;
const SECURITY_TOKEN = process.env.SECURITY_TOKEN || 'your-secret-token';

let qrBase64 = null;
let connectionStatus = "Disconnected";
let lastError = null;
let socket = null;

const SESSION_FOLDER = path.join(__dirname, 'wa_session');

async function startBridge() {
    connectionStatus = "Starting WhatsApp Library...";
    try {
        if (!fs.existsSync(SESSION_FOLDER)) {
            fs.mkdirSync(SESSION_FOLDER, { recursive: true });
        }

        const { state, saveCreds } = await useMultiFileAuthState(SESSION_FOLDER);

        socket = makeWASocket({
            auth: state,
            printQRInTerminal: false,
            logger: pino({ level: 'silent' }),
            browser: ['Chrome', 'Chrome', '1.0.0']
        });

        socket.ev.on('creds.update', saveCreds);

        socket.ev.on('connection.update', async (update) => {
            const { connection, lastDisconnect, qr } = update;

            if (qr) {
                qrBase64 = await QRCode.toDataURL(qr);
                connectionStatus = "READY TO SCAN";
            }

            if (connection === 'close') {
                const reason = lastDisconnect?.error?.output?.statusCode;
                connectionStatus = `Stopped (Reason: ${reason})`;
                lastError = `Connection closed with code: ${reason}`;
                if (reason !== DisconnectReason.loggedOut) {
                    setTimeout(startBridge, 3000);
                }
            } else if (connection === 'open') {
                connectionStatus = "CONNECTED";
                qrBase64 = null;
                lastError = null;
            }
        });

    } catch (err) {
        lastError = err.message;
        connectionStatus = "Startup Error";
        console.error('Bridge startup error:', err);
    }
}

// API Endpoint - Define OUTSIDE of startBridge function
app.post('/api/send-message', async (req, res) => {
    const { to, message } = req.body;
    const auth = req.headers['authorization'];
    
    if (auth !== `Bearer ${SECURITY_TOKEN}`) {
        return res.status(401).json({ error: 'Unauthorized' });
    }
    
    if (!socket) {
        return res.status(503).json({ error: 'WhatsApp socket not initialized' });
    }
    
    try {
        const jid = to.replace(/\D/g, '') + '@s.whatsapp.net';
        await socket.sendMessage(jid, { text: message });
        res.json({ success: true, message: 'Message sent successfully' });
    } catch (err) {
        console.error('Send message error:', err);
        res.status(500).json({ error: err.message });
    }
});

// Status API endpoint
app.get('/api/status', (req, res) => {
    res.json({
        status: connectionStatus,
        hasQR: !!qrBase64,
        error: lastError
    });
});

app.get('/', (req, res) => {
    let html = `
        <body style="font-family:sans-serif; text-align:center; padding: 50px; background: #eef2f3;">
            <div style="background: white; padding: 40px; border-radius: 20px; display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                <h1 style="color: #333; margin-bottom: 5px;">WhatsApp Bridge Lite</h1>
                <p style="color: #999; margin-top: 0; font-size: 14px;">v1.5 (Subdomain Edition)</p>
                <p style="font-size: 1.2em; margin-top: 30px;">Status: <strong style="color: ${connectionStatus === 'CONNECTED' ? '#10b981' : '#f59e0b'}">${connectionStatus}</strong></p>
    `;

    if (qrBase64) {
        html += `
            <div style="margin: 30px 0; border: 2px dashed #ddd; padding: 20px; border-radius: 15px;">
                <h3 style="color: #666; margin-top: 0;">Scan Now:</h3>
                <img src="${qrBase64}" style="width: 250px; height: 250px;" />
                <p style="color: #888; font-size: 12px;">Open WhatsApp > Linked Devices > Link a Device</p>
            </div>
        `;
    }

    if (lastError) {
        html += `<p style="color: #dc2626; background: #fef2f2; padding: 10px; border-radius: 5px;"><strong>System Error:</strong> ${lastError}</p>`;
    }

    html += `
                <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;" />
                <button onclick="window.location.reload()" style="padding: 12px 24px; border-radius: 10px; border: none; background: #000; color: white; font-weight: bold; cursor: pointer;">REFRESH STATUS</button>
            </div>
        </body>
    `;
    res.send(html);
});

app.listen(PORT, () => {
    console.log(`WhatsApp Bridge started on port ${PORT}`);
    console.log(`Security Token: ${SECURITY_TOKEN.substring(0, 5)}...`);
    console.log(`Session Folder: ${SESSION_FOLDER}`);
    startBridge();
});
