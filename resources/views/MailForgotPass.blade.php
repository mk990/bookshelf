<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    body {
        background-color: #f5f5f5;
    }

    p {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 20px;
        color: #f59f00;
        font-size: bold;
    }
</style>

<body>
    <!-- forgot_password.blade.php -->

    {{-- <p>Dear {{ $name }},</p> --}}

    <p>You are receiving this email because we received a password reset request for your account.</p>

    <p>If you did not request a password reset, no further action is required.</p>

    <p>Otherwise, please click the link below to reset your password:</p>

    <p><a href="">Reset Password</a></p>

    <p>If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>

    {{-- <p>{{ $url }}</p> --}}

    <p>This password reset link will expire in minutes.</p>

    <p>If you have any issues, please contact our support team at {{ config('app.support_email') }}.</p>

    <p>Thank you for using our application!</p>

    <p>Best regards,</p>

    <p>{{ config('app.name') }}</p>

</body>

</html>
