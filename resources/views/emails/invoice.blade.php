<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #4f46e5;
            margin: 0;
        }

        .content {
            margin-bottom: 30px;
        }

        .invoice-summary {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .invoice-summary table {
            width: 100%;
        }

        .invoice-summary td {
            padding: 5px 0;
        }

        .footer {
            text-align: center;
            color: #777;
            font-size: 12px;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4f46e5;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>New Invoice</h1>
        </div>

        <div class="content">
            <p>Dear {{ $invoice->client->name }},</p>
            <p>We hope this email finds you well. A new invoice has been generated for your recent service renewal.</p>

            <div class="invoice-summary">
                <table>
                    <tr>
                        <td><strong>Invoice Number:</strong></td>
                        <td align="right">{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Issue Date:</strong></td>
                        <td align="right">{{ $invoice->issue_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Due Date:</strong></td>
                        <td align="right">{{ $invoice->due_date->format('M d, Y') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #ddd;">
                        <td><strong>Total Amount:</strong></td>
                        <td align="right" style="color: #4f46e5; font-size: 18px;">
                            <strong>{{ number_format($invoice->total, 2) }}</strong></td>
                    </tr>
                </table>
            </div>

            <p>Please find the attached PDF for a detailed breakdown of your charges.</p>

            <p>If you have any questions or concerns regarding this invoice, please do not hesitate to contact our
                support team.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>

</html>