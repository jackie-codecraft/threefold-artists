<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ImpactMetric;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@threefoldartists.org'],
            [
                'name' => 'Admin',
                'password' => Hash::make('threefold2026!'),
                'email_verified_at' => now(),
            ]
        );

        // Impact Metrics
        $metrics = [
            ['label' => 'Performances Given', 'value' => '150+', 'icon' => '🎭', 'sort_order' => 1],
            ['label' => 'Communities Served', 'value' => '45+', 'icon' => '🏠', 'sort_order' => 2],
            ['label' => 'Audience Members Reached', 'value' => '5,000+', 'icon' => '👥', 'sort_order' => 3],
            ['label' => 'Volunteer Artists', 'value' => '80+', 'icon' => '🎵', 'sort_order' => 4],
        ];

        foreach ($metrics as $metric) {
            ImpactMetric::firstOrCreate(['label' => $metric['label']], $metric);
        }

        // Sample testimonials
        $testimonials = [
            [
                'quote' => 'The performance brought tears of joy to our residents. Many of them had not experienced live theatre in years. It was truly magical.',
                'attribution' => 'Margaret Collins',
                'venue_name' => 'Sunrise Care Home',
                'is_featured' => true,
            ],
            [
                'quote' => 'Our students were completely captivated. The musicians engaged them in ways that no recording ever could. This is what arts education should look like.',
                'attribution' => 'David Park',
                'venue_name' => 'Lincoln Elementary School',
                'is_featured' => true,
            ],
            [
                'quote' => 'For many of our guests, this was the first time they felt like valued audience members rather than charity cases. The dignity of the performance meant everything.',
                'attribution' => 'Sarah Mitchell',
                'venue_name' => 'Harbor House Shelter',
                'is_featured' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                ['attribution' => $testimonial['attribution']],
                $testimonial
            );
        }

        $this->call(ArtistSeeder::class);
    }
}
