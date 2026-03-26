<x-layouts.app title="Blog" metaDescription="News, stories, and updates from Threefold Artists.">

    <section class="bg-curtain-gradient py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Blog</h1>
            <p class="text-xl text-gray-200">News, stories, and updates from our community.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($posts->isEmpty())
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-linen-dark flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-theatre-black mb-2">Blog Coming Soon</h2>
                    <p class="text-gray-600">We are preparing our first stories. Check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                    <article class="bg-white rounded-lg shadow-sm border border-linen-dark overflow-hidden group hover:shadow-lg transition-shadow">
                        @if($post->featured_image)
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        @endif
                        <div class="p-6">
                            <div class="text-sm text-gray-500 mb-2">
                                {{ $post->published_at->format('F j, Y') }}
                                @if($post->author) &middot; {{ $post->author }} @endif
                            </div>
                            <h3 class="font-display text-xl font-bold text-theatre-black mb-2 group-hover:text-stage-gold-dark transition-colors">
                                <a href="{{ route('blog.post', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            @if($post->excerpt)
                                <p class="text-gray-600 text-sm">{{ Str::limit($post->excerpt, 150) }}</p>
                            @endif
                            <a href="{{ route('blog.post', $post->slug) }}" class="inline-block mt-4 text-sm font-semibold text-stage-gold hover:underline">
                                Read More &rarr;
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
