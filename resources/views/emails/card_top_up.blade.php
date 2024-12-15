<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Top-Up Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #e60000;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-top: 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        .content ul {
            list-style-type: none;
            padding: 0;
        }
        .content ul li {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #555;
        }
        .footer a {
            color: #e60000;
            text-decoration: none;
        }
        .button {
            display: inline-block;
            background-color: #e60000;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NAMIBIA CONTRACT HAULAGE - BUS SMART CARD</h1>
        </div>

        <div class="content">
            <p>Hello {{ $name }},</p>

            <p>Your card has been topped up successfully. Here are the details:</p>

            <ul>
                <li><strong>Amount Topped Up:</strong> NAD {{ number_format($topUp->amount, 2) }}</li>
                <li><strong>Transaction Reference:</strong> {{ $topUp->transaction_reference }}</li>
                <li><strong>New Balance:</strong> NAD {{ number_format($newBalance, 2) }}</li>
            </ul>

            <p>Thank you for using our service!</p>

        </div>

        <div class="footer">
            <p>If you have any questions, feel free to contact us at <a href="mailto:support@namibiacontracthaulage.com">support@namibiacontracthaulage.com</a>.</p>
            <p>&copy; 2024 Namibia Contract Haulage</p>
        </div>
    </div>
</body>
</html>
