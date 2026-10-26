<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Account Credentials</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f7f5f2;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #2b2521;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #ede8e1;
        }
        .header {
            background: linear-gradient(135deg, #1f1b18 0%, #342c26 100%);
            padding: 36px 30px;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0 0 8px 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #e5d7c3;
            font-size: 14px;
            margin: 0;
        }
        .content {
            padding: 36px 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f1b18;
            margin-bottom: 16px;
        }
        .intro-text {
            font-size: 15px;
            color: #554e48;
            margin-bottom: 24px;
        }
        .credentials-card {
            background-color: #fcf9f5;
            border: 1px solid #ecd8af;
            border-left: 4px solid #d4af37;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 28px;
        }
        .cred-item {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .cred-item:last-child {
            margin-bottom: 0;
        }
        .cred-label {
            font-size: 13px;
            font-weight: 600;
            color: #796c61;
            width: 140px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cred-value {
            font-size: 15px;
            font-weight: 600;
            color: #1f1b18;
            font-family: monospace;
            background: #ffffff;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid #e5dcce;
            display: inline-block;
        }
        .role-badge {
            display: inline-block;
            background-color: #d4af37;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0 24px 0;
        }
        .cta-btn {
            background-color: #d4af37;
            color: #1f1b18 !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.35);
        }
        .cta-btn:hover {
            background-color: #c49e2e;
        }
        .notice-box {
            background-color: #fff9ea;
            border: 1px dashed #e3c473;
            border-radius: 6px;
            padding: 14px 18px;
            font-size: 13px;
            color: #7a5e18;
            margin-bottom: 24px;
        }
        .footer {
            background-color: #f7f4ef;
            padding: 24px 30px;
            text-align: center;
            font-size: 12px;
            color: #8c8278;
            border-top: 1px solid #eae4db;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Forever Missed Memorials</h1>
            <p>Administrative & Staff Portal Access</p>
        </div>

        <!-- Body -->
        <div class="content">
            <div class="greeting">Hello {{ $user->name }},</div>
            <p class="intro-text">
                Your staff administrative account has been successfully provisioned on the Forever Missed Memorial Portal. You can now access your dashboard using the credentials provided below.
            </p>

            <!-- Credentials Box -->
            <div class="credentials-card">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="cred-label" style="padding: 6px 0;">Assigned Role:</td>
                        <td style="padding: 6px 0;">
                            <span class="role-badge">{{ strtoupper($user->role) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cred-label" style="padding: 6px 0;">Username:</td>
                        <td style="padding: 6px 0;">
                            <span class="cred-value">{{ $user->username }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cred-label" style="padding: 6px 0;">Email Address:</td>
                        <td style="padding: 6px 0;">
                            <span class="cred-value">{{ $user->email }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cred-label" style="padding: 6px 0;">Temporary Password:</td>
                        <td style="padding: 6px 0;">
                            <span class="cred-value" style="color: #b45309;">{{ $temporaryPassword }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Login CTA -->
            <div class="button-wrapper">
                <a href="{{ $loginUrl }}" class="cta-btn" target="_blank">
                    Log In to Your Staff Account &rarr;
                </a>
            </div>

            <!-- Security Notice -->
            <div class="notice-box">
                <strong>Security Reminder:</strong> For your security, this is a temporary password. Please log into the portal and update your password immediately from your profile settings. Do not share these credentials with anyone.
            </div>

            <p style="font-size: 13px; color: #796c61; margin: 0;">
                If you encounter any issues accessing your account, please reach out directly to your Super Administrator.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Forever Missed Memorial Directory. All rights reserved.</p>
            <p>This automated message was sent securely regarding administrative access.</p>
        </div>
    </div>
</body>
</html>
