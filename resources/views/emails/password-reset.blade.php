<h1>Reset Password Notification</h1>
<p>You are receiving this email because we received a password reset request for your account.</p>
<p>Click the link below to reset your password:</p>
<a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}">Reset Password</a>
<p>If you did not request a password reset, no further action is required.</p>
