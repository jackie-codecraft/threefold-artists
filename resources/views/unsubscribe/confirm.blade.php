<x-layouts.app title="Unsubscribe">
    <section class="py-24 sm:py-32">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-12 h-px bg-stage-gold mx-auto mb-8"></div>
            <h1 class="font-display text-3xl font-light text-theatre-black mb-4">Unsubscribe</h1>
            <p class="text-gray-500 leading-relaxed mb-8">
                Are you sure you want to unsubscribe <strong>{{ $subscriber->email }}</strong> from our newsletter?
            </p>
            <form action="{{ route('unsubscribe.process') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $subscriber->token }}">
                <button type="submit" class="px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                    Yes, Unsubscribe
                </button>
            </form>
            <p class="text-sm text-gray-400 mt-6">
                <a href="{{ route('home') }}" class="hover:text-theatre-black transition-colors">Return to Home</a>
            </p>
        </div>
    </section>
</x-layouts.app>
