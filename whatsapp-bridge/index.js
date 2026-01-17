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

// Use a session folder that is guaranteed to be in the app root
const SESSION_FOLDER = path.join(process.cwd(), 'wa_session');
if (!fs.existsSync(SESSION_FOLDER)) {
    fs.mkdirSync(SESSION_FOLDER, { recursive: true });
}

async function startBridge() {
    const { state, saveCreds } = await useMultiFileAuthState(SESSION_FOLDER);

    const socket = makeWASocket({
        auth: state,
        printQRInTerminal: false,
        logger: pino({ level: 'silent' }),
        browser: ['NetSync Billing', 'Chrome', '1.0.0']
    });

    socket.ev.on('creds.update', saveCreds);

    socket.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            // Save QR to public_html or similar if possible, but for now app root
            QRCode.toFile(path.join(process.cwd(), 'qr.png'), qr);
        }

        if (connection === 'close') {
            const shouldReconnect = (lastDisconnect.error?.output?.statusCode !== DisconnectReason.loggedOut);
            if (shouldReconnect) startBridge();
        }
    });

    // API to send message
    app.post('/api/send-message', async (req, res) => {
        const { to, message } = req.body;
        const auth = req.headers['authorization'];

        if (auth !== `Bearer ${SECURITY_TOKEN}`) return res.status(401).send('Unauthorized');

        try {
            const jid = to.replace(/\D/g, '') + '@s.whatsapp.net';
            await socket.sendMessage(jid, { text: message });
            res.json({ success: true });
        } catch (err) {
            res.status(500).json({ error: err.message });
        }
    });

    // Pulse check
    app.get('/', (req, res) => res.send('WhatsApp Bridge is Active'));

    app.listen(PORT);
}

startBridge().catch(err => {
    fs.writeFileSync(path.join(process.cwd(), 'error.log'), err.message);
});
