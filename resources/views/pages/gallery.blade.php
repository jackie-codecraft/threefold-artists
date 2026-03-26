<x-layouts.app title="Gallery" metaDescription="Photos and videos from Threefold Artists performances.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Archive</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Gallery</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">Moments from our performances.</p>
        </div>
    </section>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($items->isEmpty())
                <div class="text-center py-12">
                    <h2 class="font-display text-3xl font-light text-theatre-black mb-4">Gallery Coming Soon</h2>
                    <p class="text-gray-500">We are building our collection of performance photos and videos. Check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                    <div class="group">
                        @if($item->getFirstMediaUrl('media'))
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ $item->getFirstMediaUrl('media') }}" alt="{{ $item->title ?? 'Gallery image' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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

                <div class="mt-16">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
