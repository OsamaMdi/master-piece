<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 30px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr style="background-color: #007bff; color: #ffffff;">
                        <td style="padding: 20px; text-align: center;">
                            <h2 style="margin: 0;">Rentify</h2>
                            <p style="margin: 5px 0 0;">Reservation Confirmed ✅</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <p>Hello <strong>{{ $user->name }}</strong>,</p>

                            <p>Thank you for booking with <strong>Rentify</strong>! We're happy to confirm your reservation.</p>

                            <h4 style="margin-top: 30px; color: #007bff;">Reservation Details</h4>
                            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; margin-top: 10px;">
                                <tr style="background-color: #f9f9f9;">
                                    <td><strong>Product:</strong></td>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Start Date:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('F j, Y') }}</td>
                                </tr>
                                <tr style="background-color: #f9f9f9;">
                                    <td><strong>End Date:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Price:</strong></td>
                                    <td>{{ number_format($reservation->total_price, 2) }} JOD</td>
                                </tr>
                                <tr style="background-color: #f9f9f9;">
                                    <td><strong>Delivery Option:</strong></td>
                                    <td>{{ $product->is_deliverable ? 'Delivery Available' : 'Pickup Only' }}</td>
                                </tr>
                            </table>

                            <p style="margin-top: 20px;"><strong>Product Preview:</strong></p>
                            <p style="margin-top: 30px;">
                                You can manage your reservation anytime from your dashboard.<br>
                                If you have any questions, feel free to reply to this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f1f1; padding: 20px; text-align: center; font-size: 12px; color: #777;">
                            © {{ date('Y') }} Rentify. All rights reserved.<br>
                            www.rentify.com
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
