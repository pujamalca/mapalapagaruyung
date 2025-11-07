<?php

namespace App\Filament\Admin\Resources\EquipmentBorrowings\Pages;

use App\Filament\Admin\Resources\EquipmentBorrowings\EquipmentBorrowingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentBorrowing extends EditRecord
{
    protected static string $resource = EquipmentBorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn ($record) => in_array($record->status, ['pending', 'cancelled'])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Peminjaman berhasil diupdate';
    }
}
