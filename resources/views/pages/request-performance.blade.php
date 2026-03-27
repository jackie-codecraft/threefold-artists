<x-layouts.app title="Request a Performance" metaDescription="Request a free live performance of theatre, music, or dance for your care home, hospital, school, or community center.">

    {{-- Page Hero --}}
    <section class="pt-12 pb-12 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center opacity-20">
        <div class="absolute inset-0 bg-theatre-black/60"></div>
    </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Book Us</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Request a Performance</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Bring live performing arts to your community, completely free of charge.</p>
        </div>
    </section>
    <div class="h-12 bg-gradient-to-b from-theatre-black to-white"></div>

    {{-- Form --}}
    <section class="py-16 sm:py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-gray-500 mb-12 leading-relaxed">
                Tell us about your organization and what kind of performance you are looking for. We will get back to you within 5 business days to discuss options and availability.
            </p>

            <form action="{{ route('request-performance.store') }}" method="POST" class="space-y-12">
                @csrf

                {{-- Organization Details --}}
                <div class="border-b border-gray-200 pb-12">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-8">Organization Details</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="organization_name" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Organization Name *</label>
                            <input type="text" name="organization_name" id="organization_name" value="{{ old('organization_name') }}" required aria-required="true"
                                @error('organization_name') aria-describedby="organization_name-error" @enderror
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                            @error('organization_name') <p id="organization_name-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="venue_type" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Venue Type *</label>
                            <select name="venue_type" id="venue_type" required aria-required="true"
                                @error('venue_type') aria-describedby="venue_type-error" @enderror
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black focus:border-theatre-black focus:ring-0">
                                <option value="">Select venue type...</option>
                                <option value="care_home" {{ old('venue_type') === 'care_home' ? 'selected' : '' }}>Care Home / Nursing Home</option>
                                <option value="hospital" {{ old('venue_type') === 'hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="school" {{ old('venue_type') === 'school' ? 'selected' : '' }}>School</option>
                                <option value="shelter" {{ old('venue_type') === 'shelter' ? 'selected' : '' }}>Shelter</option>
                                <option value="rehab" {{ old('venue_type') === 'rehab' ? 'selected' : '' }}>Rehabilitation Center</option>
                                <option value="community" {{ old('venue_type') === 'community' ? 'selected' : '' }}>Community Center</option>
                                <option value="other" {{ old('venue_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('venue_type') <p id="venue_type-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8">
                        <label for="address" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Venue Address</label>
                        <textarea name="address" id="address" rows="2"
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('address') }}</textarea>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="border-b border-gray-200 pb-12">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-8">Contact Information</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="contact_name" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Contact Name *</label>
                            <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" required aria-required="true"
                                @error('contact_name') aria-describedby="contact_name-error" @enderror
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                            @error('contact_name') <p id="contact_name-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Email Address *</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" required aria-required="true"
                                @error('contact_email') aria-describedby="contact_email-error" @enderror
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                            @error('contact_email') <p id="contact_email-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Phone Number</label>
                            <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>
                    </div>
                </div>

                {{-- Performance Details --}}
                <div class="border-b border-gray-200 pb-12">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-8">Performance Details</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="audience_size" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Expected Audience Size</label>
                            <input type="number" name="audience_size" id="audience_size" value="{{ old('audience_size') }}" min="1"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>

                        <div>
                            <label for="audience_demographics" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Audience Demographics</label>
                            <input type="text" name="audience_demographics" id="audience_demographics" value="{{ old('audience_demographics') }}"
                                placeholder="e.g., Elderly residents, ages 70-90"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>

                        <div>
                            <label for="preferred_art_form" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Preferred Art Form</label>
                            <select name="preferred_art_form" id="preferred_art_form"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black focus:border-theatre-black focus:ring-0">
                                <option value="">No preference</option>
                                <option value="theatre" {{ old('preferred_art_form') === 'theatre' ? 'selected' : '' }}>Theatre</option>
                                <option value="music" {{ old('preferred_art_form') === 'music' ? 'selected' : '' }}>Music</option>
                                <option value="dance" {{ old('preferred_art_form') === 'dance' ? 'selected' : '' }}>Dance</option>
                                <option value="fine_arts" {{ old('preferred_art_form') === 'fine_arts' ? 'selected' : '' }}>Fine Arts</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8">
                        <label for="preferred_dates" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Preferred Dates / Availability</label>
                        <textarea name="preferred_dates" id="preferred_dates" rows="2"
                            placeholder="e.g., Any Friday afternoon in April, or specific dates..."
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('preferred_dates') }}</textarea>
                    </div>

                    <div class="mt-8">
                        <label for="accessibility_requirements" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Accessibility Requirements</label>
                        <textarea name="accessibility_requirements" id="accessibility_requirements" rows="2"
                            placeholder="Any special requirements we should know about..."
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('accessibility_requirements') }}</textarea>
                    </div>

                    <div class="mt-8">
                        <label for="notes" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </section>

</x-layouts.app>
