<!DOCTYPE html>
<html>
<head>
    <title>New Contact Message</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #ef4444;">New Contact Form Submission</h2>
        <p>You have received a new message from the contact form.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Name:</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $contact->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Email:</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $contact->email }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Message:</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $contact->message }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Received At:</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $contact->created_at->format('M d, Y h:i A') }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px; font-size: 12px; color: #666;">This is an automated email from your website.</p>
    </div>
</body>
</html>
