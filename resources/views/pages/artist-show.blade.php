<x-layouts.app :title="$artist->name" :metaDescription="Str::limit($artist->bio, 160)">

    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <a href="{{ route('artists') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors mb-8">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                All Artists
            </a>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">{{ ucfirst(str_replace('_', ' ', $artist->discipline)) }}</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">{{ $artist->name }}</h1>
        </div>
    </section>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                {{-- Photo --}}
                <div>
                    @if($artist->getFirstMediaUrl('photo'))
                        <img src="{{ $artist->getFirstMediaUrl('photo') }}" alt="{{ $artist->name }}" class="w-full aspect-[3/4] object-cover">
                    @else
                        <div class="w-full aspect-[3/4] bg-linen flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Bio --}}
                <div class="lg:col-span-2">
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-6">About</h2>
                    @if($artist->bio)
                        <div class="prose prose-lg max-w-none text-gray-500 leading-relaxed prose-headings:font-display prose-headings:text-theatre-black">
                            {!! nl2br(e($artist->bio)) !!}
                        </div>
                    @else
                        <p class="text-gray-400 italic">Bio coming soon.</p>
                    @endif

                    @if($artist->is_featured)
                        <div class="mt-12 pt-8 border-t border-gray-100">
                            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-stage-gold">Featured Artist</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
