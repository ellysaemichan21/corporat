<?php

namespace App\Filament\Resources\ServiceTiers;

use App\Filament\Resources\ServiceTiers\Pages\CreateServiceTier;
use App\Filament\Resources\ServiceTiers\Pages\EditServiceTier;
use App\Filament\Resources\ServiceTiers\Pages\ListServiceTiers;
use App\Filament\Resources\ServiceTiers\Schemas\ServiceTierForm;
use App\Filament\Resources\ServiceTiers\Tables\ServiceTiersTable;
use App\Models\ServiceTier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceTierResource extends Resource
{
    protected static ?string $model = ServiceTier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ServiceTier';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Luxury Tier Level')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Tier Name (e.g., Signature Care)')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\Textarea::make('description')
                            ->label('What does this tier include?')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Essential Care', 'Essential' => 'gray',
                        'Signature Care', 'Signature' => 'warning',
                        'Bespoke Structural', 'Bespoke' => 'success',
                        default => 'primary',
                    }),
                \Filament\Tables\Columns\TextColumn::make('description')->limit(50),
                // This automatically counts how many services belong to this tier!
                \Filament\Tables\Columns\TextColumn::make('services_count')->counts('services')->label('Items in Tier'),
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
            'index' => ListServiceTiers::route('/'),
            'create' => CreateServiceTier::route('/create'),
            'edit' => EditServiceTier::route('/{record}/edit'),
        ];
    }
}
