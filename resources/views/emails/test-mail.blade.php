<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
</head>
<body style="margin:0; padding:24px; background-color:#f1f5f9; font-family:Arial, Helvetica, sans-serif; color:#0f172a;">
    <div style="max-width:620px; margin:0 auto; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(15, 23, 42, 0.08);">
        <div style="padding:24px 32px; background:linear-gradient(135deg, #0369a1, #0f172a); color:#ffffff;">
            <h1 style="margin:0; font-size:26px;">Test Email Successful</h1>
            <p style="margin:12px 0 0; font-size:15px; line-height:1.6;">
                This message confirms that your Laravel mail configuration is working.
            </p>
        </div>

        <div style="padding:32px;">
            <p style="margin-top:0; font-size:16px;">Hello,</p>

            <p style="font-size:15px; line-height:1.7;">
                This is a test email from your Hospital Management System project.
            </p>

            <div style="margin:24px 0; padding:18px 20px; border:1px solid #dbeafe; border-radius:12px; background-color:#f8fafc;">
                <p style="margin:0 0 10px; font-size:14px;"><strong>Recipient:</strong> {{ $recipientEmail }}</p>
                <p style="margin:0; font-size:14px;"><strong>Sent At:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>

            <p style="margin-bottom:0; font-size:14px; line-height:1.7; color:#475569;">
                If you received this email in your inbox, your SMTP setup is working.
            </p>
        </div>
    </div>
</body>
</html>
