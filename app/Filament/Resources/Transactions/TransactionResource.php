<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Resources\Transactions\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\Transactions\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\Transactions\RelationManagers\LogsRelationManager;
use App\Filament\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Models\Transaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Transaction';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('The Garment Manifest')
                    ->description('Log a new high-class transaction.')
                    ->schema([
                        
                        // Auto-generate a sleek Invoice Code
                        \Filament\Forms\Components\TextInput::make('invoice_code')
                            ->default('INV-' . strtoupper(Str::random(6)))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),

                        // The Client Selection (With a cheat code to create a client on the fly)
                        \Filament\Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                \Filament\Forms\Components\TextInput::make('name')->required(),
                                \Filament\Forms\Components\TextInput::make('phone')->required(),
                            ])
                            ->required(),

                        // The Luxury Tier Selection
                        \Filament\Forms\Components\Select::make('tier_level')
                            ->options([
                                'Essential' => 'Essential Care',
                                'Signature' => 'Signature Care',
                                'Bespoke' => 'Bespoke Structural Care',
                            ])
                            ->default('Essential')
                            ->required(),

                        // The Total Price
                        \Filament\Forms\Components\TextInput::make('total_price')
                            ->numeric()
                            ->default(0.00)
                            ->prefix('Rp')
                            ->required(),

                    ])->columns(2), // Makes it a nice 2-column grid

                \Filament\Schemas\Components\Section::make('Live Status & Logistics')
                    ->schema([
                        
                        // The State Machine
                        \Filament\Forms\Components\Select::make('laundry_status')
                            ->options([
                                'Pending' => 'Pending Pickup',
                                'Sorting & QC' => 'Sorting & QC',
                                'Washing' => 'Washing',
                                'Drying' => 'Drying',
                                'Ironing' => 'Ironing',
                                'Ready for Dispatch' => 'Ready for Dispatch',
                                'Completed' => 'Completed',
                            ])
                            ->default('Pending')
                            ->required(),

                        \Filament\Forms\Components\Select::make('payment_status')
                            ->options([
                                'Unpaid' => 'Unpaid',
                                'Paid' => 'Paid',
                            ])
                            ->default('Unpaid')
                            ->required(),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
            LogsRelationManager::class,
            ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'edit' => EditTransaction::route('/{record}/edit'),
        ];
    }
}
