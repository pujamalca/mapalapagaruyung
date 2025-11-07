<?php

namespace App\Filament\Admin\Resources\EquipmentCategories\Pages;

use App\Filament\Admin\Resources\EquipmentCategories\EquipmentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentCategory extends EditRecord
{
    protected static string $resource = EquipmentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
