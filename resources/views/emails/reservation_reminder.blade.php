<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0;">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" style="background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <tr style="background-color: #007bff; color: #ffffff;">
                        <td style="padding: 20px; text-align: center;">
                            <h1 style="margin: 0;">Rentify</h1>
                            <p style="margin: 5px 0 0;">Reservation Reminder 🔔</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p>Hi <strong>{{ $reservation->user->name }}</strong>,</p>

                            <p>This is a friendly reminder that your reservation starts <strong>tomorrow</strong>.</p>

                            <h3 style="color: #007bff;">Reservation Details:</h3>
                            <ul style="padding-left: 20px; font-size: 15px; color: #333;">
                                <li><strong>Product:</strong> {{ $reservation->product->name }}</li>
                                <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($reservation->start_date)->format('F j, Y') }}</li>
                                <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($reservation->end_date)->format('F j, Y') }}</li>
                                <li><strong>Total Price:</strong> {{ number_format($reservation->total_price, 2) }} JOD</li>
                                <li><strong>Delivery:</strong> {{ $reservation->product->is_deliverable ? 'Delivery Available' : 'Pickup Only' }}</li>
                            </ul>

                            <p style="margin-top: 30px;">Make sure to be ready on the reservation day. You can manage your reservation anytime from your dashboard.</p>

                            <p>Best regards,<br><strong>The Rentify Team</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f1f1f1; text-align: center; padding: 15px; font-size: 12px; color: #777;">
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
