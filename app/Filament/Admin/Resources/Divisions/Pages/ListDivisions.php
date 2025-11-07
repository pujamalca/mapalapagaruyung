<?php

namespace App\Filament\Admin\Resources\Divisions\Pages;

use App\Filament\Admin\Resources\Divisions\DivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDivisions extends ListRecords
{
    protected static string $resource = DivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Divisi'),
        ];
    }
}
