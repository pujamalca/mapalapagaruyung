<?php

namespace App\Filament\Admin\Resources\TrainingPrograms\Pages;

use App\Filament\Admin\Resources\TrainingPrograms\TrainingProgramResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainingProgram extends CreateRecord
{
    protected static string $resource = TrainingProgramResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure default values
        $data['status'] = $data['status'] ?? 'draft';
        $data['registration_status'] = $data['registration_status'] ?? 'closed';
        $data['has_evaluation'] = $data['has_evaluation'] ?? true;
        $data['passing_score'] = $data['passing_score'] ?? 70;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Log activity
        activity('training')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Program pelatihan dibuat: ' . $this->getRecord()->name);
    }
}
