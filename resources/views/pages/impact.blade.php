<x-layouts.app title="Our Impact" metaDescription="See the impact of Threefold Artists - bringing live performing arts to communities across the country.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Results</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Our Impact</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">Measuring the difference live arts make in communities.</p>
        </div>
    </section>

    {{-- Metrics --}}
    @if($metrics->isNotEmpty())
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                @foreach($metrics as $metric)
                <div>
                    <div class="font-display text-5xl sm:text-6xl font-light text-theatre-black mb-2">{{ $metric->value }}</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-gray-500 font-medium">{{ $metric->label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials --}}
    <section class="py-24 sm:py-32 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Stories</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Impact Stories</h2>
            </div>

            @if($testimonials->isEmpty())
                <p class="text-gray-500">Impact stories coming soon.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                    @foreach($testimonials as $testimonial)
                    <div>
                        <blockquote>
                            <p class="text-lg text-gray-700 leading-relaxed italic font-display mb-6">"{{ $testimonial->quote }}"</p>
                            <footer class="text-sm">
                                <cite class="not-italic font-semibold text-theatre-black">{{ $testimonial->attribution }}</cite>
                                @if($testimonial->venue_name)
                                    <span class="block text-gray-400 mt-1">{{ $testimonial->venue_name }}</span>
                                @endif
                            </footer>
                        </blockquote>
                    </div>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $testimonials->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 sm:py-32 bg-theatre-black text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-4xl sm:text-5xl font-light mb-6">Be Part of the Impact</h2>
            <p class="text-lg text-gray-400 mb-12 max-w-2xl mx-auto">Your support helps us reach more communities with the power of live performance.</p>
            <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-gray-100 transition-colors">
                Make a Donation
            </a>
        </div>
    </section>

</x-layouts.app>
