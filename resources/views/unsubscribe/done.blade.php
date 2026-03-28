<x-layouts.app title="Unsubscribed">
    <section class="py-24 sm:py-32">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-12 h-px bg-stage-gold mx-auto mb-8"></div>
            @if($found)
                <h1 class="font-display text-3xl font-light text-theatre-black mb-4">You've Been Unsubscribed</h1>
                <p class="text-gray-500 leading-relaxed mb-8">We're sorry to see you go. You will no longer receive our newsletter.</p>
            @else
                <h1 class="font-display text-3xl font-light text-theatre-black mb-4">Link Expired</h1>
                <p class="text-gray-500 leading-relaxed mb-8">This unsubscribe link is no longer valid. You may already be unsubscribed.</p>
            @endif
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                Return to Home
            </a>
        </div>
    </section>
</x-layouts.app>
