<x-layouts.app title="Thank You" metaDescription="Thank you for pledging your future support for Threefold Artists.">
    <section class="bg-linen py-24 sm:py-32">
        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <p class="mb-5 text-xs font-semibold uppercase tracking-[0.25em] text-stage-gold">Pledge received</p>
            <h1 class="font-display text-5xl font-light text-curtain-red sm:text-6xl">Thank you for becoming a founding supporter.</h1>
            <p class="mx-auto mt-8 max-w-2xl text-lg leading-8 text-gray-700">
                We’ve received your pledge. When Threefold Artists is ready to accept donations, our team will reach out with next steps and clear information about how pledge commitments will work.
            </p>
            <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full bg-curtain-red px-8 py-3.5 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-theatre-black">Return Home</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full border border-curtain-red px-8 py-3.5 text-sm font-semibold uppercase tracking-wide text-curtain-red transition hover:bg-curtain-red hover:text-white">Contact Us</a>
            </div>
        </div>
    </section>
</x-layouts.app>
