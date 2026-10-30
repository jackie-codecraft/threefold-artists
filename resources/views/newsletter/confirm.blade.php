<x-layouts.app title="Confirm Newsletter Subscription">
    <section class="py-20 sm:py-28">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if($subscriber)
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-4">Stay Connected</p>
                <h1 class="font-display text-4xl font-light text-ink mb-6">Confirm your subscription</h1>
                <p class="text-ink-muted leading-relaxed mb-10">Please confirm that you would like to receive news, artist stories, and performance updates from Threefold Artists.</p>
                <form action="{{ route('newsletter.confirm.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $subscriber->token }}">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">Confirm Subscription</button>
                </form>
            @else
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-4">Newsletter</p>
                <h1 class="font-display text-4xl font-light text-ink mb-6">This link is no longer active</h1>
                <p class="text-ink-muted leading-relaxed">Your subscription may already be confirmed, or this confirmation link has expired.</p>
            @endif
        </div>
    </section>
</x-layouts.app>
