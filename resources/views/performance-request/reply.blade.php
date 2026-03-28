<x-layouts.app title="Reply to Performance Request">
    <section class="py-16 sm:py-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-12 h-px bg-stage-gold mb-8"></div>

            @if(session('success'))
                <h1 class="font-display text-3xl font-light text-theatre-black mb-4">Reply Sent</h1>
                <p class="text-gray-500 leading-relaxed mb-8">Your reply has been sent to {{ $performanceRequest->contact_name }} at {{ $performanceRequest->contact_email }}.</p>
                <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                    Go to Admin Panel
                </a>
            @else
                <h1 class="font-display text-3xl font-light text-theatre-black mb-2">Reply to {{ $performanceRequest->contact_name }}</h1>
                <p class="text-gray-400 text-sm mb-8">{{ $performanceRequest->contact_email }} &middot; {{ $performanceRequest->organization_name }} &middot; {{ $performanceRequest->created_at->format('M j, Y g:i A') }}</p>

                {{-- Request details --}}
                <div class="mb-8 p-5 bg-gray-50 border-l-4 border-gray-200">
                    <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Organization</p>
                    <p class="text-theatre-black font-medium mb-3">{{ $performanceRequest->organization_name }}</p>

                    <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Venue Type</p>
                    <p class="text-gray-600 leading-relaxed mb-3">{{ $performanceRequest->venue_type }}</p>

                    @if($performanceRequest->preferred_art_form)
                        <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Preferred Art Form</p>
                        <p class="text-gray-600 leading-relaxed mb-3">{{ ucfirst($performanceRequest->preferred_art_form) }}</p>
                    @endif

                    @if($performanceRequest->audience_size)
                        <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Audience Size</p>
                        <p class="text-gray-600 leading-relaxed mb-3">{{ $performanceRequest->audience_size }}</p>
                    @endif

                    @if($performanceRequest->preferred_dates)
                        <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Preferred Dates</p>
                        <p class="text-gray-600 leading-relaxed mb-3">{{ $performanceRequest->preferred_dates }}</p>
                    @endif

                    @if($performanceRequest->notes)
                        <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Notes</p>
                        <p class="text-gray-600 leading-relaxed">{{ $performanceRequest->notes }}</p>
                    @endif
                </div>

                @if($performanceRequest->isReplied())
                    <div class="mb-8 p-5 bg-green-50 border-l-4 border-stage-gold">
                        <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Previous Reply ({{ $performanceRequest->replied_at->format('M j, Y g:i A') }})</p>
                        <p class="text-gray-600 leading-relaxed">{{ $performanceRequest->reply }}</p>
                    </div>
                @endif

                {{-- Reply form --}}
                <form action="{{ $postUrl }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="reply_message" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-3">Your Reply</label>
                        <textarea name="reply_message" id="reply_message" rows="8" required
                            class="w-full border-0 border-b-2 border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0 resize-none"
                            placeholder="Type your reply here...">{{ old('reply_message') }}</textarea>
                        @error('reply_message')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                        Send Reply
                    </button>
                </form>
            @endif
        </div>
    </section>
</x-layouts.app>
