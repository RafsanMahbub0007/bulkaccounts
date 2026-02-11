<!DOCTYPE html>
<html>
<head>
    <title>We Received Your Message</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #ef4444;">Thank You for Contacting Us!</h2>
        <p>Hi {{ $contact->name }},</p>
        <p>We have received your message and appreciate you reaching out to us.</p>
        <p>Our team will review your inquiry and get back to you as soon as possible.</p>
        
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Your Message:</strong><br>
            <p style="font-style: italic; color: #555;">"{{ $contact->message }}"</p>
        </div>

        <p>Best regards,<br>The Support Team</p>
    </div>
</body>
</html>
