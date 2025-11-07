<?php

namespace App\Filament\Admin\Resources\Expeditions\Pages;

use App\Filament\Admin\Resources\Expeditions\ExpeditionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpedition extends EditRecord
{
    protected static string $resource = ExpeditionResource::class;

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

            Actions\Action::make('view_gallery')
                ->label('Lihat Galeri')
                ->icon('heroicon-o-photo')
                ->color('info')
                ->visible(fn () => $this->getRecord()->getMedia('photos')->count() > 0)
                ->url(fn () => static::getResource()::getUrl('edit', [
                    'record' => $this->getRecord(),
                ]) . '#media'),

            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Ekspedisi')
                ->modalDescription('Yakin ingin menghapus ekspedisi ini? Data peserta juga akan terhapus.')
                ->visible(fn () => $this->getRecord()->status === 'planned'),
        ];
    }

    protected function afterSave(): void
    {
        // Recalculate duration if dates changed
        if ($this->getRecord()->isDirty(['start_date', 'end_date'])) {
            $this->getRecord()->calculateDuration();
        }

        // Log activity
        activity('expedition')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Ekspedisi diperbarui: ' . $this->getRecord()->title);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
