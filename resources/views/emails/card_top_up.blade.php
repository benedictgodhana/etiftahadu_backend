<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Top-Up Receipt</title>
    <style>
        /* Import FuturaLT font */
        @font-face {
            font-family: 'FuturaLT';
            src: url('{{ public_path("fonts/futura-lt/FuturaLT.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'FuturaLT-Bold';
            src: url('{{ public_path("fonts/futura-lt/FuturaLT-Bold.ttf") }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'FuturaLT', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background-color: #f7f7f7;
        }

        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        /* Receipt Header */
        .receipt-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px dashed #cccccc;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            font-size: 24px;
            color: #0044cc;
            font-family: 'FuturaLT-Bold', sans-serif;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .company-info {
            margin-top: 10px;
            font-size: 13px;
        }

        .receipt-logo {
            width: 200px;
            height: 100px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background-image: url('/images/logo.png'); /* Replace with actual logo path */
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        /* Receipt Details */
        .receipt-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
        }

        .receipt-details .left-column,
        .receipt-details .right-column {
            width: 48%;
        }

        .detail-row {
            margin-bottom: 8px;
            display: flex;
        }

        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }

        .detail-value {
            flex: 1;
        }

        /* Receipt Items */
        .receipt-items {
            margin: 25px 0;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-table th {
            background-color: #f2f7ff;
            color: #0044cc;
            font-weight: bold;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #0044cc;
        }

        .receipt-table td {
            padding: 12px;
            border-bottom: 1px solid #eeeeee;
        }

        .receipt-table tr:last-child td {
            border-bottom: none;
        }

        .receipt-table .amount-column {
            text-align: right;
        }

        /* Receipt Summary */
        .receipt-summary {
            margin: 25px 0;
            text-align: right;
            padding-top: 15px;
            border-top: 2px dashed #cccccc;
        }

        .total-row {
            font-size: 18px;
            color: #0044cc;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Receipt Terms */
        .receipt-terms {
            margin: 20px 0;
            padding: 15px;
            background-color: #f7f9ff;
            border-left: 4px solid #0044cc;
            border-radius: 4px;
            font-size: 13px;
        }

        .receipt-terms p {
            margin-bottom: 5px;
            color: #555;
        }

        /* Receipt Footer */
        .receipt-footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px dashed #cccccc;
        }

        .thank-you-message {
            font-size: 18px;
            color: #0044cc;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .footer-contact {
            font-size: 13px;
            color: #777;
            margin: 10px 0;
        }

        .footer-copyright {
            font-size: 12px;
            color: #999;
            margin-top: 15px;
        }

        /* Receipt Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 68, 204, 0.03);
            z-index: 0;
            pointer-events: none;
            font-weight: bold;
            white-space: nowrap;
        }

        .warning-text {
            color: #dd3333;
            font-style: italic;
            font-size: 13px;
            margin: 15px 0;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .receipt-container {
                padding: 15px;
                margin: 10px;
            }

            .receipt-details {
                flex-direction: column;
            }

            .receipt-details .left-column,
            .receipt-details .right-column {
                width: 100%;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">RECEIPT</div>

        <!-- Receipt Header -->
        <div class="receipt-header">
        <div >
                <img src="{{ public_path('/images/nch-removebg-preview.png') }}" class="receipt-logo" alt="Company Logo">
                <h2 class="company-title">NCH</h2>
            </div>

            <h1>CARD TOP-UP RECEIPT</h1>
            <div class="company-info">
                <p><strong>Namibia Contract Haulage</strong></p>
                <p>123 Main Street, Windhoek, Namibia</p>
                <p>Tel: +264 61 123 4567 | Email: support@namibiacontracthaulage.com</p>
            </div>
        </div>

        <!-- Receipt Details -->
        <div class="receipt-details">
            <div class="left-column">
                <div class="detail-row">
                    <span class="detail-label">Customer:</span>
                    <span class="detail-value">{{ $topUp->card->name ?? 'Customer Name' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Card Number:</span>
                    <span class="detail-value">{{ $topUp->card->serial_number ?? 'XXXX-XXXX-XXXX-XXXX' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Card Type:</span>
                    <span class="detail-value">{{ $topUp->card->type ?? 'Standard' }}</span>
                </div>
            </div>
            <div class="right-column">
                <div class="detail-row">
                    <span class="detail-label">Receipt #:</span>
                    <span class="detail-value">{{ $topUp->transaction_reference ?? 'TRX-12345' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ date('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ date('H:i:s') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Offer Expiry:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($topUp->offer->expiry ?? now()->addDays(30))->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <p class="warning-text">* This offer is valid for {{ $topUp->offer->duration ?? '30' }} days from the date of top-up</p>

        <!-- Receipt Items -->
        <div class="receipt-items">
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="amount-column">Amount (NAD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Card Top-Up Amount</td>
                        <td class="amount-column">{{ number_format($topUp->amount ?? 500, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Previous Balance</td>
                        <td class="amount-column">{{ number_format(($newBalance ?? 1000) - ($topUp->amount ?? 500), 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>New Balance After Top-Up</strong></td>
                        <td class="amount-column"><strong>{{ number_format($newBalance ?? 1000, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Receipt Summary -->
        <div class="receipt-summary">
            <div class="total-row">
                <span>Total Top-Up Amount: NAD {{ number_format($topUp->amount ?? 500, 2) }}</span>
            </div>
        </div>

        <!-- Receipt Terms -->
        <div class="receipt-terms">
            <p><strong>Terms and Conditions:</strong></p>
            <p>1. The top-up amount is non-refundable once credited to the card.</p>
            <p>2. This receipt serves as proof of payment and should be kept for your records.</p>
            <p>3. For any disputes, please contact our customer service within 7 days.</p>
        </div>

        <!-- Receipt Footer -->
        <div class="receipt-footer">
            <p class="thank-you-message">Thank you for your top-up!</p>
            <p class="footer-contact">If you have any questions, please contact us at <a href="mailto:support@namibiacontracthaulage.com">support@namibiacontracthaulage.com</a> or call +264 61 123 4567.</p>
            <p class="footer-copyright">&copy; {{ date('Y') }} Namibia Contract Haulage. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
