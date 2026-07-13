<div style="font-family: 'Source Sans Pro', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #F5F0E8; padding: 40px 20px;">
    <div style="background: #1A1A1A; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;">
        <h1 style="color: #C9A84C; font-family: Georgia, serif; margin: 0; font-size: 24px;">Thank You for Your Pledge</h1>
    </div>

    <div style="background: white; padding: 30px; border-radius: 0 0 12px 12px; text-align: center;">
        <p style="font-size: 18px; color: #1A1A1A;">
            Dear {{ $pledge->name }},
        </p>

        <p style="color: #666; line-height: 1.6;">
            Thank you for pledging your support as a founding supporter of Threefold Artists. Your commitment helps us prepare to bring live performing arts to more communities once we are cleared to accept donations.
        </p>

        <div style="margin: 30px 0; padding: 20px; background: #F5F0E8; border-radius: 12px;">
            <div style="font-family: Georgia, serif; font-size: 36px; font-weight: bold; color: #8B1A2B;">
                ${{ number_format((float) $pledge->amount, 2) }}
            </div>
            <div style="color: #666; font-size: 14px; margin-top: 5px;">
                Pledge submitted on {{ $pledge->created_at->format('F j, Y') }}
            </div>
            <div style="margin-top: 14px; color: #666; font-size: 14px;">
                Public recognition: {{ $pledge->public_acknowledgment_consent ? 'Yes, you may thank me by name' : 'Please keep my pledge anonymous' }}
            </div>
        </div>

        <p style="color: #666; line-height: 1.6;">
            We will reach out when Threefold Artists is ready to accept donations and explain the next steps for honoring your pledge. No payment is due today.
        </p>

        <p style="color: #C9A84C; font-style: italic; font-family: Georgia, serif; font-size: 16px;">
            "As you give, it returns threefold."
        </p>

        <p style="color: #999; font-size: 12px; margin-top: 30px;">
            This pledge is an expression of intended support and is not a donation receipt.
        </p>
    </div>
</div>
