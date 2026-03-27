<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GallerySeeder extends Seeder
{
    /**
     * Seed 12 gallery items with Unsplash performance photography.
     * Downloads images on first run and caches locally.
     */
    public function run(): void
    {
        $items = [
            [
                'title'      => 'Opening Night',
                'caption'    => 'A dramatic moment from our theatre programme at Sunrise Care Home.',
                'type'       => 'photo',
                'art_form'   => 'theatre',
                'is_featured'=> true,
                'photo_url'  => 'https://images.unsplash.com/photo-1507676184212-d03ab07a01bf?w=800&q=80',
                'photo_name' => 'gallery-theatre-01.jpg',
            ],
            [
                'title'      => 'The Stage is Set',
                'caption'    => 'Artists preparing for an evening performance at St. Vincent\'s Hospital.',
                'type'       => 'photo',
                'art_form'   => 'theatre',
                'is_featured'=> false,
                'photo_url'  => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80',
                'photo_name' => 'gallery-theatre-02.jpg',
            ],
            [
                'title'      => 'Shakespeare in the Common Room',
                'caption'    => 'James Hartwell performing Hamlet for residents of Magnolia Gardens.',
                'type'       => 'photo',
                'art_form'   => 'theatre',
                'is_featured'=> true,
                'photo_url'  => 'https://images.unsplash.com/photo-1507676184212-d03ab07a01bf?w=800&q=80',
                'photo_name' => 'gallery-theatre-03.jpg',
            ],
            [
                'title'      => 'Grace in Motion',
                'caption'    => 'Amara Osei performing her signature West African contemporary dance.',
                'type'       => 'photo',
                'art_form'   => 'dance',
                'is_featured'=> true,
                'photo_url'  => 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=800&q=80',
                'photo_name' => 'gallery-dance-01.jpg',
            ],
            [
                'title'      => 'Dance for Everyone',
                'caption'    => 'A participatory dance session at Eastside Community Center.',
                'type'       => 'photo',
                'art_form'   => 'dance',
                'is_featured'=> false,
                'photo_url'  => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=800&q=80',
                'photo_name' => 'gallery-dance-02.jpg',
            ],
            [
                'title'      => 'Ballet at the Bedside',
                'caption'    => 'Bringing classical dance to patients at Valley Presbyterian Hospital.',
                'type'       => 'photo',
                'art_form'   => 'dance',
                'is_featured'=> false,
                'photo_url'  => 'https://images.unsplash.com/photo-1518834107812-67b0b7c58434?w=800&q=80',
                'photo_name' => 'gallery-dance-03.jpg',
            ],
            [
                'title'      => 'Piano Reverie',
                'caption'    => 'Marcus Chen performing Chopin nocturnes at Cedars-Sinai Medical Center.',
                'type'       => 'photo',
                'art_form'   => 'music',
                'is_featured'=> true,
                'photo_url'  => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=800&q=80',
                'photo_name' => 'gallery-music-01.jpg',
            ],
            [
                'title'      => 'Strings and Souls',
                'caption'    => 'Sophie Laurent performing French chansons at Brentwood Gardens Care Home.',
                'type'       => 'photo',
                'art_form'   => 'music',
                'is_featured'=> false,
                'photo_url'  => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&q=80',
                'photo_name' => 'gallery-music-02.jpg',
            ],
            [
                'title'      => 'Chamber Music Evening',
                'caption'    => 'Marcus and Sophie performing together — a rare and beautiful duet.',
                'type'       => 'photo',
                'art_form'   => 'music',
                'is_featured'=> true,
                'photo_url'  => 'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?w=800&q=80',
                'photo_name' => 'gallery-music-03.jpg',
            ],
            [
                'title'      => 'Colors of Community',
                'caption'    => 'A live painting session at Lincoln Elementary — students watching in awe.',
                'type'       => 'photo',
                'art_form'   => 'fine_arts',
                'is_featured'=> false,
                'photo_url'  => 'https://images.unsplash.com/photo-1579762715118-a6f1d4b934f1?w=800&q=80',
                'photo_name' => 'gallery-arts-01.jpg',
            ],
            [
                'title'      => 'Art in Motion',
                'caption'    => 'Collaborative painting with residents at Harbor House Shelter.',
                'type'       => 'photo',
                'art_form'   => 'fine_arts',
                'is_featured'=> false,
                'photo_url'  => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&q=80',
                'photo_name' => 'gallery-arts-02.jpg',
            ],
            [
                'title'      => 'The Curtain Falls',
                'caption'    => 'Standing ovation from residents after Elena\'s bilingual performance.',
                'type'       => 'photo',
                'art_form'   => 'theatre',
                'is_featured'=> false,
                'photo_url'  => 'https://images.unsplash.com/photo-1503095396549-807759245b35?w=800&q=80',
                'photo_name' => 'gallery-theatre-04.jpg',
            ],
        ];

        $tempDir = storage_path('app/public/gallery');
        File::ensureDirectoryExists($tempDir);

        foreach ($items as $data) {
            $photoUrl  = $data['photo_url'];
            $photoName = $data['photo_name'];
            unset($data['photo_url'], $data['photo_name']);

            $item = GalleryItem::firstOrCreate(
                ['title' => $data['title']],
                $data
            );

            // Use bundled image if available, otherwise download from URL
            $bundledPath = database_path('seeders/images/gallery/' . $photoName);
            $photoPath   = $tempDir . '/' . $photoName;

            if (File::exists($bundledPath)) {
                File::copy($bundledPath, $photoPath);
            } elseif (! File::exists($photoPath)) {
                $this->command->line("  Downloading {$photoName}...");
                $response = Http::withOptions(['allow_redirects' => true])->get($photoUrl);
                if ($response->successful()) {
                    File::put($photoPath, $response->body());
                } else {
                    $this->command->warn("  Could not download {$photoName} (HTTP {$response->status()}) — skipping.");
                    continue;
                }
            }

            if (File::exists($photoPath)) {
                $item->clearMediaCollection('media');
                $item->addMedia($photoPath)
                    ->preservingOriginal()
                    ->toMediaCollection('media');
            }
        }

        $this->command->info('  ✓ ' . GalleryItem::count() . ' gallery items seeded.');
    }
}
