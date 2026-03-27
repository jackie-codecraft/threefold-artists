<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        $artists = [
            [
                'name' => 'James Hartwell',
                'slug' => 'james-hartwell',
                'discipline' => 'theatre',
                'bio' => 'James is a classically trained actor with over fifteen years of stage experience, from Shakespeare to contemporary drama. He has performed at regional theatres across California and is passionate about bringing storytelling to audiences who rarely get to experience live theatre. His specialty is one-person shows and dramatic readings that can be adapted for any intimate space.',
                'is_featured' => true,
                'photo' => 'james-hartwell.jpg',
            ],
            [
                'name' => 'Elena Vasquez',
                'slug' => 'elena-vasquez',
                'discipline' => 'theatre',
                'bio' => 'Elena trained at the American Conservatory Theater in San Francisco and has spent the last decade performing in community and experimental theatre. She brings warmth and accessibility to every role, and specializes in interactive performances that invite audiences of all ages and backgrounds into the story. She speaks fluent Spanish and regularly performs bilingual pieces.',
                'is_featured' => true,
                'photo' => 'elena-vasquez.jpg',
            ],
            [
                'name' => 'Marcus Chen',
                'slug' => 'marcus-chen',
                'discipline' => 'music',
                'bio' => 'Marcus is a classically trained pianist and composer who studied at the San Francisco Conservatory of Music. He performs solo piano recitals ranging from Bach and Chopin to original compositions. His performances are thoughtfully adapted for care home and hospital settings, with a particular gift for reading the room and selecting music that resonates deeply with his audience.',
                'is_featured' => true,
                'photo' => 'marcus-chen.jpg',
            ],
            [
                'name' => 'Sophie Laurent',
                'slug' => 'sophie-laurent',
                'discipline' => 'music',
                'bio' => 'Sophie is a classically trained violinist and vocalist from Lyon, France, now based in Los Angeles. She performs a diverse repertoire spanning classical chamber music, French chansons, and contemporary songs. Her performances have moved audiences in hospitals, memory care facilities, and schools throughout the region. She has a particular gift for connecting with elderly audiences through the music of their era.',
                'is_featured' => false,
                'photo' => 'sophie-laurent.jpg',
            ],
            [
                'name' => 'Amara Osei',
                'slug' => 'amara-osei',
                'discipline' => 'dance',
                'bio' => 'Amara is a contemporary and West African dancer who trained at the Alvin Ailey American Dance Theater. Her performances blend modern technique with cultural storytelling, creating powerful experiences that transcend language and age. She has developed specialized programs for senior living communities and rehabilitation centers that incorporate gentle movement and audience participation, bringing joy and dignity to every performance.',
                'is_featured' => false,
                'photo' => 'amara-osei.jpg',
            ],
        ];

        foreach ($artists as $data) {
            $photo = $data['photo'];
            unset($data['photo']);

            $artist = Artist::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Add photo via Spatie Media Library
            $photoPath = storage_path('app/public/artists/' . $photo);
            if (File::exists($photoPath)) {
                $artist->clearMediaCollection('photo');
                $artist->addMedia($photoPath)
                    ->preservingOriginal()
                    ->toMediaCollection('photo');
            }
        }
    }
}
