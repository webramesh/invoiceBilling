<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = Setting::get('whatsapp_api_url');
        $this->apiKey = Setting::get('whatsapp_api_key');
    }

    public function sendMessage($to, $message)
    {
        if (!$this->apiUrl) {
            Log::error('WhatsApp API URL not configured.');
            return false;
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/api/send-message', [
                        'to' => $this->formatNumber($to),
                        'message' => $message,
                    ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WhatsApp API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp Service Exception: ' . $e->getMessage());
            return false;
        }
    }

    protected function formatNumber($number)
    {
        // Simple formatter: remove plus, spaces, etc.
        $clean = preg_replace('/[^0-9]/', '', $number);

        // Ensure it has country code if missing (assumed logic if needed, but for now just clean)
        return $clean;
    }
}
