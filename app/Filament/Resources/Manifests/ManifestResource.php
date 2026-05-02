<?php

namespace App\Filament\Resources\Manifests;

use App\Filament\Resources\Manifests\Pages\CreateManifest;
use App\Filament\Resources\Manifests\Pages\EditManifest;
use App\Filament\Resources\Manifests\Pages\ListManifests;
use App\Filament\Resources\Manifests\Schemas\ManifestForm;
use App\Filament\Resources\Manifests\Tables\ManifestsTable;
use App\Models\Manifest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManifestResource extends Resource
{
    protected static ?string $model = Manifest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Manifest';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Delivery Dispatch Log')
                    ->schema([
                        \Filament\Forms\Components\Select::make('driver_id')
                            ->relationship('driver', 'name')
                            ->label('Assign Driver')
                            ->searchable()
                            ->required(),
                            
                        \Filament\Forms\Components\Select::make('partner_id')
                            ->relationship('partner', 'name')
                            ->label('Destination (Apartment/Corporate)')
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('status')
                            ->options([
                                'Pending' => 'Pending Dispatch',
                                'En Route' => 'En Route',
                                'Delivered' => 'Delivered to Concierge',
                            ])
                            ->default('Pending')
                            ->required(),

                        \Filament\Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Scheduled Departure time'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')->sortable()->label('Manifest ID')->prefix('#'),
                \Filament\Tables\Columns\TextColumn::make('driver.name')->searchable()->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('partner.name')->searchable(),
                \Filament\Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'Pending',
                        'warning' => 'En Route',
                        'success' => 'Delivered',
                    ]),
                \Filament\Tables\Columns\TextColumn::make('scheduled_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([\Filament\Actions\EditAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManifests::route('/'),
            'create' => CreateManifest::route('/create'),
            'edit' => EditManifest::route('/{record}/edit'),
        ];
    }
}
