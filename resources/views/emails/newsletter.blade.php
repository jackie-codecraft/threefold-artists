<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->subject }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <!-- Header -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1A1A1A;">
        <tr>
            <td align="center" style="padding: 30px 20px;">
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <img src="{{ asset('images/logomark.png') }}" alt="Threefold Artists" width="60" style="display: block; max-width: 60px; height: auto; margin-bottom: 12px;">
                            <span style="color: #C9A84C; font-size: 18px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;">Threefold Artists</span>
                            <br>
                            <span style="color: #888888; font-size: 9px; letter-spacing: 4px; text-transform: uppercase;">Keeping Theatre Alive</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Gold accent line -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height: 3px; background-color: #C9A84C;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Body -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="color: #1A1A1A; font-size: 16px; line-height: 1.7;">
                            {!! $body !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1A1A1A;">
        <tr>
            <td align="center" style="padding: 30px 20px;">
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="color: #888888; font-size: 12px; line-height: 1.6;">
                            Threefold Artists Inc. &middot; Greater Los Angeles, California<br>
                            A 501(c)(3) nonprofit organization<br><br>
                            <a href="{{ $unsubscribeUrl }}" style="color: #C9A84C; text-decoration: underline;">Unsubscribe</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
