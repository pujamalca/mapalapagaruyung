<?php

namespace App\Filament\Admin\Resources\RecruitmentPeriods\Pages;

use App\Filament\Admin\Resources\RecruitmentPeriods\RecruitmentPeriodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecruitmentPeriod extends CreateRecord
{
    protected static string $resource = RecruitmentPeriodResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Periode recruitment berhasil dibuat';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate registration number prefix based on year
        if (isset($data['registration_start'])) {
            $year = date('Y', strtotime($data['registration_start']));
            $data['metadata'] = array_merge($data['metadata'] ?? [], [
                'registration_prefix' => 'MAP-' . $year . '-',
            ]);
        }

        return $data;
    }
}
