<?php

namespace App\Filament\Exports;

use App\Models\Expedition;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ExpeditionExporter extends Exporter
{
    protected static ?string $model = Expedition::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('expedition_code')
                ->label('Kode Ekspedisi'),

            ExportColumn::make('title')
                ->label('Judul'),

            ExportColumn::make('destination')
                ->label('Tujuan'),

            ExportColumn::make('type')
                ->label('Tipe')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'climbing' => 'Pendakian',
                    'hiking' => 'Hiking',
                    'camping' => 'Camping',
                    'expedition' => 'Ekspedisi',
                    'exploration' => 'Eksplorasi',
                    default => $state,
                }),

            ExportColumn::make('difficulty')
                ->label('Tingkat Kesulitan')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'easy' => 'Mudah',
                    'moderate' => 'Sedang',
                    'hard' => 'Sulit',
                    'extreme' => 'Ekstrem',
                    default => $state,
                }),

            ExportColumn::make('start_date')
                ->label('Tanggal Mulai')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('end_date')
                ->label('Tanggal Selesai')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('duration_days')
                ->label('Durasi (Hari)'),

            ExportColumn::make('max_participants')
                ->label('Peserta Maksimal'),

            ExportColumn::make('participants_count')
                ->label('Jumlah Peserta')
                ->formatStateUsing(fn ($record) => $record->participants()->count()),

            ExportColumn::make('coordinator.name')
                ->label('Koordinator'),

            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'planning' => 'Perencanaan',
                    'open_registration' => 'Pendaftaran Dibuka',
                    'preparation' => 'Persiapan',
                    'ongoing' => 'Berlangsung',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $state,
                }),

            ExportColumn::make('estimated_budget')
                ->label('Estimasi Budget')
                ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),

            ExportColumn::make('actual_budget')
                ->label('Budget Aktual')
                ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),

            ExportColumn::make('base_camp_location')
                ->label('Lokasi Base Camp'),

            ExportColumn::make('altitude')
                ->label('Ketinggian (mdpl)'),

            ExportColumn::make('is_official')
                ->label('Ekspedisi Resmi')
                ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),

            ExportColumn::make('description')
                ->label('Deskripsi'),

            ExportColumn::make('objectives')
                ->label('Tujuan'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '-'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data ekspedisi selesai dengan ' . number_format($export->successful_rows) . ' baris berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }

        return $body;
    }
}
