<x-layouts.app title="Our Artists" metaDescription="Meet the volunteer performing artists of Threefold Artists.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our People</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Our Artists</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">The talented volunteers who make it all possible.</p>
        </div>
    </section>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($artists->isEmpty())
                <div class="text-center py-12">
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-4">Artist Profiles Coming Soon</h2>
                    <p class="text-gray-500 mb-10">We are building our artist directory.</p>
                    <a href="{{ route('get-involved') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                        Become an Artist
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                    @foreach($artists as $artist)
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-6 bg-linen flex items-center justify-center overflow-hidden">
                            @if($artist->getFirstMediaUrl('photo'))
                                <img src="{{ $artist->getFirstMediaUrl('photo') }}" alt="{{ $artist->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @endif
                        </div>
                        <h3 class="font-display text-xl font-normal text-theatre-black mb-1">{{ $artist->name }}</h3>
                        <div class="w-8 h-px bg-stage-gold mx-auto my-3"></div>
                        <span class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400">{{ ucfirst(str_replace('_', ' ', $artist->discipline)) }}</span>
                        @if($artist->bio)
                            <p class="text-gray-500 text-sm mt-4">{{ Str::limit($artist->bio, 150) }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
