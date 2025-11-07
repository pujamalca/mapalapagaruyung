<?php

namespace App\Filament\Admin\Resources\Equipment\Pages;

use App\Filament\Admin\Resources\Equipment\EquipmentResource;
use App\Filament\Exports\EquipmentExporter;
use App\Filament\Imports\EquipmentImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(EquipmentImporter::class)
                ->label('Import Peralatan')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\ExportAction::make()
                ->exporter(EquipmentExporter::class)
                ->label('Export Peralatan')
                ->color('info')
                ->icon('heroicon-o-arrow-down-tray'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Peralatan'),

            'tersedia' => Tab::make('Tersedia')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'available'))
                ->badge(fn () => \App\Models\Equipment::where('status', 'available')->count()),

            'dipinjam' => Tab::make('Dipinjam')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'borrowed'))
                ->badge(fn () => \App\Models\Equipment::where('status', 'borrowed')->count())
                ->badgeColor('warning'),

            'maintenance' => Tab::make('Maintenance')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'maintenance'))
                ->badge(fn () => \App\Models\Equipment::where('status', 'maintenance')->count())
                ->badgeColor('info'),

            'perlu_maintenance' => Tab::make('Perlu Maintenance')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereNotNull('next_maintenance_date')
                    ->where(function ($q) {
                        $q->where('next_maintenance_date', '<=', now())
                          ->orWhere('next_maintenance_date', '<=', now()->addDays(30));
                    })
                )
                ->badge(fn () => \App\Models\Equipment::whereNotNull('next_maintenance_date')
                    ->where(function ($q) {
                        $q->where('next_maintenance_date', '<=', now())
                          ->orWhere('next_maintenance_date', '<=', now()->addDays(30));
                    })->count()
                )
                ->badgeColor('danger'),

            'stok_rendah' => Tab::make('Stok Rendah')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereColumn('quantity_available', '<', 'quantity')
                    ->whereRaw('quantity_available < quantity / 2')
                )
                ->badge(fn () => \App\Models\Equipment::whereColumn('quantity_available', '<', 'quantity')
                    ->whereRaw('quantity_available < quantity / 2')
                    ->count()
                )
                ->badgeColor('warning'),
        ];
    }
}
