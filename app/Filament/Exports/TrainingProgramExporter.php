<?php

namespace App\Filament\Exports;

use App\Models\TrainingProgram;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TrainingProgramExporter extends Exporter
{
    protected static ?string $model = TrainingProgram::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('training_code')
                ->label('Kode Training'),

            ExportColumn::make('title')
                ->label('Judul'),

            ExportColumn::make('type')
                ->label('Tipe')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'bkp' => 'BKP',
                    'technical' => 'Teknis',
                    'leadership' => 'Kepemimpinan',
                    'survival' => 'Survival',
                    'navigation' => 'Navigasi',
                    'first_aid' => 'Pertolongan Pertama',
                    'other' => 'Lainnya',
                    default => $state,
                }),

            ExportColumn::make('level')
                ->label('Tingkat')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'beginner' => 'Pemula',
                    'intermediate' => 'Menengah',
                    'advanced' => 'Lanjutan',
                    default => $state,
                }),

            ExportColumn::make('start_date')
                ->label('Tanggal Mulai')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('end_date')
                ->label('Tanggal Selesai')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('duration_hours')
                ->label('Durasi (Jam)'),

            ExportColumn::make('location')
                ->label('Lokasi'),

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

            ExportColumn::make('registration_fee')
                ->label('Biaya Pendaftaran')
                ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),

            ExportColumn::make('is_mandatory')
                ->label('Wajib')
                ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),

            ExportColumn::make('certificate_issued')
                ->label('Sertifikat Diterbitkan')
                ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),

            ExportColumn::make('certificate_number_prefix')
                ->label('Prefix Nomor Sertifikat'),

            ExportColumn::make('description')
                ->label('Deskripsi'),

            ExportColumn::make('objectives')
                ->label('Tujuan'),

            ExportColumn::make('requirements')
                ->label('Persyaratan'),

            ExportColumn::make('materials_covered')
                ->label('Materi yang Diajarkan'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '-'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data program pelatihan selesai dengan ' . number_format($export->successful_rows) . ' baris berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }

        return $body;
    }
}
