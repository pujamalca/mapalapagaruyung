<?php

namespace App\Filament\Admin\Resources\Galleries\Pages;

use App\Filament\Admin\Resources\Galleries\GalleryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function afterCreate(): void
    {
        $this->getRecord()->updateMediaCount();

        activity('gallery')
            ->performedOn($this->getRecord())
            ->causedBy(auth()->user())
            ->log('Galeri dibuat: ' . $this->getRecord()->title);
    }
}
