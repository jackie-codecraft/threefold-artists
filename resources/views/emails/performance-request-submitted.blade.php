<div style="font-family: 'Source Sans Pro', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #F5F0E8; padding: 40px 20px;">
    <div style="background: #1A1A1A; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;">
        <h1 style="color: #C9A84C; font-family: Georgia, serif; margin: 0; font-size: 24px;">New Performance Request</h1>
    </div>
    <div style="background: white; padding: 30px; border-radius: 0 0 12px 12px;">
        <h2 style="color: #8B1A2B; font-family: Georgia, serif; margin-top: 0;">{{ $performanceRequest->organization_name }}</h2>

        <table style="width: 100%; border-collapse: collapse;">
            <tr><td style="padding: 8px 0; color: #666; width: 40%;">Venue Type:</td><td style="padding: 8px 0;">{{ $performanceRequest->venue_type }}</td></tr>
            <tr><td style="padding: 8px 0; color: #666;">Contact:</td><td style="padding: 8px 0;">{{ $performanceRequest->contact_name }}</td></tr>
            <tr><td style="padding: 8px 0; color: #666;">Email:</td><td style="padding: 8px 0;"><a href="mailto:{{ $performanceRequest->contact_email }}">{{ $performanceRequest->contact_email }}</a></td></tr>
            @if($performanceRequest->contact_phone)<tr><td style="padding: 8px 0; color: #666;">Phone:</td><td style="padding: 8px 0;">{{ $performanceRequest->contact_phone }}</td></tr>@endif
            @if($performanceRequest->audience_size)<tr><td style="padding: 8px 0; color: #666;">Audience Size:</td><td style="padding: 8px 0;">{{ $performanceRequest->audience_size }}</td></tr>@endif
            @if($performanceRequest->preferred_art_form)<tr><td style="padding: 8px 0; color: #666;">Preferred Art Form:</td><td style="padding: 8px 0;">{{ ucfirst($performanceRequest->preferred_art_form) }}</td></tr>@endif
        </table>

        @if($performanceRequest->notes)
        <div style="margin-top: 20px; padding: 15px; background: #F5F0E8; border-radius: 8px;">
            <strong>Notes:</strong><br>{{ $performanceRequest->notes }}
        </div>
        @endif

        <!-- Reply button -->
        <div style="margin-top: 24px; text-align: center;">
            <a href="{{ $replyUrl }}"
               style="display: inline-block; background-color: #1A1A1A; color: #ffffff; font-size: 14px; font-weight: 600; text-decoration: none; padding: 14px 32px; letter-spacing: 1px; text-transform: uppercase;">
                Reply to {{ $performanceRequest->contact_name }}
            </a>
        </div>

        <p style="margin-top: 16px; color: #999; font-size: 11px; text-align: center; word-break: break-all;">{{ $replyUrl }}</p>

        <p style="margin-top: 20px; color: #666; font-size: 14px;">Or manage this request in the <a href="{{ url('/admin') }}" style="color: #8B1A2B;">admin panel</a>.</p>
    </div>
</div>
