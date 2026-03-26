<x-layouts.app title="Get Involved" metaDescription="Volunteer as a performing artist with Threefold Artists. Share your talent with communities that need it most.">

    {{-- Page Hero --}}
    <section class="bg-curtain-gradient py-16 relative overflow-hidden">
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Get Involved</h1>
            <p class="text-xl text-gray-200">Share your talent. Change lives. Return threefold.</p>
        </div>
    </section>

    {{-- Why Volunteer --}}
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl font-bold text-theatre-black mb-4">Why Volunteer With Us?</h2>
                <div class="w-24 h-1 bg-stage-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-curtain-red/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-curtain-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="font-display text-xl font-bold mb-2">Make a Difference</h3>
                    <p class="text-gray-600">Bring joy and connection to people who cannot easily access live performances.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-stage-gold/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-stage-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-display text-xl font-bold mb-2">Grow as an Artist</h3>
                    <p class="text-gray-600">Performing in diverse settings challenges and develops your artistry in unique ways.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-spotlight-amber/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-spotlight-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-display text-xl font-bold mb-2">Join a Community</h3>
                    <p class="text-gray-600">Connect with fellow artists who share your passion for accessible performing arts.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Application Form --}}
    <section class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-linen-dark p-8 sm:p-12">
                <h2 class="font-display text-2xl font-bold text-theatre-black mb-2">Artist Application</h2>
                <p class="text-gray-600 mb-8">Fill in the form below and we will be in touch about volunteer opportunities.</p>

                <form action="{{ route('get-involved.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-grey-700 mb-1">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-curtain-red focus:ring-curtain-red">
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-grey-700 mb-1">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-curtain-red focus:ring-curtain-red">
                            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-grey-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-curtain-red focus:ring-curtain-red">
                        </div>

                        <div>
                            <label for="discipline" class="block text-sm font-semibold text-grey-700 mb-1">Art Form *</label>
                            <select name="discipline" id="discipline" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-curtain-red focus:ring-curtain-red">
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
                        <label for="experience" class="block text-sm font-semibold text-grey-700 mb-1">Experience</label>
                        <textarea name="experience" id="experience" rows="3"
                            placeholder="Tell us about your performing experience..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-curtain-red focus:ring-curtain-red">{{ old('experience') }}</textarea>
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-semibold text-grey-700 mb-1">Short Bio</label>
                        <textarea name="bio" id="bio" rows="3"
                            placeholder="A brief description of yourself as an artist..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-curtain-red focus:ring-curtain-red">{{ old('bio') }}</textarea>
                    </div>

                    <div>
                        <label for="availability" class="block text-sm font-semibold text-grey-700 mb-1">Availability</label>
                        <textarea name="availability" id="availability" rows="2"
                            placeholder="e.g., Weekday afternoons, weekends..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-curtain-red focus:ring-curtain-red">{{ old('availability') }}</textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="inline-flex items-center justify-center px-10 py-4 rounded-full bg-curtain-red text-white text-lg font-bold hover:bg-curtain-red-light transition-colors shadow-lg">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-layouts.app>
