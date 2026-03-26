<x-layouts.app title="Home" metaDescription="Threefold Artists brings live performing arts to communities that cannot easily access them. Theatre, music, dance — keeping theatre alive.">

    {{-- Hero Section — Full-width, minimal, dramatic --}}
    <section class="bg-theatre-black text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 sm:py-40 lg:py-48">
            <div class="max-w-3xl">
                <h1 class="font-display text-5xl sm:text-6xl lg:text-8xl font-light leading-[0.95] mb-8">
                    Keeping<br>Theatre<br><span class="text-stage-gold font-normal italic">Alive</span>
                </h1>

                <p class="text-lg sm:text-xl text-gray-300 max-w-xl mb-4 leading-relaxed">
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
    </section>

    {{-- Three Pillars — Minimal, no cards --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Art Forms</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Three Art Forms, One Mission</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Theatre</h3>
                    <p class="text-gray-500 leading-relaxed">From Shakespeare to contemporary plays, we bring the magic of live theatre to care homes, hospitals, shelters, and community centers.</p>
                </div>

                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Music</h3>
                    <p class="text-gray-500 leading-relaxed">Classical ensembles, solo performers, and musical groups that bring the healing power of live music to those who need it most.</p>
                </div>

                <div>
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
