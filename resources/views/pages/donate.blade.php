<x-layouts.app title="Donate" metaDescription="Support Threefold Artists and help bring live performing arts to communities in need.">

    <section class="pt-12 pb-12 bg-theatre-black relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Give</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Help Keep Theatre Alive</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Your generosity helps bring the magic of live theatre, music, and artistic connection directly to those who may not otherwise have the opportunity to experience it.</p>
        </div>
    </section>

    <section class="py-16 sm:py-20 bg-linen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="w-16 h-px bg-stage-gold mb-6"></div>
                <h2 class="mb-6 border-l-4 border-stage-gold pl-5 font-display text-4xl sm:text-5xl font-medium italic leading-[1.05] text-curtain-red">What Your Donation Helps Support</h2>
                <p class="text-lg leading-8 text-gray-700">Every gift helps us create and deliver a complete live theatrical experience, from what happens on stage to everything behind the scenes that makes each performance possible.</p>
                <p class="mt-6 text-gray-700">Your generosity helps us create and deliver a complete live theatrical experience, including:</p>
            </div>

            <ul class="mt-8 grid gap-x-10 gap-y-5 sm:grid-cols-2 text-gray-700">
                <li><strong class="text-theatre-black">Lighting &amp; sound</strong> – professional equipment and technical support</li>
                <li><strong class="text-theatre-black">Costumes &amp; props</strong> – bringing each production and character to life</li>
                <li><strong class="text-theatre-black">Playbills &amp; theatre tickets</strong> – giving our audiences the experience of attending a live show</li>
                <li><strong class="text-theatre-black">Music &amp; production materials</strong> – arrangements, scripts, licensing, and other show needs</li>
                <li><strong class="text-theatre-black">Insurance &amp; safety</strong> – essential coverage that allows us to perform responsibly in hospitals, rehabilitation centers, senior communities, and other facilities</li>
                <li><strong class="text-theatre-black">Transportation &amp; equipment</strong> – getting our artists, sets, props, and production materials where they need to go</li>
                <li><strong class="text-theatre-black">Marketing &amp; printing</strong> – helping us reach facilities and communities that can benefit from our programs</li>
                <li><strong class="text-theatre-black">Seasonal &amp; themed productions</strong> – supporting new shows and special performances throughout the year</li>
            </ul>

            <p class="max-w-3xl mt-10 text-lg leading-8 text-gray-700">Every contribution, large or small, helps us bring the magic of live performance directly to those who may not otherwise have the opportunity to experience it.</p>
        </div>
    </section>

    <section class="py-16 sm:py-20" x-data="{ selectedAmount: 50, customAmount: '', donationType: 'one-time' }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl mx-auto">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-8">Choose Your Gift</p>

                <form action="{{ route('donate.checkout') }}" method="POST" class="space-y-8">
                    @csrf

                    @error('donor_email')
                        <div class="border-l-4 border-curtain-red bg-red-50 p-5 text-left" role="alert">
                            <p class="font-semibold text-curtain-red">You already have active recurring support.</p>
                            <p class="mt-2 text-sm leading-6 text-gray-700">To change your amount, cadence, payment method, pause, or cancel, use your secure donation portal.</p>
                            <div class="mt-4 flex flex-wrap gap-4">
                                <a href="{{ route('donor-access.request') }}" class="inline-flex items-center justify-center bg-theatre-black px-4 py-2.5 text-sm font-semibold uppercase tracking-wide text-white hover:bg-gray-800">Manage My Donations</a>
                                <button type="button" x-on:click="donationType = 'one-time'; $el.closest('form').querySelector('[name=donation_type]').value = 'one-time'; $el.closest('form').scrollIntoView({ behavior: 'smooth', block: 'start' })" class="text-sm font-semibold text-theatre-black underline">Make a one-time donation instead</button>
                            </div>
                        </div>
                    @enderror

                    <div class="mb-10">
                        <label class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-4">Donation Type</label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach(['one-time' => 'One-Time', 'monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'annual' => 'Annual'] as $value => $label)
                                <button type="button" x-on:click="donationType = '{{ $value }}'"
                                    :class="donationType === '{{ $value }}' ? 'bg-theatre-black text-white border-theatre-black' : 'bg-white text-theatre-black border-gray-300 hover:border-theatre-black'"
                                    class="py-3 px-3 border-2 font-semibold text-sm tracking-wide uppercase transition-colors cursor-pointer">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <input type="hidden" name="donation_type" :value="donationType">

                    <div>
                        <label class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-4">Select an Amount</label>
                        <div class="grid grid-cols-4 gap-3 mb-4">
                            @foreach([25, 50, 100, 250] as $amount)
                                <button type="button" x-on:click="selectedAmount = {{ $amount }}; customAmount = ''"
                                    :class="selectedAmount === {{ $amount }} && customAmount === '' ? 'bg-theatre-black text-white border-theatre-black' : 'bg-white text-theatre-black border-gray-300 hover:border-theatre-black'"
                                    class="py-3 px-4 border-2 font-semibold text-lg transition-colors cursor-pointer">
                                    ${{ $amount }}
                                </button>
                            @endforeach
                        </div>
                        <label for="custom_amount" class="block text-xs text-gray-400 mb-2">Or enter a custom amount:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center text-gray-500 font-semibold">$</span>
                            <input type="number" id="custom_amount" min="1" step="0.01" x-model="customAmount" x-on:input="selectedAmount = 0" placeholder="Other amount"
                                class="w-full border-0 border-b border-gray-300 bg-transparent pl-4 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>
                        <input type="hidden" name="amount" :value="customAmount !== '' ? customAmount : selectedAmount">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="donor_name" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Name (optional)</label>
                            <input type="text" name="donor_name" id="donor_name" value="{{ old('donor_name') }}"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>
                        <div>
                            <label for="donor_email" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Email <span class="text-curtain-red">(required)</span></label>
                            <input type="email" name="donor_email" id="donor_email" value="{{ old('donor_email') }}" required autocomplete="email"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>
                    </div>

                    <input type="hidden" name="is_anonymous" value="0">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" @checked(old('is_anonymous')) class="w-4 h-4 border-gray-300 text-theatre-black focus:ring-theatre-black">
                        <label for="is_anonymous" class="text-sm text-gray-500">Keep my donation anonymous on public recognition surfaces.</label>
                    </div>

                    <input type="hidden" name="public_recognition_consent" value="0">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="public_recognition_consent" id="public_recognition_consent" value="1" @checked(old('public_recognition_consent')) class="w-4 h-4 border-gray-300 text-theatre-black focus:ring-theatre-black">
                        <label for="public_recognition_consent" class="text-sm text-gray-500">Yes, you may recognize my name on the Donor Wall.</label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center justify-center w-full px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                            Donate <span x-text="customAmount !== '' ? '$' + customAmount : '$' + selectedAmount" class="ml-1"></span>
                            <span x-show="donationType === 'monthly'" class="ml-1">/ month</span>
                            <span x-show="donationType === 'quarterly'" class="ml-1">/ 3 months</span>
                            <span x-show="donationType === 'annual'" class="ml-1">/ year</span>
                        </button>
                    </div>
                </form>

                <p class="text-xs text-gray-400 text-center mt-6">Payments are processed securely via Stripe.</p>
                <p class="text-sm text-gray-500 text-center mt-4"><a href="{{ route('donor-access.request') }}" class="underline hover:text-theatre-black">Manage My Donations</a></p>
            </div>
        </div>
    </section>
</x-layouts.app>
