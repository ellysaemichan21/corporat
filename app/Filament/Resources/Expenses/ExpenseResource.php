<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Expenses\Pages\EditExpense;
use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Filament\Resources\Expenses\Schemas\ExpenseForm;
use App\Filament\Resources\Expenses\Tables\ExpensesTable;
use App\Models\Expense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Expense';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Log Shop Expense')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('item_name')
                            ->label('Expense Description')
                            ->placeholder('e.g., 50kg Premium Detergent')
                            ->required(),
                            
                        \Filament\Forms\Components\Select::make('category')
                            ->options([
                                'Chemicals' => 'Chemicals & Detergent',
                                'Fuel' => 'Fleet Fuel',
                                'Utility' => 'Electricity & Water',
                                'Maintenance' => 'Machine Maintenance',
                                'Other' => 'Other',
                            ])
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        \Filament\Forms\Components\DatePicker::make('date')
                            ->default(now())
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('date')->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('item_name')->searchable()->weight('bold'),
                \Filament\Tables\Columns\BadgeColumn::make('category')
                    ->colors(['secondary']),
                \Filament\Tables\Columns\TextColumn::make('amount')->money('IDR', true)->sortable(),
            ])
            ->defaultSort('date', 'desc')
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
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }
}
