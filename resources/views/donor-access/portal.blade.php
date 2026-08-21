<x-layouts.app title="My Donations" metaDescription="Your Threefold Artists donation history.">
    <section class="py-16 sm:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Supporter access</p>
                    <h1 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">My Donations</h1>
                    <p class="text-gray-600 mt-3">A record of support associated with {{ $donor->email }}.</p>
                </div>
                @if($donor->stripe_customer_id)
                    <form action="{{ route('donor-portal.billing') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex px-5 py-3 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">Manage recurring support</button>
                    </form>
                @endif
            </div>

            @if(session('error'))
                <div class="mt-6 border-l-4 border-curtain-red bg-red-50 p-4 text-sm text-curtain-red">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="mt-6 border-l-4 border-stage-gold bg-yellow-50 p-4 text-sm text-theatre-black">{{ session('success') }}</div>
            @endif

            <div class="mt-6 print:hidden"><a href="{{ route('donor-portal.statement') }}" class="text-sm underline">View a print-friendly annual statement</a></div>

            <div class="mt-10 grid gap-10 lg:grid-cols-2">
                <div>
                    <h2 class="font-display text-2xl text-theatre-black">Donation history</h2>
                    <div class="mt-4 divide-y divide-gray-200 border-y border-gray-200">
                        @forelse($donations as $donation)
                            <div class="py-4 flex justify-between gap-4">
                                <div><p class="font-medium text-theatre-black">{{ ucfirst($donation->status) }} donation</p><p class="text-sm text-gray-500">{{ $donation->paid_at?->format('F j, Y') ?? $donation->created_at->format('F j, Y') }}</p></div>
                                <p class="font-medium text-theatre-black">${{ number_format(($donation->amount_cents ?? (int) round(((float) $donation->amount) * 100)) / 100, 2) }}</p>
                            </div>
                        @empty
                            <p class="py-5 text-gray-500">No donation history is available.</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h2 class="font-display text-2xl text-theatre-black">Recurring support</h2>
                    <div class="mt-4 divide-y divide-gray-200 border-y border-gray-200">
                        @forelse($supports as $support)
                            <div class="py-4">
                                <p class="font-medium text-theatre-black">
                                    @if($support->status === 'active' && $support->cancel_at_period_end)
                                        Cancellation scheduled
                                    @else
                                        {{ ucfirst($support->status) }} {{ $support->interval ? '— '.$support->interval : '' }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">${{ number_format($support->amount_cents / 100, 2) }} {{ strtoupper($support->currency) }}</p>
                                @if($support->status === 'active' && $support->cancel_at_period_end && $support->current_period_ends_at)
                                    <p class="mt-2 text-sm text-curtain-red">This recurring donation will end on {{ $support->current_period_ends_at->format('F j, Y') }}. No further renewals will be charged.</p>
                                @endif
                                @if($support->pending_amount_cents !== null && $support->pending_amount_effective_at)
                                    <p class="mt-2 text-sm text-stage-gold">Your recurring amount will change to ${{ number_format($support->pending_amount_cents / 100, 2) }} on {{ $support->pending_amount_effective_at->format('F j, Y') }}. Your current billing period remains unchanged.</p>
                                @elseif($support->status === 'active' && ! $support->cancel_at_period_end && $support->current_period_ends_at)
                                    <details class="mt-3">
                                        <summary class="cursor-pointer text-sm underline">Update recurring amount</summary>
                                        <form x-data="{ amount: '' }" action="{{ route('donor-portal.supports.amount', $support) }}" method="POST" class="mt-3 flex flex-wrap items-end gap-3 rounded border border-gray-200 p-4">
                                            @csrf
                                            <input type="hidden" name="amount" x-model="amount">
                                            <label class="w-full text-sm font-medium text-theatre-black" for="cadence-{{ $support->id }}">New recurring support</label>
                                            <div class="relative">
                                                <input x-ref="amount" id="amount-{{ $support->id }}" type="text" inputmode="numeric" autocomplete="off" placeholder="$0" class="w-40 rounded border border-gray-400 bg-white px-4 py-3 text-lg font-medium text-theatre-black placeholder-gray-400 shadow-sm focus:border-theatre-black focus:ring-0" aria-label="New recurring amount in whole dollars" @focus="$event.target.select()" @input="const digits = $event.target.value.replace(/\D/g, '').slice(0, 6); amount = digits ? String(Number(digits)) : ''; $event.target.value = digits ? '$' + Number(digits).toLocaleString('en-US') : '';">
                                            </div>
                                            <select id="cadence-{{ $support->id }}" name="cadence" class="rounded border border-gray-400 bg-white px-3 py-3 text-sm text-theatre-black">
                                                @foreach(['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'annual' => 'Annual'] as $value => $label)
                                                    <option value="{{ $value }}" @selected($support->interval === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" :disabled="! amount" class="rounded bg-theatre-black px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-gray-800 disabled:cursor-not-allowed disabled:bg-gray-300">Schedule for next renewal</button>
                                            <p id="amount-help-{{ $support->id }}" class="w-full text-sm text-gray-500">Enter the total whole-dollar amount and choose a cadence. Your current billing period stays unchanged; the new support takes effect at the next renewal.</p>
                                        </form>
                                    </details>
                                @endif
                                @if($support->status === 'active' && ! $support->paused_until && ! $support->cancel_at_period_end && $support->current_period_ends_at && in_array($support->interval, ['monthly', 'quarterly', 'annual'], true))
                                    <form action="{{ route('donor-portal.supports.pause', $support) }}" method="POST" class="mt-3 flex items-center gap-2">
                                        @csrf
                                        <label class="text-sm" for="pause-periods-{{ $support->id }}">Pause for</label>
                                        <select id="pause-periods-{{ $support->id }}" name="periods" class="border-gray-300 text-sm">
                                            @foreach(['monthly' => [1, 2, 3], 'quarterly' => [1, 2], 'annual' => [1]][$support->interval] as $period)
                                                <option value="{{ $period }}">{{ $period }} {{ $period === 1 ? 'cycle' : 'cycles' }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="text-sm underline">Request pause</button>
                                    </form>
                                @elseif($support->paused_until)
                                    <p class="mt-2 text-sm text-gray-500">Paused through {{ $support->paused_until->format('F j, Y') }}.</p>
                                @endif
                            </div>
                        @empty
                            <p class="py-5 text-gray-500">No recurring support is available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <p class="mt-10 text-sm text-gray-500">For questions about your support record, please contact us.</p>
        </div>
    </section>
</x-layouts.app>
