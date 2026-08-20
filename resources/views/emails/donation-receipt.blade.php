<div style="font-family: 'Source Sans Pro', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #F5F0E8; padding: 40px 20px;">
    <div style="background: #1A1A1A; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;">
        <h1 style="color: #C9A84C; font-family: Georgia, serif; margin: 0; font-size: 24px;">Donation Receipt</h1>
    </div>
    <div style="background: white; padding: 30px; border-radius: 0 0 12px 12px; text-align: center;">
        <p style="font-size: 18px; color: #1A1A1A;">
            @if($donation->donor_name)Dear {{ $donation->donor_name }},@else Dear Friend,@endif
        </p>

        <p style="color: #666; line-height: 1.6;">
            Thank you for your generous donation to {{ $organization['legal_name'] }}. Your support helps us bring live performing arts to communities that need them most.
        </p>

        <div style="margin: 30px 0; padding: 20px; background: #F5F0E8; border-radius: 12px;">
            <div style="font-family: Georgia, serif; font-size: 36px; font-weight: bold; color: #8B1A2B;">
                ${{ number_format(($donation->amount_cents ?? (int) round(((float) $donation->amount) * 100)) / 100, 2) }}
            </div>
            <div style="color: #666; font-size: 14px; margin-top: 5px;">
                Donation received on {{ ($donation->paid_at ?? $donation->created_at)->format('F j, Y') }}
            </div>
        </div>

        <table role="presentation" style="width: 100%; border-collapse: collapse; text-align: left; color: #444; font-size: 14px;">
            <tr><td style="padding: 7px 0; font-weight: bold;">Legal name</td><td style="padding: 7px 0;">{{ $organization['legal_name'] }}</td></tr>
            <tr><td style="padding: 7px 0; font-weight: bold;">EIN</td><td style="padding: 7px 0;">{{ $organization['tax_id'] }}</td></tr>
            @if($organization['mailing_address'])
                <tr><td style="padding: 7px 0; font-weight: bold; vertical-align: top;">Mailing address</td><td style="padding: 7px 0; white-space: pre-line;">{{ $organization['mailing_address'] }}</td></tr>
            @endif
            @if($transactionId)
                <tr><td style="padding: 7px 0; font-weight: bold;">Transaction ID</td><td style="padding: 7px 0; word-break: break-all;">{{ $transactionId }}</td></tr>
            @endif
        </table>

        <p style="color: #444; line-height: 1.6; font-size: 14px; margin-top: 28px;">
            {{ $organization['tax_status'] }}
        </p>
        <p style="color: #444; line-height: 1.6; font-size: 14px;">
            {{ $organization['tax_deductibility_statement'] }}
        </p>
        <p style="color: #444; line-height: 1.6; font-size: 14px;">
            {{ $organization['receipt_disclosure'] }}
        </p>

        <p style="color: #C9A84C; font-style: italic; font-family: Georgia, serif; font-size: 16px;">
            "As you give, it returns threefold."
        </p>
    </div>
</div>
