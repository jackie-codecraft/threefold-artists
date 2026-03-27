@props(['title' => null, 'metaDescription' => null, 'ogImage' => null, 'canonical' => null])
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Threefold Artists' }} — Keeping Theatre Alive</title>
    <meta name="description" content="{{ $metaDescription ?? 'Threefold Artists brings live performing arts to communities that cannot easily access them. Theatre, music, dance — when people cannot come to the arts, we will come to them.' }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title ?? 'Threefold Artists' }} — Keeping Theatre Alive">
    <meta property="og:description" content="{{ $metaDescription ?? 'Bringing live performing arts to underserved communities.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/logo.png') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Threefold Artists' }} — Keeping Theatre Alive">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Bringing live performing arts to underserved communities.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/logo.png') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Structured Data: Organization -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "NonprofitOrganization",
        "name": "Threefold Artists",
        "description": "Threefold Artists brings live performing arts to communities that cannot easily access them.",
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": [],
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Los Angeles",
            "addressRegion": "CA",
            "addressCountry": "US"
        }
    }
    </script>

    @stack('head')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-theatre-black focus:text-white focus:text-sm focus:font-semibold">
        Skip to main content
    </a>

    {{-- Navigation --}}
    <header x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <nav aria-label="Main navigation" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Threefold Artists Inc." class="h-12 w-auto">
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">Home</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">About</a>
                    <a href="{{ route('what-we-do') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('what-we-do') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">What We Do</a>
                    <a href="{{ route('artists') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('artists') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">Artists</a>
                    <a href="{{ route('events') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('events') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">Events</a>
                    <a href="{{ route('gallery') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('gallery') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">Gallery</a>
                    <a href="{{ route('impact') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('impact') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">Impact</a>
                    <a href="{{ route('blog') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('blog*') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">Blog</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('contact*') ? 'text-theatre-black border-b-2 border-stage-gold pb-0.5' : 'text-gray-500 hover:text-theatre-black' }}">Contact</a>
                    <div class="flex items-center gap-2 ml-4">
                        <a href="{{ route('request-performance') }}" class="px-5 py-2 text-sm font-medium border border-theatre-black text-theatre-black hover:bg-theatre-black hover:text-white transition-colors">
                            Request Performance
                        </a>
                        <a href="{{ route('donate') }}" class="px-5 py-2 text-sm font-medium bg-theatre-black text-white hover:bg-gray-800 transition-colors">
                            Donate
                        </a>
                    </div>
                </div>

                {{-- Mobile menu button --}}
                <button @click="open = !open" :aria-expanded="open" class="lg:hidden p-2 text-gray-600 hover:text-theatre-black focus:outline-none" aria-label="Toggle menu">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile Navigation --}}
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="lg:hidden pb-6 border-t border-gray-100 mt-2 pt-4">
                <div class="flex flex-col gap-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('home') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">Home</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('about') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">About</a>
                    <a href="{{ route('what-we-do') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('what-we-do') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">What We Do</a>
                    <a href="{{ route('artists') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('artists') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">Artists</a>
                    <a href="{{ route('events') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('events') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">Events</a>
                    <a href="{{ route('gallery') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('gallery') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">Gallery</a>
                    <a href="{{ route('impact') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('impact') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">Impact</a>
                    <a href="{{ route('blog') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('blog*') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">Blog</a>
                    <a href="{{ route('contact') }}" class="px-3 py-2 text-base font-medium {{ request()->routeIs('contact*') ? 'text-theatre-black font-semibold' : 'text-gray-500' }}">Contact</a>
                    <div class="flex flex-col gap-2 mt-4 px-3">
                        <a href="{{ route('request-performance') }}" class="text-center px-5 py-3 text-sm font-medium border border-theatre-black text-theatre-black">Request Performance</a>
                        <a href="{{ route('donate') }}" class="text-center px-5 py-3 text-sm font-medium bg-theatre-black text-white">Donate</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 max-w-7xl mx-auto mt-4">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Main Content --}}
    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-theatre-black text-gray-300" role="contentinfo">
        <div class="border-t border-gray-800"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16">
                {{-- Brand --}}
                <div class="lg:col-span-1">
                    <div class="mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Threefold Artists Inc." class="h-14 w-auto brightness-0 invert">
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed mb-6">
                        When people cannot come to the arts, we will come to them.
                    </p>
                    <p class="text-xs text-stage-gold italic font-display">"As you give, you return threefold."</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 tracking-[0.15em] uppercase mb-6">Explore</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('what-we-do') }}" class="text-gray-300 hover:text-white transition-colors">What We Do</a></li>
                        <li><a href="{{ route('events') }}" class="text-gray-300 hover:text-white transition-colors">Events</a></li>
                        <li><a href="{{ route('blog') }}" class="text-gray-300 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-gray-300 hover:text-white transition-colors">Gallery</a></li>
                        <li><a href="{{ route('impact') }}" class="text-gray-300 hover:text-white transition-colors">Our Impact</a></li>
                        <li><a href="{{ route('press-kit') }}" class="text-gray-300 hover:text-white transition-colors">Press Kit</a></li>
                    </ul>
                </div>

                {{-- Get Involved --}}
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 tracking-[0.15em] uppercase mb-6">Get Involved</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('request-performance') }}" class="text-gray-300 hover:text-white transition-colors">Request a Performance</a></li>
                        <li><a href="{{ route('get-involved') }}" class="text-gray-300 hover:text-white transition-colors">Volunteer</a></li>
                        <li><a href="{{ route('donate') }}" class="text-gray-300 hover:text-white transition-colors">Donate</a></li>
                        <li><a href="{{ route('donor-wall') }}" class="text-gray-300 hover:text-white transition-colors">Donor Wall</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 tracking-[0.15em] uppercase mb-6">Connect</h3>
                    <div class="space-y-3 text-sm">
                        <p>
                            <a href="mailto:hello@threefoldartists.org" class="text-gray-300 hover:text-white transition-colors">hello@threefoldartists.org</a>
                        </p>
                        <p class="text-gray-400">Greater Los Angeles, California</p>
                        <div class="flex gap-5 mt-6">
                            @if(config('services.social.facebook'))
                            <a href="{{ config('services.social.facebook') }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-white transition-colors" aria-label="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            @endif
                            @if(config('services.social.instagram'))
                            <a href="{{ config('services.social.instagram') }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-white transition-colors" aria-label="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            @endif
                            @if(config('services.social.youtube'))
                            <a href="{{ config('services.social.youtube') }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-white transition-colors" aria-label="YouTube">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                            @endif
                            @if(config('services.social.tiktok'))
                            <a href="{{ config('services.social.tiktok') }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-white transition-colors" aria-label="TikTok">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-.81.05l-.38-.25z"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Newsletter --}}
                    <div class="mt-8">
                        <h4 class="text-xs font-semibold text-gray-400 tracking-[0.15em] uppercase mb-4">Newsletter</h4>
                        @if(session('newsletter_success'))
                            <p class="text-sm text-stage-gold">{{ session('newsletter_success') }}</p>
                        @else
                            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="source" value="footer">
                                <input type="email" name="email" required placeholder="Your email" aria-label="Email address for newsletter"
                                    class="flex-1 bg-transparent border-0 border-b border-gray-600 text-white text-sm py-2 placeholder-gray-500 focus:border-stage-gold focus:ring-0">
                                <button type="submit" class="text-xs font-semibold tracking-wide uppercase text-stage-gold hover:text-white transition-colors">
                                    Subscribe
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-gray-800 mt-16 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Threefold Artists. All rights reserved.</p>
                <p class="text-xs text-gray-500">A 501(c)(3) nonprofit organization. All donations are tax-deductible.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
