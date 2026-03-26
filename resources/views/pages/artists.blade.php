<x-layouts.app title="Our Artists" metaDescription="Meet the volunteer performing artists of Threefold Artists.">

    <section class="bg-curtain-gradient py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Our Artists</h1>
            <p class="text-xl text-gray-200">The talented volunteers who make it all possible.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($artists->isEmpty())
                <div class="text-center py-12">
                    <h2 class="font-display text-2xl font-bold text-theatre-black mb-2">Artist Profiles Coming Soon</h2>
                    <p class="text-gray-600 mb-6">We are building our artist directory.</p>
                    <a href="{{ route('get-involved') }}" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-curtain-red text-white font-semibold hover:bg-curtain-red-light transition-colors">
                        Become an Artist
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($artists as $artist)
                    <div class="bg-white rounded-2xl shadow-sm border border-linen-dark overflow-hidden text-center p-8">
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-linen flex items-center justify-center overflow-hidden">
                            @if($artist->getFirstMediaUrl('photo'))
                                <img src="{{ $artist->getFirstMediaUrl('photo') }}" alt="{{ $artist->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @endif
                        </div>
                        <h3 class="font-display text-xl font-bold text-theatre-black mb-1">{{ $artist->name }}</h3>
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-stage-gold/10 text-stage-gold-dark mb-3">{{ ucfirst(str_replace('_', ' ', $artist->discipline)) }}</span>
                        @if($artist->bio)
                            <p class="text-gray-600 text-sm">{{ Str::limit($artist->bio, 150) }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
