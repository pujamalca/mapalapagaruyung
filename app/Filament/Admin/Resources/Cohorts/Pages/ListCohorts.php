<?php

namespace App\Filament\Admin\Resources\Cohorts\Pages;

use App\Filament\Admin\Resources\Cohorts\CohortResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCohorts extends ListRecords
{
    protected static string $resource = CohortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Angkatan'),
        ];
    }
}
