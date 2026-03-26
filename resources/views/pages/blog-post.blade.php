<x-layouts.app :title="$post->title" :metaDescription="$post->excerpt ?? Str::limit(strip_tags($post->content), 160)">

    @push('head')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $post->title }}",
        "datePublished": "{{ $post->published_at->toIso8601String() }}",
        "author": {
            "@type": "Organization",
            "name": "Threefold Artists"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Threefold Artists",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/logo.png') }}"
            }
        }
    }
    </script>
    @endpush

    <article>
        {{-- Hero --}}
        <section class="pt-16 pb-20 border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-4">
                    {{ $post->published_at->format('F j, Y') }}
                    @if($post->author) &middot; {{ $post->author }} @endif
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-light text-theatre-black">{{ $post->title }}</h1>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-24 sm:py-32">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none prose-headings:font-display prose-headings:text-theatre-black prose-a:text-stage-gold">
                    {!! $post->content !!}
                </div>

                <div class="mt-16 pt-8 border-t border-gray-200">
                    <a href="{{ route('blog') }}" class="inline-flex items-center text-sm font-semibold text-theatre-black hover:underline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Blog
                    </a>
                </div>
            </div>
        </section>
    </article>

</x-layouts.app>
