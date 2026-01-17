# WhatsApp Bridge - cPanel Deployment Guide

## Issues Fixed

### 1. **405 Method Not Allowed Error**
- **Problem**: The `/api/send-message` route was defined inside the `startBridge()` function, causing it to be re-registered on every restart.
- **Solution**: Moved the API endpoint definition outside the `startBridge()` function to ensure it's always available.

### 2. **Socket Variable Scope**
- **Problem**: The `socket` variable was scoped inside the function, making it unavailable to the API endpoint.
- **Solution**: Made `socket` a module-level variable accessible to all functions.

### 3. **Wrong Endpoint in Laravel**
- **Problem**: Laravel was calling `/send-message` instead of `/api/send-message`.
- **Solution**: Updated WhatsAppService.php to use the correct endpoint `/api/send-message`.

### 4. **Better Error Handling**
- Added proper error logging
- Added connection error tracking
- Added status API endpoint for programmatic status checks

## cPanel Setup Instructions

1. **Upload the whatsapp-bridge folder** to your cPanel account (e.g., `/home/yourusername/whatsapp-bridge`)

2. **In cPanel, go to Setup Node.js App**:
   - Click "Create Application"
   - Application mode: Production
   - Node.js version: 20.19.4 (or latest available)
   - Application root: `whatsapp-bridge`
   - Application URL: Your subdomain (e.g., `whatsapp.divewithai.com`)
   - Application startup file: `index.js`

3. **Set Environment Variables** in cPanel Node.js App settings:
   - `SECURITY_TOKEN`: `MyWaSecret123` (or your custom token)
   - `PORT`: Leave empty (cPanel handles this automatically)

4. **Run NPM Install**:
   - In the Node.js App interface, click "Run NPM Install"
   - This will install all dependencies from package.json

5. **Start the Application**:
   - Click "Start App" or "Restart" button

6. **Configure Laravel Settings**:
   - In your Laravel app, go to Settings
   - Set WhatsApp API URL: `https://whatsapp.divewithai.com`
   - Set WhatsApp API Key: `MyWaSecret123` (same as SECURITY_TOKEN)

## Verifying the Setup

1. Visit `https://whatsapp.divewithai.com` in your browser
2. You should see the WhatsApp Bridge interface
3. Status should show "READY TO SCAN" or "CONNECTED"
4. If you see a QR code, scan it with WhatsApp mobile app:
   - Open WhatsApp > Settings > Linked Devices > Link a Device

## Troubleshooting

### If status shows "Starting WhatsApp Library..." forever:
- Check cPanel error logs for the Node.js app
- Ensure all dependencies are installed
- Restart the app from cPanel

### If you get 405 errors:
- Ensure .htaccess file is in the whatsapp-bridge folder
- Check that Passenger is enabled in cPanel
- Verify the Application Root path is correct

### If messages don't send from Laravel:
- Check Laravel logs: `storage/logs/laravel.log`
- Verify the API URL in Settings matches your subdomain
- Verify the API Key matches the SECURITY_TOKEN
- Ensure WhatsApp is connected (status shows "CONNECTED")

## Testing the API

You can test the API endpoint with curl:

```bash
curl -X POST https://whatsapp.divewithai.com/api/send-message \
  -H "Authorization: Bearer MyWaSecret123" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "1234567890",
    "message": "Test message"
  }'
```

## Status API

Check the status programmatically:

```bash
curl https://whatsapp.divewithai.com/api/status
```

Response:
```json
{
  "status": "CONNECTED",
  "hasQR": false,
  "error": null
}
```
