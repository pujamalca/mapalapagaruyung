<?php

namespace App\Filament\Admin\Resources\Competitions\Pages;

use App\Filament\Admin\Resources\Competitions\CompetitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompetition extends CreateRecord
{
    protected static string $resource = CompetitionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function afterCreate(): void
    {
        $this->getRecord()->calculateDuration();

        activity('competition')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Kompetisi/Event dibuat: ' . $this->getRecord()->title);
    }
}
