<x-layouts.app title="Donate" metaDescription="Support Threefold Artists with a tax-deductible donation. Help us bring live performing arts to communities in need.">

    {{-- Page Hero --}}
    <section class="bg-curtain-gradient py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-stage-gold rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">Support Our Mission</h1>
            <p class="text-xl text-gray-200">Every donation helps bring live performing arts to those who need it most.</p>
        </div>
    </section>

    {{-- Donation Tiers --}}
    <section class="py-16" x-data="{ selectedAmount: 50, customAmount: '', donorName: '', donorEmail: '' }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Impact Descriptions --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach([
                    ['$25', 'Provides art supplies for a community workshop'],
                    ['$50', 'Brings music to 10 seniors in a care home'],
                    ['$100', 'Funds a full theatre performance at a shelter'],
                    ['$250', 'Sponsors a month of performances across multiple venues'],
                ] as [$amount, $impact])
                <div class="text-center p-6 bg-white rounded-xl border border-linen-dark">
                    <div class="font-display text-2xl font-bold text-curtain-red mb-2">{{ $amount }}</div>
                    <p class="text-sm text-gray-600">{{ $impact }}</p>
                </div>
                @endforeach
            </div>

            {{-- Donation Form --}}
            <div class="bg-white rounded-2xl shadow-sm border border-linen-dark p-8 sm:p-12 max-w-xl mx-auto">
                <h2 class="font-display text-2xl font-bold text-theatre-black mb-6 text-center">Choose Your Donation</h2>

                <form action="{{ route('donate.checkout') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Amount Selection --}}
                    <div>
                        <label class="block text-sm font-semibold text-grey-700 mb-3">Select an Amount</label>
                        <div class="grid grid-cols-4 gap-3 mb-3">
                            @foreach([25, 50, 100, 250] as $amt)
                            <button type="button"
                                @click="selectedAmount = {{ $amt }}; customAmount = ''"
                                :class="selectedAmount === {{ $amt }} && customAmount === '' ? 'bg-curtain-red text-white border-curtain-red' : 'bg-white text-theatre-black border-gray-300 hover:border-curtain-red'"
                                class="py-3 px-4 rounded-lg border-2 font-bold text-lg transition-colors cursor-pointer">
                                ${{ $amt }}
                            </button>
                            @endforeach
                        </div>
                        <div>
                            <label for="custom_amount" class="block text-sm text-gray-500 mb-1">Or enter a custom amount:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">$</span>
                                <input type="number" id="custom_amount" min="1" step="1"
                                    x-model="customAmount"
                                    @input="selectedAmount = 0"
                                    placeholder="Other amount"
                                    class="w-full pl-8 rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                            </div>
                        </div>
                        <input type="hidden" name="amount" :value="customAmount !== '' ? customAmount : selectedAmount">
                    </div>

                    {{-- Donor Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="donor_name" class="block text-sm font-semibold text-grey-700 mb-1">Name (optional)</label>
                            <input type="text" name="donor_name" id="donor_name" x-model="donorName"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                        </div>
                        <div>
                            <label for="donor_email" class="block text-sm font-semibold text-grey-700 mb-1">Email (for receipt)</label>
                            <input type="email" name="donor_email" id="donor_email" x-model="donorEmail"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-stage-gold focus:ring-stage-gold">
                        </div>
                    </div>

                    <div class="text-center pt-4">
                        <button type="submit" class="inline-flex items-center justify-center w-full px-8 py-4 rounded-full bg-curtain-red text-white text-lg font-bold hover:bg-curtain-red-light transition-colors shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Donate <span x-text="customAmount !== '' ? '$' + customAmount : '$' + selectedAmount" class="ml-1"></span>
                        </button>
                    </div>
                </form>

                <p class="text-xs text-gray-500 text-center mt-4">
                    Threefold Artists is a 501(c)(3) nonprofit. All donations are tax-deductible.
                    Payments are processed securely via Stripe.
                </p>
            </div>
        </div>
    </section>

</x-layouts.app>
