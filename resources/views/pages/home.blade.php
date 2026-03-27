<x-layouts.app title="Home" metaDescription="Threefold Artists brings live performing arts to communities that cannot easily access them. Theatre, music, dance — keeping theatre alive.">

    {{-- Hero Section — Full-viewport, background image with overlay --}}
    <section class="relative min-h-screen flex items-end bg-theatre-black overflow-hidden">
        {{-- Background Image --}}
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1507924538820-ede94a04019d?w=1920&q=85"
                alt=""
                role="presentation"
                class="w-full h-full object-cover object-center opacity-40"
            >
            {{-- Gradient overlay — stronger at bottom for text legibility, lighter at top --}}
            <div class="absolute inset-0 bg-gradient-to-t from-theatre-black via-theatre-black/70 to-theatre-black/30"></div>
            {{-- Subtle left-side darkening for text area --}}
            <div class="absolute inset-0 bg-gradient-to-r from-theatre-black/80 via-theatre-black/20 to-transparent"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 sm:pb-32 pt-32">
            <div class="max-w-2xl">
                {{-- Gold accent line --}}
                <div class="w-16 h-px bg-stage-gold mb-10"></div>

                <h1 class="font-display text-6xl sm:text-7xl lg:text-8xl font-light leading-[0.9] text-white mb-8">
                    Keeping<br>
                    Theatre<br>
                    <em class="text-stage-gold not-italic">Alive</em>
                </h1>

                <p class="text-lg sm:text-xl text-gray-300 max-w-lg mb-4 leading-relaxed tracking-wide">
                    When people cannot come to the arts, we will come to them.
                </p>

                <p class="text-sm text-stage-gold font-display italic mb-12">
                    "As you give, you return threefold."
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-gray-100 transition-colors">
                        Request a Performance
                    </a>
                    <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-white text-white text-sm font-semibold tracking-wide uppercase hover:bg-white hover:text-theatre-black transition-colors">
                        Make a Donation
                    </a>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 text-white/40">
            <span class="text-[10px] tracking-[0.3em] uppercase">Scroll</span>
            <div class="w-px h-8 bg-white/20"></div>
        </div>
    </section>

    {{-- Three Pillars — Minimal, no cards --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Art Forms</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Three Art Forms, One Mission</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                {{-- Theatre --}}
                <div>
                    {{-- Comedy/Tragedy masks — bold filled silhouette --}}
                    <svg class="w-14 h-14 mb-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 70" fill="#1A1A1A">
                        {{-- Tragedy mask (left, frown, tilted back) --}}
                        <ellipse cx="32" cy="30" rx="22" ry="26" fill="#1A1A1A"/>
                        <ellipse cx="32" cy="30" rx="18" ry="22" fill="white"/>
                        {{-- Eyes --}}
                        <ellipse cx="25" cy="25" rx="4" ry="5" fill="#1A1A1A"/>
                        <ellipse cx="39" cy="25" rx="4" ry="5" fill="#1A1A1A"/>
                        {{-- Frown --}}
                        <path d="M24 40 Q32 34 40 40" stroke="#1A1A1A" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                        {{-- Comedy mask (right, smile, in front) --}}
                        <ellipse cx="68" cy="35" rx="22" ry="26" fill="#1A1A1A"/>
                        <ellipse cx="68" cy="35" rx="18" ry="22" fill="white"/>
                        {{-- Eyes --}}
                        <ellipse cx="61" cy="30" rx="4" ry="5" fill="#1A1A1A"/>
                        <ellipse cx="75" cy="30" rx="4" ry="5" fill="#1A1A1A"/>
                        {{-- Smile --}}
                        <path d="M60 44 Q68 52 76 44" stroke="#1A1A1A" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                        {{-- Ribbon --}}
                        <path d="M10 12 Q20 5 32 8 Q44 5 52 15 Q40 20 32 30 Q20 18 10 12Z" fill="#1A1A1A"/>
                    </svg>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Theatre</h3>
                    <p class="text-gray-500 leading-relaxed">From Shakespeare to contemporary plays, we bring the magic of live theatre to care homes, hospitals, shelters, and community centers.</p>
                </div>

                {{-- Music --}}
                <div>
                    {{-- Bold treble clef silhouette --}}
                    <svg class="w-14 h-14 mb-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 80" fill="#1A1A1A">
                        <path d="M30 4 C20 4 14 10 14 18 C14 26 19 32 26 35 L24 55 C22 58 19 60 16 61 C12 62 9 66 10 70 C11 74 15 76 19 76 C25 76 30 71 31 65 L34 38 C40 35 46 28 46 20 C46 11 39 4 30 4 Z M30 10 C36 10 40 14 40 20 C40 26 36 31 30 34 L28 34 L30 14 L30 10 Z M28 40 L26 60 C25 64 22 68 19 70 C17 70 15 69 15 67 C15 65 17 64 19 63 C23 62 27 58 28 54 L29 40 Z"/>
                        {{-- Two eighth notes below --}}
                        <rect x="8" y="52" width="6" height="18" rx="3"/>
                        <rect x="20" y="48" width="6" height="22" rx="3"/>
                        <rect x="8" y="52" width="18" height="4"/>
                        <rect x="8" y="58" width="18" height="4"/>
                    </svg>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Music</h3>
                    <p class="text-gray-500 leading-relaxed">Classical ensembles, solo performers, and musical groups that bring the healing power of live music to those who need it most.</p>
                </div>

                {{-- Dance --}}
                <div>
                    {{-- Bold dancer silhouette --}}
                    <svg class="w-14 h-14 mb-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 80" fill="#1A1A1A">
                        {{-- Head --}}
                        <circle cx="38" cy="8" r="7"/>
                        {{-- Body --}}
                        <path d="M38 15 C36 22 32 28 28 34 C25 38 22 42 20 48"/>
                        {{-- Torso fill --}}
                        <path d="M35 16 C33 22 30 28 27 34 C26 36 25 38 24 40 L20 38 C22 35 25 30 28 24 C30 19 32 17 35 16Z"/>
                        {{-- Left arm raised --}}
                        <path d="M36 20 C30 14 22 10 14 12 C12 12 10 14 12 16 C14 18 20 16 26 18 L32 22Z"/>
                        {{-- Right arm out --}}
                        <path d="M38 24 C44 22 50 24 54 28 C56 30 54 33 52 32 C48 30 44 28 40 28Z"/>
                        {{-- Skirt/legs --}}
                        <path d="M20 48 C18 54 14 62 12 70 C11 72 13 74 15 73 C17 72 19 66 22 60 L26 52Z"/>
                        <path d="M22 48 C24 54 28 62 32 68 C34 70 36 69 36 67 C36 65 32 60 30 54 L24 46Z"/>
                    </svg>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Dance</h3>
                    <p class="text-gray-500 leading-relaxed">Movement and dance performances that inspire, uplift, and connect audiences of all ages and abilities through the language of the body.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission Statement — Full width dark band --}}
    <section class="py-24 sm:py-32 bg-theatre-black text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-500 mb-8">Our Mission</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-light leading-snug mb-8">
                Everyone deserves access to the <span class="text-stage-gold">transformative power</span> of live performance.
            </h2>
            <p class="text-lg text-gray-400 leading-relaxed max-w-3xl">
                Threefold Artists is a nonprofit performing arts organization dedicated to bringing live theatre, music, and dance to communities that cannot easily access them. We serve care homes, hospitals, rehabilitation centers, shelters, schools, and community organizations — bringing professional performances to where people are.
            </p>
        </div>
    </section>

    {{-- Impact Counters --}}
    @if($metrics->isNotEmpty())
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">By the Numbers</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Our Impact</h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                @foreach($metrics as $metric)
                <div>
                    <div class="font-display text-5xl sm:text-6xl font-light text-theatre-black mb-2">{{ $metric->value }}</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-gray-500 font-medium">{{ $metric->label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
    <section class="py-24 sm:py-32 bg-linen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Voices</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">What People Say</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                @foreach($testimonials as $testimonial)
                <div>
                    <blockquote>
                        <p class="text-lg text-gray-700 leading-relaxed italic font-display mb-6">"{{ $testimonial->quote }}"</p>
                        <footer class="text-sm">
                            <cite class="not-italic font-semibold text-theatre-black">{{ $testimonial->attribution }}</cite>
                            @if($testimonial->venue_name)
                                <span class="block text-gray-400 mt-1">{{ $testimonial->venue_name }}</span>
                            @endif
                        </footer>
                    </blockquote>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Newsletter --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Stay Connected</p>
            <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black mb-6">Newsletter</h2>
            <p class="text-gray-500 leading-relaxed mb-12">Performance schedules, artist stories, and impact updates. No spam, ever.</p>

            @if(session('newsletter_success'))
                <p class="text-stage-gold font-display text-xl">{{ session('newsletter_success') }}</p>
            @else
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto">
                    @csrf
                    <input type="hidden" name="source" value="homepage">
                    <div class="flex gap-3">
                        <input type="email" name="email" required placeholder="Your email address" aria-label="Email address for newsletter" aria-required="true"
                            class="flex-1 border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0 text-center">
                        <button type="submit" class="px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                            Subscribe
                        </button>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 sm:py-32 bg-theatre-black text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-4xl sm:text-5xl font-light mb-6">Bring the Arts to Your Community</h2>
            <p class="text-lg text-gray-400 mb-12 max-w-2xl mx-auto">
                Whether you run a care home, hospital, school, or community center, we would love to bring a live performance to your space.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-gray-100 transition-colors">
                    Request a Performance
                </a>
                <a href="{{ route('get-involved') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-white text-white text-sm font-semibold tracking-wide uppercase hover:bg-white hover:text-theatre-black transition-colors">
                    Volunteer as an Artist
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
