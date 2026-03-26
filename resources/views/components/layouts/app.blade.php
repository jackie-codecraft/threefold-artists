@props(['title' => null, 'metaDescription' => null])
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Threefold Artists' }} - Keeping Theatre Alive</title>
    <meta name="description" content="{{ $metaDescription ?? 'Threefold Artists brings live performing arts to communities that cannot easily access them. Theatre, music, dance - when people cannot come to the arts, we will come to them.' }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title ?? 'Threefold Artists' }} - Keeping Theatre Alive">
    <meta property="og:description" content="{{ $metaDescription ?? 'Bringing live performing arts to underserved communities.' }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-linen text-theatre-black antialiased">
    {{-- Navigation --}}
    <header x-data="{ open: false }" class="bg-theatre-black text-white sticky top-0 z-50 shadow-lg">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Threefold Artists" class="h-12 w-auto">
                    <div class="hidden sm:block">
                        <span class="font-display text-xl font-bold text-stage-gold">Threefold Artists</span>
                        <span class="block text-xs text-gray-400 tracking-wider uppercase">Keeping Theatre Alive</span>
                    </div>
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('home') ? 'text-stage-gold' : 'text-gray-300' }}">Home</a>
                    <a href="{{ route('about') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('about') ? 'text-stage-gold' : 'text-gray-300' }}">About</a>
                    <a href="{{ route('what-we-do') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('what-we-do') ? 'text-stage-gold' : 'text-gray-300' }}">What We Do</a>
                    <a href="{{ route('request-performance') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('request-performance*') ? 'text-stage-gold' : 'text-gray-300' }}">Request a Performance</a>
                    <a href="{{ route('get-involved') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('get-involved*') ? 'text-stage-gold' : 'text-gray-300' }}">Get Involved</a>
                    <a href="{{ route('events') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('events') ? 'text-stage-gold' : 'text-gray-300' }}">Events</a>
                    <a href="{{ route('gallery') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('gallery') ? 'text-stage-gold' : 'text-gray-300' }}">Gallery</a>
                    <a href="{{ route('impact') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('impact') ? 'text-stage-gold' : 'text-gray-300' }}">Impact</a>
                    <a href="{{ route('blog') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors hover:text-stage-gold {{ request()->routeIs('blog*') ? 'text-stage-gold' : 'text-gray-300' }}">Blog</a>
                    <a href="{{ route('donate') }}" class="ml-2 inline-flex items-center px-4 py-2 rounded-full bg-curtain-red text-white text-sm font-semibold hover:bg-curtain-red-light transition-colors shadow-md">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Donate
                    </a>
                    <a href="{{ route('contact') }}" class="ml-1 inline-flex items-center px-4 py-2 rounded-full border border-stage-gold text-stage-gold text-sm font-semibold hover:bg-stage-gold hover:text-theatre-black transition-colors">
                        Contact
                    </a>
                </div>

                {{-- Mobile menu button --}}
                <button @click="open = !open" class="lg:hidden p-2 rounded-md text-gray-300 hover:text-white focus:outline-none" aria-label="Toggle menu">
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
                 class="lg:hidden pb-4 border-t border-gray-700 mt-2 pt-4">
                <div class="flex flex-col gap-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('home') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Home</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('about') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">About</a>
                    <a href="{{ route('what-we-do') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('what-we-do') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">What We Do</a>
                    <a href="{{ route('request-performance') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('request-performance*') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Request a Performance</a>
                    <a href="{{ route('get-involved') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('get-involved*') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Get Involved</a>
                    <a href="{{ route('events') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('events') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Events</a>
                    <a href="{{ route('gallery') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('gallery') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Gallery</a>
                    <a href="{{ route('impact') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('impact') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Impact</a>
                    <a href="{{ route('blog') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('blog*') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Blog</a>
                    <a href="{{ route('contact') }}" class="px-3 py-2 rounded-md text-base font-medium hover:bg-gray-800 hover:text-stage-gold {{ request()->routeIs('contact*') ? 'text-stage-gold bg-gray-800' : 'text-gray-300' }}">Contact</a>
                    <div class="flex gap-2 mt-3 px-3">
                        <a href="{{ route('donate') }}" class="flex-1 text-center px-4 py-2.5 rounded-full bg-curtain-red text-white font-semibold hover:bg-curtain-red-light transition-colors">Donate</a>
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
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-theatre-black text-gray-300">
        {{-- Decorative top border --}}
        <div class="h-1 bg-gold-shimmer"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                {{-- Brand --}}
                <div class="lg:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Threefold Artists" class="h-10 w-auto">
                        <span class="font-display text-lg font-bold text-stage-gold">Threefold Artists</span>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed mb-4">
                        When people cannot come to the arts, we will come to them. We bring live performing arts - theatre, music, and dance - to communities that need them most.
                    </p>
                    <p class="text-xs text-stage-gold italic font-display">"As you give, you return threefold."</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="font-display text-lg font-semibold text-white mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-stage-gold transition-colors">About Us</a></li>
                        <li><a href="{{ route('what-we-do') }}" class="hover:text-stage-gold transition-colors">What We Do</a></li>
                        <li><a href="{{ route('events') }}" class="hover:text-stage-gold transition-colors">Upcoming Events</a></li>
                        <li><a href="{{ route('blog') }}" class="hover:text-stage-gold transition-colors">News & Blog</a></li>
                        <li><a href="{{ route('gallery') }}" class="hover:text-stage-gold transition-colors">Gallery</a></li>
                        <li><a href="{{ route('impact') }}" class="hover:text-stage-gold transition-colors">Our Impact</a></li>
                    </ul>
                </div>

                {{-- Get Involved --}}
                <div>
                    <h3 class="font-display text-lg font-semibold text-white mb-4">Get Involved</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('request-performance') }}" class="hover:text-stage-gold transition-colors">Request a Performance</a></li>
                        <li><a href="{{ route('get-involved') }}" class="hover:text-stage-gold transition-colors">Volunteer as an Artist</a></li>
                        <li><a href="{{ route('donate') }}" class="hover:text-stage-gold transition-colors">Make a Donation</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-stage-gold transition-colors">Contact Us</a></li>
                    </ul>
                </div>

                {{-- Contact & Social --}}
                <div>
                    <h3 class="font-display text-lg font-semibold text-white mb-4">Connect</h3>
                    <div class="space-y-3 text-sm">
                        <p>
                            <a href="mailto:hello@threefoldartists.org" class="hover:text-stage-gold transition-colors">hello@threefoldartists.org</a>
                        </p>
                        <div class="flex gap-4 mt-4">
                            {{-- Social media placeholders --}}
                            <a href="#" class="text-gray-400 hover:text-stage-gold transition-colors" aria-label="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-stage-gold transition-colors" aria-label="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-stage-gold transition-colors" aria-label="YouTube">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Newsletter placeholder --}}
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-white mb-2">Stay Updated</h4>
                        <p class="text-xs text-gray-400 mb-2">Newsletter coming soon.</p>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-gray-700 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Threefold Artists. All rights reserved.</p>
                <p class="text-xs text-gray-500">A 501(c)(3) nonprofit organization. All donations are tax-deductible.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
