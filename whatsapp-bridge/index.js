const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason
} = require('@whiskeysockets/baileys');
const express = require('express');
const QRCode = require('qrcode');
const fs = require('fs');
const path = require('path');
const pino = require('pino');

const app = express();
app.use(express.json());

const PORT = process.env.PORT || 3000;
const SECURITY_TOKEN = process.env.SECURITY_TOKEN || 'your-secret-token';

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('auth_info_baileys');

    const socket = makeWASocket({
        auth: state,
        printQRInTerminal: true,
        logger: pino({ level: 'silent' })
    });

    socket.ev.on('creds.update', saveCreds);

    socket.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            // Save QR code to a file so cPanel user can view/scan it
            QRCode.toFile(path.join(__dirname, 'qr.png'), qr, (err) => {
                if (err) console.error('Failed to save QR code:', err);
                else console.log('QR Code generated! Open qr.png in your file manager to scan.');
            });
        }

        if (connection === 'close') {
            const shouldReconnect = (lastDisconnect.error?.output?.statusCode !== DisconnectReason.loggedOut);
            console.log('connection closed due to ', lastDisconnect.error, ', reconnecting ', shouldReconnect);
            if (shouldReconnect) {
                connectToWhatsApp();
            }
        } else if (connection === 'open') {
            console.log('opened connection');
            // Remove qr.png once connected
            if (fs.existsSync(path.join(__dirname, 'qr.png'))) {
                fs.unlinkSync(path.join(__dirname, 'qr.png'));
            }
        }
    });

    // API Endpoint to send message
    app.post('/api/send-message', async (req, res) => {
        const { to, message } = req.body;
        const authHeader = req.headers['authorization'];

        // Token Validation
        if (authHeader !== `Bearer ${SECURITY_TOKEN}`) {
            return res.status(401).json({ error: 'Unauthorized' });
        }

        if (!to || !message) {
            return res.status(400).json({ error: 'Missing parameters' });
        }

        try {
            const jid = to.includes('@s.whatsapp.net') ? to : `${to}@s.whatsapp.net`;
            await socket.sendMessage(jid, { text: message });
            res.json({ success: true });
        } catch (err) {
            res.status(500).json({ error: err.message });
        }
    });

    app.listen(PORT, () => {
        console.log(`WhatsApp Bridge Running on port ${PORT}`);
    });
}

connectToWhatsApp();
