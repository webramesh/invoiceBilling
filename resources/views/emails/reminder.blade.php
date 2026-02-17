<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Reminder</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .content {
            white-space: pre-line;
            margin-bottom: 30px;
        }
        .info-box {
            background-color: #F3F4F6;
            border-left: 4px solid #4F46E5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box strong {
            color: #4F46E5;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            margin-top: 30px;
            font-size: 12px;
            color: #6B7280;
        }
        .urgency-high {
            background-color: #FEF2F2;
            border-left-color: #EF4444;
        }
        .urgency-high strong {
            color: #EF4444;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📧 Invoice Reminder</h1>
        </div>
        
        <div class="content">
            {{ $body }}
        </div>
        
        <div class="info-box {{ $daysUntilDue <= 3 ? 'urgency-high' : '' }}">
            <strong>Service Details:</strong><br>
            Service: {{ $subscription->service_alias ?? $subscription->service->name }}<br>
            Amount: ${{ number_format($subscription->price * ($subscription->quantity ?? 1), 2) }}<br>
            Due Date: {{ $subscription->next_billing_date->format('F j, Y') }}<br>
            Days Until Due: <strong>{{ $daysUntilDue }} days</strong>
            
            @if($subscription->items && $subscription->items->count() > 0)
                <br><br>
                <strong>Included Items:</strong><br>
                @foreach($subscription->items as $item)
                    • {{ $item->name }} ({{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }})<br>
                @endforeach
            @endif
        </div>
        
        @if($daysUntilDue <= 3)
            <div style="background-color: #FEF2F2; padding: 15px; border-radius: 4px; margin: 20px 0; text-align: center;">
                <strong style="color: #EF4444;">⚠️ Urgent: Payment due in {{ $daysUntilDue }} day{{ $daysUntilDue != 1 ? 's' : '' }}!</strong>
            </div>
        @endif
        
        <div class="footer">
            <p>This is an automated reminder. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
