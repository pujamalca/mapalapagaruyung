<?php

namespace App\Filament\Admin\Resources\Galleries\Pages;

use App\Filament\Admin\Resources\Galleries\GalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGallery extends EditRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->getRecord()->updateMediaCount();

        activity('gallery')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Galeri diperbarui: ' . $this->getRecord()->title);
    }
}
