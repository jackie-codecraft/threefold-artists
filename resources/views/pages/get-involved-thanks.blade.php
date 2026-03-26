<x-layouts.app title="Thank You" metaDescription="Your artist application has been submitted.">
    <section class="py-32 sm:py-40">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <svg class="w-12 h-12 text-stage-gold mx-auto mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            <h1 class="font-display text-4xl sm:text-5xl font-light text-theatre-black mb-6">Welcome Aboard</h1>
            <p class="text-lg text-gray-500 mb-12">Thank you for your interest in volunteering with Threefold Artists. We will review your application and be in touch soon.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                Return to Home
            </a>
        </div>
    </section>
</x-layouts.app>
