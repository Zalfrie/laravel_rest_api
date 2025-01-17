<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Notification</title>
</head>
<body>
<h1>New User Registered</h1>
<p>A new user has created an account on the platform. Here are their details:</p>
<ul>
    <li><strong>Name:</strong> {{ $user->name }}</li>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Account Created:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</li>
</ul>
<p>Please review the account if necessary.</p>
<p>Best regards,</p>
<p>The System</p>
</body>
</html>
