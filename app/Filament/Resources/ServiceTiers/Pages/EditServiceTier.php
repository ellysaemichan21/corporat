<?php

namespace App\Filament\Resources\ServiceTiers\Pages;

use App\Filament\Resources\ServiceTiers\ServiceTierResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceTier extends EditRecord
{
    protected static string $resource = ServiceTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
