<?php

namespace App\Filament\Admin\Resources\Divisions\Pages;

use App\Filament\Admin\Resources\Divisions\DivisionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDivision extends CreateRecord
{
    protected static string $resource = DivisionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Divisi berhasil dibuat!';
    }
}
