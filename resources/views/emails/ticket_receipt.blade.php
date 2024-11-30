<!DOCTYPE html>
<html>
<head>
    <title>Ticket Receipt</title>
    <style>
        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header section styling */
        .header {
            text-align: center;
            font-size: 28px;
            color: #d9534f;
            margin-bottom: 20px;
        }

        .sub-header {
            font-size: 16px;
            text-align: center;
            color: #777;
        }

        /* Customer and transaction details styling */
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.8;
        }

        .content p {
            margin: 5px 0;
        }

        .content .highlight {
            font-weight: bold;
            color: #555;
        }

        .content .amount {
            font-size: 18px;
            font-weight: bold;
            color: #d9534f;
        }

        /* Footer section styling */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .footer p {
            margin: 5px 0;
        }

        /* Divider line for separation */
        .divider {
            border-top: 2px solid #ddd;
            margin: 20px 0;
        }

        /* Small print for legal or additional information */
        .small-print {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin-top: 40px;
        }

        .small-print p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Ticket Purchase Receipt</h1>
            <div class="sub-header">
                Namibia Contract Haulage - BUS SMART CARD
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="content">
            <p><span class="highlight">Customer Name:</span> {{ $name }}</p>
            <p><span class="highlight">Route:</span> {{ $from }} to {{ $to }}</p>
            <div class="divider"></div>
            <p><span class="highlight">Amount Paid:</span> <span class="amount">{{ $amount }}</span></p>
            <p><span class="highlight">New Balance:</span> <span class="amount">{{ $new_balance }}</span></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing Namibia Contract Haulage!</p>
            <p>For inquiries, contact customer service at <strong>+123 456 789</strong></p>
        </div>
    </div>

    <!-- Small Print for legal or additional info -->
    <div class="small-print">
        <p>Receipt valid for 30 days from the date of purchase.</p>
        <p>Terms and conditions apply.</p>
    </div>
</body>
</html>
