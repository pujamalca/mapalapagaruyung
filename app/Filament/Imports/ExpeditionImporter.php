<?php

namespace App\Filament\Imports;

use App\Models\Expedition;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ExpeditionImporter extends Importer
{
    protected static ?string $model = Expedition::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('expedition_code')
                ->label('Kode Ekspedisi')
                ->rules(['nullable', 'string', 'max:50', 'unique:expeditions,expedition_code'])
                ->example('EXP-2024-001'),

            ImportColumn::make('title')
                ->label('Judul')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Pendakian Gunung Kerinci'),

            ImportColumn::make('destination')
                ->label('Tujuan')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Gunung Kerinci, Jambi'),

            ImportColumn::make('type')
                ->label('Tipe')
                ->rules(['nullable', 'string', 'in:climbing,hiking,camping,expedition,exploration'])
                ->example('climbing'),

            ImportColumn::make('difficulty')
                ->label('Tingkat Kesulitan')
                ->rules(['nullable', 'string', 'in:easy,moderate,hard,extreme'])
                ->example('hard'),

            ImportColumn::make('start_date')
                ->label('Tanggal Mulai')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->example('2024-01-15'),

            ImportColumn::make('end_date')
                ->label('Tanggal Selesai')
                ->requiredMapping()
                ->rules(['required', 'date', 'after_or_equal:start_date'])
                ->example('2024-01-20'),

            ImportColumn::make('duration_days')
                ->label('Durasi (Hari)')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:1'])
                ->example('5'),

            ImportColumn::make('max_participants')
                ->label('Jumlah Peserta Maksimal')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:1'])
                ->example('20'),

            ImportColumn::make('coordinator_email')
                ->label('Email Koordinator')
                ->rules(['nullable', 'email', 'exists:users,email'])
                ->example('coordinator@example.com'),

            ImportColumn::make('status')
                ->label('Status')
                ->rules(['nullable', 'string', 'in:planning,open_registration,preparation,ongoing,completed,cancelled'])
                ->example('completed'),

            ImportColumn::make('estimated_budget')
                ->label('Estimasi Budget')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('5000000'),

            ImportColumn::make('actual_budget')
                ->label('Budget Aktual')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('4800000'),

            ImportColumn::make('base_camp_location')
                ->label('Lokasi Base Camp')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Kersik Tuo'),

            ImportColumn::make('altitude')
                ->label('Ketinggian (mdpl)')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0'])
                ->example('3805'),

            ImportColumn::make('description')
                ->label('Deskripsi')
                ->rules(['nullable', 'string'])
                ->example('Ekspedisi pendakian ke puncak tertinggi di Sumatera'),

            ImportColumn::make('objectives')
                ->label('Tujuan')
                ->rules(['nullable', 'string'])
                ->example('Mencapai puncak dan dokumentasi flora fauna'),

            ImportColumn::make('route_description')
                ->label('Deskripsi Rute')
                ->rules(['nullable', 'string']),

            ImportColumn::make('report_summary')
                ->label('Ringkasan Laporan')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?Expedition
    {
        $coordinator = null;
        if (!empty($this->data['coordinator_email'])) {
            $coordinator = User::where('email', $this->data['coordinator_email'])->first();
        }

        $expeditionCode = $this->data['expedition_code'];
        if (empty($expeditionCode)) {
            $expeditionCode = Expedition::generateExpeditionCode();
        }

        $expedition = Expedition::firstOrNew([
            'expedition_code' => $expeditionCode,
        ]);

        $expedition->fill([
            'title' => $this->data['title'],
            'destination' => $this->data['destination'],
            'type' => $this->data['type'] ?? 'expedition',
            'difficulty' => $this->data['difficulty'] ?? 'moderate',
            'start_date' => $this->data['start_date'],
            'end_date' => $this->data['end_date'],
            'duration_days' => $this->data['duration_days'] ?? null,
            'max_participants' => $this->data['max_participants'] ?? null,
            'coordinator_id' => $coordinator?->id,
            'status' => $this->data['status'] ?? 'completed',
            'estimated_budget' => $this->data['estimated_budget'] ?? null,
            'actual_budget' => $this->data['actual_budget'] ?? null,
            'base_camp_location' => $this->data['base_camp_location'] ?? null,
            'altitude' => $this->data['altitude'] ?? null,
            'description' => $this->data['description'] ?? null,
            'objectives' => $this->data['objectives'] ?? null,
            'route_description' => $this->data['route_description'] ?? null,
            'report_summary' => $this->data['report_summary'] ?? null,
        ]);

        $expedition->save();

        return $expedition;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import ekspedisi berhasil diselesaikan dengan ' . number_format($import->successful_rows) . ' baris berhasil diimport';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' dan ' . number_format($failedRowsCount) . ' baris gagal diimport.';
        }

        return $body;
    }
}
