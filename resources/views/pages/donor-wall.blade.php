<x-layouts.app title="Donor Wall" metaDescription="Thank you to everyone who supports Threefold Artists.">

    <section class="pt-16 pb-20 bg-band relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Our Supporters</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Donor Wall</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Every gift, no matter the size, helps keep theatre alive.</p>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-24 sm:py-32 border-b border-line-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-16 max-w-2xl">
                <div>
                    <div class="font-display text-5xl font-light text-ink">${{ number_format($totalRaised, 0) }}</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-ink-muted font-medium">Total Raised</div>
                </div>
                <div>
                    <div class="font-display text-5xl font-light text-ink">{{ $totalDonors }}</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-ink-muted font-medium">Donors</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Donor Names --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($donors->isEmpty())
                <div class="max-w-lg mx-auto text-center">
                    <div class="w-12 h-px bg-stage-gold mx-auto mb-8"></div>
                    <h2 class="font-display text-3xl font-light text-ink mb-4">Be Our First Donor</h2>
                    <p class="text-ink-muted leading-relaxed mb-4">"As you give, it returns threefold."</p>
                    <p class="text-ink-muted leading-relaxed mb-10">Your name could be the first on our wall. Every donation, no matter the size, helps keep theatre alive.</p>
                    @if($siteSettings->donationsEnabled())
                        <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">
                            Make a Donation
                        </a>
                    @endif
                </div>
            @else
                <div class="mb-20">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-4">With Gratitude</p>
                    <h2 class="font-display text-4xl sm:text-5xl font-light text-ink">Thank You</h2>
                </div>

                <div class="columns-1 sm:columns-2 lg:columns-3 gap-x-16 gap-y-0">
                    @foreach($donors as $donor)
                    <div class="break-inside-avoid mb-6 pb-6 border-b border-line-soft">
                        <p class="font-display text-xl font-normal text-ink">{{ $donor->donor_name }}</p>
                    </div>
                    @endforeach
                </div>

                @if($siteSettings->donationsEnabled())
                    <div class="text-center mt-20 pt-16 border-t border-line-soft">
                        <p class="text-ink-faint italic font-display text-lg mb-8">"As you give, it returns threefold."</p>
                        <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">
                            Join Our Donors
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </section>

</x-layouts.app>
