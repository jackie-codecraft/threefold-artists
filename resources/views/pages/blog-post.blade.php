<x-layouts.app :title="$post->title" :metaDescription="$post->excerpt ?? Str::limit(strip_tags($post->content), 160)">

    @push('head')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
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
        <section class="pt-16 pb-12 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center opacity-60">
    </div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="w-16 h-px bg-stage-gold mb-6"></div>
                <div class="flex items-center gap-3 text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-6">
                    {{ $post->published_at->format('F j, Y') }}
                    @if($post->author)
                        <span class="text-gray-500">·</span>
                        <span>{{ $post->author }}</span>
                    @endif
                    @if($post->category)
                        <span class="text-gray-500">·</span>
                        <span class="text-stage-gold">{{ ucfirst(str_replace('-', ' ', $post->category)) }}</span>
                    @endif
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-light text-white leading-tight">{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="text-lg text-gray-300 mt-6 leading-relaxed max-w-2xl">{{ $post->excerpt }}</p>
                @endif
            </div>
        </section>
        <div class="h-12 bg-gradient-to-b from-theatre-black to-white"></div>

        {{-- Featured Image --}}
        @if($post->getFirstMediaUrl('featured_image') || $post->featured_image)
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <div class="aspect-[21/9] overflow-hidden">
                <img src="{{ $post->getFirstMediaUrl('featured_image') ?: $post->featured_image }}"
                     alt="{{ $post->title }}"
                     class="w-full h-full object-cover">
            </div>
        </div>
        @endif

        {{-- Content --}}
        <section class="py-16 sm:py-24">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg prose-slate max-w-none
                    prose-headings:font-display prose-headings:font-light prose-headings:text-theatre-black
                    prose-h2:text-3xl prose-h2:mt-12 prose-h2:mb-4
                    prose-h3:text-2xl prose-h3:mt-8 prose-h3:mb-3
                    prose-p:text-gray-600 prose-p:leading-relaxed prose-p:mb-6
                    prose-a:text-stage-gold prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-theatre-black prose-strong:font-semibold
                    prose-em:text-theater-black prose-em:italic
                    prose-blockquote:border-l-4 prose-blockquote:border-stage-gold prose-blockquote:pl-6 prose-blockquote:text-gray-500 prose-blockquote:italic">
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

        {{-- Share --}}
        <section class="pb-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mt-12 pt-8 border-t border-gray-100">
                    <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-4">Share This</p>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-theatre-black transition-colors" aria-label="Share on Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-theatre-black transition-colors" aria-label="Share on X">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-theatre-black transition-colors" aria-label="Share on LinkedIn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href).then(() => this.textContent = 'Copied!')" class="text-xs font-semibold tracking-wide uppercase text-gray-400 hover:text-theatre-black transition-colors">
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </section>

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
