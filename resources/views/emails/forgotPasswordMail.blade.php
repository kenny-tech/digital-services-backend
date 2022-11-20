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
    </style>
</head>
<body>
   
    <p>Hello {{ $mailData['name'] }},</p>
    <p>Please click the 'Reset Password' button to reset your password.</p>
    <p>Please note that this link will expire in 15 minutes. </p>
    <a href={{ $mailData['link'] }} class="button">
        Reset Password
    </a>
    <br>
    <p>Thank you.</p>

</body>
</html>