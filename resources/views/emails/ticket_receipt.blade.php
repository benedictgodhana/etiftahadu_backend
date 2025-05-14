    <!DOCTYPE html>
    <html>
    <head>
    <title>Ticket Receipt</title>
    <style>
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

    /* General styling */
    body {
    font-family: 'FuturaLT', 'Arial', sans-serif;
    background-color: #f8f8f8;
    color: #333;
    margin: 0;
    padding: 0;
    }

    .container {
    width: 80%;
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Receipt styling - red and white theme */
    .receipt {
    border: 2px solid #d9292a;
    padding: 15px;
    background-color: #fff;
    font-size: 14px;
    line-height: 1.5;
    }

    /* Header section styling */
    .header {
    text-align: center;
    border-bottom: 1px dashed #d9292a;
    padding-bottom: 10px;
    margin-bottom: 15px;
    color: #d9292a;
    }

    .header h1 {
    font-family: 'FuturaLT-Bold', 'Arial', sans-serif;
    font-size: 20px;
    margin: 0 0 5px 0;
    color: #d9292a;
    }

    .sub-header {
    font-size: 14px;
    color: #d9292a;
    }

    /* Logo styling */
    .logo-container {
    text-align: center;
    margin-bottom: 10px;
    }

    .receipt-logo {
    max-width: 120px;
    height: auto;
    }

    /* Content styling */
    .content {
    margin: 15px 0;
    }

    .content p {
    margin: 5px 0;
    display: flex;
    justify-content: space-between;
    }

    .highlight {
    font-family: 'FuturaLT-Bold', 'Arial', sans-serif;
    color: #d9292a;
    }

    .amount {
    font-family: 'FuturaLT-Bold', 'Arial', sans-serif;
    }

    /* Divider styling */
    .divider {
    border-top: 1px dashed #d9292a;
    margin: 10px 0;
    }

    /* Table styling */
    .routes-table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
    }

    .routes-table th {
    background-color: #d9292a;
    color: white;
    text-align: left;
    padding: 8px;
    font-family: 'FuturaLT-Bold', 'Arial', sans-serif;
    }

    .routes-table td {
    border-bottom: 1px solid #ddd;
    padding: 8px;
    }

    .routes-table tr:nth-child(even) {
    background-color: #f9f0f0;
    }

    .routes-table tr:hover {
    background-color: #f2e5e5;
    }

    /* Footer styling */
    .footer {
    text-align: center;
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px dashed #d9292a;
    font-size: 12px;
    color: #666;
    }

    .contact {
    margin-top: 5px;
    color: #d9292a;
    }

    /* Small print */
    .small-print {
    font-size: 10px;
    color: #999;
    text-align: center;
    margin-top: 15px;
    }

    /* Receipt number */
    .receipt-number {
    font-size: 12px;
    color: #888;
    text-align: center;
    margin: 10px 0;
    }

    /* Date and time */
    .date-time {
    text-align: right;
    font-size: 12px;
    color: #888;
    margin-bottom: 10px;
    }

    /* Current trip highlight */
    .current-trip {
    background-color: #ffecec !important;
    font-weight: bold;
    }
    </style>
    </head>
    <body>
    <div class="container">
    <div class="receipt">
        <!-- Logo -->
        <div class="logo-container">
        <img src="{{ public_path('/images/nch-removebg-preview.png') }}" class="receipt-logo" alt="Company Logo">
        </div>

        <!-- Header -->
        <div class="header">
        <h1>NAMIBIA CONTRACT HAULAGE</h1>
        <div class="sub-header">BUS SMART CARD RECEIPT</div>
        </div>

        <!-- Date and Time -->
        <div class="date-time">
        Date: {{ date('d/m/Y') }}<br>
        Time: {{ date('H:i:s') }}
        </div>

        <!-- Receipt Number -->
        <div class="receipt-number">
        Receipt #: NCH-{{ mt_rand(10000, 99999) }}
        </div>

        <!-- Customer Details -->
        <div class="content">
        <p><span class="highlight">Customer:</span> {{ $name }}</p>

        <div class="divider"></div>

        <!-- Routes and Fares Table -->
        <table class="routes-table">
            <thead>
            <tr>
                <th>Route</th>
                <th>From</th>
                <th>To</th>
                <th>Fare (NAD)</th>
            </tr>
            </thead>
            <tbody>
            <tr class="{{ ($from == 'Windhoek' && $to == 'Swakopmund') ? 'current-trip' : '' }}">
                <td>Route </td>
                <td>{{ $from }}</td>
                <td>{{ $to }}</td>
                <td> {{ number_format($amount, 2) }}</td>
            </tr>

            </tbody>
        </table>

        <div class="divider"></div>

        <p><span class="highlight">New Balance:</span> <span class="amount">NAD {{ number_format($new_balance, 2) }}</span></p>
        </div>

        <!-- Footer -->
        <div class="footer">
        <p>Thank you for choosing Namibia Contract Haulage!</p>
        <div class="contact">
            Customer Service: +123 456 789
        </div>
        </div>

        <!-- Small Print -->
        <div class="small-print">
        <p>Receipt valid for 30 days from the date of purchase.</p>
        <p>Terms and conditions apply.</p>
        </div>
    </div>
    </div>
    </body>
    </html>
