<!DOCTYPE html>
<html>

<head>
    <title>Cakamba Digital Services</title>
    <style>
        .button {
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            background-color: #008CBA;
            color: #fff !important;
            cursor: pointer;
        }

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
        <h1>Welcome!</h1>
        <p>Hello {{ $mailData['name'] }},</p>
        <p>Thanks for creating an account on Cakamba Digital Store.</p>
        <p>Please click the button below to activate your account. </p>
        <a href={{ $mailData['link'] }} class="button">
            Activate Your Account
        </a>
        <br>
        <p>Thank you.</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Contact us at {{ $mailData['mail_from_address'] }}.</p>
    </div>

</body>

</html>
