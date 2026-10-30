<x-layouts.app title="Donate" metaDescription="Support Threefold Artists and help bring live performing arts to communities in need.">

    <section class="pt-12 pb-12 bg-band relative overflow-hidden">
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
                <h2 class="font-display text-4xl sm:text-5xl font-light text-ink mb-6">What Your Donation Helps Support</h2>
                <p class="text-lg leading-8 text-ink-soft">Every gift helps us create and deliver a complete live theatrical experience, from what happens on stage to everything behind the scenes that makes each performance possible.</p>
                <p class="mt-6 text-ink-soft">Your generosity helps us create and deliver a complete live theatrical experience, including:</p>
            </div>

            <ul class="mt-8 grid gap-x-10 gap-y-5 sm:grid-cols-2 text-ink-soft">
                <li><strong class="text-ink">Lighting &amp; sound</strong> – professional equipment and technical support</li>
                <li><strong class="text-ink">Costumes &amp; props</strong> – bringing each production and character to life</li>
                <li><strong class="text-ink">Playbills &amp; theatre tickets</strong> – giving our audiences the experience of attending a live show</li>
                <li><strong class="text-ink">Music &amp; production materials</strong> – arrangements, scripts, licensing, and other show needs</li>
                <li><strong class="text-ink">Insurance &amp; safety</strong> – essential coverage that allows us to perform responsibly in hospitals, rehabilitation centers, senior communities, and other facilities</li>
                <li><strong class="text-ink">Transportation &amp; equipment</strong> – getting our artists, sets, props, and production materials where they need to go</li>
                <li><strong class="text-ink">Marketing &amp; printing</strong> – helping us reach facilities and communities that can benefit from our programs</li>
                <li><strong class="text-ink">Seasonal &amp; themed productions</strong> – supporting new shows and special performances throughout the year</li>
            </ul>

            <p class="max-w-3xl mt-10 text-lg leading-8 text-ink-soft">Every contribution, large or small, helps us bring the magic of live performance directly to those who may not otherwise have the opportunity to experience it.</p>
        </div>
    </section>

    <section class="py-16 sm:py-20" x-data="{
        selectedAmount: 50, customAmount: '', donationType: 'one-time', coverFees: @js((bool) old('cover_processing_fees')),
        feePercent: @js($processingFeePercent), feeFixedCents: @js($processingFeeFixedCents),
        giftCents() { const gift = Number(this.customAmount !== '' ? this.customAmount : this.selectedAmount); return Number.isFinite(gift) && gift > 0 ? Math.round(gift * 100) : 0; },
        feeCents() { const gift = this.giftCents(); return gift > 0 ? Math.ceil((gift + this.feeFixedCents) / (1 - this.feePercent / 100)) - gift : 0; },
        totalCents() { return this.giftCents() + (this.coverFees ? this.feeCents() : 0); },
        money(cents) { return '$' + (cents / 100).toLocaleString('en-US', { minimumFractionDigits: cents % 100 === 0 ? 0 : 2, maximumFractionDigits: 2 }); },
    }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl mx-auto">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-8">Choose Your Gift</p>

                <form action="{{ route('donate.checkout') }}" method="POST" class="space-y-8">
                    @csrf

                    @error('donor_email')
                        <div class="border-l-4 border-curtain-red bg-red-50 p-5 text-left dark:bg-red-950/40" role="alert">
                            <p class="font-semibold text-red-text">You already have active recurring support.</p>
                            <p class="mt-2 text-sm leading-6 text-ink-soft">To change your amount, cadence, payment method, pause, or cancel, use your secure donation portal.</p>
                            <div class="mt-4 flex flex-wrap gap-4">
                                <a href="{{ route('donor-access.request') }}" class="inline-flex items-center justify-center bg-cta px-4 py-2.5 text-sm font-semibold uppercase tracking-wide text-on-cta hover:bg-cta-hover">Manage My Donations</a>
                                <button type="button" x-on:click="donationType = 'one-time'; $el.closest('form').querySelector('[name=donation_type]').value = 'one-time'; $el.closest('form').scrollIntoView({ behavior: 'smooth', block: 'start' })" class="text-sm font-semibold text-ink underline">Make a one-time donation instead</button>
                            </div>
                        </div>
                    @enderror

                    <fieldset class="mb-10">
                        <legend class="block text-xs font-semibold tracking-[0.15em] uppercase text-ink-muted mb-4">Donation Type</legend>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach(['one-time' => 'One-Time', 'monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'annual' => 'Annual'] as $value => $label)
                                <button type="button" x-on:click="donationType = '{{ $value }}'" :aria-pressed="(donationType === '{{ $value }}').toString()"
                                    :class="donationType === '{{ $value }}' ? 'bg-cta text-on-cta border-cta' : 'bg-card text-ink border-line-strong hover:border-ink'"
                                    class="py-3 px-3 border-2 font-semibold text-sm tracking-wide uppercase transition-colors cursor-pointer">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </fieldset>

                    <input type="hidden" name="donation_type" :value="donationType">

                    <fieldset>
                        <legend class="block text-xs font-semibold tracking-[0.15em] uppercase text-ink-muted mb-4">Select an Amount</legend>
                        <div class="grid grid-cols-4 gap-3 mb-4">
                            @foreach([25, 50, 100, 250] as $amount)
                                <button type="button" x-on:click="selectedAmount = {{ $amount }}; customAmount = ''" :aria-pressed="(selectedAmount === {{ $amount }} && customAmount === '').toString()"
                                    :class="selectedAmount === {{ $amount }} && customAmount === '' ? 'bg-cta text-on-cta border-cta' : 'bg-card text-ink border-line-strong hover:border-ink'"
                                    class="py-3 px-4 border-2 font-semibold text-lg transition-colors cursor-pointer">
                                    ${{ $amount }}
                                </button>
                            @endforeach
                        </div>
                        <label for="custom_amount" class="block text-xs text-ink-faint mb-2">Or enter a custom amount:</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-ink-muted font-semibold">$</span>
                            <input type="number" id="custom_amount" min="1" step="0.01" x-model="customAmount" x-on:input="selectedAmount = 0" placeholder="Other amount"
                                class="form-field pl-8">
                        </div>
                        <input type="hidden" name="amount" :value="customAmount !== '' ? customAmount : selectedAmount">
                    </fieldset>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="donor_name" class="form-label">Name (optional)</label>
                            <input type="text" name="donor_name" id="donor_name" value="{{ old('donor_name') }}"
                                class="form-field">
                        </div>
                        <div>
                            <label for="donor_email" class="form-label">Email <span class="text-red-text">(required)</span></label>
                            <input type="email" name="donor_email" id="donor_email" value="{{ old('donor_email') }}" required autocomplete="email"
                                class="form-field">
                        </div>
                    </div>

                    <input type="hidden" name="cover_processing_fees" value="0">
                    <div class="flex items-start gap-3 border border-line bg-card p-4" data-testid="cover-processing-fees">
                        <input type="checkbox" name="cover_processing_fees" id="cover_processing_fees" value="1" x-model="coverFees" class="mt-0.5 w-4 h-4 border-line-strong text-ink focus:ring-ink">
                        <label for="cover_processing_fees" class="text-sm text-ink-soft">
                            <span class="font-semibold text-ink">Add <span x-text="money(feeCents())"></span> to help cover processing fees</span>
                            <span class="block text-ink-muted mt-1">Card processing costs about {{ rtrim(rtrim(number_format($processingFeePercent, 2), '0'), '.') }}% + {{ $processingFeeFixedCents }}¢ per gift. Covering it means your full gift goes to our live performances.</span>
                        </label>
                    </div>

                    <input type="hidden" name="is_anonymous" value="0">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" @checked(old('is_anonymous')) class="w-4 h-4 border-line-strong text-ink focus:ring-ink">
                        <label for="is_anonymous" class="text-sm text-ink-muted">Keep my donation anonymous on public recognition surfaces.</label>
                    </div>

                    <input type="hidden" name="public_recognition_consent" value="0">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="public_recognition_consent" id="public_recognition_consent" value="1" @checked(old('public_recognition_consent')) class="w-4 h-4 border-line-strong text-ink focus:ring-ink">
                        <label for="public_recognition_consent" class="text-sm text-ink-muted">Yes, you may recognize my name on the Donor Wall.</label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center justify-center w-full px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">
                            Donate <span x-text="money(totalCents())" class="ml-1"></span>
                            <span x-show="donationType === 'monthly'" class="ml-1">/ month</span>
                            <span x-show="donationType === 'quarterly'" class="ml-1">/ 3 months</span>
                            <span x-show="donationType === 'annual'" class="ml-1">/ year</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-line pt-6 text-center space-y-2" data-testid="donation-tax-info">
                    <p class="inline-flex items-center justify-center gap-1.5 text-xs text-ink-faint">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Payments are processed securely via Stripe.
                    </p>
                    <p class="text-sm font-semibold text-ink" x-data="{ copied: false }">
                        Threefold Artists, Inc. is a 501(c)(3) nonprofit
                        <span class="text-ink-ghost mx-1" aria-hidden="true">&middot;</span>
                        <span class="whitespace-nowrap">EIN {{ config('donations.tax_id') }}</span>
                        <button type="button"
                                x-on:click="navigator.clipboard.writeText('{{ config('donations.tax_id') }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                class="ml-1 align-middle text-xs font-normal text-ink-muted underline hover:text-ink"
                                aria-label="Copy EIN to clipboard">
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied" x-cloak>Copied</span>
                        </button>
                    </p>
                    <p class="text-xs text-ink-muted">Donations are tax-deductible to the extent allowed by law.</p>
                </div>
                <p class="text-sm text-ink-muted text-center mt-4"><a href="{{ route('donor-access.request') }}" class="underline hover:text-ink">Manage My Donations</a></p>
            </div>
        </div>
    </section>
</x-layouts.app>
