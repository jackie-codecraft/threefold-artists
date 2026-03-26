<x-layouts.app title="Thank You" metaDescription="Your performance request has been submitted.">
    <section class="py-32 sm:py-40">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <svg class="w-12 h-12 text-stage-gold mx-auto mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            <h1 class="font-display text-4xl sm:text-5xl font-light text-theatre-black mb-6">Thank You</h1>
            <p class="text-lg text-gray-500 mb-12">Your performance request has been submitted successfully. We will review your request and get back to you within 5 business days.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                Return to Home
            </a>
        </div>
    </section>
</x-layouts.app>
