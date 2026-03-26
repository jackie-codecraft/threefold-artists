<x-layouts.app title="Events" metaDescription="Upcoming live performances by Threefold Artists. Theatre, music, and dance events in your community.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Calendar</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Upcoming Events</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">Find a performance near you.</p>
        </div>
    </section>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($events->isEmpty())
                <div class="text-center py-12">
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-4">No Upcoming Events</h2>
                    <p class="text-gray-500">Check back soon for new performances, or <a href="{{ route('request-performance') }}" class="text-theatre-black font-semibold hover:underline">request a performance</a> for your community.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16">
                    @foreach($events as $event)
                    <div>
                        <div class="mb-4">
                            <span class="font-display text-4xl font-light text-theatre-black">{{ $event->date->format('d') }}</span>
                            <span class="text-sm font-semibold tracking-[0.15em] uppercase text-gray-400 ml-2">{{ $event->date->format('M Y') }}</span>
                        </div>
                        <div class="w-12 h-px bg-stage-gold mb-4"></div>
                        <h3 class="font-display text-xl font-normal text-theatre-black mb-2">{{ $event->title }}</h3>
                        @if($event->art_form)
                            <span class="text-xs font-semibold tracking-[0.15em] uppercase text-stage-gold-dark">{{ ucfirst($event->art_form) }}</span>
                        @endif
                        <p class="text-gray-500 text-sm mt-3 mb-3">{{ Str::limit($event->description, 120) }}</p>
                        <div class="text-sm text-gray-400 space-y-1">
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
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
