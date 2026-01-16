const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const bodyParser = require('body-parser');
require('dotenv').config();

const app = express();
const port = process.env.PORT || 3001;
const API_KEY = process.env.API_KEY || 'your-secret-api-key';

app.use(bodyParser.json());

// Initialize WhatsApp Client
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
        executablePath: process.env.CHROME_BIN || null
    }
});

client.on('qr', (qr) => {
    console.log('QR RECEIVED', qr);
    qrcode.generate(qr, { small: true });
    console.log('Scan the QR code above to log in to WhatsApp');
});

client.on('ready', () => {
    console.log('WhatsApp Client is ready!');
});

client.on('authenticated', () => {
    console.log('WhatsApp Client is authenticated!');
});

client.on('auth_failure', (msg) => {
    console.error('AUTHENTICATION FAILURE', msg);
});

client.on('disconnected', (reason) => {
    console.log('WhatsApp Client was logged out', reason);
});

// Middleware for API Key
const authMiddleware = (req, res, next) => {
    const apiKey = req.body.api_key || req.query.api_key;
    if (apiKey !== API_KEY) {
        return res.status(401).json({ success: false, message: 'Unauthorized' });
    }
    next();
};

// API Endpoint to send message
app.post('/send-message', authMiddleware, async (req, res) => {
    const { phone, message } = req.body;

    if (!phone || !message) {
        return res.status(400).json({ success: false, message: 'Phone and message are required' });
    }

    try {
        // Sanitize phone number (remove +, spaces, ensure it has @c.us suffix)
        let sanitizedPhone = phone.replace(/[^0-9]/g, '');
        if (!sanitizedPhone.endsWith('@c.us')) {
            sanitizedPhone = sanitizedPhone + '@c.us';
        }

        const response = await client.sendMessage(sanitizedPhone, message);
        res.json({ success: true, response });
    } catch (error) {
        console.error('Error sending message:', error);
        res.status(500).json({ success: false, error: error.message });
    }
});

// Status endpoint
app.get('/status', (req, res) => {
    res.json({ 
        success: true, 
        message: 'WhatsApp Service is running',
        client_ready: client.info ? true : false
    });
});

app.listen(port, () => {
    console.log(`WhatsApp Service API listening at http://localhost:${port}`);
    client.initialize();
});
