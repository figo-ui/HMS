<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal Credentials</title>
</head>
<body style="margin:0; padding:24px; background-color:#eef4ff; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <div style="max-width:680px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 14px 36px rgba(15, 23, 42, 0.08);">
        <div style="padding:24px 32px; background:linear-gradient(135deg, #0f766e, #1d4ed8); color:#ffffff;">
            <h1 style="margin:0; font-size:28px;">Your Patient Portal Is Ready</h1>
            <p style="margin:12px 0 0; line-height:1.6; font-size:15px;">
                We created your private patient account. Your login details are also attached as a PDF.
            </p>
        </div>

        <div style="padding:32px;">
            <p style="margin-top:0; font-size:16px;">Hello {{ $patient->full_name }},</p>

            <p style="font-size:15px; line-height:1.7;">
                You can now sign in to your private hospital portal to see your profile, appointments, history, reports, and notifications.
            </p>

            <div style="margin:24px 0; padding:20px; border:1px solid #dbe4f0; border-radius:12px; background-color:#f8fafc;">
                <p style="margin:0 0 12px; font-size:14px;"><strong>Portal URL:</strong> {{ url('/admin/login') }}</p>
                <p style="margin:0 0 12px; font-size:14px;"><strong>Email:</strong> {{ $user->email }}</p>
                <p style="margin:0 0 12px; font-size:14px;"><strong>Temporary Password:</strong> {{ $plainTextPassword }}</p>
                <p style="margin:0; font-size:14px;"><strong>Patient ID:</strong> {{ $patient->patient_id }}</p>
            </div>

            <p style="font-size:15px; line-height:1.7;">
                Please sign in and change your password after your first login.
            </p>

            <p style="margin:32px 0;">
                <a href="{{ url('/admin/login') }}" style="display:inline-block; padding:14px 22px; background-color:#0f766e; color:#ffffff; text-decoration:none; border-radius:10px; font-weight:700;">
                    Open Patient Portal
                </a>
            </p>

            <p style="margin-bottom:0; font-size:14px; line-height:1.7; color:#475569;">
                The attached PDF contains the same login information for easy printing or sharing with the patient.
            </p>
        </div>
    </div>
</body>
</html>
