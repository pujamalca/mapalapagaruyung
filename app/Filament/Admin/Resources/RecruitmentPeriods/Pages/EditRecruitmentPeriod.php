<?php

namespace App\Filament\Admin\Resources\RecruitmentPeriods\Pages;

use App\Filament\Admin\Resources\RecruitmentPeriods\RecruitmentPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecruitmentPeriod extends EditRecord
{
    protected static string $resource = RecruitmentPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Periode Recruitment')
                ->modalDescription('Yakin ingin menghapus periode ini? Semua data pendaftar juga akan terhapus!'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Periode recruitment berhasil diperbarui';
    }
}
