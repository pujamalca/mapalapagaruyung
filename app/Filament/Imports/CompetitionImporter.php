<?php

namespace App\Filament\Imports;

use App\Models\Competition;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CompetitionImporter extends Importer
{
    protected static ?string $model = Competition::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('competition_code')
                ->label('Kode Kompetisi')
                ->rules(['nullable', 'string', 'max:50', 'unique:competitions,competition_code'])
                ->example('COMP-2024-001'),

            ImportColumn::make('title')
                ->label('Judul')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Lomba Panjat Tebing Tingkat Nasional'),

            ImportColumn::make('type')
                ->label('Tipe')
                ->rules(['nullable', 'string', 'in:climbing,hiking,navigation,survival,photography,other'])
                ->example('climbing'),

            ImportColumn::make('level')
                ->label('Tingkat')
                ->rules(['nullable', 'string', 'in:local,regional,national,international'])
                ->example('national'),

            ImportColumn::make('start_date')
                ->label('Tanggal Mulai')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->example('2024-03-10'),

            ImportColumn::make('end_date')
                ->label('Tanggal Selesai')
                ->requiredMapping()
                ->rules(['required', 'date', 'after_or_equal:start_date'])
                ->example('2024-03-12'),

            ImportColumn::make('location')
                ->label('Lokasi')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Jakarta Convention Center'),

            ImportColumn::make('organizer')
                ->label('Penyelenggara')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('FPTI Jakarta'),

            ImportColumn::make('coordinator_email')
                ->label('Email Koordinator Tim')
                ->rules(['nullable', 'email', 'exists:users,email'])
                ->example('coordinator@example.com'),

            ImportColumn::make('status')
                ->label('Status')
                ->rules(['nullable', 'string', 'in:upcoming,ongoing,completed,cancelled'])
                ->example('completed'),

            ImportColumn::make('registration_deadline')
                ->label('Batas Pendaftaran')
                ->rules(['nullable', 'date'])
                ->example('2024-03-01'),

            ImportColumn::make('max_participants')
                ->label('Jumlah Peserta Maksimal')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:1'])
                ->example('50'),

            ImportColumn::make('registration_fee')
                ->label('Biaya Pendaftaran')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('250000'),

            ImportColumn::make('description')
                ->label('Deskripsi')
                ->rules(['nullable', 'string'])
                ->example('Kompetisi panjat tebing bergengsi tingkat nasional'),

            ImportColumn::make('rules')
                ->label('Peraturan')
                ->rules(['nullable', 'string'])
                ->example('Mengikuti standar IFSC'),

            ImportColumn::make('prizes')
                ->label('Hadiah')
                ->rules(['nullable', 'string'])
                ->example('Juara 1: Rp 10.000.000, Juara 2: Rp 7.000.000'),

            ImportColumn::make('website_url')
                ->label('URL Website')
                ->rules(['nullable', 'url', 'max:500'])
                ->example('https://lomba.example.com'),
        ];
    }

    public function resolveRecord(): ?Competition
    {
        $coordinator = null;
        if (!empty($this->data['coordinator_email'])) {
            $coordinator = User::where('email', $this->data['coordinator_email'])->first();
        }

        $competitionCode = $this->data['competition_code'];
        if (empty($competitionCode)) {
            $competitionCode = Competition::generateCompetitionCode();
        }

        $competition = Competition::firstOrNew([
            'competition_code' => $competitionCode,
        ]);

        $competition->fill([
            'title' => $this->data['title'],
            'type' => $this->data['type'] ?? 'other',
            'level' => $this->data['level'] ?? 'local',
            'start_date' => $this->data['start_date'],
            'end_date' => $this->data['end_date'],
            'location' => $this->data['location'],
            'organizer' => $this->data['organizer'] ?? null,
            'team_coordinator_id' => $coordinator?->id,
            'status' => $this->data['status'] ?? 'completed',
            'registration_deadline' => $this->data['registration_deadline'] ?? null,
            'max_participants' => $this->data['max_participants'] ?? null,
            'registration_fee' => $this->data['registration_fee'] ?? null,
            'description' => $this->data['description'] ?? null,
            'rules' => $this->data['rules'] ?? null,
            'prizes' => $this->data['prizes'] ?? null,
            'website_url' => $this->data['website_url'] ?? null,
        ]);

        $competition->save();

        return $competition;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import kompetisi berhasil diselesaikan dengan ' . number_format($import->successful_rows) . ' baris berhasil diimport';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' dan ' . number_format($failedRowsCount) . ' baris gagal diimport.';
        }

        return $body;
    }
}
