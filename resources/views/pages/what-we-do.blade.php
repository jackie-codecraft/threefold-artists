<x-layouts.app title="What We Do" metaDescription="Threefold Artists brings theatre, music, dance, and fine arts to care homes, hospitals, shelters, schools, and community centers.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center opacity-20">
        <div class="absolute inset-0 bg-theatre-black/60"></div>
    </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Work</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">What We Do</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Professional performing arts, delivered to communities in need.</p>
        </div>
    </section>
    <div class="h-12 bg-gradient-to-b from-theatre-black to-white"></div>

    {{-- Art Forms --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Disciplines</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Our Art Forms</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                {{-- Theatre --}}
                <div>
                    <img src="{{ asset('images/icon-theatre.jpg') }}" alt="Theatre" class="w-16 h-16 object-contain mb-6">
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Theatre</h3>
                    <p class="text-gray-500 leading-relaxed">From dramatic readings and monologues to full ensemble productions, our theatre performances are adapted to suit any space and any audience. We bring stories to life in intimate settings that create powerful connections between performers and audiences.</p>
                </div>

                {{-- Music --}}
                <div>
                    <img src="{{ asset('images/icon-music.jpg') }}" alt="Music" class="w-16 h-16 object-contain mb-6">
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Music</h3>
                    <p class="text-gray-500 leading-relaxed">Solo vocalists, chamber groups, pianists, guitarists, and ensembles performing classical, jazz, folk, and popular music. Our musical performances are designed to engage, soothe, and inspire audiences of all ages and abilities.</p>
                </div>

                {{-- Dance --}}
                <div>
                    <img src="{{ asset('images/icon-dance.png') }}" alt="Dance" class="w-16 h-16 object-contain mb-6">
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Dance</h3>
                    <p class="text-gray-500 leading-relaxed">Contemporary, classical, and cultural dance performances that bring movement and expression into spaces where they are rarely seen. Our dancers adapt their performances to engage audiences, sometimes inviting gentle participation.</p>
                </div>

                {{-- Fine Arts --}}
                <div>
                    <img src="{{ asset('images/icon-fine-arts.jpg') }}" alt="Fine Arts" class="w-16 h-16 object-contain mb-6">
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Fine Arts</h3>
                    <p class="text-gray-500 leading-relaxed">Live painting demonstrations, collaborative art sessions, and visual art experiences that bring creativity and color into community spaces. Fine arts performances can be interactive, inviting audiences to participate in the creative process.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Venues We Serve --}}
    <section class="py-24 sm:py-32 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Communities</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Who We Serve</h2>
                <p class="text-lg text-gray-500 mt-4 max-w-2xl">We bring performances to any community that would benefit from live arts but cannot easily access them.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                @foreach([
                    ['Care Homes', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['Hospitals', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['Schools', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['Shelters', 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ['Rehab Centers', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['Community Centers', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as [$label, $path])
                <div class="text-center">
                    <svg class="w-6 h-6 text-stage-gold mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}"/></svg>
                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-24 sm:py-32 bg-theatre-black text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-500 mb-4">The Process</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light">How It Works</h2>
                <p class="text-lg text-gray-400 mt-4">Three simple steps to bring live performance to your community.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                @foreach([
                    ['1', 'Request', 'Tell us about your venue, your audience, and what kind of performance you would like. We will match you with the right artists.'],
                    ['2', 'Prepare', 'We coordinate with our volunteer artists to design a performance tailored to your space and audience. We handle all the logistics.'],
                    ['3', 'Perform', 'Our artists arrive, set up, and deliver a professional-quality live performance. Your community experiences the magic of live arts.'],
                ] as [$number, $title, $description])
                <div>
                    <span class="font-display text-6xl font-light text-stage-gold mb-6 block">{{ $number }}</span>
                    <h3 class="font-display text-2xl font-normal mb-4">{{ $title }}</h3>
                    <p class="text-gray-400 leading-relaxed">{{ $description }}</p>
                </div>
                @endforeach
            </div>

            <div class="mt-20">
                <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-gray-100 transition-colors">
                    Request a Performance
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
