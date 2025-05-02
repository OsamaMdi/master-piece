<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Rentify</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr style="background-color: #007bff; color: #ffffff;">
                        <td style="padding: 20px; text-align: center;">
                            <h1 style="margin: 0;">Welcome to Rentify 👋</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #333;">Hi {{ $user->name }},</h2>

                            <p style="font-size: 16px; color: #555;">
                                We're thrilled to have you on board with <strong>Rentify</strong> — your trusted tool rental platform!
                            </p>

                            <p style="font-size: 16px; color: #555;">
                                You can now explore hundreds of tools, book what you need, and manage your reservations with ease.
                            </p>

                            <p style="font-size: 16px; color: #555;">
                                Need help? Just reply to this email and our support team will be happy to assist you.
                            </p>

                            <div style="margin: 30px 0; text-align: center;">
                                <a href="{{ url('/') }}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-size: 16px;">Start Browsing Tools 🔍</a>
                            </div>

                            <p style="font-size: 16px; color: #555;">We're glad you're here!</p>
                            <p style="font-size: 16px; color: #555;">— The Rentify Team</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f1f1; padding: 20px; text-align: center; font-size: 12px; color: #777;">
                            © {{ date('Y') }} Rentify. All rights reserved.<br>
                            <a href="{{ url('/') }}" style="color: #007bff;">www.rentify.com</a>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
