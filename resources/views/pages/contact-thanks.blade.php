<x-layouts.app title="Message Sent" metaDescription="Your message has been sent.">
    <section class="py-32 sm:py-40">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <svg class="w-12 h-12 text-stage-gold mx-auto mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            <h1 class="font-display text-4xl sm:text-5xl font-light text-theatre-black mb-6">Message Sent</h1>
            <p class="text-lg text-gray-500 mb-12">Thank you for reaching out. We will get back to you as soon as possible.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                Return to Home
            </a>
        </div>
    </section>
</x-layouts.app>
