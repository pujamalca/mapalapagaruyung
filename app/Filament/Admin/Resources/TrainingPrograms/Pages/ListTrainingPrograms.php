<?php

namespace App\Filament\Admin\Resources\TrainingPrograms\Pages;

use App\Filament\Admin\Resources\TrainingPrograms\TrainingProgramResource;
use App\Filament\Exports\TrainingProgramExporter;
use App\Filament\Imports\TrainingProgramImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTrainingPrograms extends ListRecords
{
    protected static string $resource = TrainingProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Program Pelatihan')
                ->icon('heroicon-o-plus'),
            Actions\ImportAction::make()
                ->importer(TrainingProgramImporter::class)
                ->label('Import Program Pelatihan')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\ExportAction::make()
                ->exporter(TrainingProgramExporter::class)
                ->label('Export Program Pelatihan')
                ->color('info')
                ->icon('heroicon-o-arrow-down-tray'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(fn () => $this->getModel()::count()),

            'draft' => Tab::make('Draft')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft'))
                ->badge(fn () => $this->getModel()::where('status', 'draft')->count())
                ->badgeColor('gray'),

            'scheduled' => Tab::make('Terjadwal')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'scheduled'))
                ->badge(fn () => $this->getModel()::where('status', 'scheduled')->count())
                ->badgeColor('info'),

            'ongoing' => Tab::make('Berlangsung')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'ongoing'))
                ->badge(fn () => $this->getModel()::where('status', 'ongoing')->count())
                ->badgeColor('warning'),

            'completed' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed'))
                ->badge(fn () => $this->getModel()::where('status', 'completed')->count())
                ->badgeColor('success'),

            'mandatory' => Tab::make('Wajib')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_mandatory', true))
                ->badge(fn () => $this->getModel()::where('is_mandatory', true)->count())
                ->badgeColor('danger'),
        ];
    }
}
