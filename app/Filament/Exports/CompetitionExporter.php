<?php

namespace App\Filament\Exports;

use App\Models\Competition;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CompetitionExporter extends Exporter
{
    protected static ?string $model = Competition::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('competition_code')
                ->label('Kode Kompetisi'),

            ExportColumn::make('title')
                ->label('Judul'),

            ExportColumn::make('type')
                ->label('Tipe')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'climbing' => 'Panjat Tebing',
                    'hiking' => 'Hiking',
                    'navigation' => 'Navigasi',
                    'survival' => 'Survival',
                    'photography' => 'Fotografi',
                    'other' => 'Lainnya',
                    default => $state,
                }),

            ExportColumn::make('level')
                ->label('Tingkat')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'local' => 'Lokal',
                    'regional' => 'Regional',
                    'national' => 'Nasional',
                    'international' => 'Internasional',
                    default => $state,
                }),

            ExportColumn::make('start_date')
                ->label('Tanggal Mulai')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('end_date')
                ->label('Tanggal Selesai')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('location')
                ->label('Lokasi'),

            ExportColumn::make('organizer')
                ->label('Penyelenggara'),

            ExportColumn::make('teamCoordinator.name')
                ->label('Koordinator Tim'),

            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'upcoming' => 'Akan Datang',
                    'ongoing' => 'Berlangsung',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $state,
                }),

            ExportColumn::make('registration_deadline')
                ->label('Batas Pendaftaran')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('max_participants')
                ->label('Peserta Maksimal'),

            ExportColumn::make('participants_count')
                ->label('Jumlah Peserta')
                ->formatStateUsing(fn ($record) => $record->participants()->count()),

            ExportColumn::make('registration_fee')
                ->label('Biaya Pendaftaran')
                ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),

            ExportColumn::make('description')
                ->label('Deskripsi'),

            ExportColumn::make('rules')
                ->label('Peraturan'),

            ExportColumn::make('prizes')
                ->label('Hadiah'),

            ExportColumn::make('website_url')
                ->label('Website'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '-'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data kompetisi selesai dengan ' . number_format($export->successful_rows) . ' baris berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }

        return $body;
    }
}
