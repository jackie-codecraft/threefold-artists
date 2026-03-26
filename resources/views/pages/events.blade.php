<x-layouts.app title="Events" metaDescription="Upcoming live performances by Threefold Artists. Theatre, music, and dance events in your community.">

    <section class="bg-curtain-gradient py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Upcoming Events</h1>
            <p class="text-xl text-gray-200">Find a performance near you.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($events->isEmpty())
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-linen-dark flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-theatre-black mb-2">No Upcoming Events</h2>
                    <p class="text-gray-600">Check back soon for new performances, or <a href="{{ route('request-performance') }}" class="text-stage-gold hover:underline">request a performance</a> for your community.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-sm border border-linen-dark overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="bg-theatre-black p-4 text-white text-center">
                            <div class="font-display text-3xl font-bold">{{ $event->date->format('d') }}</div>
                            <div class="text-sm uppercase tracking-wider">{{ $event->date->format('M Y') }}</div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-display text-xl font-bold text-theatre-black mb-2">{{ $event->title }}</h3>
                            @if($event->art_form)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-stage-gold/10 text-stage-gold-dark mb-3">{{ ucfirst($event->art_form) }}</span>
                            @endif
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($event->description, 120) }}</p>
                            <div class="text-sm text-gray-500 space-y-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->venue_name }}
                                </div>
                                @if($event->time)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
