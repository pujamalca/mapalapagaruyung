<?php

namespace App\Filament\Admin\Resources\EquipmentBorrowings\Pages;

use App\Filament\Admin\Resources\EquipmentBorrowings\EquipmentBorrowingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipmentBorrowing extends CreateRecord
{
    protected static string $resource = EquipmentBorrowingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default status
        $data['status'] = 'pending';

        // Generate borrowing code if not set
        if (!isset($data['borrowing_code'])) {
            $data['borrowing_code'] = \App\Models\EquipmentBorrowing::generateBorrowingCode();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Peminjaman berhasil dibuat';
    }
}
