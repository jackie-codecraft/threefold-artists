<x-layouts.app title="About Us" metaDescription="Learn about Threefold Artists, a nonprofit bringing live performing arts to communities that cannot easily access them.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Who We Are</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">About Threefold Artists</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Our story, our mission, our promise.</p>
        </div>
    </section>

    {{-- Mission & Vision --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-20">
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Mission</p>
                    <h2 class="font-display text-3xl sm:text-4xl font-light text-theatre-black mb-6">Why We Exist</h2>
                    <p class="text-gray-500 leading-relaxed text-lg">
                        Threefold Artists exists to bring the transformative power of live performing arts to people and communities that cannot easily access them. We believe that everyone, regardless of age, ability, or circumstance, deserves to experience the joy, healing, and connection that live performance creates.
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Vision</p>
                    <h2 class="font-display text-3xl sm:text-4xl font-light text-theatre-black mb-6">What We See</h2>
                    <p class="text-gray-500 leading-relaxed text-lg">
                        A world where live performing arts are accessible to every person, in every community. Where the curtain rises in care homes as often as concert halls. Where music fills hospital corridors and dance brings light to shelters.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Origin Story --}}
    <section class="py-24 sm:py-32 border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Story</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">How It Started</h2>
            </div>
            <div class="space-y-6 text-lg text-gray-500 leading-relaxed">
                <p>
                    Threefold Artists was born from a simple observation: the people who could benefit most from live performing arts are often the ones least able to access them. Residents of care homes, patients in hospitals, people in shelters, and communities in underserved areas rarely have the opportunity to experience the magic of live theatre, music, or dance.
                </p>
                <p>
                    We set out to change that. By connecting volunteer performing artists with venues and communities in need, we create moments of joy, connection, and transformation. Our name reflects our core belief: the act of giving through art returns to the giver, the receiver, and the community threefold.
                </p>
            </div>
        </div>
    </section>

    {{-- Three Promises --}}
    <section class="py-24 sm:py-32 bg-theatre-black text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-500 mb-4">What We Stand For</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light">Our Three Promises</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-stage-gold mb-4">Access</h3>
                    <p class="text-gray-400 leading-relaxed">We remove every barrier between people and live performance. No tickets, no transport, no cost. If you cannot come to the arts, we will come to you.</p>
                </div>

                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-stage-gold mb-4">Dignity</h3>
                    <p class="text-gray-400 leading-relaxed">Every performance we deliver is professional quality. Our audiences deserve the same standard of artistry as any paying audience in any venue.</p>
                </div>

                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-stage-gold mb-4">Connection</h3>
                    <p class="text-gray-400 leading-relaxed">Live performance creates bonds between performer and audience, between individuals in a shared experience, and between communities and the wider world of art.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Team Placeholder --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">The People</p>
            <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black mb-8">Our Team</h2>
            <p class="text-gray-500 text-lg mb-12 max-w-2xl mx-auto">
                Threefold Artists is powered by a dedicated team of arts professionals and volunteers who share a passion for making live performance accessible to everyone.
            </p>
            <a href="{{ route('get-involved') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                Join Our Team
            </a>
        </div>
    </section>

</x-layouts.app>
