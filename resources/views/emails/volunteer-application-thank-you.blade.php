<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Interest in Threefold Artists</title>
</head>
<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <!-- Header -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1A1A1A;">
        <tr>
            <td align="center" style="padding: 24px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px;">
                    <tr>
                        <td align="center">
                            <img src="{{ asset('images/logomark.png') }}" alt="Threefold Artists" width="50" style="display: block; max-width: 50px; height: auto; margin-bottom: 10px;">
                            <span style="color: #C9A84C; font-size: 16px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;">Thank You</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Gold accent -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center"><table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px;"><tr><td style="height: 3px; background-color: #C9A84C;"></td></tr></table></td></tr>
    </table>

    <!-- Body -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px;">
                    <tr>
                        <td style="color: #1A1A1A; font-size: 16px; line-height: 1.7;">
                            <p style="margin: 0 0 20px;">Hi {{ $application->firstName() }},</p>
                            <p style="margin: 0 0 20px;">Thank you so much for your interest in volunteering with Threefold Artists!</p>
                            <p style="margin: 0 0 20px;">We're thrilled that you'd like to share your time and talents with us. Our volunteers play such an important role in helping us bring the joy of live theatre to the communities we serve.</p>
                            <p style="margin: 0 0 20px;">We've received your information and look forward to connecting with you as opportunities become available.</p>
                            <p style="margin: 0 0 32px;">Thank you for wanting to be part of something so special!</p>
                            <p style="margin: 0;">Warmly,</p>
                            <p style="margin: 0 0 32px; font-weight: 600;">The Threefold Artists Team</p>
                            <p style="margin: 0; color: #C9A84C; font-family: Georgia, serif; font-style: italic;">Keeping Theatre Alive</p>
                            <p style="margin: 4px 0 0;"><a href="{{ url('/') }}" style="color: #8B1A2B; text-decoration: none;">www.threefoldartists.org</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1A1A1A;">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px;">
                    <tr>
                        <td align="center" style="color: #888; font-size: 11px;">
                            You're receiving this because you submitted a volunteer interest form at threefoldartists.org.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
