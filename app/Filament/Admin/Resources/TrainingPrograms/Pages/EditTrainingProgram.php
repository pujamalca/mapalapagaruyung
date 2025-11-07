<?php

namespace App\Filament\Admin\Resources\TrainingPrograms\Pages;

use App\Filament\Admin\Resources\TrainingPrograms\TrainingProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingProgram extends EditRecord
{
    protected static string $resource = TrainingProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_participants')
                ->label('Lihat Peserta')
                ->icon('heroicon-o-users')
                ->color('info')
                ->url(fn () => static::getResource()::getUrl('edit', [
                    'record' => $this->getRecord(),
                ]) . '#participants'),

            Actions\Action::make('view_sessions')
                ->label('Lihat Sesi')
                ->icon('heroicon-o-calendar-days')
                ->color('info')
                ->url(fn () => static::getResource()::getUrl('edit', [
                    'record' => $this->getRecord(),
                ]) . '#sessions'),

            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Program Pelatihan')
                ->modalDescription('Yakin ingin menghapus program ini? Data sesi dan peserta juga akan terhapus.')
                ->visible(fn () => $this->getRecord()->status === 'draft'),
        ];
    }

    protected function afterSave(): void
    {
        // Log activity
        activity('training')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Program pelatihan diperbarui: ' . $this->getRecord()->name);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
