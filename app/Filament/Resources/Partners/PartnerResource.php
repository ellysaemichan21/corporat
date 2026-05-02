<?php

namespace App\Filament\Resources\Partners;

use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\Partners\Pages\EditPartner;
use App\Filament\Resources\Partners\Pages\ListPartners;
use App\Filament\Resources\Partners\Schemas\PartnerForm;
use App\Filament\Resources\Partners\Tables\PartnersTable;
use App\Models\Partner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Partner';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Corporate Partner Details')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Building/Company Name')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('contact_person')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\DatePicker::make('contract_start_date'),
                        \Filament\Forms\Components\Textarea::make('address')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('contact_person')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('contract_start_date')->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('customers_count')->counts('customers')->label('Residents Registered'),
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
            'index' => ListPartners::route('/'),
            'create' => CreatePartner::route('/create'),
            'edit' => EditPartner::route('/{record}/edit'),
        ];
    }
}
