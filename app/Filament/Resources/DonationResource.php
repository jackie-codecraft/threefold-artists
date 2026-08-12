<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Donation ledger entry';

    protected static ?string $pluralModelLabel = 'Donation Ledger';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donor.name')
                    ->label('Donor')
                    ->searchable(['name', 'email'])
                    ->placeholder('Unlinked / legacy'),
                Tables\Columns\TextColumn::make('donor_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'refunded', 'disputed' => 'danger',
                        'legacy' => 'gray',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('amount_cents')
                    ->label('Ledger amount')
                    ->formatStateUsing(fn (?int $state, Donation $record): string => '$'.number_format(($state ?? (int) round((float) $record->amount * 100)) / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('recurring_interval')
                    ->label('Cadence')
                    ->badge()
                    ->placeholder('One-time'),
                Tables\Columns\TextColumn::make('donationSupport.status')
                    ->label('Support status')
                    ->badge()
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('is_anonymous')
                    ->label('Anonymous')
                    ->boolean(),
                Tables\Columns\IconColumn::make('public_recognition_consent')
                    ->label('Public consent')
                    ->boolean(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid date')
                    ->dateTime()
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('receipt_sent_at')
                    ->label('Receipt')
                    ->dateTime()
                    ->placeholder('Not sent'),
                Tables\Columns\TextColumn::make('adjustmentFor.stripe_checkout_session_id')
                    ->label('Adjusts')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stripe_checkout_session_id')
                    ->label('Checkout session')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stripe_payment_intent_id')
                    ->label('Payment intent')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stripe_invoice_id')
                    ->label('Invoice')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stripe_charge_id')
                    ->label('Charge')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stripe_refund_id')
                    ->label('Refund')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stripe_dispute_id')
                    ->label('Dispute')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('paid_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'paid' => 'Paid',
                    'refunded' => 'Refund adjustment',
                    'disputed' => 'Dispute adjustment',
                    'legacy' => 'Legacy / unverified',
                ]),
                Tables\Filters\TernaryFilter::make('is_recurring')->label('Recurring'),
                Tables\Filters\TernaryFilter::make('is_anonymous')->label('Anonymous'),
                Tables\Filters\TernaryFilter::make('public_recognition_consent')->label('Public recognition consent'),
                Tables\Filters\Filter::make('receipt_sent')
                    ->label('Receipt sent')
                    ->query(fn ($query) => $query->whereNotNull('receipt_sent_at')),
                Tables\Filters\Filter::make('adjustments')
                    ->label('Adjustments only')
                    ->query(fn ($query) => $query->whereNotNull('adjustment_for_donation_id')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
        ];
    }
}
