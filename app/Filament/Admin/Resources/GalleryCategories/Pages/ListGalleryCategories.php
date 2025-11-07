<?php

namespace App\Filament\Admin\Resources\GalleryCategories\Pages;

use App\Filament\Admin\Resources\GalleryCategories\GalleryCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGalleryCategories extends ListRecords
{
    protected static string $resource = GalleryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
