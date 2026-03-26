<x-layouts.app title="Request a Performance" metaDescription="Request a free live performance of theatre, music, or dance for your care home, hospital, school, or community center.">

    {{-- Page Hero --}}
    <section class="bg-curtain-gradient py-16 relative overflow-hidden">
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Request a Performance</h1>
            <p class="text-xl text-gray-200">Bring live performing arts to your community, completely free of charge.</p>
        </div>
    </section>

    {{-- Form --}}
    <section class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-linen-dark p-8 sm:p-12">
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Tell us about your organization and what kind of performance you are looking for. We will get back to you within 5 business days to discuss options and availability.
                </p>

                <form action="{{ route('request-performance.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Organization Details --}}
                    <div class="border-b border-linen-dark pb-6 mb-6">
                        <h2 class="font-display text-xl font-bold text-theatre-black mb-4">Organization Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="organization_name" class="block text-sm font-semibold text-grey-700 mb-1">Organization Name *</label>
                                <input type="text" name="organization_name" id="organization_name" value="{{ old('organization_name') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                                @error('organization_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="venue_type" class="block text-sm font-semibold text-grey-700 mb-1">Venue Type *</label>
                                <select name="venue_type" id="venue_type" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                                    <option value="">Select venue type...</option>
                                    <option value="care_home" {{ old('venue_type') === 'care_home' ? 'selected' : '' }}>Care Home / Nursing Home</option>
                                    <option value="hospital" {{ old('venue_type') === 'hospital' ? 'selected' : '' }}>Hospital</option>
                                    <option value="school" {{ old('venue_type') === 'school' ? 'selected' : '' }}>School</option>
                                    <option value="shelter" {{ old('venue_type') === 'shelter' ? 'selected' : '' }}>Shelter</option>
                                    <option value="rehab" {{ old('venue_type') === 'rehab' ? 'selected' : '' }}>Rehabilitation Center</option>
                                    <option value="community" {{ old('venue_type') === 'community' ? 'selected' : '' }}>Community Center</option>
                                    <option value="other" {{ old('venue_type') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('venue_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="address" class="block text-sm font-semibold text-grey-700 mb-1">Venue Address</label>
                            <textarea name="address" id="address" rows="2"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    {{-- Contact Information --}}
                    <div class="border-b border-linen-dark pb-6 mb-6">
                        <h2 class="font-display text-xl font-bold text-theatre-black mb-4">Contact Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_name" class="block text-sm font-semibold text-grey-700 mb-1">Contact Name *</label>
                                <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                                @error('contact_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="contact_email" class="block text-sm font-semibold text-grey-700 mb-1">Email Address *</label>
                                <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                                @error('contact_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-semibold text-grey-700 mb-1">Phone Number</label>
                                <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                            </div>
                        </div>
                    </div>

                    {{-- Performance Details --}}
                    <div class="border-b border-linen-dark pb-6 mb-6">
                        <h2 class="font-display text-xl font-bold text-theatre-black mb-4">Performance Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="audience_size" class="block text-sm font-semibold text-grey-700 mb-1">Expected Audience Size</label>
                                <input type="number" name="audience_size" id="audience_size" value="{{ old('audience_size') }}" min="1"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                            </div>

                            <div>
                                <label for="audience_demographics" class="block text-sm font-semibold text-grey-700 mb-1">Audience Demographics</label>
                                <input type="text" name="audience_demographics" id="audience_demographics" value="{{ old('audience_demographics') }}"
                                    placeholder="e.g., Elderly residents, ages 70-90"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                            </div>

                            <div>
                                <label for="preferred_art_form" class="block text-sm font-semibold text-grey-700 mb-1">Preferred Art Form</label>
                                <select name="preferred_art_form" id="preferred_art_form"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                                    <option value="">No preference</option>
                                    <option value="theatre" {{ old('preferred_art_form') === 'theatre' ? 'selected' : '' }}>Theatre</option>
                                    <option value="music" {{ old('preferred_art_form') === 'music' ? 'selected' : '' }}>Music</option>
                                    <option value="dance" {{ old('preferred_art_form') === 'dance' ? 'selected' : '' }}>Dance</option>
                                    <option value="fine_arts" {{ old('preferred_art_form') === 'fine_arts' ? 'selected' : '' }}>Fine Arts</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="preferred_dates" class="block text-sm font-semibold text-grey-700 mb-1">Preferred Dates / Availability</label>
                            <textarea name="preferred_dates" id="preferred_dates" rows="2"
                                placeholder="e.g., Any Friday afternoon in April, or specific dates..."
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">{{ old('preferred_dates') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="accessibility_requirements" class="block text-sm font-semibold text-grey-700 mb-1">Accessibility Requirements</label>
                            <textarea name="accessibility_requirements" id="accessibility_requirements" rows="2"
                                placeholder="Any special requirements we should know about..."
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">{{ old('accessibility_requirements') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-semibold text-grey-700 mb-1">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="inline-flex items-center justify-center px-10 py-4 rounded-md bg-stage-gold text-theatre-black text-lg font-bold hover:bg-stage-gold-light transition-colors shadow-lg">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-layouts.app>
