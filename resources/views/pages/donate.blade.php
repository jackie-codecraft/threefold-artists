<x-layouts.app title="Donate" metaDescription="Support Threefold Artists with a tax-deductible donation. Help us bring live performing arts to communities in need.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Give</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Support Our Mission</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">Every donation helps bring live performing arts to those who need it most.</p>
        </div>
    </section>

    {{-- Donation Tiers --}}
    <section class="py-24 sm:py-32" x-data="{ selectedAmount: 50, customAmount: '', donorName: '', donorEmail: '', donationType: 'one-time' }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Impact Descriptions --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
                @foreach([
                    ['$25', 'Provides art supplies for a community workshop'],
                    ['$50', 'Brings music to 10 seniors in a care home'],
                    ['$100', 'Funds a full theatre performance at a shelter'],
                    ['$250', 'Sponsors a month of performances across multiple venues'],
                ] as [$amount, $impact])
                <div class="text-center">
                    <div class="font-display text-3xl font-light text-theatre-black mb-3">{{ $amount }}</div>
                    <div class="w-8 h-px bg-stage-gold mx-auto my-3"></div>
                    <p class="text-sm text-gray-500">{{ $impact }}</p>
                </div>
                @endforeach
            </div>

            {{-- Donation Form --}}
            <div class="max-w-xl mx-auto">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-8">Choose Your Donation</p>

                <form action="{{ route('donate.checkout') }}" method="POST" class="space-y-8">
                    @csrf

                    {{-- Donation Type --}}
                    <div class="mb-10">
                        <label class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-4">Donation Type</label>
                        <div class="flex gap-3">
                            <button type="button" x-on:click="donationType = 'one-time'"
                                :class="donationType === 'one-time' ? 'bg-theatre-black text-white border-theatre-black' : 'bg-white text-theatre-black border-gray-300 hover:border-theatre-black'"
                                class="flex-1 py-3 px-4 border-2 font-semibold text-sm tracking-wide uppercase transition-colors cursor-pointer">
                                One-Time
                            </button>
                            <button type="button" x-on:click="donationType = 'monthly'"
                                :class="donationType === 'monthly' ? 'bg-theatre-black text-white border-theatre-black' : 'bg-white text-theatre-black border-gray-300 hover:border-theatre-black'"
                                class="flex-1 py-3 px-4 border-2 font-semibold text-sm tracking-wide uppercase transition-colors cursor-pointer">
                                Monthly
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="donation_type" :value="donationType">

                    {{-- Amount Selection --}}
                    <div>
                        <label class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-4">Select an Amount</label>
                        <div class="grid grid-cols-4 gap-3 mb-4">
                            @foreach([25, 50, 100, 250] as $amt)
                            <button type="button"
                                x-on:click="selectedAmount = {{ $amt }}; customAmount = ''"
                                :class="selectedAmount === {{ $amt }} && customAmount === '' ? 'bg-theatre-black text-white border-theatre-black' : 'bg-white text-theatre-black border-gray-300 hover:border-theatre-black'"
                                class="py-3 px-4 border-2 font-semibold text-lg transition-colors cursor-pointer">
                                ${{ $amt }}
                            </button>
                            @endforeach
                        </div>
                        <div>
                            <label for="custom_amount" class="block text-xs text-gray-400 mb-2">Or enter a custom amount:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center text-gray-500 font-semibold">$</span>
                                <input type="number" id="custom_amount" min="1" step="1"
                                    x-model="customAmount"
                                    x-on:input="selectedAmount = 0"
                                    placeholder="Other amount"
                                    class="w-full border-0 border-b border-gray-300 bg-transparent pl-4 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                            </div>
                        </div>
                        <input type="hidden" name="amount" :value="customAmount !== '' ? customAmount : selectedAmount">
                    </div>

                    {{-- Donor Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="donor_name" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Name (optional)</label>
                            <input type="text" name="donor_name" id="donor_name" x-model="donorName"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>
                        <div>
                            <label for="donor_email" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Email (for receipt)</label>
                            <input type="email" name="donor_email" id="donor_email" x-model="donorEmail"
                                class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        </div>
                    </div>

                    {{-- Anonymous --}}
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="w-4 h-4 border-gray-300 text-theatre-black focus:ring-theatre-black">
                        <label for="is_anonymous" class="text-sm text-gray-500">Make my donation anonymous</label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center justify-center w-full px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                            Donate <span x-text="customAmount !== '' ? '$' + customAmount : '$' + selectedAmount" class="ml-1"></span>
                            <span x-show="donationType === 'monthly'" class="ml-1">/ month</span>
                        </button>
                    </div>
                </form>

                <p class="text-xs text-gray-400 text-center mt-6">
                    Threefold Artists is a 501(c)(3) nonprofit. All donations are tax-deductible.
                    Payments are processed securely via Stripe.
                </p>
            </div>
        </div>
    </section>

</x-layouts.app>
