<div style="font-family: 'Source Sans Pro', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #F5F0E8; padding: 40px 20px;">
    <div style="background: #1A1A1A; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;">
        <h1 style="color: #C9A84C; font-family: Georgia, serif; margin: 0; font-size: 24px;">Thank You for Your Donation</h1>
    </div>
    <div style="background: white; padding: 30px; border-radius: 0 0 12px 12px; text-align: center;">
        <p style="font-size: 18px; color: #1A1A1A;">
            @if($donation->donor_name)Dear {{ $donation->donor_name }},@else Dear Friend,@endif
        </p>

        <p style="color: #666; line-height: 1.6;">
            Thank you for your generous donation to Threefold Artists. Your support helps us bring live performing arts to communities that need them most.
        </p>

        <div style="margin: 30px 0; padding: 20px; background: #F5F0E8; border-radius: 12px;">
            <div style="font-family: Georgia, serif; font-size: 36px; font-weight: bold; color: #8B1A2B;">
                ${{ number_format((float) $donation->amount, 2) }}
            </div>
            <div style="color: #666; font-size: 14px; margin-top: 5px;">
                Donation received on {{ $donation->created_at->format('F j, Y') }}
            </div>
        </div>

        <p style="color: #C9A84C; font-style: italic; font-family: Georgia, serif; font-size: 16px;">
            "As you give, you return threefold."
        </p>

        <p style="color: #999; font-size: 12px; margin-top: 30px;">
            Threefold Artists is a 501(c)(3) nonprofit organization. This donation is tax-deductible to the extent allowed by law.
            @if($donation->stripe_payment_id)
            <br>Transaction ID: {{ $donation->stripe_payment_id }}
            @endif
        </p>
    </div>
</div>
