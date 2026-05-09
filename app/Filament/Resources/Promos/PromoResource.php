<?php

namespace App\Filament\Resources\Promos;

use App\Models\Promo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Promos\Pages\CreatePromo;
use App\Filament\Resources\Promos\Pages\EditPromo;
use App\Filament\Resources\Promos\Pages\ListPromos;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Promos';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Promo / Discount')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('code')
                            ->label('Promo Code')
                            ->placeholder('e.g. LAUNDRY10')
                            ->required()
                            ->maxLength(50)
                            ->columnSpan(1),

                        \Filament\Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->placeholder('e.g. Diskon 10% untuk order pertama')
                            ->columnSpan(1),

                        \Filament\Forms\Components\Select::make('type')
                            ->label('Discount Type')
                            ->options([
                                'percent' => 'Percent (%)',
                                'fixed'   => 'Fixed Amount (Rp)',
                            ])
                            ->required()
                            ->reactive(),

                        \Filament\Forms\Components\TextInput::make('value')
                            ->label(fn ($get) => $get('type') === 'fixed' ? 'Discount Amount (Rp)' : 'Discount (%)')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        \Filament\Forms\Components\TextInput::make('min_order')
                            ->label('Minimum Order (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->nullable(),

                        \Filament\Forms\Components\DatePicker::make('expires_at')
                            ->label('Expires At')
                            ->nullable(),

                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                \Filament\Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(40),

                \Filament\Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'percent',
                        'warning' => 'fixed',
                    ]),

                \Filament\Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn ($record) => $record->type === 'percent'
                        ? $record->value . '%'
                        : 'Rp ' . number_format($record->value, 0, ',', '.')
                    ),

                \Filament\Tables\Columns\TextColumn::make('expires_at')
                    ->date()
                    ->sortable(),

                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([\Filament\Actions\EditAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPromos::route('/'),
            'create' => CreatePromo::route('/create'),
            'edit'   => EditPromo::route('/{record}/edit'),
        ];
    }
}
