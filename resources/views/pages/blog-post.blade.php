<x-layouts.app :title="$post->title" :metaDescription="$post->excerpt ?? Str::limit(strip_tags($post->content), 160)">

    <article>
        {{-- Hero --}}
        <section class="bg-curtain-gradient py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="text-sm text-gray-300 mb-4">
                    {{ $post->published_at->format('F j, Y') }}
                    @if($post->author) &middot; {{ $post->author }} @endif
                </div>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white">{{ $post->title }}</h1>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none prose-headings:font-display prose-headings:text-theatre-black prose-a:text-stage-gold">
                    {!! $post->content !!}
                </div>

                <div class="mt-12 pt-8 border-t border-linen-dark">
                    <a href="{{ route('blog') }}" class="inline-flex items-center text-stage-gold font-semibold hover:underline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Blog
                    </a>
                </div>
            </div>
        </section>
    </article>

</x-layouts.app>
