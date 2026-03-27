<x-layouts.app title="Press Kit" metaDescription="Download logos, photos, and fact sheets about Threefold Artists for media and press use.">

    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center opacity-50">
        <div class="absolute inset-0 bg-theatre-black/40"></div>
    </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Media</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Press Kit</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Everything you need to write about Threefold Artists.</p>
        </div>
    </section>
    <div class="h-12 bg-gradient-to-b from-theatre-black to-white"></div>

    {{-- About Section --}}
    <section class="py-24 sm:py-32 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">About</p>
                <h2 class="font-display text-4xl font-light text-theatre-black">Who We Are</h2>
            </div>

            <div class="space-y-6 text-gray-500 leading-relaxed text-lg">
                <p>Threefold Artists is a 501(c)(3) nonprofit performing arts organization based in greater Los Angeles, California. Founded in 2026, we bring live theatre, music, and dance directly to communities that cannot easily access traditional venues.</p>
                <p>Our volunteer artists perform at senior living communities, hospitals, rehabilitation centers, schools, shelters, and community centers — always free of charge. We believe that access to live art is a right, not a privilege.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-12 mt-16">
                <div>
                    <div class="font-display text-3xl font-light text-theatre-black mb-2">2026</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-gray-500">Founded</div>
                </div>
                <div>
                    <div class="font-display text-3xl font-light text-theatre-black mb-2">Los Angeles</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-gray-500">Based In</div>
                </div>
                <div>
                    <div class="font-display text-3xl font-light text-theatre-black mb-2">501(c)(3)</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-gray-500">Tax Status</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Key Messages --}}
    <section class="py-24 sm:py-32 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Messaging</p>
                <h2 class="font-display text-4xl font-light text-theatre-black">Key Messages</h2>
            </div>

            <div class="space-y-12">
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-6"></div>
                    <h3 class="font-display text-xl font-normal text-theatre-black mb-3">Mission Statement</h3>
                    <p class="text-gray-500 leading-relaxed">Everyone deserves to experience the arts. Threefold Artists exists to bring entertainment and inspiration via live theatre, music, fine arts, and dance to those who are not able to attend a venue in person.</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-6"></div>
                    <h3 class="font-display text-xl font-normal text-theatre-black mb-3">Tagline</h3>
                    <p class="text-gray-500 leading-relaxed italic font-display text-lg">"Keeping Theatre Alive"</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-6"></div>
                    <h3 class="font-display text-xl font-normal text-theatre-black mb-3">Boilerplate</h3>
                    <p class="text-gray-500 leading-relaxed">Threefold Artists is a Los Angeles–based 501(c)(3) nonprofit that brings professional-quality live theatre, music, and dance to communities that cannot easily access them, at no cost. For more information, visit threefoldartists.org.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Brand Assets --}}
    <section class="py-24 sm:py-32 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Assets</p>
                <h2 class="font-display text-4xl font-light text-theatre-black">Brand Assets</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-12">
                {{-- Logo on light --}}
                <div>
                    <div class="bg-linen p-12 flex items-center justify-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Threefold Artists Logo" class="h-16 w-auto">
                    </div>
                    <p class="text-sm text-gray-500">Logo — Light background</p>
                </div>

                {{-- Logo on dark --}}
                <div>
                    <div class="bg-theatre-black p-12 flex items-center justify-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Threefold Artists Logo" class="h-16 w-auto">
                    </div>
                    <p class="text-sm text-gray-500">Logo — Dark background</p>
                </div>
            </div>

            {{-- Color Palette --}}
            <div class="mt-16">
                <h3 class="font-display text-xl font-normal text-theatre-black mb-6">Color Palette</h3>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                    @foreach([
                        ['Theatre Black', '#1A1A1A', 'bg-theatre-black', 'text-white'],
                        ['Curtain Red', '#8B1A2B', 'bg-curtain-red', 'text-white'],
                        ['Stage Gold', '#C9A84C', 'bg-stage-gold', 'text-theatre-black'],
                        ['Spotlight Amber', '#D4943A', 'bg-spotlight-amber', 'text-white'],
                        ['Linen', '#F5F0E8', 'bg-linen', 'text-theatre-black'],
                    ] as [$name, $hex, $bg, $textColor])
                    <div>
                        <div class="{{ $bg }} h-20 mb-3"></div>
                        <p class="text-sm font-medium text-theatre-black">{{ $name }}</p>
                        <p class="text-xs text-gray-400">{{ $hex }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Typography --}}
            <div class="mt-16">
                <h3 class="font-display text-xl font-normal text-theatre-black mb-6">Typography</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-12">
                    <div>
                        <p class="font-display text-4xl font-light text-theatre-black mb-2">Cormorant Garamond</p>
                        <p class="text-sm text-gray-400">Display & Headlines</p>
                    </div>
                    <div>
                        <p class="text-2xl font-light text-theatre-black mb-2">Source Sans 3</p>
                        <p class="text-sm text-gray-400">Body & UI Text</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact for Media --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Media Inquiries</p>
            <h2 class="font-display text-4xl font-light text-theatre-black mb-6">Get in Touch</h2>
            <p class="text-gray-500 leading-relaxed mb-8">For press inquiries, interviews, or additional assets, please contact us.</p>
            <a href="mailto:hello@threefoldartists.org" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                Contact Press Team
            </a>
        </div>
    </section>

</x-layouts.app>
