<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        /* Global styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }

        /* Header */
        .header {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #cccccc;
        }

        .logo {
            display: inline-block;
            max-width: 100%;
            height: auto;
        }

        /* Main content */
        .content {
            padding: 20px;
            text-align: left;
            background-color: #ffffff;
            border-bottom: 1px solid #cccccc;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            margin-bottom: 20px;
        }

        p {
            margin: 0;
            margin-bottom: 10px;
        }

        /* Footer */
        .footer {
            background-color: #f2f2f2;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666666;
            border-top: 1px solid #cccccc;
        }

        .footer p {
            margin: 0;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="https://digitalstore.cakamba.com/logo.jpeg" alt="Logo" class="logo">
    </div>

    <!-- Main content -->
    <div class="content">
        <h1>Payment Alert</h1>
        <p>A payment of <strong>NGN{{ $mailData['amount'] }}</strong> has just been made to Cakamba Technology Limited by
            {{ $mailData['name'] }} for {{ $mailData['payment_title'] }}.</p>
        </p>The transaction Reference for this payment is {{ $mailData['flw_ref'] }}.</p>
    </div>

</body>

</html>
