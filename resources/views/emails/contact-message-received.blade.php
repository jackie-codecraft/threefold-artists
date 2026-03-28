<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
</head>
<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <!-- Header -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1A1A1A;">
        <tr>
            <td align="center" style="padding: 24px 20px;">
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <img src="{{ asset('images/logomark.png') }}" alt="Threefold Artists" width="50" style="display: block; max-width: 50px; height: auto; margin-bottom: 10px;">
                            <span style="color: #C9A84C; font-size: 16px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;">New Contact Message</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Gold accent -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center"><table width="600" cellpadding="0" cellspacing="0"><tr><td style="height: 3px; background-color: #C9A84C;"></td></tr></table></td></tr>
    </table>

    <!-- Body -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="color: #1A1A1A; font-size: 16px; line-height: 1.7;">
                            <p style="margin: 0 0 24px; color: #888; font-size: 14px;">A new contact form submission has been received.</p>

                            <!-- Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td width="100" style="padding: 10px 0; font-size: 13px; color: #888; vertical-align: top;">From</td>
                                    <td style="padding: 10px 0; font-size: 15px; color: #1A1A1A; font-weight: 600;">{{ $contactMessage->name }}</td>
                                </tr>
                                <tr><td colspan="2" style="border-top: 1px solid #f0f0f0;"></td></tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #888; vertical-align: top;">Email</td>
                                    <td style="padding: 10px 0; font-size: 15px; color: #1A1A1A;">{{ $contactMessage->email }}</td>
                                </tr>
                                @if($contactMessage->subject)
                                <tr><td colspan="2" style="border-top: 1px solid #f0f0f0;"></td></tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #888; vertical-align: top;">Subject</td>
                                    <td style="padding: 10px 0; font-size: 15px; color: #1A1A1A; font-weight: 600;">{{ $contactMessage->subject }}</td>
                                </tr>
                                @endif
                                <tr><td colspan="2" style="border-top: 1px solid #f0f0f0;"></td></tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #888; vertical-align: top;">Message</td>
                                    <td style="padding: 10px 0; font-size: 15px; color: #1A1A1A; line-height: 1.6;">{{ $contactMessage->message }}</td>
                                </tr>
                                <tr><td colspan="2" style="border-top: 1px solid #f0f0f0;"></td></tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #888; vertical-align: top;">Received</td>
                                    <td style="padding: 10px 0; font-size: 14px; color: #888;">{{ $contactMessage->created_at->format('M j, Y g:i A') }}</td>
                                </tr>
                            </table>

                            <!-- Reply button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $replyUrl }}"
                                           style="display: inline-block; background-color: #1A1A1A; color: #ffffff; font-size: 14px; font-weight: 600; text-decoration: none; padding: 14px 32px; letter-spacing: 1px; text-transform: uppercase;">
                                            Reply to {{ $contactMessage->name }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 11px; color: #cccccc; text-align: center; word-break: break-all;">{{ $replyUrl }}</p>
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
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="color: #888; font-size: 11px;">
                            Threefold Artists Admin Notification
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
