<x-layouts.app title="Contact Us" metaDescription="Get in touch with Threefold Artists. We would love to hear from you.">

    {{-- Page Hero --}}
    <section class="bg-curtain-gradient py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Contact Us</h1>
            <p class="text-xl text-gray-200">We would love to hear from you.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Contact Info --}}
                <div class="lg:col-span-1">
                    <h2 class="font-display text-2xl font-bold text-theatre-black mb-6">Get in Touch</h2>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-stage-gold/10 flex items-center justify-center shrink-0 mt-1">
                                <svg class="w-5 h-5 text-stage-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-theatre-black">Email</h3>
                                <a href="mailto:hello@threefoldartists.org" class="text-stage-gold hover:underline">hello@threefoldartists.org</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-stage-gold/10 flex items-center justify-center shrink-0 mt-1">
                                <svg class="w-5 h-5 text-stage-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-theatre-black">Social Media</h3>
                                <div class="flex gap-3 mt-2">
                                    <a href="#" class="text-gray-500 hover:text-stage-gold">Facebook</a>
                                    <a href="#" class="text-gray-500 hover:text-stage-gold">Instagram</a>
                                    <a href="#" class="text-gray-500 hover:text-stage-gold">YouTube</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-linen-dark p-8">
                        <h2 class="font-display text-2xl font-bold text-theatre-black mb-6">Send a Message</h2>

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-grey-700 mb-1">Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-grey-700 mb-1">Email *</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-semibold text-grey-700 mb-1">Subject</label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-grey-700 mb-1">Message *</label>
                                <textarea name="message" id="message" rows="5" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">{{ old('message') }}</textarea>
                                @error('message') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-stage-gold text-theatre-black font-bold hover:bg-stage-gold-light transition-colors shadow-lg">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
