<x-layouts.app title="Blog" metaDescription="News, stories, and updates from Threefold Artists.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Journal</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Blog</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">News, stories, and updates from our community.</p>
        </div>
    </section>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($posts->isEmpty())
                <div class="text-center py-12">
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-4">Blog Coming Soon</h2>
                    <p class="text-gray-500">We are preparing our first stories. Check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16">
                    @foreach($posts as $post)
                    <article class="group">
                        @if($post->featured_image)
                        <div class="aspect-[16/9] overflow-hidden mb-6">
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        @endif
                        <div>
                            <div class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-3">
                                {{ $post->published_at->format('F j, Y') }}
                                @if($post->author) &middot; {{ $post->author }} @endif
                            </div>
                            <h3 class="font-display text-xl font-normal text-theatre-black mb-3 group-hover:text-stage-gold-dark transition-colors">
                                <a href="{{ route('blog.post', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            @if($post->excerpt)
                                <p class="text-gray-500 text-sm leading-relaxed">{{ Str::limit($post->excerpt, 150) }}</p>
                            @endif
                            <a href="{{ route('blog.post', $post->slug) }}" class="inline-block mt-4 text-sm font-semibold text-theatre-black hover:underline">
                                Read More &rarr;
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
