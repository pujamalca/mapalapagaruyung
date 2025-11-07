<?php

namespace App\Filament\Admin\Resources\Cohorts\Pages;

use App\Filament\Admin\Resources\Cohorts\CohortResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCohort extends CreateRecord
{
    protected static string $resource = CohortResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Angkatan berhasil dibuat!';
    }
}
