<x-layouts.app title="Gallery" metaDescription="Photos and videos from Threefold Artists performances.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                    <a href="{{ route('events') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-theatre-black text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-theatre-black hover:text-white transition-colors">
                        View Upcoming Events
                    </a>
                </div>
            @else
                <div x-data="{
                    lightbox: false,
                    currentImage: '',
                    currentTitle: '',
                    currentCaption: '',
                    filter: 'all'
                }" @keydown.escape.window="lightbox = false">

                    {{-- Filter buttons --}}
                    <div class="flex flex-wrap gap-3 mb-16">
                        <template x-for="f in ['all', 'theatre', 'music', 'dance', 'fine_arts']">
                            <button @click="filter = f"
                                :class="filter === f ? 'bg-theatre-black text-white' : 'border border-gray-300 text-gray-500 hover:border-theatre-black hover:text-theatre-black'"
                                class="px-5 py-2 text-xs font-semibold tracking-[0.15em] uppercase transition-colors"
                                x-text="f === 'all' ? 'All' : f.replace('_', ' ')">
                            </button>
                        </template>
                    </div>

                    {{-- Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($items as $item)
                        <div x-show="filter === 'all' || filter === '{{ $item->art_form }}'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="group">
                            @if($item->getFirstMediaUrl('media'))
                                <div @click="lightbox = true; currentImage = '{{ $item->getFirstMediaUrl('media') }}'; currentTitle = '{{ addslashes($item->title ?? '') }}'; currentCaption = '{{ addslashes($item->caption ?? '') }}'"
                                     class="cursor-pointer">
                                    <div class="aspect-[4/3] overflow-hidden">
                                        <img src="{{ $item->getFirstMediaUrl('media') }}" alt="{{ $item->title ?? 'Gallery image' }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
                            <img :src="currentImage" :alt="currentTitle" class="max-h-[80vh] w-auto mx-auto">
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
