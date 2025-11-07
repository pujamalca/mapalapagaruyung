<?php

namespace App\Filament\Admin\Resources\Competitions\Pages;

use App\Filament\Admin\Resources\Competitions\CompetitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompetition extends EditRecord
{
    protected static string $resource = CompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => $this->getRecord()->status === 'planned'),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->getRecord()->isDirty(['start_date', 'end_date'])) {
            $this->getRecord()->calculateDuration();
        }

        activity('competition')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Kompetisi/Event diperbarui: ' . $this->getRecord()->title);
    }
}
