<x-layouts.app title="Blog" metaDescription="News, stories, and updates from Threefold Artists.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 bg-band relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Journal</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Blog</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">News, stories, and updates from our community.</p>
        </div>
    </section>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($posts->isEmpty())
                <div class="max-w-lg mx-auto text-center">
                    <div class="w-12 h-px bg-stage-gold mx-auto mb-8"></div>
                    <h2 class="font-display text-3xl font-light text-ink mb-4">Stories Coming Soon</h2>
                    <p class="text-ink-muted leading-relaxed mb-10">Performance recaps, artist spotlights, and community stories. Subscribe to be the first to read them.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-sm mx-auto">
                        @csrf
                        <input type="hidden" name="source" value="blog-empty">
                        <x-turnstile />
                        <div class="flex gap-3">
                            <input type="email" name="email" required placeholder="Your email" aria-label="Email for newsletter"
                                class="form-field min-w-0 flex-1 text-center">
                            <button type="submit" class="px-6 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">
                                Notify Me
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16">
                    @foreach($posts as $post)
                    <article class="group">
                        @if($post->featuredImageUrl())
                        <div class="aspect-[16/9] overflow-hidden mb-6">
                            <img src="{{ $post->featuredImageUrl() }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        @endif
                        <div>
                            @if($post->category)
                                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-2">{{ ucfirst(str_replace('-', ' ', $post->category)) }}</p>
                            @endif
                            <div class="text-xs font-semibold tracking-[0.15em] uppercase text-ink-faint mb-3">
                                {{ $post->published_at->format('F j, Y') }}
                                @if($post->author) &middot; {{ $post->author }} @endif
                            </div>
                            <h3 class="font-display text-xl font-normal text-ink mb-3 group-hover:text-gold-text transition-colors">
                                <a href="{{ route('blog.post', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            @if($post->excerpt)
                                <p class="text-ink-muted text-sm leading-relaxed">{{ Str::limit($post->excerpt, 150) }}</p>
                            @endif
                            <a href="{{ route('blog.post', $post->slug) }}" class="inline-block mt-4 text-sm font-semibold text-ink hover:underline">
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
