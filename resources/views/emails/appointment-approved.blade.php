<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Approved</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 16px;
            margin-bottom: 25px;
            color: #1f2937;
        }
        
        .greeting strong {
            color: #059669;
        }
        
        .message {
            font-size: 15px;
            margin-bottom: 30px;
            color: #4b5563;
            line-height: 1.8;
        }
        
        .details-box {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 25px;
            margin: 30px 0;
            border-radius: 4px;
        }
        
        .details-box h3 {
            color: #059669;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .detail-label {
            color: #6b7280;
            font-weight: 500;
            min-width: 100px;
            margin-right: 15px;
        }
        
        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white !important;
            padding: 14px 35px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            margin: 30px 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .instructions {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
            font-size: 14px;
            color: #92400e;
            line-height: 1.7;
        }
        
        .instructions strong {
            color: #b45309;
        }
        
        .footer {
            background-color: #f3f4f6;
            padding: 30px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            margin-bottom: 10px;
        }
        
        .disclaimer {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-style: italic;
        }
        
        .badge {
            display: inline-block;
            background-color: #d1fae5;
            color: #059669;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .content {
                padding: 25px 20px;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                margin-bottom: 5px;
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Appointment Approved</h1>
            <p>Your appointment request has been successfully approved</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Hello <strong>{{ $user->name }}</strong>,
            </div>
            
            <!-- Message -->
            <div class="message">
                Great news! Your appointment request has been <strong style="color: #10b981;">successfully approved</strong>. 
                We're looking forward to meeting with you on the scheduled date.
            </div>
            
            <!-- Appointment Details -->
            <div class="details-box">
                <h3>📅 Appointment Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ $date }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Day:</span>
                    <span class="detail-value">{{ $dayOfWeek }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">
                        @if($appointment->appointment_time)
                            {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('h:i A') }}
                        @else
                            To be confirmed
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Purpose:</span>
                    <span class="detail-value">{{ $appointment->purpose }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="badge">Approved</span>
                    </span>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <strong>📝 Important:</strong> Please mark this date in your calendar and plan to arrive 
                a few minutes early. If you need to reschedule or have any questions, please reply to this 
                email or contact us directly.
            </div>
            
            <!-- CTA Button -->
            <center>
                <a href="{{ route('user.home') }}" class="cta-button">View Your Profile</a>
            </center>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>{{ config('app.name') }}</strong>
            </div>
            <div class="footer-text">
                If you did not request this appointment, please contact us immediately.
            </div>
            <div class="disclaimer">
                This is an automated message. Please do not reply to this email. 
                For inquiries, use the contact form on our website.
            </div>
        </div>
    </div>
</body>
</html>
