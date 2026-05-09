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
                \Filament\Tables\Columns\TextColumn::make('customer_name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('laundry_status')->badge()->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('sorter.name')
                    ->label('Sorter')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('washer.name')
                    ->label('Washer')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('presser.name')
                    ->label('Presser')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('packer.name')
                    ->label('Packer')
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
