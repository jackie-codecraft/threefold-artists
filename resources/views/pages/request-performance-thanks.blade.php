<x-layouts.app title="Thank You" metaDescription="Your performance request has been submitted.">
    <section class="py-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 mx-auto mb-8 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="font-display text-4xl font-bold text-theatre-black mb-4">Thank You!</h1>
            <p class="text-xl text-gray-600 mb-8">Your performance request has been submitted successfully. We will review your request and get back to you within 5 business days.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-curtain-red text-white font-semibold hover:bg-curtain-red-light transition-colors">
                Return to Home
            </a>
        </div>
    </section>
</x-layouts.app>
