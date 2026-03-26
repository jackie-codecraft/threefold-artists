<x-layouts.app title="About Us" metaDescription="Learn about Threefold Artists, a nonprofit bringing live performing arts to communities that cannot easily access them.">

    {{-- Page Hero --}}
    <section class="bg-curtain-gradient py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-stage-gold rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">About Threefold Artists</h1>
            <p class="text-xl text-gray-200">Our story, our mission, our promise.</p>
        </div>
    </section>

    {{-- Mission & Vision --}}
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h2 class="font-display text-3xl font-bold text-theatre-black mb-4">Our Mission</h2>
                    <div class="w-16 h-1 bg-stage-gold mb-6"></div>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Threefold Artists exists to bring the transformative power of live performing arts to people and communities that cannot easily access them. We believe that everyone, regardless of age, ability, or circumstance, deserves to experience the joy, healing, and connection that live performance creates.
                    </p>
                </div>
                <div>
                    <h2 class="font-display text-3xl font-bold text-theatre-black mb-4">Our Vision</h2>
                    <div class="w-16 h-1 bg-stage-gold mb-6"></div>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        A world where live performing arts are accessible to every person, in every community. Where the curtain rises in care homes as often as concert halls. Where music fills hospital corridors and dance brings light to shelters.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Origin Story --}}
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">How It Started</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>
            <div class="prose prose-lg max-w-none text-gray-600">
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
    <section class="py-20 bg-theatre-black text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl sm:text-4xl font-bold mb-4">Our Three Promises</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <div class="text-center p-8">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-curtain-red flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-3 text-stage-gold">Access</h3>
                    <p class="text-gray-300 leading-relaxed">We remove every barrier between people and live performance. No tickets, no transport, no cost. If you cannot come to the arts, we will come to you.</p>
                </div>

                <div class="text-center p-8">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-stage-gold flex items-center justify-center">
                        <svg class="w-8 h-8 text-theatre-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-3 text-stage-gold">Dignity</h3>
                    <p class="text-gray-300 leading-relaxed">Every performance we deliver is professional quality. Our audiences deserve the same standard of artistry as any paying audience in any venue.</p>
                </div>

                <div class="text-center p-8">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-spotlight-amber flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-3 text-stage-gold">Connection</h3>
                    <p class="text-gray-300 leading-relaxed">Live performance creates bonds between performer and audience, between individuals in a shared experience, and between communities and the wider world of art.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Team Placeholder --}}
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-theatre-black mb-4">Our Team</h2>
            <div class="w-24 h-1 bg-stage-gold mx-auto mb-8"></div>
            <p class="text-gray-600 text-lg mb-8">
                Threefold Artists is powered by a dedicated team of arts professionals and volunteers who share a passion for making live performance accessible to everyone.
            </p>
            <a href="{{ route('get-involved') }}" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-stage-gold text-theatre-black font-semibold hover:bg-stage-gold-light transition-colors">
                Join Our Team
            </a>
        </div>
    </section>

</x-layouts.app>
