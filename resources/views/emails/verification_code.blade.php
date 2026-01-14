<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            margin: 0 0 20px 0;
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }
        .code-box {
            background-color: #f0f0f0;
            border: 2px solid #3B82F6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 48px;
            font-weight: bold;
            color: #3B82F6;
            letter-spacing: 8px;
            margin: 0;
            font-family: 'Courier New', monospace;
        }
        .footer {
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Email Verification</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name ?? 'User' }},</p>
            
            <p>Please use the following 4-digit code to verify your email address. This code will expire in 10 minutes.</p>
            
            <div class="code-box">
                <p class="code">{{ $code }}</p>
            </div>
            
            <p>If you did not request this verification code, please ignore this email.</p>
            
            <p style="color: #999; font-size: 14px;">This code is valid for 10 minutes only.</p>
        </div>
        <div class="footer">
            <p style="margin: 0;">© {{ date('Y') }} TPPOQR. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
