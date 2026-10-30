<x-layouts.app title="My Donations" metaDescription="Your Threefold Artists donation history.">
    <section class="py-16 sm:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-4">Supporter access</p>
                    <h1 class="font-display text-4xl sm:text-5xl font-light text-ink">My Donations</h1>
                    <p class="text-ink-soft mt-3">A record of support associated with {{ $donor->email }}.</p>
                </div>
                @if($donor->stripe_customer_id)
                    <form action="{{ route('donor-portal.billing') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex px-5 py-3 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors">Manage recurring support</button>
                    </form>
                @endif
            </div>

            @if(session('error'))
                <div class="mt-6 border-l-4 border-curtain-red bg-red-50 p-4 text-sm text-red-text dark:bg-red-950/40">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="mt-6 border-l-4 border-stage-gold bg-yellow-50 p-4 text-sm text-ink">{{ session('success') }}</div>
            @endif

            <div class="mt-6 print:hidden"><a href="{{ route('donor-portal.statement') }}" class="text-sm underline">View a print-friendly annual statement</a></div>

            <div class="mt-10 grid gap-10 lg:grid-cols-2">
                <div>
                    <h2 class="font-display text-2xl text-ink">Donation history</h2>
                    <div class="mt-4 divide-y divide-line border-y border-line">
                        @forelse($donations as $donation)
                            <div class="py-4 flex justify-between gap-4">
                                <div><p class="font-medium text-ink">{{ ucfirst($donation->status) }} donation</p><p class="text-sm text-ink-muted">{{ $donation->paid_at?->format('F j, Y') ?? $donation->created_at->format('F j, Y') }}</p></div>
                                <p class="font-medium text-ink">${{ number_format(($donation->amount_cents ?? (int) round(((float) $donation->amount) * 100)) / 100, 2) }}</p>
                            </div>
                        @empty
                            <p class="py-5 text-ink-muted">No donation history is available.</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h2 class="font-display text-2xl text-ink">Recurring support</h2>
                    <div class="mt-4 divide-y divide-line border-y border-line">
                        @forelse($supports as $support)
                            <div class="py-4">
                                <p class="font-medium text-ink">
                                    @if($support->status === 'active' && $support->cancel_at_period_end)
                                        Cancellation scheduled
                                    @else
                                        {{ ucfirst($support->status) }} {{ $support->interval ? '— '.$support->interval : '' }}
                                    @endif
                                </p>
                                <p class="text-sm text-ink-muted">${{ number_format($support->amount_cents / 100, 2) }} {{ strtoupper($support->currency) }}</p>
                                @if($support->status === 'active' && $support->cancel_at_period_end && $support->current_period_ends_at)
                                    <p class="mt-2 text-sm text-red-text">This recurring donation will end on {{ $support->current_period_ends_at->format('F j, Y') }}. No further renewals will be charged.</p>
                                @endif
                                @if($support->pending_amount_cents !== null && $support->pending_amount_effective_at)
                                    <p class="mt-2 text-sm text-stage-gold">Your recurring amount will change to ${{ number_format($support->pending_amount_cents / 100, 2) }} on {{ $support->pending_amount_effective_at->format('F j, Y') }}. Your current billing period remains unchanged.</p>
                                @elseif($support->status === 'active' && ! $support->cancel_at_period_end && $support->current_period_ends_at)
                                    <details class="mt-3">
                                        <summary class="cursor-pointer text-sm underline">Update recurring amount</summary>
                                        <form x-data="{ amount: '', coverFees: @js($support->covers_processing_fees), feePercent: @js(\App\Support\ProcessingFee::percent()), feeFixedCents: @js(\App\Support\ProcessingFee::fixedCents()), feeCents() { const gift = Number(this.amount) * 100; return gift > 0 ? Math.ceil((gift + this.feeFixedCents) / (1 - this.feePercent / 100)) - gift : 0; } }" action="{{ route('donor-portal.supports.amount', $support) }}" method="POST" class="mt-3 flex flex-wrap items-end gap-3 rounded border border-line p-4">
                                            @csrf
                                            <input type="hidden" name="amount" x-model="amount">
                                            <label class="w-full text-sm font-medium text-ink" for="cadence-{{ $support->id }}">New recurring support</label>
                                            <div class="relative">
                                                <input x-ref="amount" id="amount-{{ $support->id }}" type="text" inputmode="numeric" autocomplete="off" placeholder="$0" class="w-40 rounded border border-line-strong bg-card px-4 py-3 text-lg font-medium text-ink placeholder-ink-subtle shadow-sm focus:border-ink focus:ring-0" aria-label="New recurring amount in whole dollars" @focus="$event.target.select()" @input="const digits = $event.target.value.replace(/\D/g, '').slice(0, 6); amount = digits ? String(Number(digits)) : ''; $event.target.value = digits ? '$' + Number(digits).toLocaleString('en-US') : '';">
                                            </div>
                                            <select id="cadence-{{ $support->id }}" name="cadence" class="rounded border border-line-strong bg-card px-3 py-3 text-sm text-ink">
                                                @foreach(['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'annual' => 'Annual'] as $value => $label)
                                                    <option value="{{ $value }}" @selected($support->interval === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="cover_processing_fees" value="0">
                                            <label class="flex w-full items-start gap-2 text-sm text-ink-soft" for="cover-fees-{{ $support->id }}">
                                                <input type="checkbox" id="cover-fees-{{ $support->id }}" name="cover_processing_fees" value="1" x-model="coverFees" class="mt-0.5 h-4 w-4 border-line-strong text-ink focus:ring-ink">
                                                <span>Also cover processing fees<span x-show="amount" x-text="' (+$' + (feeCents() / 100).toFixed(2) + ' per renewal)'"></span>, so your full gift goes to our programs.</span>
                                            </label>
                                            <button type="submit" :disabled="! amount" class="rounded bg-cta px-4 py-3 text-sm font-semibold text-on-cta transition-colors hover:bg-cta-hover disabled:cursor-not-allowed disabled:bg-gray-300 dark:disabled:bg-line-strong">Schedule for next renewal</button>
                                            <p id="amount-help-{{ $support->id }}" class="w-full text-sm text-ink-muted">Enter your gift in whole dollars and choose a cadence. Your current billing period stays unchanged; the new support takes effect at the next renewal.</p>
                                        </form>
                                    </details>
                                @endif
                                @if($support->status === 'active' && ! $support->paused_until && ! $support->cancel_at_period_end && $support->current_period_ends_at && in_array($support->interval, ['monthly', 'quarterly', 'annual'], true))
                                    <form action="{{ route('donor-portal.supports.pause', $support) }}" method="POST" class="mt-3 flex items-center gap-2">
                                        @csrf
                                        <label class="text-sm" for="pause-periods-{{ $support->id }}">Pause for</label>
                                        <select id="pause-periods-{{ $support->id }}" name="periods" class="border-line-strong text-sm">
                                            @foreach(['monthly' => [1, 2, 3], 'quarterly' => [1, 2], 'annual' => [1]][$support->interval] as $period)
                                                <option value="{{ $period }}">{{ $period }} {{ $period === 1 ? 'cycle' : 'cycles' }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="text-sm underline">Request pause</button>
                                    </form>
                                @elseif($support->paused_until)
                                    <p class="mt-2 text-sm text-ink-muted">Paused through {{ $support->paused_until->format('F j, Y') }}.</p>
                                @endif
                            </div>
                        @empty
                            <p class="py-5 text-ink-muted">No recurring support is available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <p class="mt-10 text-sm text-ink-muted">For questions about your support record, please contact us.</p>
        </div>
    </section>
</x-layouts.app>
