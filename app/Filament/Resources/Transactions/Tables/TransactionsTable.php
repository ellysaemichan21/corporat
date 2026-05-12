<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\IconColumn::make('is_priority')
                    ->label('Priority')
                    ->boolean()
                    ->trueIcon('heroicon-o-bolt')
                    ->falseIcon('heroicon-o-clock')
                    ->color(fn (bool $state): string => $state ? 'amber' : 'gray'),
                \Filament\Tables\Columns\IconColumn::make('is_corporate')
                    ->label('Corporate')
                    ->boolean()
                    ->trueIcon('heroicon-o-building-office-2')
                    ->falseIcon('heroicon-o-user')
                    ->color(fn (bool $state): string => $state ? 'blue' : 'gray'),
                \Filament\Tables\Columns\TextColumn::make('invoice_code')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('customer.name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('laundry_status')->badge()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Unpaid' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('tier_level')
                    ->label('Service Tier')
                    ->sortable()
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('details.service.name')
                    ->label('Services / Items')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limit(20)
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('delivery_fee')
                    ->label('Delivery Fee')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('asap_surcharge')
                    ->label('ASAP Surcharge')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('promo_discount')
                    ->label('Promo Discount')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Investment')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('driver.name')
                    ->label('Pickup Driver (Inbound)')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('delivery_driver.name')
                    ->label('Delivery Driver (Outbound)')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('sorter.name')
                    ->label('Intake Sorter')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('washer.name')
                    ->label('Primary Washer')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('presser.name')
                    ->label('Artisanal Presser')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('packer.name')
                    ->label('Quality Packer')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
