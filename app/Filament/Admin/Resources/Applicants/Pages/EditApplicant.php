<?php

namespace App\Filament\Admin\Resources\Applicants\Pages;

use App\Filament\Admin\Resources\Applicants\ApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplicant extends EditRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verify')
                ->label('Verifikasi')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status === 'registered')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->markAsVerified();
                    $this->refreshFormData(['status', 'verified_at']);
                })
                ->successNotificationTitle('Pendaftar berhasil diverifikasi'),

            Actions\Action::make('accept')
                ->label('Terima Sebagai Anggota')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status === 'passed')
                ->requiresConfirmation()
                ->modalHeading('Terima Pendaftar')
                ->modalDescription('Yakin menerima pendaftar ini sebagai anggota?')
                ->action(function () {
                    $this->record->markAsAccepted();
                    $this->refreshFormData(['status', 'accepted_at']);
                })
                ->successNotificationTitle('Pendaftar berhasil diterima'),

            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data pendaftar berhasil diperbarui';
    }
}
