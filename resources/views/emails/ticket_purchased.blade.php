<!DOCTYPE html>
<html>
<head>
    <title>Receipt - Ticket Purchase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #e63946; /* Red color for heading */
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        .amount {
            font-weight: bold;
            color: #e63946;
        }

        .balance {
            font-weight: bold;
            color: #e63946;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Namibia Contract Haulage</h1>
    <p>Dear {{ $name }},</p>
    <p>You have successfully purchased a ticket for the route:</p>
    <p><strong>From:</strong> {{ $from }} <strong>To:</strong> {{ $to }}</p>
    <p class="amount">Amount: {{ $amount }}</p>
    <p class="balance">Your new balance is: {{ $new_balance }}</p>
    <p>Thank you for choosing Namibia Contract Haulage for your travel needs!</p>

    <div class="footer">
        <p>If you have any questions or issues, please contact us at [support email].</p>
    </div>
</body>
</html>
