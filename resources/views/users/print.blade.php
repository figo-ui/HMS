<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information - {{ $user->name }}</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
            color: #111827;
            line-height: 1.6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 24px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 16px;
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            color: #0f766e;
        }
        .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-top: 4px;
        }
        .info-section {
            margin-bottom: 24px;
            padding: 20px;
            border: 1px solid #dbe4f0;
            border-radius: 12px;
            background-color: #f8fafc;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #0f766e;
            border-bottom: 1px solid #dbe4f0;
            padding-bottom: 8px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
        }
        .info-label {
            font-weight: 600;
            width: 150px;
            color: #374151;
        }
        .info-value {
            flex: 1;
            color: #111827;
        }
        .credentials-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
        }
        .credentials-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
        }
        .credentials-note {
            font-size: 14px;
            color: #92400e;
            font-style: italic;
        }
        .print-button {
            display: none;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                margin: 0;
            }
        }
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1 class="title">Clinical Management System</h1>
            <p class="subtitle">User Account Information</p>
        </div>
        <div style="text-align: right;">
            <p><strong>Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
            <p><strong>User ID:</strong> {{ $user->id }}</p>
        </div>
    </div>

    <div class="info-section">
        <h2 class="section-title">Personal Information</h2>
        <div class="info-row">
            <span class="info-label">Full Name:</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
         <div class="info-row">
            <span class="info-label">Password:</span>
            <span class="info-value">{{ $user->password }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">{{ ucfirst($user->status ?? 'active') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Roles:</span>
            <span class="info-value">{{ $user->getRoleNames()->join(', ') ?: 'No roles assigned' }}</span>
        </div>
        @if($user->email_verified_at)
        <div class="info-row">
            <span class="info-label">Email Verified:</span>
            <span class="info-value">{{ $user->email_verified_at->format('M d, Y H:i') }}</span>
        </div>
        @endif
    </div>

    <div class="credentials-box">
        <h3 class="credentials-title">🔐 Login Credentials</h3>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Password: </span>
            <span class="info-value">
                @if(request()->has('temp_password') && !empty(request('temp_password')))
                    {{ request('temp_password') }}
                @else
                    [Password not provided - Please set manually or check email]
                @endif
            </span>
        </div>
        <p class="credentials-note">
            ⚠️ For security, please change this password upon first login.
        </p>
    </div>

    @if($user->doctors->count() > 0)
    <div class="info-section">
        <h2 class="section-title">Doctor Information</h2>
        @foreach($user->doctors as $doctor)
        <div class="info-row">
            <span class="info-label">Doctor ID:</span>
            <span class="info-value">{{ $doctor->doctor_id }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Specialization:</span>
            <span class="info-value">{{ $doctor->specialization ?? 'Not specified' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">License No:</span>
            <span class="info-value">{{ $doctor->license_no ?? 'Not specified' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Department:</span>
            <span class="info-value">{{ $doctor->department ?? 'Not assigned' }}</span>
        </div>
        @endforeach
    </div>
    @endif
    <div class="footer">
        <p>This document contains sensitive information. Please handle securely and share only with authorized personnel.</p>
        <p>Clinical Management System - Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
