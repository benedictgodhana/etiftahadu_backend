<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Top-Up Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px 40px;
            border: 1px solid #ddd;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0044cc;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            color: #0044cc;
        }
        .header .logo {
            width: 80px;
            height: 80px;
            background-color: #ddd;
            border-radius: 50%;
            background-image: url('/images/logo.png'); /* Replace with actual logo path */
            background-size: cover;
            background-position: center;
        }
        .details {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }
        .details .column {
            width: 48%;
        }
        .details p {
            margin: 5px 0;
            font-size: 14px;
        }
        .details p strong {
            font-size: 15px;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        table th {
            background: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
            text-align: right;
        }
        .summary p {
            font-size: 16px;
        }
        .summary p strong {
            font-size: 18px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
        }
        .footer p {
            font-size: 14px;
            color: #777;
        }
        .footer .thank-you {
            font-size: 20px;
            color: #0044cc;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>TOP-UP RECEIPT</h1>
        </div>

        <!-- Billing Details -->
        <div class="details">
            <div class="column">
                <p><strong>Namibia Contract Haulage</strong></p>
                <p>123 Main Street</p>
                <p>Windhoek, Namibia</p>
                <p>Email: support@namibiacontracthaulage.com</p>
            </div>
            <div class="column">
                <p><strong>Date:</strong> {{ date('d/m/Y') }}</p>
                <p><strong>Receipt #:</strong> {{ $topUp->transaction_reference }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount (NAD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Card Top-Up Amount</td>
                        <td>{{ number_format($topUp->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>New Balance After Top-Up</td>
                        <td>{{ number_format($newBalance, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary">
            <p><strong>Total Top-Up:</strong> NAD {{ number_format($topUp->amount, 2) }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="thank-you">Thank you for topping up your card!</p>
            <p>If you have any questions, please contact us at <a href="mailto:support@namibiacontracthaulage.com">support@namibiacontracthaulage.com</a>.</p>
            <p>&copy; 2024 Namibia Contract Haulage</p>
        </div>
    </div>
</body>
</html>
