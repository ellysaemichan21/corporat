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
        $employeeOption = fn ($record) => '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px; padding:6px 0; min-width:0;"><img src="' . ($record->photo ? asset("storage/{$record->photo}") : "https://ui-avatars.com/api/?name=".urlencode($record->name)."&color=FFFFFF&background=18181b") . '" style="width:36px; height:36px; flex-shrink:0; border-radius:50%; object-fit:cover; border:1px solid #3f3f46; box-shadow:0 4px 6px -1px rgba(0, 0, 0, 0.1);" /><span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; width:100%; text-align:center; font-size:12px; font-weight:500;">' . e($record->name) . '</span></div>';

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
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $record) {
                                if ($record) {
                                    $record->is_corporate = $state;
                                    $record->syncTotal();
                                    $set('subtotal', $record->subtotal);
                                    $set('asap_surcharge', $record->asap_surcharge);
                                    $set('delivery_fee', $record->delivery_fee);
                                    $set('promo_discount', $record->promo_discount);
                                    $set('total_price', $record->total_price);
                                }
                            }),

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
                            ->offIcon('heroicon-m-clock')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $record) {
                                if ($record) {
                                    $record->is_priority = $state;
                                    $record->syncTotal();
                                    $set('subtotal', $record->subtotal);
                                    $set('asap_surcharge', $record->asap_surcharge);
                                    $set('delivery_fee', $record->delivery_fee);
                                    $set('promo_discount', $record->promo_discount);
                                    $set('total_price', $record->total_price);
                                }
                            }),

                    ])->columns(3),

                \Filament\Schemas\Components\Section::make('Chain of Custody (Employee Tracking)')
                    ->description('Personnel involved in this garment journey (Auto-Assigned).')
                    ->schema([
                        \Filament\Forms\Components\Select::make('driver_id')
                            ->relationship('driver', 'name')
                            ->label('Pickup Driver (Inbound)')
                            ->placeholder('No Pickup Needed')
                            ->getOptionLabelFromRecordUsing($employeeOption)
                            ->allowHtml()
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('delivery_driver_id')
                            ->relationship('delivery_driver', 'name')
                            ->label('Delivery Driver (Outbound)')
                            ->placeholder('No Delivery Needed')
                            ->getOptionLabelFromRecordUsing($employeeOption)
                            ->allowHtml()
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('sorter_id')
                            ->relationship('sorter', 'name')
                            ->label('Intake Sorter')
                            ->placeholder('Assigned on Intake')
                            ->getOptionLabelFromRecordUsing($employeeOption)
                            ->allowHtml()
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('washer_id')
                            ->relationship('washer', 'name')
                            ->label('Primary Washer')
                            ->placeholder('Assigned on Purification')
                            ->getOptionLabelFromRecordUsing($employeeOption)
                            ->allowHtml()
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('presser_id')
                            ->relationship('presser', 'name')
                            ->label('Artisanal Presser')
                            ->placeholder('Assigned on Ironing')
                            ->getOptionLabelFromRecordUsing($employeeOption)
                            ->allowHtml()
                            ->disabled(),

                        \Filament\Forms\Components\Select::make('packer_id')
                            ->relationship('packer', 'name')
                            ->label('Quality Packer')
                            ->placeholder('Assigned on QC')
                            ->getOptionLabelFromRecordUsing($employeeOption)
                            ->allowHtml()
                            ->disabled(),

                    ])->columns(3),

                \Filament\Schemas\Components\Section::make('Financial Breakdown')
                    ->description('Honest and visible pricing details.')
                    ->schema([
                        \Filament\Forms\Components\Select::make('promo_id')
                            ->relationship('promo', 'code')
                            ->label('Apply Promo Code')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $record) {
                                if ($record) {
                                    $record->promo_id = $state;
                                    $record->syncTotal();
                                    $set('subtotal', $record->subtotal);
                                    $set('asap_surcharge', $record->asap_surcharge);
                                    $set('delivery_fee', $record->delivery_fee);
                                    $set('promo_discount', $record->promo_discount);
                                    $set('total_price', $record->total_price);
                                }
                            }),
                            
                        \Filament\Forms\Components\TextInput::make('subtotal')
                            ->label('Garment Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                            
                        \Filament\Forms\Components\TextInput::make('asap_surcharge')
                            ->label('ASAP Surcharge')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                            
                        \Filament\Forms\Components\TextInput::make('delivery_fee')
                            ->label('Delivery Cost')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                            
                        \Filament\Forms\Components\TextInput::make('promo_discount')
                            ->label('Promo Cut')
                            ->numeric()
                            ->prefix('-Rp')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                            
                        \Filament\Forms\Components\TextInput::make('total_price')
                            ->label('Grand Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->extraInputAttributes(['style' => 'font-weight: bold; font-size: 1.1em; color: var(--primary-600);']),
                    ])->columns([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 3,
                    ]),
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
