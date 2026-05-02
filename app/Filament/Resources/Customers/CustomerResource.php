<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Customer';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Client Profile')
                    ->schema([
                        \Filament\Forms\Components\Select::make('partner_id')
                            ->relationship('partner', 'name')
                            ->label('Apartment Building (Optional)')
                            ->searchable()
                            ->preload(),
                        \Filament\Forms\Components\Select::make('tier_preference')
                            ->options([
                                'Essential' => 'Essential Care',
                                'Signature' => 'Signature Care',
                                'Bespoke' => 'Bespoke Structural Care',
                            ])
                            ->default('Essential')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('name')->required(),
                        \Filament\Forms\Components\TextInput::make('phone')->required()->tel(),
                        \Filament\Forms\Components\Textarea::make('address')->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('phone')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('partner.name')->label('Apartment')->sortable(),
                \Filament\Tables\Columns\BadgeColumn::make('tier_preference')
                    ->colors([
                        'primary' => 'Essential',
                        'warning' => 'Signature',
                        'success' => 'Bespoke',
                    ]),
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
