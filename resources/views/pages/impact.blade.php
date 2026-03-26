<x-layouts.app title="Our Impact" metaDescription="See the impact of Threefold Artists - bringing live performing arts to communities across the country.">

    <section class="bg-curtain-gradient py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Our Impact</h1>
            <p class="text-xl text-gray-200">Measuring the difference live arts make in communities.</p>
        </div>
    </section>

    {{-- Metrics --}}
    @if($metrics->isNotEmpty())
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($metrics as $metric)
                <div class="text-center p-6 bg-linen rounded-2xl">
                    @if($metric->icon)
                        <div class="text-4xl mb-3">{{ $metric->icon }}</div>
                    @endif
                    <div class="font-display text-4xl sm:text-5xl font-bold text-stage-gold-dark mb-2">{{ $metric->value }}</div>
                    <div class="text-gray-600 font-medium">{{ $metric->label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl font-bold text-theatre-black mb-4">Impact Stories</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>

            @if($testimonials->isEmpty())
                <p class="text-center text-gray-600">Impact stories coming soon.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($testimonials as $testimonial)
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-linen-dark relative">
                        <div class="text-stage-gold text-6xl font-display absolute -top-2 left-6 leading-none">"</div>
                        <blockquote class="relative pt-6">
                            <p class="text-gray-700 leading-relaxed italic mb-4">{{ $testimonial->quote }}</p>
                            <footer class="text-sm">
                                <cite class="not-italic font-semibold text-theatre-black">{{ $testimonial->attribution }}</cite>
                                @if($testimonial->venue_name)
                                    <span class="block text-gray-500 mt-1">{{ $testimonial->venue_name }}</span>
                                @endif
                            </footer>
                        </blockquote>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $testimonials->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-theatre-black text-white text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-3xl font-bold mb-4">Be Part of the Impact</h2>
            <p class="text-gray-300 mb-8">Your support helps us reach more communities with the power of live performance.</p>
            <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-full bg-stage-gold text-theatre-black text-lg font-bold hover:bg-stage-gold-light transition-colors shadow-lg">
                Make a Donation
            </a>
        </div>
    </section>

</x-layouts.app>
