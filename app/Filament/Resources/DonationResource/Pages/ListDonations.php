<?php

declare(strict_types=1);

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use App\Models\Donation;
use App\Models\Donor;
use App\Services\OfflineDonationService;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Auth;

class ListDonations extends ListRecords
{
    protected static string $resource = DonationResource::class;

    protected function getHeaderActions(): array
    {
        return [self::recordOfflineDonationAction()];
    }

    public static function recordOfflineDonationAction(): Action
    {
        return Action::make('recordOfflineDonation')
            ->label('Record offline donation')
            ->icon('heroicon-o-banknotes')
            ->modalHeading('Record offline donation')
            ->modalDescription('For cash, checks, or other gifts received outside the website. The gift counts toward totals, donor statements, and the donor portal.')
            ->modalSubmitActionLabel('Record donation')
            ->modalWidth('2xl')
            ->schema([
                Section::make('Donor')
                    ->schema([
                        Select::make('donor_id')
                            ->label('Donor')
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => Donor::query()
                                ->where(fn ($query) => $query
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%"))
                                ->orderBy('name')
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn (Donor $donor): array => [$donor->id => self::donorLabel($donor)])
                                ->all())
                            ->getOptionLabelUsing(fn ($value): ?string => ($donor = Donor::query()->find($value)) ? self::donorLabel($donor) : null)
                            ->helperText('Search by name or email. Use + to add a new donor.')
                            ->createOptionForm([
                                TextInput::make('name')->label('Full name')->required()->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email (optional)')
                                    ->email()
                                    ->maxLength(255)
                                    ->unique('donors', 'email')
                                    ->helperText('Needed to email a receipt and for the donor to use the donor portal.'),
                            ])
                            ->createOptionUsing(fn (array $data): int => self::createDonor($data)),
                    ]),
                Section::make('Gift')
                    ->columns(2)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->required()
                            ->prefix('$')
                            ->inputMode('numeric')
                            ->mask(RawJs::make('$money($input, \'.\', \',\', 0)'))
                            ->stripCharacters(',')
                            ->rule('regex:/^\d+$/')
                            ->rule('integer')
                            ->minValue(1)
                            ->maxValue(10_000_000)
                            ->validationMessages(['regex' => 'Enter whole dollars only.'])
                            ->placeholder('250'),
                        DatePicker::make('received_on')
                            ->label('Date received')
                            ->required()
                            ->native(false)
                            ->default(now())
                            ->maxDate(now())
                            ->helperText('The gift counts under this date on statements.'),
                        ToggleButtons::make('payment_method')
                            ->label('Payment method')
                            ->required()
                            ->inline()
                            ->live()
                            ->options(Donation::PAYMENT_METHODS)
                            ->default('check'),
                        TextInput::make('check_number')
                            ->label('Check number')
                            ->maxLength(50)
                            ->visible(fn (Get $get): bool => $get('payment_method') === 'check'),
                    ]),
                Section::make('Recognition & receipt')
                    ->schema([
                        Checkbox::make('is_anonymous')
                            ->label('Anonymous gift')
                            ->helperText('Never shown publicly.')
                            ->live(),
                        Checkbox::make('public_recognition_consent')
                            ->label('Donor agreed to be listed on the donor wall')
                            ->helperText('Only check this if the donor explicitly said yes.')
                            ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                        Checkbox::make('send_receipt')
                            ->label('Email a receipt to the donor now')
                            ->helperText('Requires the donor to have an email address.')
                            ->default(true),
                        Textarea::make('admin_note')
                            ->label('Internal note (optional)')
                            ->helperText('Staff only. Never shown to the donor.')
                            ->rows(2)
                            ->maxLength(2000),
                    ]),
            ])
            ->action(function (array $data): void {
                $donation = app(OfflineDonationService::class)->record($data, Auth::user());
                $receipt = $donation->receipt_sent_at !== null
                    ? ' Receipt emailed.'
                    : (($data['send_receipt'] ?? false) ? ' No receipt sent: donor has no email.' : '');

                Notification::make()
                    ->title('Offline donation recorded')
                    ->body('$'.number_format($donation->amount_cents / 100).' from '.($donation->donor_name ?: 'donor').' via '.$donation->paymentMethodLabel().'.'.$receipt)
                    ->success()
                    ->send();
            });
    }

    public static function voidAction(): Action
    {
        return Action::make('void')
            ->label('Void')
            ->icon('heroicon-o-no-symbol')
            ->color('danger')
            ->visible(fn (Donation $record): bool => $record->isVoidable())
            ->requiresConfirmation()
            ->modalHeading('Void offline donation')
            ->modalDescription(fn (Donation $record): string => 'This removes $'.number_format($record->amount_cents / 100).' from totals and donor statements. The entry stays in the ledger as voided. To correct it, void it and record the gift again.')
            ->modalSubmitActionLabel('Void donation')
            ->schema([
                Textarea::make('reason')->label('Reason')->required()->rows(2)->maxLength(1000),
            ])
            ->action(function (Donation $record, array $data): void {
                app(OfflineDonationService::class)->void($record, $data['reason'], Auth::user());

                Notification::make()->title('Donation voided')->success()->send();
            });
    }

    /** @param array{name: string, email?: string|null} $data */
    public static function createDonor(array $data): int
    {
        return Donor::query()->create([
            'name' => trim($data['name']),
            'email' => $data['email'] ?? null,
        ])->getKey();
    }

    private static function donorLabel(Donor $donor): string
    {
        $name = $donor->name ?: 'Unnamed donor';

        return $donor->email ? "{$name} ({$donor->email})" : "{$name} (no email)";
    }
}
