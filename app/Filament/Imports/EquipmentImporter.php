<?php

namespace App\Filament\Imports;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EquipmentImporter extends Importer
{
    protected static ?string $model = Equipment::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode Alat')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:50', 'unique:equipment,code'])
                ->example('EQ-001'),

            ImportColumn::make('name')
                ->label('Nama Alat')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('category')
                ->label('Kategori')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(['required', 'exists:equipment_categories,name'])
                ->example('Tenda'),

            ImportColumn::make('brand')
                ->label('Merek')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('Consina'),

            ImportColumn::make('model')
                ->label('Model')
                ->rules(['nullable', 'string', 'max:100']),

            ImportColumn::make('quantity_total')
                ->label('Jumlah Total')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'min:0'])
                ->example('10'),

            ImportColumn::make('quantity_available')
                ->label('Jumlah Tersedia')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0'])
                ->example('8'),

            ImportColumn::make('unit')
                ->label('Satuan')
                ->rules(['nullable', 'string', 'max:20'])
                ->example('unit'),

            ImportColumn::make('condition')
                ->label('Kondisi')
                ->rules(['nullable', 'string', 'in:excellent,good,fair,poor,damaged'])
                ->example('good'),

            ImportColumn::make('status')
                ->label('Status')
                ->rules(['nullable', 'string', 'in:available,borrowed,maintenance,retired'])
                ->example('available'),

            ImportColumn::make('purchase_date')
                ->label('Tanggal Pembelian')
                ->rules(['nullable', 'date'])
                ->example('2024-01-15'),

            ImportColumn::make('purchase_price')
                ->label('Harga Beli')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('500000'),

            ImportColumn::make('location')
                ->label('Lokasi Penyimpanan')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Gudang A - Rak 1'),

            ImportColumn::make('weight')
                ->label('Berat (kg)')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('2.5'),

            ImportColumn::make('dimensions')
                ->label('Dimensi')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('40x30x20 cm'),

            ImportColumn::make('notes')
                ->label('Catatan')
                ->rules(['nullable', 'string', 'max:1000']),
        ];
    }

    public function resolveRecord(): ?Equipment
    {
        $category = EquipmentCategory::where('name', $this->data['category'])->first();

        if (!$category) {
            return null;
        }

        $equipment = Equipment::firstOrNew([
            'code' => $this->data['code'],
        ]);

        $quantityAvailable = $this->data['quantity_available'] ?? $this->data['quantity_total'];

        $equipment->fill([
            'name' => $this->data['name'],
            'equipment_category_id' => $category->id,
            'brand' => $this->data['brand'] ?? null,
            'model' => $this->data['model'] ?? null,
            'quantity_total' => $this->data['quantity_total'],
            'quantity_available' => $quantityAvailable,
            'unit' => $this->data['unit'] ?? 'unit',
            'condition' => $this->data['condition'] ?? 'good',
            'status' => $this->data['status'] ?? 'available',
            'purchase_date' => $this->data['purchase_date'] ?? null,
            'purchase_price' => $this->data['purchase_price'] ?? null,
            'location' => $this->data['location'] ?? null,
            'weight' => $this->data['weight'] ?? null,
            'dimensions' => $this->data['dimensions'] ?? null,
            'notes' => $this->data['notes'] ?? null,
        ]);

        $equipment->save();

        return $equipment;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import peralatan berhasil diselesaikan dengan ' . number_format($import->successful_rows) . ' baris berhasil diimport';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' dan ' . number_format($failedRowsCount) . ' baris gagal diimport.';
        }

        return $body;
    }
}
