<x-layouts.app title="Home" metaDescription="Threefold Artists brings live performing arts to communities that cannot easily access them. Theatre, music, dance - keeping theatre alive.">

    {{-- Hero Section --}}
    <section class="relative bg-curtain-gradient overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-stage-gold rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-spotlight-amber rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Threefold Artists" class="mx-auto h-28 sm:h-36 w-auto mb-8 drop-shadow-2xl">

            <h1 class="font-display text-4xl sm:text-5xl lg:text-7xl font-bold text-white mb-6 text-shadow-theatrical">
                Keeping Theatre <span class="text-stage-gold">Alive</span>
            </h1>

            <p class="text-xl sm:text-2xl text-gray-200 max-w-3xl mx-auto mb-4 font-light tracking-wide">
                When people cannot come to the arts, we will come to them.
            </p>

            <p class="text-lg text-stage-gold italic font-display max-w-2xl mx-auto mb-12">
                "As you give, you return threefold."
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-md bg-stage-gold text-theatre-black text-lg font-bold hover:bg-stage-gold-light transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Request a Performance
                </a>
                <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-md border-2 border-white text-white text-lg font-bold hover:bg-white hover:text-theatre-black transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Make a Donation
                </a>
            </div>
        </div>

        {{-- Curtain curve at bottom --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 80L0 40C240 0 480 0 720 20C960 40 1200 40 1440 20L1440 80L0 80Z" fill="#F5F0E8"/>
            </svg>
        </div>
    </section>

    {{-- Three Pillars --}}
    <section class="py-20 sm:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">Three Art Forms, One Mission</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                {{-- Theatre --}}
                <div class="group text-center p-8 rounded-lg bg-white shadow-sm hover:shadow-xl transition-all duration-500 border border-linen-dark">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-stage-gold/10 flex items-center justify-center group-hover:bg-stage-gold/20 transition-colors">
                        <svg class="w-10 h-10 text-stage-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-theatre-black mb-3">Theatre</h3>
                    <p class="text-gray-600 leading-relaxed">From Shakespeare to contemporary plays, we bring the magic of live theatre to care homes, hospitals, shelters, and community centers.</p>
                </div>

                {{-- Music --}}
                <div class="group text-center p-8 rounded-lg bg-white shadow-sm hover:shadow-xl transition-all duration-500 border border-linen-dark">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-stage-gold/10 flex items-center justify-center group-hover:bg-stage-gold/20 transition-colors">
                        <svg class="w-10 h-10 text-stage-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-theatre-black mb-3">Music</h3>
                    <p class="text-gray-600 leading-relaxed">Classical ensembles, solo performers, and musical groups that bring the healing power of live music to those who need it most.</p>
                </div>

                {{-- Dance --}}
                <div class="group text-center p-8 rounded-lg bg-white shadow-sm hover:shadow-xl transition-all duration-500 border border-linen-dark">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-spotlight-amber/10 flex items-center justify-center group-hover:bg-spotlight-amber/20 transition-colors">
                        <svg class="w-10 h-10 text-spotlight-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-theatre-black mb-3">Dance</h3>
                    <p class="text-gray-600 leading-relaxed">Movement and dance performances that inspire, uplift, and connect audiences of all ages and abilities through the language of the body.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission Statement --}}
    <section class="py-20 bg-theatre-black text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full border border-stage-gold"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] rounded-full border border-stage-gold"></div>
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-16 h-1 bg-stage-gold mx-auto mb-8"></div>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold mb-8 leading-tight">
                Everyone deserves access to the <span class="text-stage-gold">transformative power</span> of live performance.
            </h2>
            <p class="text-lg text-gray-300 leading-relaxed max-w-3xl mx-auto">
                Threefold Artists is a nonprofit performing arts organization dedicated to bringing live theatre, music, and dance to communities that cannot easily access them. We serve care homes, hospitals, rehabilitation centers, shelters, schools, and community organizations - bringing professional performances to where people are.
            </p>
        </div>
    </section>

    {{-- Impact Counters --}}
    @if($metrics->isNotEmpty())
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">Our Impact</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($metrics as $metric)
                <div class="text-center p-6">
                    @if($metric->icon)
                        <div class="text-4xl mb-3">{{ $metric->icon }}</div>
                    @endif
                    <div class="font-display text-4xl sm:text-5xl font-bold text-stage-gold-dark mb-2">{{ $metric->value }}</div>
                    <div class="text-gray-600 font-medium">{{ $metric->label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
    <section class="py-20 bg-linen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">What People Say</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-lg p-8 shadow-sm border border-linen-dark relative">
                    <div class="text-stage-gold text-6xl font-display absolute -top-2 left-6 leading-none">"</div>
                    <blockquote class="relative pt-6">
                        <p class="text-gray-700 leading-relaxed italic mb-4">{{ $testimonial->quote }}</p>
                        <footer class="text-sm">
                            <cite class="not-italic font-semibold text-theatre-black">{{ $testimonial->attribution }}</cite>
                            @if($testimonial->venue_name)
                                <span class="block text-gray-500 mt-1">{{ $testimonial->venue_name }}</span>
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
    <section class="py-20 bg-curtain-gradient text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-64 h-64 bg-stage-gold rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-spotlight-amber rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-3xl sm:text-4xl font-bold mb-6">Bring the Arts to Your Community</h2>
            <p class="text-lg text-gray-200 mb-10 max-w-2xl mx-auto">
                Whether you run a care home, hospital, school, or community center, we would love to bring a live performance to your space.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-md bg-stage-gold text-theatre-black text-lg font-bold hover:bg-stage-gold-light transition-all duration-300 shadow-lg">
                    Request a Performance
                </a>
                <a href="{{ route('get-involved') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-md border-2 border-white text-white text-lg font-bold hover:bg-white hover:text-theatre-black transition-all duration-300">
                    Volunteer as an Artist
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
