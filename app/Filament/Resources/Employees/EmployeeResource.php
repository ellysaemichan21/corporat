<?php

namespace App\Filament\Resources\Employees;

use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Employee Data')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('photo')
                            ->label('Photo')
                            ->image()
                            ->directory('employees')
                            ->disk('public')
                            ->visibility('public')
                            // ->imageEditor()
                            // ->circleCropper()
                            ->nullable()
                            ->columnSpanFull(),

                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        \Filament\Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->nullable(),

                        \Filament\Forms\Components\Select::make('role')
                            ->label('Job Role')
                            ->options([
                                'manager' => 'Manager',
                                'admin'   => 'Administrator',
                                'washer'  => 'Washer',
                                'presser' => 'Presser / Ironing',
                                'packer'  => 'Outbound Packer',
                                'sorter'  => 'QC Sorter',
                                'driver'  => 'Delivery Driver',
                            ])
                            ->required(),

                        \Filament\Forms\Components\DatePicker::make('join_date')
                            ->label('Join Date')
                            ->default(now())
                            ->nullable(),

                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active'   => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=6366f1&color=fff')
                    ->size(48),

                \Filament\Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                \Filament\Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger'  => 'admin',
                        'info'    => 'manager',
                        'warning' => 'driver',
                        'primary' => 'sorter',
                        'success' => fn ($state) => in_array($state, ['washer', 'presser', 'packer']),
                    ]),

                \Filament\Tables\Columns\TextColumn::make('join_date')
                    ->label('Join Date')
                    ->date()
                    ->sortable(),

                \Filament\Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ]),
            ])
            ->defaultSort('name')
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
            'index'  => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit'   => EditEmployee::route('/{record}/edit'),
        ];
    }
}
