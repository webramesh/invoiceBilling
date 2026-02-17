<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
            'whatsapp_api_url',
            'whatsapp_api_key',
            'whatsapp_template'
        ];

        // Handle Logo Upload
        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
            Setting::set('company_logo', $path);
        }

        $keys = [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
            'whatsapp_api_url',
            'whatsapp_api_key',
            'whatsapp_template',
            // New Branding Keys
            'company_name',
            'company_color'
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            Mail::raw('This is a test email from your application setup.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Test Email Configuration');
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
