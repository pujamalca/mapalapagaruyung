<?php

namespace App\Filament\Exports;

use App\Models\EquipmentBorrowing;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EquipmentBorrowingExporter extends Exporter
{
    protected static ?string $model = EquipmentBorrowing::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('borrowing_code')
                ->label('Kode Peminjaman'),

            ExportColumn::make('user.name')
                ->label('Peminjam'),

            ExportColumn::make('user.email')
                ->label('Email Peminjam'),

            ExportColumn::make('equipment.name')
                ->label('Nama Peralatan'),

            ExportColumn::make('equipment.code')
                ->label('Kode Peralatan'),

            ExportColumn::make('quantity_borrowed')
                ->label('Jumlah Dipinjam'),

            ExportColumn::make('borrow_date')
                ->label('Tanggal Pinjam')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('due_date')
                ->label('Tanggal Jatuh Tempo')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('return_date')
                ->label('Tanggal Kembali')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'active' => 'Sedang Dipinjam',
                    'returned' => 'Dikembalikan',
                    'cancelled' => 'Dibatalkan',
                    default => $state,
                }),

            ExportColumn::make('is_overdue')
                ->label('Terlambat')
                ->formatStateUsing(fn ($record) => $record->isOverdue() ? 'Ya' : 'Tidak'),

            ExportColumn::make('days_late')
                ->label('Hari Terlambat')
                ->formatStateUsing(fn ($record) => $record->isOverdue() ? $record->getDaysLate() : 0),

            ExportColumn::make('late_penalty')
                ->label('Denda Keterlambatan')
                ->formatStateUsing(fn ($record) =>
                    $record->late_penalty ? 'Rp ' . number_format($record->late_penalty, 0, ',', '.') : '-'
                ),

            ExportColumn::make('condition_before')
                ->label('Kondisi Sebelum')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'excellent' => 'Sangat Baik',
                    'good' => 'Baik',
                    'fair' => 'Cukup',
                    'poor' => 'Kurang Baik',
                    'damaged' => 'Rusak',
                    default => $state ?? '-',
                }),

            ExportColumn::make('condition_after')
                ->label('Kondisi Setelah')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'excellent' => 'Sangat Baik',
                    'good' => 'Baik',
                    'fair' => 'Cukup',
                    'poor' => 'Kurang Baik',
                    'damaged' => 'Rusak',
                    default => $state ?? '-',
                }),

            ExportColumn::make('purpose')
                ->label('Tujuan Peminjaman'),

            ExportColumn::make('notes')
                ->label('Catatan'),

            ExportColumn::make('approved_by.name')
                ->label('Disetujui Oleh'),

            ExportColumn::make('approved_at')
                ->label('Tanggal Disetujui')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '-'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '-'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data peminjaman peralatan selesai dengan ' . number_format($export->successful_rows) . ' baris berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }

        return $body;
    }
}
