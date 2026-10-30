<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\SiteSettingsPage;
use App\Filament\Resources\VolunteerApplicationResource;
use App\Filament\Resources\VolunteerApplicationResource\Pages\EditVolunteerApplication;
use App\Filament\Resources\VolunteerApplicationResource\Pages\ListVolunteerApplications;
use App\Mail\ContactMessageReceived;
use App\Mail\VolunteerApplicationSubmitted;
use App\Mail\VolunteerApplicationThankYou;
use App\Models\SiteSettings;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class VolunteerApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_involved_page_shows_volunteer_copy_form_and_privacy_notice(): void
    {
        $this->get(route('get-involved'))
            ->assertOk()
            ->assertSee('If you love theatre and have a heart for giving back')
            ->assertSee('Volunteer Opportunities')
            ->assertSee('Sound, Lighting &amp; Technical Support', false)
            ->assertSee('Join Our Volunteer Team')
            ->assertSee('name="interests[]"', false)
            ->assertSee('name="availability[]"', false)
            ->assertSee('type="checkbox" name="availability[]"', false)
            ->assertDontSee('type="radio"', false)
            ->assertSee('name="contact_consent"', false)
            ->assertSee('I agree to be contacted by Threefold Artists regarding volunteer opportunities.')
            ->assertSee('Your privacy:')
            ->assertSee('Submit Volunteer Interest Form')
            ->assertSee(route('artist-application.create'));
    }

    public function test_valid_submission_is_saved_and_emails_volunteer_and_admin(): void
    {
        Mail::fake();

        $this->postVolunteer($this->validPayload())
            ->assertRedirect(route('get-involved.thanks'));

        $application = VolunteerApplication::query()->sole();

        $this->assertSame('Jamie Stagehand', $application->name);
        $this->assertSame(['sound_lighting', 'photo_video_social'], $application->interests);
        $this->assertSame(['evenings', 'weekends'], $application->availability);
        $this->assertSame('new', $application->status);
        $this->assertNotNull($application->contact_consent_at);

        Mail::assertSent(VolunteerApplicationThankYou::class, fn ($mail) => $mail->hasTo('jamie@example.com'));
        Mail::assertSent(VolunteerApplicationSubmitted::class, fn ($mail) => $mail->hasTo(config('mail.from.address'))
            && $mail->hasReplyTo('jamie@example.com'));
    }

    public function test_thank_you_page_confirms_submission(): void
    {
        $this->get(route('get-involved.thanks'))
            ->assertOk()
            ->assertSee('Thank You for Volunteering!')
            ->assertSee('A confirmation email is on its way');
    }

    public function test_admin_notification_goes_to_configured_admin_email(): void
    {
        Mail::fake();
        SiteSettings::query()->updateOrCreate([], ['admin_notification_email' => 'volunteers@example.org']);

        $this->postVolunteer($this->validPayload())->assertRedirect(route('get-involved.thanks'));

        Mail::assertSent(VolunteerApplicationSubmitted::class, fn ($mail) => $mail->hasTo('volunteers@example.org')
            && ! $mail->hasTo(config('mail.from.address')));
    }

    public function test_name_email_interest_and_consent_are_required(): void
    {
        Mail::fake();

        $this->postVolunteer($this->validPayload([
            'name' => '',
            'email' => 'not-an-email',
            'interests' => [],
            'contact_consent' => null,
        ]), route('get-involved'))
            ->assertRedirect(route('get-involved'))
            ->assertSessionHasErrors(['name', 'email', 'interests', 'contact_consent']);

        $this->assertDatabaseCount(VolunteerApplication::class, 0);
        Mail::assertNothingSent();
    }

    public function test_phone_experience_availability_and_message_are_optional(): void
    {
        Mail::fake();

        $payload = $this->validPayload();
        unset($payload['phone'], $payload['availability'], $payload['experience'], $payload['message']);

        $this->postVolunteer($payload)->assertRedirect(route('get-involved.thanks'));

        $this->assertSame([], VolunteerApplication::query()->sole()->availability);
    }

    public function test_unknown_interest_or_availability_values_are_rejected(): void
    {
        $this->postVolunteer($this->validPayload([
            'interests' => ['sound_lighting', 'hacking'],
            'availability' => ['midnight'],
        ]), route('get-involved'))
            ->assertSessionHasErrors(['interests.1', 'availability.0']);

        $this->assertDatabaseCount(VolunteerApplication::class, 0);
    }

    public function test_honeypot_and_too_fast_submissions_are_rejected(): void
    {
        Mail::fake();

        $this->postVolunteer($this->validPayload(['website' => 'https://spam.example']), route('get-involved'))
            ->assertSessionHasErrors('website');

        $this->postVolunteer($this->validPayload(['form_started_at' => (string) now()->timestamp]), route('get-involved'))
            ->assertSessionHasErrors('form_started_at');

        $this->assertDatabaseCount(VolunteerApplication::class, 0);
        Mail::assertNothingSent();
    }

    public function test_turnstile_token_is_required(): void
    {
        $this->postVolunteer($this->validPayload(['cf-turnstile-response' => '']), route('get-involved'))
            ->assertSessionHasErrors('cf-turnstile-response');

        $this->assertDatabaseCount(VolunteerApplication::class, 0);
    }

    public function test_submissions_are_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->postVolunteer($this->validPayload(['email' => "person{$i}@example.com"]))
                ->assertRedirect(route('get-involved.thanks'));
        }

        $this->postVolunteer($this->validPayload(['email' => 'blocked@example.com']))->assertTooManyRequests();

        $this->assertDatabaseCount(VolunteerApplication::class, 3);
    }

    public function test_submission_is_kept_when_mail_delivery_fails(): void
    {
        Log::spy();
        Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP down'));

        $this->postVolunteer($this->validPayload())->assertRedirect(route('get-involved.thanks'));

        $this->assertDatabaseCount(VolunteerApplication::class, 1);
        Log::shouldHaveReceived('warning')->twice();
    }

    public function test_thank_you_email_uses_supplied_copy_and_first_name(): void
    {
        $application = VolunteerApplication::create($this->modelAttributes());

        $html = (new VolunteerApplicationThankYou($application))->render();

        $this->assertSame('Thank You for Your Interest in Threefold Artists', (new VolunteerApplicationThankYou($application))->envelope()->subject);
        $this->assertStringContainsString('Hi Jamie,', $html);
        $this->assertStringContainsString('Thank you so much for your interest in volunteering with Threefold Artists!', $html);
        $this->assertStringContainsString('The Threefold Artists Team', $html);
        $this->assertStringContainsString('Keeping Theatre Alive', $html);
    }

    public function test_admin_email_lists_answers_and_links_to_admin_record(): void
    {
        $application = VolunteerApplication::create($this->modelAttributes());

        $html = (new VolunteerApplicationSubmitted($application))->render();

        $this->assertStringContainsString('Sound / QLab / Lighting, Photography / Video / Social Media', $html);
        $this->assertStringContainsString('Evenings, Weekends', $html);
        $this->assertStringContainsString(VolunteerApplicationResource::getUrl('edit', ['record' => $application]), $html);
    }

    public function test_navigation_homepage_and_footer_link_to_get_involved(): void
    {
        $home = $this->get(route('home'))->assertOk();

        $home->assertSee('>Get Involved</a>', false)
            ->assertSee('Volunteer With Us')
            ->assertSee('Apply as an Artist');

        $this->assertGreaterThanOrEqual(4, substr_count($home->getContent(), 'href="'.route('get-involved').'"'));
    }

    public function test_artist_application_moved_under_get_involved(): void
    {
        $this->get(route('artist-application.create'))
            ->assertOk()
            ->assertSee('Apply as an Artist')
            ->assertSee('Artist Application')
            ->assertSee('action="'.route('artist-application.store').'"', false);

        $this->assertSame(url('/get-involved/artists'), route('artist-application.create'));

        $this->get(route('artists'))->assertSee(route('artist-application.create'));
    }

    public function test_admin_can_list_filter_and_update_follow_up_without_changing_submission(): void
    {
        $user = User::factory()->create();
        $application = VolunteerApplication::create($this->modelAttributes());
        $other = VolunteerApplication::create($this->modelAttributes([
            'name' => 'Pat Publicist',
            'email' => 'pat@example.com',
            'interests' => ['marketing_outreach'],
        ]));

        $this->actingAs($user)->get(VolunteerApplicationResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Jamie Stagehand');

        Livewire::actingAs($user)
            ->test(ListVolunteerApplications::class)
            ->assertCanSeeTableRecords([$application, $other])
            ->filterTable('interests', 'sound_lighting')
            ->assertCanSeeTableRecords([$application])
            ->assertCanNotSeeTableRecords([$other]);

        $this->assertFalse(VolunteerApplicationResource::canCreate());

        $this->actingAs($user)->get(VolunteerApplicationResource::getUrl('edit', ['record' => $application]))
            ->assertOk()
            ->assertSee('Follow-up');

        Livewire::actingAs($user)
            ->test(EditVolunteerApplication::class, ['record' => $application->getRouteKey()])
            ->fillForm([
                'status' => 'contacted',
                'internal_notes' => 'Called on Tuesday.',
                'name' => 'Tampered Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $application->refresh();
        $this->assertSame('contacted', $application->status);
        $this->assertSame('Called on Tuesday.', $application->internal_notes);
        $this->assertSame('Jamie Stagehand', $application->name);
    }

    public function test_admin_navigation_badge_counts_new_applications(): void
    {
        $this->assertNull(VolunteerApplicationResource::getNavigationBadge());

        VolunteerApplication::create($this->modelAttributes());
        VolunteerApplication::create($this->modelAttributes(['status' => 'contacted']));

        $this->assertSame('1', VolunteerApplicationResource::getNavigationBadge());
    }

    public function test_admin_can_set_admin_notification_email_in_site_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(SiteSettingsPage::getUrl())
            ->assertOk()
            ->assertSee('Admin notifications email')
            ->assertSee('Leave blank to use '.config('mail.from.address'));

        Livewire::actingAs($user)
            ->test(SiteSettingsPage::class)
            ->fillForm(['admin_notification_email' => 'alerts@example.org'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('alerts@example.org', SiteSettings::current()->adminNotificationEmail());
    }

    public function test_admin_notification_email_falls_back_to_mail_from_address(): void
    {
        $this->assertSame(config('mail.from.address'), SiteSettings::current()->adminNotificationEmail());

        SiteSettings::query()->updateOrCreate([], ['admin_notification_email' => '   ']);

        $this->assertSame(config('mail.from.address'), SiteSettings::current()->adminNotificationEmail());
    }

    public function test_contact_notifications_use_admin_notification_email(): void
    {
        Mail::fake();
        SiteSettings::query()->updateOrCreate([], ['admin_notification_email' => 'alerts@example.org']);

        $this->withSession(['_token' => 't'])->post(route('contact.store'), [
            '_token' => 't',
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            'website' => '',
            'form_started_at' => (string) now()->subSeconds(10)->timestamp,
            'cf-turnstile-response' => 'test-token',
        ])->assertRedirect(route('contact.thanks'));

        Mail::assertSent(ContactMessageReceived::class, fn ($mail) => $mail->hasTo('alerts@example.org'));
    }

    private function postVolunteer(array $payload, ?string $from = null): TestResponse
    {
        $request = $this->withSession(['_token' => 'test-token']);

        if ($from !== null) {
            $request = $request->from($from);
        }

        return $request->post(route('get-involved.store'), array_merge($payload, ['_token' => 'test-token']));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jamie Stagehand',
            'email' => 'jamie@example.com',
            'phone' => '555-0100',
            'interests' => ['sound_lighting', 'photo_video_social'],
            'availability' => ['evenings', 'weekends'],
            'experience' => 'Ran QLab for a community theatre.',
            'message' => 'Happy to help with load-in.',
            'contact_consent' => '1',
            'website' => '',
            'form_started_at' => (string) now()->subSeconds(10)->timestamp,
            'cf-turnstile-response' => 'test-token',
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function modelAttributes(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jamie Stagehand',
            'email' => 'jamie@example.com',
            'interests' => ['sound_lighting', 'photo_video_social'],
            'availability' => ['evenings', 'weekends'],
            'contact_consent_at' => now(),
        ], $overrides);
    }
}
