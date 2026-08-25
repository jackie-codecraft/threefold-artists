<x-layouts.app :title="$event->title" :metaDescription="Str::limit(strip_tags((string) $event->description), 155)">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Event",
        "name": "{{ $event->title }}",
        "startDate": "{{ $event->date->toIso8601String() }}",
        "url": "{{ route('events.show', $event) }}",
        "location": {
            "@@type": "Place",
            "name": "{{ $event->venue_name }}",
            "address": "{{ $event->venue_address }}"
        },
        "organizer": {
            "@@type": "Organization",
            "name": "Threefold Artists"
        },
        "description": "{{ Str::limit(strip_tags((string) $event->description), 200) }}"
    }
    </script>

    @php($featuredImageUrl = $event->featuredImageUrl())

    <section class="pt-20 pb-24 bg-theatre-black relative overflow-hidden min-h-[520px] flex items-end">
        <div class="absolute inset-0">
            @if($featuredImageUrl)
                <img src="{{ $featuredImageUrl }}" alt="{{ $event->title }}" class="w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-r from-theatre-black/90 via-theatre-black/65 to-theatre-black/20"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-theatre-black/75 via-transparent to-theatre-black/20"></div>
            @else
                <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-black/35"></div>
            @endif
        </div>
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-4xl">
                <div class="w-16 h-px bg-stage-gold mb-6"></div>
                <a href="{{ $isPastEvent ? route('events.past') : route('events') }}" class="inline-flex text-xs font-semibold tracking-[0.2em] uppercase text-gray-300 hover:text-stage-gold transition-colors mb-4">
                    {{ $isPastEvent ? 'Past Events' : 'Upcoming Events' }}
                </a>
                @if($event->art_form)
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-stage-gold mb-4">{{ str_replace('_', ' ', $event->art_form) }}</p>
                @endif
                <h1 class="font-display text-5xl sm:text-7xl font-light text-white leading-tight">{{ $event->title }}</h1>
                <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-8 text-gray-100">
                    <p class="text-lg">
                        {{ $event->date->format('F j, Y') }}@if($event->time) at {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}@endif
                    </p>
                    <p class="text-lg text-gray-300">{{ $event->venue_name }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 sm:py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                <aside class="lg:col-span-1">
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Date</h2>
                            <p class="font-display text-2xl font-light text-theatre-black">{{ $event->date->format('F j, Y') }}</p>
                        </div>

                        @if($event->time)
                            <div>
                                <h2 class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Time</h2>
                                <p class="text-gray-700">{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</p>
                            </div>
                        @endif

                        <div>
                            <h2 class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Location</h2>
                            <p class="font-medium text-theatre-black">{{ $event->venue_name }}</p>
                            @if($event->venue_address)
                                <p class="text-gray-500 mt-1">{{ $event->venue_address }}</p>
                            @endif
                        </div>

                        @if($event->art_form)
                            <div>
                                <h2 class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Art Form</h2>
                                <p class="text-stage-gold-dark font-semibold uppercase tracking-[0.15em] text-xs">{{ str_replace('_', ' ', $event->art_form) }}</p>
                            </div>
                        @endif
                    </div>
                </aside>

                <article class="lg:col-span-2">
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    @if($event->description)
                        <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    @else
                        <p class="text-gray-500 leading-relaxed">Full details for this performance are coming soon.</p>
                    @endif

                    @if($isPastEvent && $event->recap)
                        <div class="mt-10 pt-10 border-t border-gray-100">
                            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-3">Event Recap</p>
                            <h2 class="font-display text-3xl font-light text-theatre-black mb-6">How it went</h2>
                            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                                {!! nl2br(e($event->recap)) !!}
                            </div>
                        </div>
                    @endif

                    @if($galleryItems->isNotEmpty() && $siteSettings->galleryEnabled())
                        <div class="mt-12 pt-10 border-t border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
                                <div>
                                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-3">Event Gallery</p>
                                    <h2 class="font-display text-3xl font-light text-theatre-black">Moments from {{ $event->title }}</h2>
                                </div>
                                <a href="{{ route('gallery', ['related_type' => 'event', 'related_id' => $event->id]) }}" class="inline-flex items-center text-sm font-semibold text-theatre-black hover:text-stage-gold-dark transition-colors">
                                    View Gallery
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($galleryItems as $galleryItem)
                                    <a href="{{ route('gallery', ['related_type' => 'event', 'related_id' => $event->id]) }}" class="group block">
                                        <div class="aspect-[4/3] overflow-hidden bg-linen">
                                            @if($galleryItem->getFirstMediaUrl('media'))
                                                @if($galleryItem->type === 'video')
                                                    <video src="{{ $galleryItem->getFirstMediaUrl('media') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" muted playsinline preload="metadata"></video>
                                                @else
                                                    <img src="{{ $galleryItem->thumbnailUrl() }}" alt="{{ $galleryItem->title ?? 'Event gallery image' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                @endif
                                            @else
                                                <div class="w-full h-full flex items-center justify-center px-4 text-center text-xs font-semibold tracking-[0.15em] uppercase text-gray-400">Gallery Image</div>
                                            @endif
                                        </div>
                                        @if($galleryItem->title)
                                            <p class="mt-3 text-sm font-medium text-theatre-black group-hover:text-stage-gold-dark transition-colors">{{ $galleryItem->title }}</p>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($isPastEvent && $testimonials->isNotEmpty())
                        <div class="mt-12 pt-10 border-t border-gray-100">
                            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-3">In Their Words</p>
                            <h2 class="font-display text-3xl font-light text-theatre-black mb-8">What people shared</h2>
                            <div class="grid gap-6 sm:grid-cols-2">
                                @foreach($testimonials as $testimonial)
                                    <figure class="border-l-2 border-stage-gold bg-linen/50 p-6">
                                        <blockquote class="font-display text-xl leading-relaxed text-theatre-black">“{{ $testimonial->quote }}”</blockquote>
                                        <figcaption class="mt-5 text-sm text-gray-600">
                                            <span class="font-semibold text-theatre-black">{{ $testimonial->attribution }}</span>
                                            @if($testimonial->venue_name)
                                                <span class="text-gray-400"> · {{ $testimonial->venue_name }}</span>
                                            @endif
                                        </figcaption>
                                    </figure>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('events') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-theatre-black text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-theatre-black hover:text-white transition-colors">
                            Back to Events
                        </a>
                        <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                            Request a Performance
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>

</x-layouts.app>
