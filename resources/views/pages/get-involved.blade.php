<x-layouts.app title="Get Involved" metaDescription="Volunteer with Threefold Artists. Share your time and talents backstage, behind the scenes and in the community to help bring live theatre to people who need it most.">

    {{-- Page Hero --}}
    <section class="pt-12 pb-12 bg-theatre-black relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Join Us</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Get Involved</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Share your talents. Make a difference.</p>
        </div>
    </section>

    {{-- Intro --}}
    <section class="py-16 sm:py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="font-display text-2xl sm:text-3xl font-light leading-relaxed text-theatre-black mb-8">
                If you love theatre and have a heart for giving back, we’d love to meet you!
            </p>
            <div class="space-y-5 text-gray-500 leading-relaxed">
                <p>At Threefold Artists, we believe that everyone deserves to experience the joy and magic of live performance. Our nonprofit brings theatre, music, and dance directly to seniors, hospitals, rehabilitation centers, and communities that may not otherwise have access to the arts.</p>
                <p>Behind every performance is a wonderful team of people who help bring it all to life. Whether you’re an experienced theatre professional, a student looking to gain hands-on experience, or simply someone who wants to give back, there’s a place for you at Threefold Artists.</p>
                <p>Your time, talents, and generosity can help us bring joy, connection, and unforgettable moments to the audiences we serve.</p>
            </div>
        </div>
    </section>

    {{-- Volunteer Opportunities --}}
    <section class="py-16 sm:py-20 bg-linen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-14 max-w-2xl">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">How You Can Help</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black mb-4">Volunteer Opportunities</h2>
                <p class="text-gray-500 leading-relaxed">We’re always excited to connect with individuals interested in helping with:</p>
            </div>
            @php
                $opportunities = [
                    ['Stage Management & Production', 'Rehearsal coordination, backstage assistance, and production support.'],
                    ['Sound, Lighting & Technical Support', 'QLab operators, sound engineers, lighting technicians, and equipment setup.'],
                    ['Costumes, Props & Set Design', 'Helping bring our productions to life.'],
                    ['Photography, Video & Social Media', 'Capturing and sharing the magic of our performances.'],
                    ['Marketing & Community Outreach', 'Helping us connect with facilities, supporters, and the community.'],
                    ['Fundraising & Special Events', 'Supporting donor engagement, events, and fundraising initiatives.'],
                    ['General Volunteer Support', 'Assisting with performances, transportation, setup, and other behind-the-scenes needs.'],
                ];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-12">
                @foreach($opportunities as [$title, $description])
                    <div>
                        <div class="w-12 h-px bg-stage-gold mb-6"></div>
                        <h3 class="font-display text-2xl font-normal text-theatre-black mb-3">{{ $title }}</h3>
                        <p class="text-gray-500 leading-relaxed">{{ $description }}</p>
                    </div>
                @endforeach
                <div class="border border-theatre-black/15 bg-white p-8">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-3">Are you a performer?</p>
                    <p class="text-gray-600 leading-relaxed mb-6">Actors, singers, musicians, and dancers can apply to perform with us.</p>
                    <a href="{{ route('artist-application.create') }}" class="inline-flex items-center gap-3 text-sm font-semibold tracking-wide uppercase text-theatre-black hover:text-curtain-red transition-colors">
                        Apply as an Artist
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Volunteer Interest Form --}}
    <section id="volunteer-form" class="py-16 sm:py-20 scroll-mt-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">We’d Love to Hear From You!</p>
                <h2 class="font-display text-4xl font-light text-theatre-black mb-4">Join Our Volunteer Team</h2>
                <p class="text-gray-500 leading-relaxed">You don’t need years of experience to make a difference. All you need is enthusiasm, reliability, and a willingness to be part of something meaningful. Tell us a little about yourself and how you’d like to get involved. We look forward to connecting with you!</p>
            </div>

            @php
                $inputClass = 'w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0';
                $labelClass = 'block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2';
                $choiceClass = 'flex items-start gap-3 border border-gray-200 px-4 py-3.5 cursor-pointer transition-colors hover:border-theatre-black has-[:checked]:border-theatre-black has-[:checked]:bg-linen';
                $selectedInterests = (array) old('interests', []);
                $selectedAvailability = (array) old('availability', []);
            @endphp

            <form action="{{ route('get-involved.store') }}" method="POST" class="space-y-10"
                x-data="{ submitting: false }"
                @submit="if (submitting) { $event.preventDefault(); return; } submitting = true">
                @csrf
                <input type="text" name="website" value="" autocomplete="off" tabindex="-1" class="hidden" aria-hidden="true">
                <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">

                @if($errors->any())
                    <div class="border-l-4 border-red-500 bg-red-50 p-4" role="alert">
                        <p class="text-sm text-red-700">Please check the highlighted fields below.</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label for="name" class="{{ $labelClass }}">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required aria-required="true" autocomplete="name"
                            @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                            class="{{ $inputClass }}">
                        @error('name') <p id="name-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="{{ $labelClass }}">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required aria-required="true" autocomplete="email"
                            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                            class="{{ $inputClass }}">
                        @error('email') <p id="email-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="{{ $labelClass }}">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" autocomplete="tel"
                            @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror
                            class="{{ $inputClass }}">
                        @error('phone') <p id="phone-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <fieldset @error('interests') aria-describedby="interests-error" @enderror>
                    <legend class="{{ $labelClass }}">How would you like to help? *</legend>
                    <p class="text-sm text-gray-500 mb-4">Select all that interest you.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($interests as $value => $label)
                            <label class="{{ $choiceClass }}">
                                <input type="checkbox" name="interests[]" value="{{ $value }}" @checked(in_array($value, $selectedInterests, true))
                                    class="mt-0.5 h-4 w-4 shrink-0 border-gray-300 text-theatre-black accent-theatre-black focus:ring-theatre-black">
                                <span class="text-sm text-theatre-black">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('interests') <p id="interests-error" class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    @error('interests.*') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </fieldset>

                <div>
                    <label for="experience" class="{{ $labelClass }}">Do you have any relevant experience?</label>
                    <p id="experience-hint" class="text-sm text-gray-500 mb-1">No experience necessary!</p>
                    <textarea name="experience" id="experience" rows="3" aria-describedby="experience-hint @error('experience') experience-error @enderror"
                        class="{{ $inputClass }}">{{ old('experience') }}</textarea>
                    @error('experience') <p id="experience-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <fieldset>
                    <legend class="{{ $labelClass }}">What is your general availability?</legend>
                    <p class="text-sm text-gray-500 mb-4">Select all that apply.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($availabilityOptions as $value => $label)
                            <label class="{{ $choiceClass }}">
                                <input type="checkbox" name="availability[]" value="{{ $value }}" @checked(in_array($value, $selectedAvailability, true))
                                    class="mt-0.5 h-4 w-4 shrink-0 border-gray-300 text-theatre-black accent-theatre-black focus:ring-theatre-black">
                                <span class="text-sm text-theatre-black">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('availability.*') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </fieldset>

                <div>
                    <label for="message" class="{{ $labelClass }}">Anything else you’d like us to know?</label>
                    <textarea name="message" id="message" rows="3"
                        @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                        class="{{ $inputClass }}">{{ old('message') }}</textarea>
                    @error('message') <p id="message-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-gray-200 pt-8 space-y-6">
                    <div>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="contact_consent" value="1" required aria-required="true" @checked(old('contact_consent'))
                                @error('contact_consent') aria-invalid="true" aria-describedby="contact-consent-error" @enderror
                                class="mt-0.5 h-4 w-4 shrink-0 border-gray-300 text-theatre-black accent-theatre-black focus:ring-theatre-black">
                            <span class="text-sm text-theatre-black">I agree to be contacted by Threefold Artists regarding volunteer opportunities. *</span>
                        </label>
                        @error('contact_consent') <p id="contact-consent-error" class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    <p class="text-xs text-gray-500 leading-relaxed">
                        <span class="font-semibold text-gray-600">Your privacy:</span>
                        We use the details you share only to follow up about volunteering with Threefold Artists. Your information is stored securely, is never sold or shared, and is seen only by our team. To update or remove your details at any time, email
                        <a href="mailto:{{ $privacyEmail }}" class="underline hover:text-theatre-black">{{ $privacyEmail }}</a>.
                    </p>

                    <x-turnstile />
                    @error('form_started_at') <p class="text-red-600 text-sm" role="alert">{{ $message }}</p> @enderror

                    <button type="submit" :disabled="submitting" :aria-busy="submitting"
                        class="inline-flex w-full sm:w-auto min-w-56 cursor-pointer items-center justify-center gap-3 px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase transition-all hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-theatre-black disabled:cursor-wait disabled:translate-y-0 disabled:bg-gray-500 disabled:shadow-none">
                        <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Submitting…' : 'Submit Volunteer Interest Form'">Submit Volunteer Interest Form</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- Closing --}}
    <section class="py-16 sm:py-20 bg-linen text-theatre-black text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="font-display text-3xl sm:text-4xl font-light mb-4">Keeping Theatre Alive.</p>
            <p class="text-curtain-red font-display italic">As you give, it returns threefold.</p>
        </div>
    </section>

</x-layouts.app>
