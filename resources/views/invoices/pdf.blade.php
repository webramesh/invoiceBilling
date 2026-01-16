<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.5;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header-content {
            display: table-cell;
            vertical-align: top;
        }

        .header-right {
            text-align: right;
        }

        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-box {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th {
            background: #f8f9fa;
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        table td {
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            float: right;
            width: 300px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-unpaid {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 50px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div class="header-content">
                <h1 style="margin:0;color:#4f46e5;">INVOICE</h1>
                <p style="margin:5px 0;">#{{ $invoice->invoice_number }}</p>
            </div>
            <div class="header-content header-right">
                <p><strong>Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3 style="margin-top:0;">Billed To:</h3>
                <p><strong>{{ $invoice->client->name }}</strong></p>
                @if($invoice->client->company)
                    <p>{{ $invoice->client->company }}</p>
                @endif
                <p>{{ $invoice->client->email }}</p>
                <p>{{ $invoice->client->phone }}</p>
                <p>{!! nl2br(e($invoice->client->address)) !!}</p>
            </div>
            <div class="info-box text-right">
                <h3>Payment Status:</h3>
                <span class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center" width="50">Qty</th>
                    <th class="text-right" width="100">Price</th>
                    <th class="text-right" width="100">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table>
                <tr>
                    <td style="border:none;"><strong>Subtotal</strong></td>
                    <td class="text-right" style="border:none;">{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($invoice->tax > 0)
                    <tr>
                        <td style="border:none;"><strong>Tax</strong></td>
                        <td class="text-right" style="border:none;">{{ number_format($invoice->tax, 2) }}</td>
                    </tr>
                @endif
                <tr style="background:#f8f9fa;">
                    <td><strong>Total Amount</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->total, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div style="clear:both;"></div>

        @if($invoice->notes)
            <div style="margin-top:30px;">
                <h4>Notes:</h4>
                <p>{{ $invoice->notes }}</p>
            </div>
        @endif

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions, please contact us at support@example.com</p>
        </div>
    </div>
</body>

</html>