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
                            <div class="py-4"><p class="font-medium text-theatre-black">{{ ucfirst($support->status) }} {{ $support->interval ? '— every '.$support->interval : '' }}</p><p class="text-sm text-gray-500">${{ number_format($support->amount_cents / 100, 2) }} {{ strtoupper($support->currency) }}</p></div>
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
