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

    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black/35"></div>
        </div>
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <a href="{{ route('events') }}" class="inline-flex text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 hover:text-stage-gold transition-colors mb-4">
                Events
            </a>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white max-w-4xl">{{ $event->title }}</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">
                {{ $event->date->format('F j, Y') }}@if($event->time) at {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}@endif
            </p>
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
