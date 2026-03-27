<x-layouts.app title="Contact Us" metaDescription="Get in touch with Threefold Artists. We would love to hear from you.">

    {{-- Page Hero --}}
    <section class="pt-12 pb-12 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/55"></div>
    </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Reach Out</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Contact Us</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">We would love to hear from you.</p>
        </div>
    </section>
    <div class="h-12 bg-gradient-to-b from-theatre-black to-white"></div>

    <section class="py-16 sm:py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-20">
                {{-- Contact Info --}}
                <div class="lg:col-span-1">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-6">Get in Touch</p>

                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Email</h3>
                            <a href="mailto:hello@threefoldartists.org" class="text-theatre-black hover:underline">hello@threefoldartists.org</a>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Location</h3>
                            <p class="text-gray-700">Greater Los Angeles, California</p>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-2">Social</h3>
                            <div class="flex gap-4 mt-2">
                                <a href="#" class="text-gray-500 hover:text-theatre-black transition-colors">Facebook</a>
                                <a href="#" class="text-gray-500 hover:text-theatre-black transition-colors">Instagram</a>
                                <a href="#" class="text-gray-500 hover:text-theatre-black transition-colors">YouTube</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-2">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-6">Send a Message</p>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="name" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required aria-required="true"
                                    @error('name') aria-describedby="name-error" @enderror
                                    class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                                @error('name') <p id="name-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required aria-required="true"
                                    @error('email') aria-describedby="email-error" @enderror
                                    class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                                @error('email') <p id="email-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Message *</label>
                            <textarea name="message" id="message" rows="5" required aria-required="true"
                                @error('message') aria-describedby="message-error" @enderror
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('message') }}</textarea>
                            @error('message') <p id="message-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
