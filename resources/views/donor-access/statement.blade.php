<x-layouts.app title="Donation Statement" metaDescription="Your donation statement.">
    @push('head')
        <style>
            @media print {
                @page { margin: 12mm; }

                header,
                footer,
                .print\:hidden {
                    display: none !important;
                }

                body,
                main {
                    background: #fff !important;
                }
            }
        </style>
    @endpush

    <section class="py-16 sm:py-20 print:py-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between gap-6 print:hidden">
                <a href="{{ route('donor-portal') }}" class="text-sm underline">Back to donations</a>
                <button onclick="window.print()" class="px-4 py-2 bg-theatre-black text-white text-sm">Print statement</button>
            </div>
            <article class="mt-8 print:mt-0">
                <h1 class="font-display text-4xl text-theatre-black">Donation Statement — {{ $statement['year'] }}</h1>
                <p class="mt-4 font-medium">{{ $organization['legal_name'] }}</p>
                <p class="text-sm text-gray-600">EIN: {{ $organization['tax_id'] }}</p>
                @if($organization['mailing_address'])<p class="whitespace-pre-line text-sm text-gray-600">{{ $organization['mailing_address'] }}</p>@endif
                <p class="mt-4 text-sm text-gray-600">{{ $organization['tax_status'] }}</p>
                <p class="mt-6 text-gray-600">Prepared for {{ $donor->name ?: $donor->email }}</p>
                <table class="w-full mt-8 text-sm border-collapse"><thead><tr class="border-b text-left"><th class="py-2">Date</th><th>Description</th><th class="py-2 text-right">Amount</th></tr></thead><tbody>
                    @forelse($statement['entries'] as $entry)
                        <tr class="border-b"><td class="py-3">{{ app(\App\Services\DonationStatementService::class)->effectiveDate($entry)->format('M j, Y') }}</td><td>{{ ucfirst($entry->status) }}{{ $entry->status === 'paid' ? ' payment' : ' adjustment' }}</td><td class="text-right">${{ number_format($entry->amount_cents / 100, 2) }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-gray-600">No ledger entries are available for this calendar year.</td></tr>
                    @endforelse
                </tbody><tfoot><tr class="font-semibold"><td colspan="2" class="pt-4">Net recorded amount</td><td class="pt-4 text-right">${{ number_format($statement['total_cents'] / 100, 2) }}</td></tr></tfoot></table>
                <p class="mt-8 text-xs text-gray-600">{{ $organization['statement_notice'] }}</p>
            </article>
        </div>
    </section>
</x-layouts.app>
