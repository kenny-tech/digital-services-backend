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
        }
    </style>
</head>
<body>
   
    <p>Hello {{ $mailData['name'] }},</p>
    <p>Thanks for creating an account on our digital services platform.</p>
    <p>Please click the button below to activate your account. </p>
    <button class="button">Activate Your Account</button>
    <br>
    <p>Thank you.</p>

</body>
</html>