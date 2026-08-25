<x-layouts.app title="Past Events" metaDescription="Explore past Threefold Artists performances, event recaps, photographs, and community reflections.">
    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black/45"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Archive</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Past Events</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Performance memories, community reflections, and the work we shared together.</p>
        </div>
    </section>

    <section class="py-20 sm:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-end mb-10">
                <a href="{{ route('events') }}" class="inline-flex items-center text-xs font-semibold tracking-[0.15em] uppercase text-theatre-black hover:text-stage-gold-dark transition-colors">
                    Upcoming Events
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            @if($events->isEmpty())
                <div class="max-w-xl mx-auto text-center py-12">
                    <div class="w-12 h-px bg-stage-gold mx-auto mb-8"></div>
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-4">Our archive is growing</h2>
                    <p class="text-gray-500 leading-relaxed">Check back soon for recaps, photographs, and reflections from our performances.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($events as $event)
                        @php($featuredImageUrl = $event->featuredThumbnailUrl())
                        <article class="group h-full bg-white border border-gray-100 shadow-sm hover:shadow-xl transition-shadow duration-300">
                            <a href="{{ route('events.show', $event) }}" class="block relative aspect-[4/3] overflow-hidden bg-theatre-black">
                                @if($featuredImageUrl)
                                    <img src="{{ $featuredImageUrl }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-theatre-black via-curtain-red to-stage-gold/70"></div>
                                    <div class="relative h-full flex items-end p-6"><span class="text-xs font-semibold tracking-[0.25em] uppercase text-white/80">Threefold Artists</span></div>
                                @endif
                                <div class="absolute left-5 top-5 bg-white/95 px-4 py-3 text-center shadow-md">
                                    <span class="block font-display text-3xl leading-none text-theatre-black">{{ $event->date->format('d') }}</span>
                                    <span class="block text-[0.65rem] font-semibold tracking-[0.18em] uppercase text-gray-500 mt-1">{{ $event->date->format('M Y') }}</span>
                                </div>
                            </a>
                            <div class="p-6">
                                <div class="w-12 h-px bg-stage-gold mb-4"></div>
                                @if($event->art_form)
                                    <p class="text-xs font-semibold tracking-[0.15em] uppercase text-stage-gold-dark mb-3">{{ str_replace('_', ' ', $event->art_form) }}</p>
                                @endif
                                <h2 class="font-display text-2xl font-normal text-theatre-black leading-tight"><a href="{{ route('events.show', $event) }}" class="hover:text-stage-gold-dark transition-colors">{{ $event->title }}</a></h2>
                                <p class="mt-3 text-sm leading-relaxed text-gray-500">{{ Str::limit($event->recap ?: $event->description, 150) }}</p>
                                <a href="{{ route('events.show', $event) }}" class="inline-flex mt-5 text-xs font-semibold tracking-[0.15em] uppercase text-stage-gold-dark hover:text-theatre-black transition-colors">Read the recap</a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-16">{{ $events->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.app>
