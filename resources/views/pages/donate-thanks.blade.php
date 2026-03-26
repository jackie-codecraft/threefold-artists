<x-layouts.app title="Thank You for Your Donation" metaDescription="Thank you for your generous donation to Threefold Artists.">
    <section class="py-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 mx-auto mb-8 rounded-full bg-stage-gold/20 flex items-center justify-center">
                <svg class="w-10 h-10 text-stage-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h1 class="font-display text-4xl font-bold text-theatre-black mb-4">Thank You!</h1>
            <p class="text-xl text-gray-600 mb-4">Your generous donation helps us bring live performing arts to communities that need them most.</p>
            <p class="text-lg text-stage-gold-dark italic font-display mb-8">"As you give, you return threefold."</p>
            <p class="text-gray-500 mb-8">If you provided an email address, a receipt will be sent to you shortly.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-curtain-red text-white font-semibold hover:bg-curtain-red-light transition-colors">
                Return to Home
            </a>
        </div>
    </section>
</x-layouts.app>
