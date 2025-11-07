<?php

namespace App\Filament\Admin\Resources\Expeditions\Pages;

use App\Filament\Admin\Resources\Expeditions\ExpeditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListExpeditions extends ListRecords
{
    protected static string $resource = ExpeditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Ekspedisi')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(fn () => $this->getModel()::count()),

            'planned' => Tab::make('Direncanakan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'planned'))
                ->badge(fn () => $this->getModel()::where('status', 'planned')->count())
                ->badgeColor('gray'),

            'preparing' => Tab::make('Persiapan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'preparing'))
                ->badge(fn () => $this->getModel()::where('status', 'preparing')->count())
                ->badgeColor('info'),

            'ongoing' => Tab::make('Berlangsung')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'ongoing'))
                ->badge(fn () => $this->getModel()::where('status', 'ongoing')->count())
                ->badgeColor('warning'),

            'completed' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed'))
                ->badge(fn () => $this->getModel()::where('status', 'completed')->count())
                ->badgeColor('success'),

            'upcoming' => Tab::make('Akan Datang')
                ->modifyQueryUsing(fn (Builder $query) => $query->upcoming())
                ->badge(fn () => $this->getModel()::upcoming()->count())
                ->badgeColor('info'),

            'official' => Tab::make('Resmi')
                ->modifyQueryUsing(fn (Builder $query) => $query->official())
                ->badge(fn () => $this->getModel()::official()->count())
                ->badgeColor('primary'),
        ];
    }
}
