<div style="font-family: 'Source Sans Pro', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #F5F0E8; padding: 40px 20px;">
    <div style="background: #1A1A1A; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;">
        <h1 style="color: #C9A84C; font-family: Georgia, serif; margin: 0; font-size: 24px;">Donation Activity</h1>
    </div>
    <div style="background: #FFFFFF; padding: 30px; border-radius: 0 0 12px 12px;">
        <h2 style="color: #1A1A1A; font-family: Georgia, serif; font-size: 24px; margin: 0 0 20px;">{{ $title }}</h2>
        <table role="presentation" style="width: 100%; border-collapse: collapse; color: #444; font-size: 14px;">
            @foreach($details as $label => $value)
                <tr>
                    <td style="padding: 8px 12px 8px 0; font-weight: bold; vertical-align: top; width: 38%;">{{ $label }}</td>
                    <td style="padding: 8px 0; vertical-align: top;">{{ $value }}</td>
                </tr>
            @endforeach
        </table>
        <p style="margin: 28px 0 0;">
            <a href="{{ $ledgerUrl }}" style="display: inline-block; background: #1A1A1A; color: #FFFFFF; padding: 12px 18px; text-decoration: none; font-size: 13px; font-weight: bold; letter-spacing: 0.04em; text-transform: uppercase;">Open Donation Ledger</a>
        </p>
    </div>
</div>
