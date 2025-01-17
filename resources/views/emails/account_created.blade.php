<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created</title>
</head>
<body>
<h1>Welcome, {{ $user->name }}!</h1>
<p>Thank you for creating an account with us. Here are your account details:</p>
<ul>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Account Created:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</li>
</ul>
<p>If you have any questions, feel free to contact our support team.</p>
<p>Best regards,</p>
<p>The Team</p>
</body>
</html>
