<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Competition;
use App\Models\Expedition;
use App\Models\TrainingProgram;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Aktivitas Terbaru')
            ->query(
                fn () => $this->getActivitiesQuery()
            )
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ekspedisi' => 'success',
                        'Pelatihan' => 'info',
                        'Kompetisi' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'ongoing' => 'warning',
                        'preparation', 'open_registration' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'planning' => 'Perencanaan',
                        'open_registration' => 'Pendaftaran',
                        'preparation' => 'Persiapan',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'upcoming' => 'Akan Datang',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Peserta')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),
            ])
            ->defaultSort('start_date', 'desc')
            ->paginated([10]);
    }

    protected function getActivitiesQuery(): Builder
    {
        // Combine expeditions, training, and competitions into one query
        // We'll use a union approach by getting collections and merging

        $expeditions = Expedition::select([
            'id',
            'expedition_code as code',
            'title',
            'status',
            'start_date',
            'end_date',
            DB::raw("'Ekspedisi' as type"),
        ])
            ->with('participants')
            ->whereIn('status', ['ongoing', 'preparation', 'completed'])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get()
            ->map(function ($expedition) {
                $expedition->participants_count = $expedition->participants->count();
                return $expedition;
            });

        $training = TrainingProgram::select([
            'id',
            'training_code as code',
            'title',
            'status',
            'start_date',
            'end_date',
            DB::raw("'Pelatihan' as type"),
        ])
            ->with('participants')
            ->whereIn('status', ['ongoing', 'open_registration', 'completed'])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get()
            ->map(function ($training) {
                $training->participants_count = $training->participants->count();
                return $training;
            });

        $competitions = Competition::select([
            'id',
            'competition_code as code',
            'title',
            'status',
            'start_date',
            'end_date',
            DB::raw("'Kompetisi' as type"),
        ])
            ->with('participants')
            ->whereIn('status', ['upcoming', 'ongoing', 'completed'])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get()
            ->map(function ($competition) {
                $competition->participants_count = $competition->participants->count();
                return $competition;
            });

        // Merge and sort
        $activities = $expeditions
            ->merge($training)
            ->merge($competitions)
            ->sortByDesc('start_date');

        // Convert to a query builder for Filament table
        // Since we can't directly return a collection, we'll use one of the models as base
        // and filter in memory (not ideal for large datasets, but works for recent activities)

        return Expedition::query()->whereRaw('1 = 0'); // Dummy query, we'll override in getTableQuery
    }

    public function getTableQuery(): Builder
    {
        return $this->getActivitiesQuery();
    }

    public function getTableRecords(): Collection
    {
        $expeditions = Expedition::select([
            'id',
            'expedition_code as code',
            'title',
            'status',
            'start_date',
            'end_date',
        ])
            ->whereIn('status', ['ongoing', 'preparation', 'completed'])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get()
            ->map(function ($expedition) {
                return (object) [
                    'type' => 'Ekspedisi',
                    'title' => $expedition->title,
                    'status' => $expedition->status,
                    'start_date' => $expedition->start_date,
                    'participants_count' => $expedition->participants()->count(),
                ];
            });

        $training = TrainingProgram::select([
            'id',
            'training_code as code',
            'title',
            'status',
            'start_date',
            'end_date',
        ])
            ->whereIn('status', ['ongoing', 'open_registration', 'completed'])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get()
            ->map(function ($training) {
                return (object) [
                    'type' => 'Pelatihan',
                    'title' => $training->title,
                    'status' => $training->status,
                    'start_date' => $training->start_date,
                    'participants_count' => $training->participants()->count(),
                ];
            });

        $competitions = Competition::select([
            'id',
            'competition_code as code',
            'title',
            'status',
            'start_date',
            'end_date',
        ])
            ->whereIn('status', ['upcoming', 'ongoing', 'completed'])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get()
            ->map(function ($competition) {
                return (object) [
                    'type' => 'Kompetisi',
                    'title' => $competition->title,
                    'status' => $competition->status,
                    'start_date' => $competition->start_date,
                    'participants_count' => $competition->participants()->count(),
                ];
            });

        return $expeditions
            ->merge($training)
            ->merge($competitions)
            ->sortByDesc('start_date')
            ->take(10);
    }
}
