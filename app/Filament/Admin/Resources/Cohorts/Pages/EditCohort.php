<?php

namespace App\Filament\Admin\Resources\Cohorts\Pages;

use App\Filament\Admin\Resources\Cohorts\CohortResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCohort extends EditRecord
{
    protected static string $resource = CohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Angkatan berhasil diperbarui!';
    }
}
