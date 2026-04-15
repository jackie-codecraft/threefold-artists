<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\ArtistApplicationResource\Pages\ListArtistApplications;
use App\Models\Artist;
use App\Models\ArtistApplication;
use App\Models\Discipline;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class ArtistApplicationConversionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_and_convert_an_application_into_an_inactive_artist(): void
    {
        $user = User::factory()->create();
        $music = Discipline::query()->where('slug', 'music')->firstOrFail();
        $dance = Discipline::query()->where('slug', 'dance')->firstOrFail();

        $application = ArtistApplication::create([
            'name' => 'Taylor Artist',
            'email' => 'taylor@example.com',
            'discipline' => 'music',
            'status' => 'pending',
            'bio' => 'Multidisciplinary performer',
        ]);

        $this->actingAs($user);

        Livewire::test(ListArtistApplications::class)
            ->callTableAction('approve', $application)
            ->assertHasNoTableActionErrors();

        $application->refresh();
        $this->assertSame('approved', $application->status);
        $this->assertNotNull($application->approved_at);

        Livewire::test(ListArtistApplications::class)
            ->callTableAction('convert_to_artist', $application, data: [
                'name' => 'Taylor Artist',
                'slug' => 'taylor-artist',
                'discipline_ids' => [$music->id, $dance->id],
                'bio' => 'Multidisciplinary performer',
                'is_featured' => false,
            ])
            ->assertHasNoTableActionErrors();

        $application->refresh();

        $artist = Artist::query()->where('slug', 'taylor-artist')->first();

        $this->assertNotNull($artist);
        $this->assertFalse($artist->is_active);
        $this->assertSame(['Dance', 'Music'], $artist->disciplines()->orderBy('name')->pluck('name')->all());
        $this->assertSame('converted', $application->status);
        $this->assertNotNull($application->converted_at);
        $this->assertSame($artist->id, $application->converted_artist_id);
    }

    public function test_artist_application_reply_does_not_overwrite_workflow_status(): void
    {
        $application = ArtistApplication::create([
            'name' => 'Jordan Artist',
            'email' => 'jordan@example.com',
            'discipline' => 'theatre',
            'status' => 'approved',
        ]);

        Mail::fake();

        $response = $this->post(URL::signedRoute('artist-application.reply.send', ['artistApplication' => $application->id]), [
            'reply_message' => 'Thanks for applying!',
        ]);

        $response->assertRedirect();

        $application->refresh();

        $this->assertSame('approved', $application->status);
        $this->assertSame('Thanks for applying!', $application->reply);
        $this->assertNotNull($application->replied_at);
    }
}
