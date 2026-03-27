<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Seed upcoming events across a 3-month window.
     * Dates are relative to the current date so the seeder stays fresh.
     */
    public function run(): void
    {
        $events = [
            // --- Late March ---
            [
                'title'         => 'An Evening of Shakespeare',
                'description'   => 'James Hartwell performs beloved scenes and soliloquies from Shakespeare — Hamlet, Macbeth, and A Midsummer Night\'s Dream. Adapted for an intimate care home setting, this performance brings the Bard to life in a way that is accessible, moving, and deeply human.',
                'date'          => now()->setDay(28)->format('Y-m-d'),
                'time'          => '14:00:00',
                'venue_name'    => 'Sunrise Care Home',
                'venue_address' => '1420 West 7th Street, Los Angeles, CA 90017',
                'art_form'      => 'theatre',
                'is_public'     => true,
            ],
            [
                'title'         => 'Classical Piano Afternoon',
                'description'   => 'Marcus Chen performs a programme of Chopin nocturnes, a Schubert impromptu, and his own original compositions. This intimate recital is designed to soothe, inspire, and transport the audience.',
                'date'          => now()->setDay(30)->format('Y-m-d'),
                'time'          => '15:30:00',
                'venue_name'    => 'St. Vincent\'s Hospital — Common Room',
                'venue_address' => '2131 West 3rd Street, Los Angeles, CA 90057',
                'art_form'      => 'music',
                'is_public'     => true,
            ],

            // --- April ---
            [
                'title'         => 'Songs of Spring',
                'description'   => 'Sophie Laurent performs French chansons, classical violin pieces, and beloved songs of the 20th century — from Édith Piaf to The Beatles. A warm and joyful afternoon concert for residents and their families.',
                'date'          => now()->addMonth()->setDay(5)->format('Y-m-d'),
                'time'          => '14:00:00',
                'venue_name'    => 'Magnolia Gardens Senior Living',
                'venue_address' => '3350 Wilshire Blvd, Los Angeles, CA 90010',
                'art_form'      => 'music',
                'is_public'     => true,
            ],
            [
                'title'         => 'Movement & Joy — Dance for Everyone',
                'description'   => 'Amara Osei brings her signature blend of contemporary and West African dance to Eastside Community Center. This participatory performance invites audiences to move together, celebrate their bodies, and connect through the universal language of dance.',
                'date'          => now()->addMonth()->setDay(11)->format('Y-m-d'),
                'time'          => '10:30:00',
                'venue_name'    => 'Eastside Community Center',
                'venue_address' => '1346 South Boyle Avenue, Los Angeles, CA 90023',
                'art_form'      => 'dance',
                'is_public'     => true,
            ],
            [
                'title'         => 'Voices & Stories — Bilingual Theatre',
                'description'   => 'Elena Vasquez performs a bilingual programme of monologues and dramatic readings in English and Spanish. The performance explores themes of belonging, memory, and community — told through characters that audiences across cultures will recognise.',
                'date'          => now()->addMonth()->setDay(18)->format('Y-m-d'),
                'time'          => '16:00:00',
                'venue_name'    => 'Harbor House Shelter',
                'venue_address' => '1400 East 7th Street, Los Angeles, CA 90021',
                'art_form'      => 'theatre',
                'is_public'     => true,
            ],
            [
                'title'         => 'Live Painting — Art in Motion',
                'description'   => 'Watch as our resident fine arts volunteer creates a full painting from blank canvas to finished artwork in a single session. The audience is invited to participate, suggest colours, and take part in the creative process.',
                'date'          => now()->addMonth()->setDay(25)->format('Y-m-d'),
                'time'          => '13:00:00',
                'venue_name'    => 'Lincoln Elementary School',
                'venue_address' => '2530 Workman Street, Los Angeles, CA 90031',
                'art_form'      => 'fine_arts',
                'is_public'     => true,
            ],
            [
                'title'         => 'Chamber Music Evening',
                'description'   => 'Marcus Chen and Sophie Laurent perform together for a rare chamber music evening — piano and violin duets ranging from Beethoven sonatas to contemporary works. An elegant and intimate concert experience.',
                'date'          => now()->addMonth()->setDay(30)->format('Y-m-d'),
                'time'          => '17:00:00',
                'venue_name'    => 'Cedars-Sinai Medical Center — Atrium',
                'venue_address' => '8700 Beverly Blvd, Los Angeles, CA 90048',
                'art_form'      => 'music',
                'is_public'     => true,
            ],

            // --- May ---
            [
                'title'         => 'The World\'s a Stage — Shakespeare for Young Audiences',
                'description'   => 'James Hartwell and Elena Vasquez team up for an energetic and accessible introduction to Shakespeare for school-age audiences. Comedy, swordfights (safely!), and audience participation bring the greatest playwright in history to life.',
                'date'          => now()->addMonths(2)->setDay(8)->format('Y-m-d'),
                'time'          => '10:00:00',
                'venue_name'    => 'Roosevelt High School — Auditorium',
                'venue_address' => '456 South Mathews Street, Los Angeles, CA 90033',
                'art_form'      => 'theatre',
                'is_public'     => true,
            ],
            [
                'title'         => 'Dance Through the Decades',
                'description'   => 'Amara Osei presents a dance journey through the 20th century — from swing and jazz to disco and hip-hop. Each era comes alive through movement, music, and storytelling, creating a joyful memory-making experience for older audiences.',
                'date'          => now()->addMonths(2)->setDay(15)->format('Y-m-d'),
                'time'          => '14:30:00',
                'venue_name'    => 'Valley Presbyterian Hospital',
                'venue_address' => '15107 Vanowen Street, Van Nuys, CA 91405',
                'art_form'      => 'dance',
                'is_public'     => true,
            ],
            [
                'title'         => 'An Afternoon of French Song',
                'description'   => 'Sophie Laurent performs a programme of classic French chansons and popular songs spanning five decades. A nostalgic and beautiful afternoon for residents of all backgrounds.',
                'date'          => now()->addMonths(2)->setDay(20)->format('Y-m-d'),
                'time'          => '15:00:00',
                'venue_name'    => 'Brentwood Gardens Care Home',
                'venue_address' => '11500 Goshen Avenue, Los Angeles, CA 90049',
                'art_form'      => 'music',
                'is_public'     => true,
            ],
            [
                'title'         => 'Monologues & Memories',
                'description'   => 'A moving and intimate performance by Elena Vasquez featuring monologues drawn from real stories of immigrants, caregivers, and community members in Los Angeles. This piece celebrates the dignity and richness of every life.',
                'date'          => now()->addMonths(2)->setDay(28)->format('Y-m-d'),
                'time'          => '16:30:00',
                'venue_name'    => 'St. Bernard High School',
                'venue_address' => '9100 Riverside Drive, Los Angeles, CA 90028',
                'art_form'      => 'theatre',
                'is_public'     => true,
            ],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(
                ['title' => $event['title'], 'date' => $event['date']],
                $event
            );
        }

        $this->command->info('  ✓ ' . Event::count() . ' events seeded.');
    }
}
