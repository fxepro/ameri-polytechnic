<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: #fff; margin: 0;">American Polytechnic</h1>
    </div>
    
    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px;">
        <h2 style="color: #2c3e50; margin-top: 0;">Verify Your Email Address</h2>
        
        <p>Hello {{ $user->first_name ?? 'there' }},</p>
        
        <p>Thank you for registering with American Polytechnic! Please verify your email address by clicking the button below:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
               style="display: inline-block; background: #D4AF37; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Verify Email Address
            </a>
        </div>
        
        <p>Or copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #666; font-size: 12px;">{{ $verificationUrl }}</p>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            This verification link will expire in 24 hours. If you didn't create an account, please ignore this email.
        </p>
        
        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
        
        <p style="color: #666; font-size: 12px; margin: 0;">
            © {{ date('Y') }} American Polytechnic. All rights reserved.
        </p>
    </div>
</body>
</html>

