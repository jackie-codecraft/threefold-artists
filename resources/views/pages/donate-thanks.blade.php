<x-layouts.app title="Thank You for Your Donation" metaDescription="Thank you for your generous donation to Threefold Artists.">
    <section class="py-32 sm:py-40">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <svg class="w-12 h-12 text-stage-gold mx-auto mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <h1 class="font-display text-4xl sm:text-5xl font-light text-theatre-black mb-6">Thank You</h1>
            <p class="text-lg text-gray-500 mb-4">Your generous donation helps us bring live performing arts to communities that need them most.</p>
            <p class="text-stage-gold italic font-display mb-8">"As you give, you return threefold."</p>
            <p class="text-gray-400 text-sm mb-12">If you provided an email address, a receipt will be sent to you shortly.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                    Return to Home
                </a>
                <a href="{{ route('donor-wall') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-theatre-black text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-theatre-black hover:text-white transition-colors">
                    View Donor Wall
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
