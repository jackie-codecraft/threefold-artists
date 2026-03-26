<x-layouts.app title="Gallery" metaDescription="Photos and videos from Threefold Artists performances.">

    <section class="bg-curtain-gradient py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Gallery</h1>
            <p class="text-xl text-gray-200">Moments from our performances.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($items->isEmpty())
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-linen-dark flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-theatre-black mb-2">Gallery Coming Soon</h2>
                    <p class="text-gray-600">We are building our collection of performance photos and videos. Check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                    <div class="bg-white rounded-lg shadow-sm border border-linen-dark overflow-hidden group">
                        @if($item->getFirstMediaUrl('media'))
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ $item->getFirstMediaUrl('media') }}" alt="{{ $item->title ?? 'Gallery image' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif
                        @if($item->title || $item->caption)
                        <div class="p-4">
                            @if($item->title)
                                <h3 class="font-display text-lg font-bold text-theatre-black">{{ $item->title }}</h3>
                            @endif
                            @if($item->caption)
                                <p class="text-sm text-gray-600 mt-1">{{ $item->caption }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.app>
