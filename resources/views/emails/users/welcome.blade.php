<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body style="margin:0; padding:24px; background-color:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <div style="max-width:640px; margin:0 auto; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 12px 32px rgba(15, 23, 42, 0.08);">
        <div style="padding:24px 32px; background:linear-gradient(135deg, #0f766e, #0f172a); color:#ffffff;">
            <h1 style="margin:0; font-size:28px;">Welcome to Hospital Management System</h1>
            <p style="margin:12px 0 0; font-size:15px; line-height:1.6;">
                Your account is ready. You can now sign in and start using the platform.
            </p>
        </div>

        <div style="padding:32px;">
            <p style="margin-top:0; font-size:16px;">Hello {{ $user->name }},</p>

            <p style="font-size:15px; line-height:1.7;">
                We created an account for you on the hospital management portal. Your login details are below.
            </p>

            <div style="margin:24px 0; padding:20px; border:1px solid #dbe4f0; border-radius:12px; background-color:#f8fafc;">
                <p style="margin:0 0 12px; font-size:14px;"><strong>Email:</strong> {{ $user->email }}</p>

                @if (filled($plainTextPassword))
                    <p style="margin:0 0 12px; font-size:14px;"><strong>Temporary Password:</strong> {{ $plainTextPassword }}</p>
                @endif

                <p style="margin:0; font-size:14px;"><strong>Role:</strong> {{ $user->getRoleNames()->join(', ') ?: 'User' }}</p>
            </div>

            <p style="font-size:15px; line-height:1.7;">
                For security, please sign in and change your password as soon as possible.
            </p>

            <p style="margin:32px 0;">
                <a href="{{ url('/admin/login') }}" style="display:inline-block; padding:14px 22px; background-color:#0f766e; color:#ffffff; text-decoration:none; border-radius:10px; font-weight:700;">
                    Sign In to Your Account
                </a>
            </p>

            <p style="margin-bottom:0; font-size:14px; line-height:1.7; color:#475569;">
                If you did not expect this account, please contact the administrator.
            </p>
        </div>
    </div>
</body>
</html>
