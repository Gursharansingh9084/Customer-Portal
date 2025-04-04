<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Hi,</p>
    <p>You requested a password reset. Click the link below to reset your password:</p>
    <p>
        <a href="{{ env('FRONTEND_URL') }}/reset-password?token={{ $token }}">
            Reset Password
        </a>
    </p>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
