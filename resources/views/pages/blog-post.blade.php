<x-layouts.app :title="$post->title" :metaDescription="$post->excerpt ?? Str::limit(strip_tags($post->content), 160)">

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
            </div>
        </section>

        @if($relatedPosts->isNotEmpty())
        <section class="py-24 sm:py-32 bg-linen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-16">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Keep Reading</p>
                    <h2 class="font-display text-4xl font-light text-theatre-black">Related Posts</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('blog.post', $related->slug) }}" class="group">
                        @if($related->getFirstMediaUrl('featured_image'))
                        <div class="aspect-[16/9] overflow-hidden mb-4">
                            <img src="{{ $related->getFirstMediaUrl('featured_image') }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        @endif
                        <p class="text-sm text-gray-400 mb-2">{{ $related->published_at->format('F j, Y') }}</p>
                        <h3 class="font-display text-xl font-normal text-theatre-black group-hover:text-stage-gold-dark transition-colors">{{ $related->title }}</h3>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <section class="py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('blog') }}" class="inline-flex items-center text-sm font-semibold text-theatre-black hover:underline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Blog
                </a>
            </div>
        </section>
    </article>

</x-layouts.app>
