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
let connectionStatus = "Initializing...";
let lastError = null;

const SESSION_FOLDER = path.join(__dirname, 'wa_session');

async function startBridge() {
    try {
        if (!fs.existsSync(SESSION_FOLDER)) {
            fs.mkdirSync(SESSION_FOLDER, { recursive: true });
        }

        const { state, saveCreds } = await useMultiFileAuthState(SESSION_FOLDER);

        const socket = makeWASocket({
            auth: state,
            printQRInTerminal: false,
            logger: pino({ level: 'silent' }),
            browser: ['NetSync Billing', 'Chrome', '1.0.0']
        });

        socket.ev.on('creds.update', saveCreds);

        socket.ev.on('connection.update', async (update) => {
            const { connection, lastDisconnect, qr } = update;

            if (qr) {
                qrBase64 = await QRCode.toDataURL(qr);
                connectionStatus = "Waiting for Scan";
            }

            if (connection === 'close') {
                const reason = lastDisconnect?.error?.output?.statusCode;
                connectionStatus = `Disconnected (Reason: ${reason})`;
                const shouldReconnect = (reason !== DisconnectReason.loggedOut);
                if (shouldReconnect) startBridge();
            } else if (connection === 'open') {
                connectionStatus = "Connected";
                qrBase64 = null;
            }
        });

        // API to send message
        app.post('/api/send-message', async (req, res) => {
            const { to, message } = req.body;
            const auth = req.headers['authorization'];

            if (auth !== `Bearer ${SECURITY_TOKEN}`) return res.status(401).send('Unauthorized');
            if (connectionStatus !== "Connected") return res.status(503).send('WhatsApp not connected');

            try {
                const jid = to.replace(/\D/g, '') + '@s.whatsapp.net';
                await socket.sendMessage(jid, { text: message });
                res.json({ success: true });
            } catch (err) {
                res.status(500).json({ error: err.message });
            }
        });

    } catch (err) {
        lastError = err.message;
        connectionStatus = "Critical Error";
    }
}

// GUI for monitoring
app.get('/', (req, res) => {
    let html = `
        <body style="font-family:sans-serif; text-align:center; padding: 50px; background: #f4f7f6;">
            <div style="background: white; padding: 40px; border-radius: 20px; display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                <h1>WhatsApp Bridge Status</h1>
                <p>Status: <strong style="color: ${connectionStatus === 'Connected' ? 'green' : 'orange'}">${connectionStatus}</strong></p>
    `;

    if (qrBase64) {
        html += `
            <h3>Scan this QR Code:</h3>
            <div style="background: white; padding: 20px; border-radius: 10px; display: inline-block;">
                <img src="${qrBase64}" style="width: 250px; height: 250px;" />
            </div>
            <p>Expires in 60 seconds. Refresh page if it stops working.</p>
        `;
    }

    if (lastError) {
        html += `<p style="color: red;">Error: ${lastError}</p>`;
    }

    html += `
                <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;" />
                <p style="font-size: 12px; color: #999;">NetSync Billing Bridge v1.3</p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button onclick="window.location.reload()" style="padding: 10px 20px; border-radius: 10px; border: none; background: #2492a8; color: white; font-weight: bold; cursor: pointer;">Refresh Status</button>
                </div>
            </div>
        </body>
    `;
    res.send(html);
});

app.get('/api/status', (req, res) => res.json({ status: connectionStatus }));

app.listen(PORT, () => {
    console.log('Bridge running on port ' + PORT);
});

startBridge();
