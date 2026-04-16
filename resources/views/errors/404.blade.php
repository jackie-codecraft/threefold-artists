<x-layouts.app
    title="Page Not Found"
    metaDescription="The page you were looking for has stepped offstage. Return home or explore what Threefold Artists brings to the community."
>
    <section class="relative overflow-hidden bg-theatre-black text-white">
        <div class="absolute inset-0">
            <img
                src="{{ asset('images/hero-bg.jpg') }}"
                alt=""
                role="presentation"
                class="h-full w-full object-cover object-center opacity-20"
            >
            <div class="absolute inset-0 bg-gradient-to-br from-theatre-black via-theatre-black/95 to-curtain-red/70"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(201,168,76,0.18),_transparent_38%)]"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8 lg:py-40">
            <div class="max-w-3xl">
                <div class="mb-8 w-16 h-px bg-stage-gold"></div>
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-stage-gold">Error 404</p>
                <h1 class="font-display text-5xl font-light leading-[0.95] text-white sm:text-6xl lg:text-7xl">
                    This page has
                    <span class="text-stage-gold">left the stage</span>
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-gray-300 sm:text-xl">
                    The link may be outdated, the page may have moved, or the curtain may have dropped a little early.
                    Let’s get you back to something worth seeing.
                </p>
                <p class="mt-4 text-sm italic text-stage-gold font-display sm:text-base">
                    "Keeping Theatre Alive," even when a page misses its cue.
                </p>

                <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('home') }}"
                        class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-theatre-black text-sm font-semibold tracking-wide uppercase transition-colors hover:bg-gray-100"
                    >
                        Back to Home
                    </a>
                    <a
                        href="{{ route('what-we-do') }}"
                        class="inline-flex items-center justify-center border border-white px-8 py-3.5 text-sm font-semibold tracking-wide uppercase text-white transition-colors hover:bg-white hover:text-theatre-black"
                    >
                        See What We Do
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-linen py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-start lg:gap-12">
                <div>
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Helpful Next Steps</p>
                    <h2 class="font-display text-4xl font-light text-theatre-black sm:text-5xl">Find your way back to the performance</h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <a href="{{ route('artists') }}" class="group border border-gray-200 bg-white p-6 transition-colors hover:border-stage-gold">
                        <div class="mb-4 w-10 h-px bg-stage-gold"></div>
                        <h3 class="font-display text-2xl font-normal text-theatre-black transition-colors group-hover:text-stage-gold-dark">Meet Our Artists</h3>
                        <p class="mt-2 text-gray-500 leading-relaxed">Explore the performers bringing theatre, music, and dance into the community.</p>
                    </a>

                    <a href="{{ route('contact') }}" class="group border border-gray-200 bg-white p-6 transition-colors hover:border-stage-gold">
                        <div class="mb-4 w-10 h-px bg-stage-gold"></div>
                        <h3 class="font-display text-2xl font-normal text-theatre-black transition-colors group-hover:text-stage-gold-dark">Contact Us</h3>
                        <p class="mt-2 text-gray-500 leading-relaxed">Need help finding something specific? We’re happy to point you in the right direction.</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
