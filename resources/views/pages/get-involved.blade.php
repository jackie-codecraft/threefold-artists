<x-layouts.app title="Get Involved" metaDescription="Volunteer as a performing artist with Threefold Artists. Share your talent with communities that need it most.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Join Us</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Get Involved</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">Share your talent. Change lives. Return threefold.</p>
        </div>
    </section>

    {{-- Why Volunteer --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Why Volunteer</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Why Volunteer With Us?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Make a Difference</h3>
                    <p class="text-gray-500 leading-relaxed">Bring joy and connection to people who cannot easily access live performances.</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Grow as an Artist</h3>
                    <p class="text-gray-500 leading-relaxed">Performing in diverse settings challenges and develops your artistry in unique ways.</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Join a Community</h3>
                    <p class="text-gray-500 leading-relaxed">Connect with fellow artists who share your passion for accessible performing arts.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Application Form --}}
    <section class="py-24 sm:py-32 border-t border-gray-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Apply</p>
                <h2 class="font-display text-4xl font-light text-theatre-black mb-4">Artist Application</h2>
                <p class="text-gray-500">Fill in the form below and we will be in touch about volunteer opportunities.</p>
            </div>

            <form action="{{ route('get-involved.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                    </div>

                    <div>
                        <label for="discipline" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Art Form *</label>
                        <select name="discipline" id="discipline" required
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black focus:border-theatre-black focus:ring-0">
                            <option value="">Select...</option>
                            <option value="theatre" {{ old('discipline') === 'theatre' ? 'selected' : '' }}>Theatre</option>
                            <option value="music" {{ old('discipline') === 'music' ? 'selected' : '' }}>Music</option>
                            <option value="dance" {{ old('discipline') === 'dance' ? 'selected' : '' }}>Dance</option>
                            <option value="fine_arts" {{ old('discipline') === 'fine_arts' ? 'selected' : '' }}>Fine Arts</option>
                        </select>
                        @error('discipline') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="experience" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Experience</label>
                    <textarea name="experience" id="experience" rows="3"
                        placeholder="Tell us about your performing experience..."
                        class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('experience') }}</textarea>
                </div>

                <div>
                    <label for="bio" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Short Bio</label>
                    <textarea name="bio" id="bio" rows="3"
                        placeholder="A brief description of yourself as an artist..."
                        class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('bio') }}</textarea>
                </div>

                <div>
                    <label for="availability" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Availability</label>
                    <textarea name="availability" id="availability" rows="2"
                        placeholder="e.g., Weekday afternoons, weekends..."
                        class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('availability') }}</textarea>
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </section>

</x-layouts.app>
