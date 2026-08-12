<?php

declare(strict_types=1);

return [
    'organization_name' => 'Threefold Artists, Inc.',
    // Set this only to a mailing address verified by the organization.
    'mailing_address' => env('DONATIONS_MAILING_ADDRESS'),
    'legal_name' => env('DONATIONS_LEGAL_NAME'),
    'tax_id' => env('DONATIONS_TAX_ID'),
    'tax_status' => env('DONATIONS_TAX_STATUS'),
    'statement_notice' => 'This statement is a record of payments and adjustments. Please consult your own records or advisor regarding any tax treatment.',
];
