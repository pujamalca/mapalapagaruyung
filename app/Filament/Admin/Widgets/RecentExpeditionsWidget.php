<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Expeditions\ExpeditionResource;
use App\Models\Expedition;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\ViewAction;

class RecentExpeditionsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Ekspedisi Terbaru')
            ->query(
                Expedition::query()
                    ->whereIn('status', ['ongoing', 'preparing', 'completed'])
                    ->latest('start_date')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('expedition_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 40) {
                            return $state;
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('destination')
                    ->label('Tujuan')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'ongoing' => 'warning',
                        'preparation' => 'info',
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
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Peserta')
                    ->counts('participants')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('coordinator.name')
                    ->label('Koordinator')
                    ->limit(20)
                    ->default('-'),
            ])
            ->actions([
                ViewAction::make('view')
                    ->url(fn (Expedition $record): string => ExpeditionResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-eye'),
            ])
            ->paginated(false);
    }
}
