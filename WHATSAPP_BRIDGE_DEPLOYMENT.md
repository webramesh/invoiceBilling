# Next Steps - Deploy the Fixed WhatsApp Bridge

## What Was Fixed

1. ✅ **405 Method Not Allowed Error** - API endpoints moved outside startBridge function
2. ✅ **Socket scope issue** - Made socket accessible to all functions
3. ✅ **Wrong endpoint path** - Laravel now calls `/api/send-message` instead of `/send-message`
4. ✅ **Added status API** - `/api/status` endpoint for health checks
5. ✅ **Better error logging** - More detailed console output and error tracking

## Deployment Steps

### Step 1: Upload the Fixed Code
Upload the entire `whatsapp-bridge` folder to your cPanel. The fixed files are:
- `index.js` (main application - FIXED)
- `.htaccess` (new file for proper routing)
- `README.md` (deployment guide)

### Step 2: In cPanel - Restart the Application
1. Go to **Setup Node.js App** in cPanel
2. Find your `whatsapp.divewithai.com` application
3. Click **"STOP APP"** button
4. Wait a few seconds
5. Click **"START APP"** button

### Step 3: Check if it Works
1. Visit: `https://whatsapp.divewithai.com`
2. You should see:
   - Status: "READY TO SCAN" (if not logged in yet)
   - OR Status: "CONNECTED" (if already logged in)
   - A QR code to scan (if not logged in)

### Step 4: Connect WhatsApp (if needed)
If you see a QR code:
1. Open WhatsApp on your phone
2. Go to: Settings → Linked Devices → Link a Device
3. Scan the QR code on the screen
4. Wait for status to change to "CONNECTED"

### Step 5: Update Laravel Configuration
Make sure your Laravel settings have:
- **WhatsApp API URL**: `https://whatsapp.divewithai.com`
- **WhatsApp API Key**: `MyWaSecret123` (must match your SECURITY_TOKEN in cPanel)

### Step 6: Test Sending a Message
From your Laravel application, try sending an invoice notification to test if messages are delivered.

## If You Still See Issues

### "Starting WhatsApp Library..." forever
**Solution**: Check the Node.js error logs in cPanel:
1. In Setup Node.js App, scroll down to find the log file path
2. Click to view logs
3. Look for any errors related to missing packages or permissions

### "Stopped (Reason: 405)" still appears
**Possible causes**:
1. The app hasn't been restarted with the new code
2. cPanel is caching the old version

**Solution**:
1. Stop the app completely
2. Clear browser cache
3. Start the app again
4. Wait 10-20 seconds before refreshing the page

### Session/Authentication Issues
If WhatsApp keeps disconnecting (Reason: 401, 428, etc.):
1. Delete the `wa_session` folder in cPanel File Manager
2. Restart the app
3. Scan the QR code again with WhatsApp

## Testing the API Directly

You can test if the API works using this command (from terminal or cPanel Terminal):

```bash
curl -X POST https://whatsapp.divewithai.com/api/send-message \
  -H "Authorization: Bearer MyWaSecret123" \
  -H "Content-Type: application/json" \
  -d '{"to": "YOUR_PHONE_NUMBER", "message": "Test from API"}'
```

Replace:
- `MyWaSecret123` with your actual SECURITY_TOKEN
- `YOUR_PHONE_NUMBER` with your phone number (with country code, no + sign)

Expected response:
```json
{"success": true, "message": "Message sent successfully"}
```

## Need More Help?

Check these log files:
1. **cPanel Node.js logs**: Shows startup and runtime errors
2. **Laravel logs**: `/storage/logs/laravel.log` - Shows API call errors
3. **Browser console**: May show JavaScript errors on the status page
