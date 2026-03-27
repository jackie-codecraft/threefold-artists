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
                    {{-- Lucide "theater" — comedy/tragedy masks --}}
                    <svg class="w-10 h-10 text-theatre-black mb-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 10s3-3 3-8"/>
                        <path d="M22 10s-3-3-3-8"/>
                        <path d="M10 2c0 4.4-3.6 8-8 8"/>
                        <path d="M14 2c0 4.4 3.6 8 8 8"/>
                        <path d="M2 10s2 2 2 5"/>
                        <path d="M22 10s-2 2-2 5"/>
                        <path d="M8 15h8"/>
                        <path d="M2 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1"/>
                        <path d="M14 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1"/>
                    </svg>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Theatre</h3>
                    <p class="text-gray-500 leading-relaxed">From Shakespeare to contemporary plays, we bring the magic of live theatre to care homes, hospitals, shelters, and community centers.</p>
                </div>

                {{-- Music --}}
                <div>
                    {{-- Lucide "music" — two-note musical score --}}
                    <svg class="w-10 h-10 text-theatre-black mb-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18V5l12-2v13"/>
                        <circle cx="6" cy="18" r="3"/>
                        <circle cx="18" cy="16" r="3"/>
                    </svg>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Music</h3>
                    <p class="text-gray-500 leading-relaxed">Classical ensembles, solo performers, and musical groups that bring the healing power of live music to those who need it most.</p>
                </div>

                {{-- Dance --}}
                {{-- Lucide "person-standing" — elegant figure in motion --}}
                <div>
                    <svg class="w-10 h-10 text-theatre-black mb-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="5" r="1"/>
                        <path d="m9 20 3-6 3 6"/>
                        <path d="m6 8 6 2 6-2"/>
                        <path d="M12 10v4"/>
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
