<x-layouts.app title="Reply to Contact Message">
    <section class="py-16 sm:py-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-12 h-px bg-stage-gold mb-8"></div>

            @if(session('success'))
                <h1 class="font-display text-3xl font-light text-ink mb-4">Reply Sent</h1>
                <p class="text-ink-muted leading-relaxed mb-8">Your reply has been sent to {{ $contactMessage->name }} at {{ $contactMessage->email }}.</p>
                <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">
                    Go to Admin Panel
                </a>
            @else
                <h1 class="font-display text-3xl font-light text-ink mb-2">Reply to {{ $contactMessage->name }}</h1>
                <p class="text-ink-faint text-sm mb-8">{{ $contactMessage->email }} &middot; {{ $contactMessage->created_at->format('M j, Y g:i A') }}</p>

                {{-- Original message --}}
                <div class="mb-8 p-5 bg-gray-50 border-l-4 border-line dark:bg-surface">
                    @if($contactMessage->subject)
                        <p class="text-xs font-semibold tracking-[0.15em] uppercase text-ink-faint mb-2">Subject</p>
                        <p class="text-ink font-medium mb-3">{{ $contactMessage->subject }}</p>
                    @endif
                    <p class="text-xs font-semibold tracking-[0.15em] uppercase text-ink-faint mb-2">Message</p>
                    <p class="text-ink-soft leading-relaxed">{{ $contactMessage->message }}</p>
                </div>

                @if($contactMessage->isReplied())
                    <div class="mb-8 p-5 bg-green-50 border-l-4 border-stage-gold dark:bg-green-950/40">
                        <p class="text-xs font-semibold tracking-[0.15em] uppercase text-ink-faint mb-2">Previous Reply ({{ $contactMessage->replied_at->format('M j, Y g:i A') }})</p>
                        <p class="text-ink-soft leading-relaxed">{{ $contactMessage->reply }}</p>
                    </div>
                @endif

                {{-- Reply form --}}
                <form action="{{ $postUrl }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="reply_message" class="block text-xs font-semibold tracking-[0.15em] uppercase text-ink-muted mb-3">Your Reply</label>
                        <textarea name="reply_message" id="reply_message" rows="8" required
                            class="form-field"
                            placeholder="Type your reply here...">{{ old('reply_message') }}</textarea>
                        @error('reply_message')
                            <p class="text-red-500 text-sm mt-2 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">
                        Send Reply
                    </button>
                </form>
            @endif
        </div>
    </section>
</x-layouts.app>
