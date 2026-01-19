<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $userName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        .qr-section {
            text-align: center;
            margin: 40px 0;
        }

        .qr-code {
            display: inline-block;
            padding: 20px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .qr-code svg {
            max-width: 300px;
            height: auto;
        }

        .info-section {
            background: #f0f9ff;
            border-left: 4px solid #3B82F6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 14px;
        }

        .info-label {
            font-weight: 600;
            color: #1f2937;
            min-width: 150px;
        }

        .info-value {
            color: #4b5563;
            word-break: break-all;
        }

        .validation-url {
            word-break: break-word;
            background: #f3f4f6;
            padding: 8px;
            border-radius: 4px;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }

        .instructions {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #92400e;
        }

        .instructions h3 {
            margin-bottom: 10px;
            color: #b45309;
        }

        .instructions ul {
            margin-left: 20px;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }

        .expiry-warning {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #991b1b;
            font-size: 13px;
        }

        .expiry-warning strong {
            display: block;
            margin-bottom: 5px;
            color: #7f1d1d;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>QR Code Access</h1>
            <p>This QR code grants temporary access to the system</p>
        </div>

        <!-- User Info -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">User Name:</span>
                <span class="info-value">{{ $userName }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $userEmail }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Generated:</span>
                <span class="info-value">{{ now()->format('M d, Y H:i:s') }}</span>
            </div>
        </div>

        <!-- Expiry Warning -->
        <div class="expiry-warning">
            <strong>⏰ Expires in: 10 Minutes</strong>
            This QR code will be valid until {{ \Carbon\Carbon::createFromTimestamp($expiresAt)->format('M d, Y H:i:s') }}
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            <p style="color: #6b7280; margin-bottom: 15px; font-size: 14px;">Scan this QR code to access your profile:</p>
            <div class="qr-code">
                <img src="{{ $qrBase64 }}" alt="QR Code" style="max-width: 300px; height: auto; display: block; margin: 0 auto;">
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p>This document was generated on {{ now()->format('M d, Y H:i:s') }}</p>
            <p>Keep this document secure and do not share it with others</p>
        </div>
    </div>
</body>
</html>
