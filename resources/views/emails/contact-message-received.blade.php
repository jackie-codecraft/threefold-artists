<div style="font-family: 'Source Sans Pro', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #F5F0E8; padding: 40px 20px;">
    <div style="background: #1A1A1A; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;">
        <h1 style="color: #C9A84C; font-family: Georgia, serif; margin: 0; font-size: 24px;">New Contact Message</h1>
    </div>
    <div style="background: white; padding: 30px; border-radius: 0 0 12px 12px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr><td style="padding: 8px 0; color: #666; width: 30%;">From:</td><td style="padding: 8px 0;">{{ $contactMessage->name }}</td></tr>
            <tr><td style="padding: 8px 0; color: #666;">Email:</td><td style="padding: 8px 0;"><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></td></tr>
            @if($contactMessage->subject)<tr><td style="padding: 8px 0; color: #666;">Subject:</td><td style="padding: 8px 0;">{{ $contactMessage->subject }}</td></tr>@endif
        </table>

        <div style="margin-top: 20px; padding: 15px; background: #F5F0E8; border-radius: 8px;">
            {{ $contactMessage->message }}
        </div>

        <p style="margin-top: 20px; color: #666; font-size: 14px;">Reply directly to <a href="mailto:{{ $contactMessage->email }}" style="color: #8B1A2B;">{{ $contactMessage->email }}</a>.</p>
    </div>
</div>
