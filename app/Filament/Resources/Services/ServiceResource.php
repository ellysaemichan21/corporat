<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Service';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Service Definition')
                    ->schema([
                        \Filament\Forms\Components\Select::make('service_tier_id')
                            ->relationship('tier', 'name')
                            ->required()
                            ->preload(),
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Garment / Service Name')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        \Filament\Forms\Components\Select::make('unit_type')
                            ->options([
                                'Piece' => 'Per Piece (Structured Garment)',
                                'Kg' => 'Per Kg (Bulk Weight)',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('tier.name')->sortable()->badge(),
                \Filament\Tables\Columns\TextColumn::make('price')->money('IDR', true),
                \Filament\Tables\Columns\TextColumn::make('unit_type'),
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
