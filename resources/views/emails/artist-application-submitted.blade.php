<div style="font-family: 'Source Sans Pro', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #F5F0E8; padding: 40px 20px;">
    <div style="background: #1A1A1A; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;">
        <h1 style="color: #C9A84C; font-family: Georgia, serif; margin: 0; font-size: 24px;">New Artist Application</h1>
    </div>
    <div style="background: white; padding: 30px; border-radius: 0 0 12px 12px;">
        <h2 style="color: #8B1A2B; font-family: Georgia, serif; margin-top: 0;">{{ $application->name }}</h2>

        <table style="width: 100%; border-collapse: collapse;">
            <tr><td style="padding: 8px 0; color: #666; width: 40%;">Email:</td><td style="padding: 8px 0;"><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></td></tr>
            @if($application->phone)<tr><td style="padding: 8px 0; color: #666;">Phone:</td><td style="padding: 8px 0;">{{ $application->phone }}</td></tr>@endif
            <tr><td style="padding: 8px 0; color: #666;">Discipline:</td><td style="padding: 8px 0;">{{ ucfirst(str_replace('_', ' ', $application->discipline)) }}</td></tr>
        </table>

        @if($application->experience)
        <div style="margin-top: 20px; padding: 15px; background: #F5F0E8; border-radius: 8px;">
            <strong>Experience:</strong><br>{{ $application->experience }}
        </div>
        @endif

        @if($application->bio)
        <div style="margin-top: 10px; padding: 15px; background: #F5F0E8; border-radius: 8px;">
            <strong>Bio:</strong><br>{{ $application->bio }}
        </div>
        @endif

        <p style="margin-top: 20px; color: #666; font-size: 14px;">Manage this application in the <a href="{{ url('/admin') }}" style="color: #8B1A2B;">admin panel</a>.</p>
    </div>
</div>
