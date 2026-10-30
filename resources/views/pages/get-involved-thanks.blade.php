<x-layouts.app title="Thank You" metaDescription="Your volunteer interest form has been submitted.">
    <section class="py-32 sm:py-40">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <svg class="w-12 h-12 text-stage-gold mx-auto mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            <h1 class="font-display text-4xl sm:text-5xl font-light text-ink mb-6">Thank You for Volunteering!</h1>
            <p class="text-lg text-ink-muted mb-4">We’re thrilled that you’d like to share your time and talents with Threefold Artists. We’ve received your information and look forward to connecting with you as opportunities become available.</p>
            <p class="text-ink-muted mb-12">A confirmation email is on its way to your inbox.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">
                Return to Home
            </a>
        </div>
    </section>
</x-layouts.app>
