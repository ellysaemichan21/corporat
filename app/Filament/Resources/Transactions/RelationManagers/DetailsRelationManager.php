<?php

namespace App\Filament\Resources\Transactions\RelationManagers;

use App\Models\Service;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $service = Service::find($state);
                        if ($service) {
                            $set('unit_price', $service->price);
                        }
                    }),

                TextInput::make('weight')
                    ->label('Weight (Kg) / Quantity')
                    ->numeric()
                    ->default(1.0)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $unitPrice = $get('unit_price') ?? 0;
                        $set('subtotal_display', number_format($state * $unitPrice, 2));
                    }),

                TextInput::make('unit_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $weight = $get('weight') ?? 1.0;
                        $set('subtotal_display', number_format($weight * $state, 2));
                    }),

                TextInput::make('subtotal_display')
                    ->label('Calculated Subtotal')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder(fn (Get $get) => number_format(($get('weight') ?? 1.0) * ($get('unit_price') ?? 0), 2))
                    ->prefix('Rp'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable(),
                TextColumn::make('weight')
                    ->label('Weight/Qty')
                    ->suffix(' Kg'),
                TextColumn::make('unit_price')
                    ->money('IDR'),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->state(fn ($record) => $record->weight * $record->unit_price),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
