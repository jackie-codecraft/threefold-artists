<x-layouts.app title="Gallery" metaDescription="Photos and videos from Threefold Artists performances.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Archive</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Gallery</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Moments from our performances.</p>
        </div>
    </section>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($items->isEmpty())
                <div class="max-w-lg mx-auto text-center">
                    <div class="w-12 h-px bg-stage-gold mx-auto mb-8"></div>
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-4">Gallery Coming Soon</h2>
                    <p class="text-gray-500 leading-relaxed mb-10">We are documenting our performances. Beautiful moments from the stage are on their way.</p>
                    @if($siteSettings->eventsEnabled())
                        <a href="{{ route('events') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-theatre-black text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-theatre-black hover:text-white transition-colors">
                            View Upcoming Events
                        </a>
                    @endif
                </div>
            @else
                <div x-data="{
                    lightbox: false,
                    currentImage: '',
                    currentType: 'photo',
                    currentTitle: '',
                    currentCaption: ''
                }" @keydown.escape.window="lightbox = false">

                    {{-- Filters --}}
                    <div class="mb-16 space-y-6">
                        @php($artForms = ['' => 'All Art Forms', 'theatre' => 'Theatre', 'music' => 'Music', 'dance' => 'Dance', 'fine_arts' => 'Fine Arts'])
                        <div class="flex flex-wrap gap-3">
                            @foreach($artForms as $value => $label)
                                <a href="{{ route('gallery', array_filter(['art_form' => $value, 'related_type' => $selectedRelatedType, 'related_id' => $selectedRelatedId])) }}"
                                   class="px-5 py-2 text-xs font-semibold tracking-[0.15em] uppercase transition-colors {{ $selectedArtForm === $value ? 'bg-theatre-black text-white' : 'border border-gray-300 text-gray-500 hover:border-theatre-black hover:text-theatre-black' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>

                        @if($events->isNotEmpty() || $posts->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-4 items-end p-5 bg-linen">
                                <form method="GET" action="{{ route('gallery') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    @if($selectedArtForm !== '')
                                        <input type="hidden" name="art_form" value="{{ $selectedArtForm }}">
                                    @endif
                                    <div>
                                        <label for="related_type" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Content Type</label>
                                        <select id="related_type" name="related_type" class="w-full border border-gray-300 bg-white px-3 py-2 text-sm text-theatre-black">
                                            <option value="">Any</option>
                                            <option value="event" @selected($selectedRelatedType === 'event')>Events</option>
                                            <option value="blog_post" @selected($selectedRelatedType === 'blog_post')>Blog Posts</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="related_id" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Related Content</label>
                                        <select id="related_id" name="related_id" class="w-full border border-gray-300 bg-white px-3 py-2 text-sm text-theatre-black">
                                            <option value="">Any event or post</option>
                                            @if($events->isNotEmpty())
                                                <optgroup label="Events">
                                                    @foreach($events as $event)
                                                        <option value="{{ $event->id }}" @selected($selectedRelatedType === 'event' && $selectedRelatedId === $event->id)>Event: {{ $event->title }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                            @if($posts->isNotEmpty())
                                                <optgroup label="Blog Posts">
                                                    @foreach($posts as $post)
                                                        <option value="{{ $post->id }}" @selected($selectedRelatedType === 'blog_post' && $selectedRelatedId === $post->id)>Blog: {{ $post->title }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        </select>
                                    </div>
                                    <button type="submit" class="sm:col-span-3 inline-flex items-center justify-center px-6 py-3 bg-theatre-black text-white text-xs font-semibold tracking-[0.15em] uppercase hover:bg-gray-800 transition-colors">Filter Gallery</button>
                                </form>
                                @if($selectedArtForm !== '' || $selectedRelatedType !== '')
                                    <a href="{{ route('gallery') }}" class="inline-flex items-center justify-center px-6 py-3 border border-theatre-black text-theatre-black text-xs font-semibold tracking-[0.15em] uppercase hover:bg-theatre-black hover:text-white transition-colors">Clear Filters</a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($items as $item)
                        <div class="group">
                            @if($item->getFirstMediaUrl('media'))
                                <div @click="lightbox = true; currentImage = '{{ $item->getFirstMediaUrl('media') }}'; currentType = '{{ $item->type }}'; currentTitle = '{{ addslashes($item->title ?? '') }}'; currentCaption = '{{ addslashes($item->caption ?? '') }}'"
                                     class="cursor-pointer">
                                    <div class="aspect-[4/3] overflow-hidden">
                                        @if($item->type === 'video')
                                            <video src="{{ $item->getFirstMediaUrl('media') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" muted playsinline preload="metadata"></video>
                                        @else
                                            <img src="{{ $item->getFirstMediaUrl('media') }}" alt="{{ $item->title ?? 'Gallery image' }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($item->title || $item->caption)
                            <div class="pt-4">
                                @if($item->title)
                                    <h3 class="font-display text-lg font-normal text-theatre-black">{{ $item->title }}</h3>
                                @endif
                                @if($item->caption)
                                    <p class="text-sm text-gray-500 mt-1">{{ $item->caption }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Lightbox overlay --}}
                    <div x-show="lightbox" x-cloak
                         role="dialog" aria-modal="true" aria-label="Image viewer"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4"
                         @click.self="lightbox = false">
                        <button @click="lightbox = false" class="absolute top-6 right-6 text-white/70 hover:text-white" aria-label="Close image viewer">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="max-w-5xl w-full">
                            <template x-if="currentType === 'video'">
                                <video :src="currentImage" class="max-h-[80vh] w-auto mx-auto" controls autoplay></video>
                            </template>
                            <template x-if="currentType !== 'video'">
                                <img :src="currentImage" :alt="currentTitle" class="max-h-[80vh] w-auto mx-auto">
                            </template>
                            <div class="text-center mt-6" x-show="currentTitle || currentCaption">
                                <p class="text-white font-display text-xl" x-text="currentTitle"></p>
                                <p class="text-gray-400 text-sm mt-2" x-text="currentCaption"></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
