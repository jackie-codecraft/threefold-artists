<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title'         => 'When the Curtain Rises in a Hospital Hallway',
                'category'      => 'stories',
                'author'        => 'Elena Vasquez',
                'published_at'  => now()->subDays(45),
                'is_published'  => true,
                'excerpt'       => 'What happens when a theatre performance finds its stage not in a grand playhouse but in the common room of a rehabilitation centre? Elena Vasquez reflects on what live performance means to those who cannot come to it.',
                'content'       => '<p>There is a moment every performer knows. The house lights dim. The audience settles. The world outside ceases to exist. For most people, that moment happens in a theatre — a dedicated space built specifically to hold the magic of live performance.</p>

<p>But for many people, the theatre simply is not accessible. Whether due to age, illness, mobility, financial barriers, or geography, millions of people in greater Los Angeles — and around the world — will never sit in a velvet seat and watch a curtain rise. Not because they do not want to. Not because they would not love it. But because the world has not found a way to bring the stage to them.</p>

<p>That is what Threefold Artists exists to change.</p>

<p>I performed my first Threefold set at St. Vincent\'s Hospital in March. The room was a common area on the third floor — fluorescent lights, linoleum floors, a cluster of chairs arranged loosely around a small open space. Not exactly the Ahmanson Theatre. But as I began my first monologue — a piece from García Lorca\'s <em>Blood Wedding</em>, translated into Spanish — something happened that I have experienced on every stage I have ever stood on. The room changed.</p>

<p>A woman in a wheelchair moved closer. An elderly man who had been staring at the window turned to watch. A nurse stopped in the doorway, then quietly sat down on a spare chair. By the time I finished the final scene, there was silence — not the awkward silence of indifference, but the particular silence of an audience that has been somewhere else entirely and is not quite ready to come back.</p>

<p>That is theatre. That is what it does, wherever it happens.</p>

<p>We sometimes talk about bringing the arts to underserved communities as if we are doing them a favour — delivering something they lack. But I have come to understand it differently. When I perform at a care home or a shelter or a school, I am not giving something to the audience. We are doing something together. We are creating a moment that would not exist without both of us.</p>

<p>Art is not a product. It is a relationship. And relationships can happen anywhere — even in a hospital hallway.</p>',
                'featured_image_url'  => 'https://images.unsplash.com/photo-1559494007-9f5847c49d94?w=1200&q=80',
                'featured_image_name' => 'blog-theatre-hallway.jpg',
            ],
            [
                'title'         => 'The Healing Power of Music: What Science Says and What We Know',
                'category'      => 'community',
                'author'        => 'Marcus Chen',
                'published_at'  => now()->subDays(32),
                'is_published'  => true,
                'excerpt'       => 'Music has been shown to reduce anxiety, ease pain, and lift mood in clinical settings. But what does it feel like to be on the other side of that equation — to be the musician in the room?',
                'content'       => '<p>Before I started performing with Threefold Artists, I knew the research. I had read the studies on music therapy in dementia care, on how familiar melodies can unlock memories that other pathways cannot reach. I had read about reductions in cortisol levels, improvements in post-operative recovery, the way music can reach patients in vegetative states who respond to nothing else.</p>

<p>I knew the data. What I did not know was what it would feel like to play Chopin in a room full of strangers who, within three minutes, would no longer be strangers at all.</p>

<h2>The Room Before</h2>

<p>My first performance for Threefold was at Cedars-Sinai — a lunchtime recital in a ground-floor atrium near the main entrance. I arrived early and sat at the piano while people walked past, barely glancing at me. The room smelled like a hospital. The acoustics were difficult. A maintenance worker ran a floor polisher nearby for the first ten minutes.</p>

<p>I played the Chopin <em>Nocturne in E-flat major</em>, Op. 9, No. 2. Within two minutes, the floor polisher had stopped. The maintenance worker was leaning against the wall, listening. A woman who had been walking briskly toward the exit slowed, stopped, and stood still for the remainder of the piece, her eyes closed.</p>

<h2>What Music Does</h2>

<p>We have known for centuries that music affects the body and mind in ways that other art forms do not. It activates more areas of the brain simultaneously than almost any other human activity. It synchronises heart rate and breathing. It triggers the release of dopamine — the same neurochemical that responds to food, sex, and other primary rewards.</p>

<p>But none of that explains the maintenance worker leaning against the wall. None of that explains the woman with closed eyes. Science can tell us the mechanism; it cannot tell us the meaning.</p>

<h2>What We Know</h2>

<p>After fifteen performances with Threefold, what I know is simpler than any study. Music connects people to themselves. It connects them to each other. It connects them to moments and memories and emotions that have nowhere else to go.</p>

<p>In a hospital, where so much is clinical and impersonal, that connection is not a luxury. It is a necessity.</p>

<p>When I finish a recital and see a patient tapping their foot, or a nurse humming a melody as she returns to work, I am not thinking about dopamine levels. I am thinking: <em>this is why I became a musician.</em></p>',
                'featured_image_url'  => 'https://images.unsplash.com/photo-1552422535-c45813c61732?w=1200&q=80',
                'featured_image_name' => 'blog-music-healing.jpg',
            ],
            [
                'title'         => 'Dance as Language: Performing for Audiences Who Cannot Speak',
                'category'      => 'artist-spotlight',
                'author'        => 'Amara Osei',
                'published_at'  => now()->subDays(18),
                'is_published'  => true,
                'excerpt'       => 'When Amara Osei performed at a memory care facility, she encountered an audience that could not tell her what they felt. What happened next changed how she thinks about performance forever.',
                'content'       => '<p>I trained at Alvin Ailey. I have performed on stages in New York, Los Angeles, and London. I have danced for critics, for choreographers, for audiences who came with programs in hand and opinions ready.</p>

<p>Nothing prepared me for the memory care unit at Sunrise.</p>

<p>The residents there are at various stages of Alzheimer\'s disease and other forms of dementia. Some can speak clearly. Some speak in fragments. Some have lost language almost entirely. When I was told I would be performing there, I honestly did not know what to expect. I did not know if they would understand what I was doing. I did not know if it would mean anything.</p>

<h2>Before Words</h2>

<p>Dance is older than language. Before humans had words for what they felt, they had movement. Grief makes you double over. Joy makes you leap. Fear makes you freeze. The body knows things the mind has not yet articulated, and dance — at its core — is the act of making those things visible.</p>

<p>This is why, I have come to believe, dance reaches people that language sometimes cannot.</p>

<p>When I began my set — a piece I had choreographed to West African percussion — a woman in the front row who had not spoken a word since I arrived began to move her hands. Not randomly. In rhythm. Her eyes were on my feet. Her hands moved with the beat. Her face, which had been neutral and distant, was alive.</p>

<h2>The Language Between</h2>

<p>We talk about accessibility in the arts as if it is primarily a physical problem — ramps, large-print programs, captioning. But the deepest form of accessibility is not physical at all. It is the question of whether a work of art can reach someone whose relationship with the world has fundamentally changed.</p>

<p>Dance answered that question for me that afternoon. The woman with the moving hands did not need words. She did not need to know who I was or what I was doing or why. The rhythm was there. Her body knew what to do with it.</p>

<p>I danced for another forty minutes. When I finished, she looked at me and said two words very clearly.</p>

<p>"Again, please."</p>',
                'featured_image_url'  => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=1200&q=80',
                'featured_image_name' => 'blog-dance-language.jpg',
            ],
            [
                'title'         => 'Why We Started Threefold Artists',
                'category'      => 'news',
                'author'        => 'Threefold Artists',
                'published_at'  => now()->subDays(60),
                'is_published'  => true,
                'excerpt'       => 'Every nonprofit has an origin story. Ours began with a simple question: why does access to live performing arts depend on where you live, what you earn, and whether your body will carry you to a theatre?',
                'content'       => '<p>Every nonprofit has an origin story. Ours began with a simple question.</p>

<p>Why does access to live performing arts depend on where you live, what you earn, and whether your body will carry you to a theatre?</p>

<p>Performing arts organisations in greater Los Angeles — and around the world — do extraordinary work. They produce world-class theatre, music, and dance. They build beautiful venues. They train remarkable artists. They create experiences that genuinely change people\'s lives.</p>

<p>But they are, almost without exception, built on a model that requires the audience to come to the art. Buy a ticket. Drive to a venue. Find parking. Sit in a seat designed for a body without significant mobility limitations. Stay for two hours. Drive home.</p>

<p>For a large portion of the population, this model simply does not work.</p>

<h2>The People We Were Thinking About</h2>

<p>We were thinking about the 85-year-old woman in a care home in Koreatown who grew up attending the theatre and has not been to a performance in twelve years — not because she stopped loving it, but because she can no longer get there.</p>

<p>We were thinking about the patient in a long-term rehabilitation unit who spends most of his days staring at a ceiling, waiting for the physical therapy sessions that break the monotony.</p>

<p>We were thinking about the children in under-resourced schools whose arts programs have been cut, who have never seen live theatre or a professional musician perform.</p>

<p>We were thinking about the people in shelters who are often made to feel invisible — who are not typically the target audience for any performing arts organisation.</p>

<h2>The Answer We Came To</h2>

<p>The answer was simple, even if the execution is complex: go to them.</p>

<p>We recruit performing artists who are willing to volunteer their time and skills. We connect them with venues that serve communities in need. We handle all the logistics. We show up. We perform. We leave. And we come back.</p>

<p>That is Threefold Artists. That is where we started, and that is where we are going.</p>

<p>If you are a performing artist who believes that your craft belongs to everyone, not just to those who can afford a ticket — we want to hear from you.</p>

<p>If you run a facility that would love to bring live arts to the people you serve — we want to hear from you too.</p>

<p><em>As you give, you return threefold.</em></p>',
                'featured_image_url'  => 'https://images.unsplash.com/photo-1507676184212-d03ab07a01bf?w=1200&q=80',
                'featured_image_name' => 'blog-origin-story.jpg',
            ],
            [
                'title'         => 'Painting Live: Why Fine Arts Belong on the Threefold Stage',
                'category'      => 'community',
                'author'        => 'Threefold Artists',
                'published_at'  => now()->subDays(8),
                'is_published'  => true,
                'excerpt'       => 'Theatre, music, and dance are Threefold\'s founding disciplines. But fine arts — live painting, collaborative visual art, drawing workshops — have quietly become one of the most powerful tools in our repertoire.',
                'content'       => '<p>When we talk about "performing arts," most people picture a stage. A performer. An audience watching.</p>

<p>Fine arts disrupts that model in an interesting way. A painting is usually experienced after the fact — you see the finished work in a gallery, framed and lit, with no idea of the thousands of decisions and movements that produced it. The process is invisible.</p>

<p>Live painting makes the process visible. And that changes everything.</p>

<h2>What Happens When You Watch Someone Create</h2>

<p>At our first fine arts session at Lincoln Elementary, we set up a large canvas in the school\'s gymnasium. Our artist arrived, mixed colours, and began to paint — a sweeping abstraction in deep blues and golds, inspired by the idea of a theatre curtain rising.</p>

<p>The children watched in complete silence for the first ten minutes. Then they began to inch forward. Then the questions started.</p>

<p>"Why did you use that colour?"<br>
"What\'s it going to look like when it\'s finished?"<br>
"Can I try?"</p>

<p>That last question is the one that changes a passive audience into an active participant. Fine arts, done in a community setting, has an invitation built into it that theatre and music do not always have. The canvas is there. The brushes are there. The act of creation is visible, and that visibility is an invitation.</p>

<h2>Collaboration as Performance</h2>

<p>At Harbor House Shelter, we tried something different. Instead of a single artist performing while the audience watched, we provided a large canvas and invited every resident who wanted to participate to add something to it. A colour, a line, a shape, a word. The artist facilitated — guiding, encouraging, blending, building something coherent from the collective contributions.</p>

<p>By the end of the session, the canvas held sixty different marks from sixty different people. It was, in the most literal sense, a community artwork. It was also — and several residents said this unprompted — the most creative thing they had done in years.</p>

<h2>Why Fine Arts Belong Here</h2>

<p>Threefold Artists is built on the belief that the performing arts belong to everyone. Fine arts extends that belief into a different dimension — not just the experience of art, but the making of it.</p>

<p>You do not need to be a musician to feel the power of music. But you also do not need to be a trained painter to pick up a brush. That accessibility — the sense that this is something you could do, that you are invited to try — is something fine arts offers that no other discipline in our repertoire quite matches.</p>

<p>We are glad it found its way onto our stage.</p>',
                'featured_image_url'  => 'https://images.unsplash.com/photo-1579762715118-a6f1d4b934f1?w=1200&q=80',
                'featured_image_name' => 'blog-fine-arts.jpg',
            ],
        ];

        $tempDir = storage_path('app/public/blog');
        File::ensureDirectoryExists($tempDir);

        foreach ($posts as $data) {
            $photoUrl  = $data['featured_image_url'];
            $photoName = $data['featured_image_name'];
            unset($data['featured_image_url'], $data['featured_image_name']);

            $data['slug'] = Str::slug($data['title']);

            $post = BlogPost::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Download featured image
            $photoPath = $tempDir . '/' . $photoName;
            if (! File::exists($photoPath)) {
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
                $post->clearMediaCollection('featured_image');
                $media = $post->addMedia($photoPath)
                    ->preservingOriginal()
                    ->toMediaCollection('featured_image');

                // Also store URL in the featured_image column for backwards compatibility
                $post->update(['featured_image' => $media->getUrl()]);
            }
        }

        $this->command->info('  ✓ ' . BlogPost::published()->count() . ' blog posts seeded.');
    }
}
