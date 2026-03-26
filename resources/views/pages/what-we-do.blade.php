<x-layouts.app title="What We Do" metaDescription="Threefold Artists brings theatre, music, dance, and fine arts to care homes, hospitals, shelters, schools, and community centers.">

    {{-- Page Hero --}}
    <section class="bg-curtain-gradient py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-spotlight-amber rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">What We Do</h1>
            <p class="text-xl text-gray-200">Professional performing arts, delivered to communities in need.</p>
        </div>
    </section>

    {{-- Art Forms --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">Our Art Forms</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Theatre --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-linen-dark">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-curtain-red/10 flex items-center justify-center shrink-0">
                            <svg class="w-7 h-7 text-curtain-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-theatre-black">Theatre</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed">From dramatic readings and monologues to full ensemble productions, our theatre performances are adapted to suit any space and any audience. We bring stories to life in intimate settings that create powerful connections between performers and audiences.</p>
                </div>

                {{-- Music --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-linen-dark">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-stage-gold/10 flex items-center justify-center shrink-0">
                            <svg class="w-7 h-7 text-stage-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-theatre-black">Music</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed">Solo vocalists, chamber groups, pianists, guitarists, and ensembles performing classical, jazz, folk, and popular music. Our musical performances are designed to engage, soothe, and inspire audiences of all ages and abilities.</p>
                </div>

                {{-- Dance --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-linen-dark">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-spotlight-amber/10 flex items-center justify-center shrink-0">
                            <svg class="w-7 h-7 text-spotlight-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-theatre-black">Dance</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed">Contemporary, classical, and cultural dance performances that bring movement and expression into spaces where they are rarely seen. Our dancers adapt their performances to engage audiences, sometimes inviting gentle participation.</p>
                </div>

                {{-- Fine Arts --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-linen-dark">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-curtain-red/10 flex items-center justify-center shrink-0">
                            <svg class="w-7 h-7 text-curtain-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="font-display text-2xl font-bold text-theatre-black">Fine Arts</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed">Live painting demonstrations, collaborative art sessions, and visual art experiences that bring creativity and color into community spaces. Fine arts performances can be interactive, inviting audiences to participate in the creative process.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Venues We Serve --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">Who We Serve</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto mb-6"></div>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">We bring performances to any community that would benefit from live arts but cannot easily access them.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach([
                    ['Care Homes', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['Hospitals', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['Schools', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['Shelters', 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ['Rehab Centers', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['Community Centers', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as [$label, $path])
                <div class="text-center p-4">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-linen flex items-center justify-center">
                        <svg class="w-7 h-7 text-curtain-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}"/></svg>
                    </div>
                    <span class="text-sm font-medium text-grey-700">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-20 bg-linen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">How It Works</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto mb-6"></div>
                <p class="text-gray-600 text-lg">Three simple steps to bring live performance to your community.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['1', 'Request', 'Tell us about your venue, your audience, and what kind of performance you would like. We will match you with the right artists.', 'bg-curtain-red'],
                    ['2', 'Prepare', 'We coordinate with our volunteer artists to design a performance tailored to your space and audience. We handle all the logistics.', 'bg-stage-gold'],
                    ['3', 'Perform', 'Our artists arrive, set up, and deliver a professional-quality live performance. Your community experiences the magic of live arts.', 'bg-spotlight-amber'],
                ] as [$number, $title, $description, $bg])
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-full {{ $bg }} flex items-center justify-center">
                        <span class="text-2xl font-bold text-white font-display">{{ $number }}</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-theatre-black mb-3">{{ $title }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $description }}</p>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-full bg-curtain-red text-white text-lg font-bold hover:bg-curtain-red-light transition-colors shadow-lg">
                    Request a Performance
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
