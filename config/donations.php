<?php

declare(strict_types=1);

return [
    'organization_name' => 'Threefold Artists, Inc.',
    'mailing_address' => env('DONATIONS_MAILING_ADDRESS', "14014 Moorpark Street\nSuite 308\nSherman Oaks, CA 91423"),
    'legal_name' => env('DONATIONS_LEGAL_NAME', 'Threefold Artists, Inc.'),
    // Optional donor-covered processing fees. Match these to the Stripe
    // account's actual domestic card rate (2.9% + 30¢ standard; 2.2% + 30¢
    // once Stripe approves the nonprofit discount).
    'processing_fee' => [
        'percent' => (float) env('DONATIONS_PROCESSING_FEE_PERCENT', 2.9),
        'fixed_cents' => (int) env('DONATIONS_PROCESSING_FEE_FIXED_CENTS', 30),
    ],
    'tax_id' => env('DONATIONS_TAX_ID', '85-0567934'),
    'tax_status' => env('DONATIONS_TAX_STATUS', 'Threefold Artists, Inc. is recognized by the Internal Revenue Service as a 501(c)(3) nonprofit charitable organization.'),
    'receipt_disclosure' => 'No goods or services were provided in exchange for this contribution. Therefore, the full amount of your donation is tax-deductible to the extent allowed by law.',
    'tax_deductibility_statement' => 'Contributions to Threefold Artists, Inc. are tax-deductible to the fullest extent permitted by law.',
    'statement_notice' => 'This annual giving summary is a record of payments and adjustments. Contributions to Threefold Artists, Inc. are tax-deductible to the fullest extent permitted by law. Please retain it with your tax records.',
];
