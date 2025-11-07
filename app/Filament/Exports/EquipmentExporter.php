<?php

namespace App\Filament\Exports;

use App\Models\Equipment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EquipmentExporter extends Exporter
{
    protected static ?string $model = Equipment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('code')
                ->label('Kode Alat'),

            ExportColumn::make('name')
                ->label('Nama Alat'),

            ExportColumn::make('equipmentCategory.name')
                ->label('Kategori'),

            ExportColumn::make('brand')
                ->label('Merek'),

            ExportColumn::make('model')
                ->label('Model'),

            ExportColumn::make('quantity_total')
                ->label('Jumlah Total'),

            ExportColumn::make('quantity_available')
                ->label('Jumlah Tersedia'),

            ExportColumn::make('quantity')
                ->label('Jumlah Dipinjam')
                ->formatStateUsing(fn ($record) => $record->quantity_total - $record->quantity_available),

            ExportColumn::make('unit')
                ->label('Satuan'),

            ExportColumn::make('condition')
                ->label('Kondisi')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'excellent' => 'Sangat Baik',
                    'good' => 'Baik',
                    'fair' => 'Cukup',
                    'poor' => 'Kurang Baik',
                    'damaged' => 'Rusak',
                    default => $state,
                }),

            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'available' => 'Tersedia',
                    'borrowed' => 'Dipinjam',
                    'maintenance' => 'Maintenance',
                    'retired' => 'Pensiun',
                    default => $state,
                }),

            ExportColumn::make('purchase_date')
                ->label('Tanggal Pembelian')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('purchase_price')
                ->label('Harga Beli')
                ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),

            ExportColumn::make('location')
                ->label('Lokasi Penyimpanan'),

            ExportColumn::make('weight')
                ->label('Berat (kg)'),

            ExportColumn::make('dimensions')
                ->label('Dimensi'),

            ExportColumn::make('last_maintenance_date')
                ->label('Maintenance Terakhir')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('next_maintenance_date')
                ->label('Maintenance Berikutnya')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : '-'),

            ExportColumn::make('notes')
                ->label('Catatan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data peralatan selesai dengan ' . number_format($export->successful_rows) . ' baris berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }

        return $body;
    }
}
