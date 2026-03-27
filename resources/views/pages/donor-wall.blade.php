<x-layouts.app title="Donor Wall" metaDescription="Thank you to everyone who supports Threefold Artists.">

    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
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
    <section class="py-24 sm:py-32 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-16 max-w-2xl">
                <div>
                    <div class="font-display text-5xl font-light text-theatre-black">${{ number_format($totalRaised, 0) }}</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-gray-500 font-medium">Total Raised</div>
                </div>
                <div>
                    <div class="font-display text-5xl font-light text-theatre-black">{{ $totalDonors }}</div>
                    <div class="w-8 h-px bg-stage-gold my-3"></div>
                    <div class="text-sm text-gray-500 font-medium">Donors</div>
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
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-4">Be Our First Donor</h2>
                    <p class="text-gray-500 leading-relaxed mb-4">"As you give, you return threefold."</p>
                    <p class="text-gray-500 leading-relaxed mb-10">Your name could be the first on our wall. Every donation, no matter the size, helps keep theatre alive.</p>
                    <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                        Make a Donation
                    </a>
                </div>
            @else
                <div class="mb-20">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">With Gratitude</p>
                    <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Thank You</h2>
                </div>

                <div class="columns-1 sm:columns-2 lg:columns-3 gap-x-16 gap-y-0">
                    @foreach($donors as $donor)
                    <div class="break-inside-avoid mb-6 pb-6 border-b border-gray-100">
                        <p class="font-display text-xl font-normal text-theatre-black">{{ $donor->donor_name }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-20 pt-16 border-t border-gray-100">
                    <p class="text-gray-400 italic font-display text-lg mb-8">"As you give, you return threefold."</p>
                    <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                        Join Our Donors
                    </a>
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
