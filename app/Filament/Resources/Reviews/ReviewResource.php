<?php

namespace App\Filament\Resources\Reviews;

use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Customer Reviews';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Customer Review')
                    ->schema([
                        \Filament\Forms\Components\Select::make('transaction_id')
                            ->relationship('transaction', 'id')
                            ->label('Transaction')
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->label('Customer')
                            ->searchable()
                            ->nullable(),

                        \Filament\Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ 1 - Very Poor',
                                2 => '⭐⭐ 2 - Poor',
                                3 => '⭐⭐⭐ 3 - Average',
                                4 => '⭐⭐⭐⭐ 4 - Good',
                                5 => '⭐⭐⭐⭐⭐ 5 - Excellent',
                            ])
                            ->required(),

                        \Filament\Forms\Components\Textarea::make('comment')
                            ->label('Comment')
                            ->placeholder('Customer feedback...')
                            ->columnSpanFull()
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction')
                    ->prefix('#')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', (int) $state) . " ($state/5)")
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable(),
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
            'index'  => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit'   => EditReview::route('/{record}/edit'),
        ];
    }
}
