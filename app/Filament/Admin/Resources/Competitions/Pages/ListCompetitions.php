<?php

namespace App\Filament\Admin\Resources\Competitions\Pages;

use App\Filament\Admin\Resources\Competitions\CompetitionResource;
use App\Filament\Exports\CompetitionExporter;
use App\Filament\Imports\CompetitionImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCompetitions extends ListRecords
{
    protected static string $resource = CompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kompetisi/Event')
                ->icon('heroicon-o-plus'),
            Actions\ImportAction::make()
                ->importer(CompetitionImporter::class)
                ->label('Import Kompetisi')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\ExportAction::make()
                ->exporter(CompetitionExporter::class)
                ->label('Export Kompetisi')
                ->color('info')
                ->icon('heroicon-o-arrow-down-tray'),
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

            'registration_open' => Tab::make('Pendaftaran')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'registration_open'))
                ->badge(fn () => $this->getModel()::where('status', 'registration_open')->count())
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
        ];
    }
}
