<?php

namespace App\Filament\Resources\ServiceTiers\Pages;

use App\Filament\Resources\ServiceTiers\ServiceTierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceTiers extends ListRecords
{
    protected static string $resource = ServiceTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
