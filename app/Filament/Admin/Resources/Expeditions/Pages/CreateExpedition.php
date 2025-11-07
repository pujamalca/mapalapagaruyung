<?php

namespace App\Filament\Admin\Resources\Expeditions\Pages;

use App\Filament\Admin\Resources\Expeditions\ExpeditionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpedition extends CreateRecord
{
    protected static string $resource = ExpeditionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure default values
        $data['status'] = $data['status'] ?? 'planned';
        $data['registration_status'] = $data['registration_status'] ?? 'open';
        $data['is_official'] = $data['is_official'] ?? true;
        $data['requires_approval'] = $data['requires_approval'] ?? true;
        $data['difficulty_level'] = $data['difficulty_level'] ?? 'moderate';
        $data['min_participants'] = $data['min_participants'] ?? 4;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Calculate duration
        $this->getRecord()->calculateDuration();

        // Log activity
        activity('expedition')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Ekspedisi dibuat: ' . $this->getRecord()->title);
    }
}
