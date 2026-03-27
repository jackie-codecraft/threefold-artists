<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ArtistSeeder extends Seeder
{
    /**
     * Images are downloaded from Unsplash on first run and cached locally.
     * This makes the seeder portable — works on any machine with internet access.
     */
    public function run(): void
    {
        $artists = [
            [
                'name'        => 'James Hartwell',
                'slug'        => 'james-hartwell',
                'discipline'  => 'theatre',
                'bio'         => 'James is a classically trained actor with over fifteen years of stage experience, from Shakespeare to contemporary drama. He has performed at regional theatres across California and is passionate about bringing storytelling to audiences who rarely get to experience live theatre. His specialty is one-person shows and dramatic readings that can be adapted for any intimate space.',
                'is_featured' => true,
                'photo_url'   => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80',
                'photo_name'  => 'james-hartwell.jpg',
            ],
            [
                'name'        => 'Elena Vasquez',
                'slug'        => 'elena-vasquez',
                'discipline'  => 'theatre',
                'bio'         => 'Elena trained at the American Conservatory Theater in San Francisco and has spent the last decade performing in community and experimental theatre. She brings warmth and accessibility to every role, and specializes in interactive performances that invite audiences of all ages and backgrounds into the story. She speaks fluent Spanish and regularly performs bilingual pieces.',
                'is_featured' => true,
                'photo_url'   => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80',
                'photo_name'  => 'elena-vasquez.jpg',
            ],
            [
                'name'        => 'Marcus Chen',
                'slug'        => 'marcus-chen',
                'discipline'  => 'music',
                'bio'         => 'Marcus is a classically trained pianist and composer who studied at the San Francisco Conservatory of Music. He performs solo piano recitals ranging from Bach and Chopin to original compositions. His performances are thoughtfully adapted for care home and hospital settings, with a particular gift for reading the room and selecting music that resonates deeply with his audience.',
                'is_featured' => true,
                'photo_url'   => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80',
                'photo_name'  => 'marcus-chen.jpg',
            ],
            [
                'name'        => 'Sophie Laurent',
                'slug'        => 'sophie-laurent',
                'discipline'  => 'music',
                'bio'         => 'Sophie is a classically trained violinist and vocalist from Lyon, France, now based in Los Angeles. She performs a diverse repertoire spanning classical chamber music, French chansons, and contemporary songs. Her performances have moved audiences in hospitals, memory care facilities, and schools throughout the region. She has a particular gift for connecting with elderly audiences through the music of their era.',
                'is_featured' => false,
                'photo_url'   => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&q=80',
                'photo_name'  => 'sophie-laurent.jpg',
            ],
            [
                'name'        => 'Amara Osei',
                'slug'        => 'amara-osei',
                'discipline'  => 'dance',
                'bio'         => 'Amara is a contemporary and West African dancer who trained at the Alvin Ailey American Dance Theater. Her performances blend modern technique with cultural storytelling, creating powerful experiences that transcend language and age. She has developed specialized programs for senior living communities and rehabilitation centers that incorporate gentle movement and audience participation, bringing joy and dignity to every performance.',
                'is_featured' => false,
                'photo_url'   => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&q=80',
                'photo_name'  => 'amara-osei.jpg',
            ],
            [
                'name'        => 'Gabriel Santos',
                'slug'        => 'gabriel-santos',
                'discipline'  => 'music',
                'bio'         => 'Gabriel is a classically trained baritone and gospel vocalist from East Los Angeles. He studied voice at UCLA\'s Herb Alpert School of Music and has performed with choral ensembles and opera companies throughout Southern California. His warm, resonant voice moves effortlessly between operatic arias, jazz standards, and traditional gospel, making him one of Threefold Artists\' most versatile and beloved performers. Gabriel has a particular gift for connecting with audiences through story — weaving the background of each song into the performance itself, creating an experience that is both concert and conversation.',
                'is_featured' => true,
                'photo_url'   => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&q=80',
                'photo_name'  => 'gabriel-santos.jpg',
            ],
        ];

        $tempDir = storage_path('app/public/artists');
        File::ensureDirectoryExists($tempDir);

        foreach ($artists as $data) {
            $photoUrl  = $data['photo_url'];
            $photoName = $data['photo_name'];
            unset($data['photo_url'], $data['photo_name']);

            $artist = Artist::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Use bundled image if available, otherwise download from URL
            $bundledPath = database_path('seeders/images/artists/' . $photoName);
            $photoPath   = $tempDir . '/' . $photoName;

            if (File::exists($bundledPath)) {
                File::copy($bundledPath, $photoPath);
            } elseif (! File::exists($photoPath)) {
                $this->command->line("  Downloading {$photoName}...");
                $response = Http::withOptions(['allow_redirects' => true])->get($photoUrl);
                if ($response->successful()) {
                    File::put($photoPath, $response->body());
                } else {
                    $this->command->warn("  Could not download {$photoName} (HTTP {$response->status()}) — skipping photo.");
                    continue;
                }
            }

            // Attach via Spatie Media Library (replace existing)
            if (File::exists($photoPath)) {
                $artist->clearMediaCollection('photo');
                $artist->addMedia($photoPath)
                    ->preservingOriginal()
                    ->toMediaCollection('photo');
            }
        }

        $this->command->info('  ✓ ' . Artist::count() . ' artists seeded.');
    }
}
