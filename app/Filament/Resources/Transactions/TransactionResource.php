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

                        // Corporate Status
                        \Filament\Forms\Components\Toggle::make('is_corporate')
                            ->label('Corporate / B2B Order')
                            ->inline(false)
                            ->live(),

                        \Filament\Forms\Components\Select::make('partner_id')
                            ->relationship('partner', 'name')
                            ->label('B2B Partner')
                            ->placeholder('Select if Corporate')
                            ->searchable()
                            ->preload()
                            ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('is_corporate')),

                        // The Luxury Tier Selection
                        \Filament\Forms\Components\Select::make('tier_level')
                            ->options([
                                'Essential' => 'Essential Care',
                                'Signature' => 'Signature Care',
                                'Bespoke' => 'Bespoke Structural Care',
                            ])
                            ->default('Essential')
                            ->required(),

                        // The Total Price (Managed by System)
                        \Filament\Forms\Components\TextInput::make('total_price')
                            ->label('Total Investment')
                            ->helperText('This is automatically calculated based on weights and surcharges.')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                    ])->columns(3), // Increased columns to fit new fields nicely

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
                            ->default('Paid')
                            ->required(),

                        \Filament\Forms\Components\Toggle::make('is_priority')
                            ->label('Priority Service (ASAP)')
                            ->inline(false)
                            ->onIcon('heroicon-m-bolt')
                            ->offIcon('heroicon-m-clock'),

                        \Filament\Forms\Components\Toggle::make('is_fast_track')
                            ->label('Fast Track Surcharge (30%)')
                            ->inline(false)
                            ->onIcon('heroicon-m-sparkles')
                            ->offIcon('heroicon-m-no-symbol'),

                    ])->columns(4),

                \Filament\Schemas\Components\Section::make('Chain of Custody (Employee Tracking)')
                    ->description('Personnel involved in this garment journey (Auto-Assigned).')
                    ->schema([
                        \Filament\Forms\Components\Select::make('driver_id')
                            ->relationship('driver', 'name')
                            ->label('Outbound Collector')
                            ->placeholder('Assigned on Dispatch')
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('delivery_driver_id')
                            ->relationship('delivery_driver', 'name')
                            ->label('Inbound Courier')
                            ->placeholder('Assigned on Delivery')
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('sorter_id')
                            ->relationship('sorter', 'name')
                            ->label('Intake Sorter')
                            ->placeholder('Assigned on Intake')
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('washer_id')
                            ->relationship('washer', 'name')
                            ->label('Primary Washer')
                            ->placeholder('Assigned on Purification')
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('presser_id')
                            ->relationship('presser', 'name')
                            ->label('Artisanal Presser')
                            ->placeholder('Assigned on Ironing')
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('packer_id')
                            ->relationship('packer', 'name')
                            ->label('Quality Packer')
                            ->placeholder('Assigned on QC')
                            ->disabled(),

                    ])->columns(6),
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
