<?php

namespace App\Filament\Admin\Resources\Equipment\Pages;

use App\Filament\Admin\Resources\Equipment\EquipmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipment extends CreateRecord
{
    protected static string $resource = EquipmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure quantity_available doesn't exceed quantity
        if (isset($data['quantity_available']) && isset($data['quantity'])) {
            $data['quantity_available'] = min($data['quantity_available'], $data['quantity']);
        }

        // Set default quantity_available if not set
        if (!isset($data['quantity_available']) && isset($data['quantity'])) {
            $data['quantity_available'] = $data['quantity'];
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
