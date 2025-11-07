<?php

namespace App\Filament\Imports;

use App\Models\TrainingProgram;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TrainingProgramImporter extends Importer
{
    protected static ?string $model = TrainingProgram::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('training_code')
                ->label('Kode Training')
                ->rules(['nullable', 'string', 'max:50', 'unique:training_programs,training_code'])
                ->example('TRN-2024-001'),

            ImportColumn::make('title')
                ->label('Judul')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('BKP (Brevet Khusus Pendakian) Tingkat I'),

            ImportColumn::make('type')
                ->label('Tipe')
                ->rules(['nullable', 'string', 'in:bkp,technical,leadership,survival,navigation,first_aid,other'])
                ->example('bkp'),

            ImportColumn::make('level')
                ->label('Tingkat')
                ->rules(['nullable', 'string', 'in:beginner,intermediate,advanced'])
                ->example('beginner'),

            ImportColumn::make('start_date')
                ->label('Tanggal Mulai')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->example('2024-02-01'),

            ImportColumn::make('end_date')
                ->label('Tanggal Selesai')
                ->requiredMapping()
                ->rules(['required', 'date', 'after_or_equal:start_date'])
                ->example('2024-02-05'),

            ImportColumn::make('duration_hours')
                ->label('Durasi (Jam)')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:1'])
                ->example('40'),

            ImportColumn::make('location')
                ->label('Lokasi')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Basecamp Mapala + Gunung Singgalang'),

            ImportColumn::make('max_participants')
                ->label('Jumlah Peserta Maksimal')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:1'])
                ->example('30'),

            ImportColumn::make('coordinator_email')
                ->label('Email Koordinator')
                ->rules(['nullable', 'email', 'exists:users,email'])
                ->example('coordinator@example.com'),

            ImportColumn::make('status')
                ->label('Status')
                ->rules(['nullable', 'string', 'in:planning,open_registration,preparation,ongoing,completed,cancelled'])
                ->example('completed'),

            ImportColumn::make('registration_fee')
                ->label('Biaya Pendaftaran')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('500000'),

            ImportColumn::make('description')
                ->label('Deskripsi')
                ->rules(['nullable', 'string'])
                ->example('Pelatihan dasar pendakian gunung untuk anggota baru'),

            ImportColumn::make('objectives')
                ->label('Tujuan')
                ->rules(['nullable', 'string'])
                ->example('Memberikan pengetahuan dasar pendakian yang aman'),

            ImportColumn::make('requirements')
                ->label('Persyaratan')
                ->rules(['nullable', 'string'])
                ->example('Anggota aktif Mapala, sehat jasmani dan rohani'),

            ImportColumn::make('materials_covered')
                ->label('Materi yang Diajarkan')
                ->rules(['nullable', 'string'])
                ->example('Teknik mendaki, navigasi, survival, SAR'),

            ImportColumn::make('certificate_issued')
                ->label('Sertifikat Diterbitkan')
                ->boolean()
                ->rules(['nullable', 'boolean'])
                ->example('1'),

            ImportColumn::make('certificate_number_prefix')
                ->label('Prefix Nomor Sertifikat')
                ->rules(['nullable', 'string', 'max:50'])
                ->example('BKP-I-2024'),
        ];
    }

    public function resolveRecord(): ?TrainingProgram
    {
        $coordinator = null;
        if (!empty($this->data['coordinator_email'])) {
            $coordinator = User::where('email', $this->data['coordinator_email'])->first();
        }

        $trainingCode = $this->data['training_code'];
        if (empty($trainingCode)) {
            $trainingCode = TrainingProgram::generateTrainingCode();
        }

        $training = TrainingProgram::firstOrNew([
            'training_code' => $trainingCode,
        ]);

        $training->fill([
            'title' => $this->data['title'],
            'type' => $this->data['type'] ?? 'other',
            'level' => $this->data['level'] ?? 'beginner',
            'start_date' => $this->data['start_date'],
            'end_date' => $this->data['end_date'],
            'duration_hours' => $this->data['duration_hours'] ?? null,
            'location' => $this->data['location'] ?? null,
            'max_participants' => $this->data['max_participants'] ?? null,
            'coordinator_id' => $coordinator?->id,
            'status' => $this->data['status'] ?? 'completed',
            'registration_fee' => $this->data['registration_fee'] ?? null,
            'description' => $this->data['description'] ?? null,
            'objectives' => $this->data['objectives'] ?? null,
            'requirements' => $this->data['requirements'] ?? null,
            'materials_covered' => $this->data['materials_covered'] ?? null,
            'certificate_issued' => $this->data['certificate_issued'] ?? false,
            'certificate_number_prefix' => $this->data['certificate_number_prefix'] ?? null,
        ]);

        $training->save();

        return $training;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import program training berhasil diselesaikan dengan ' . number_format($import->successful_rows) . ' baris berhasil diimport';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' dan ' . number_format($failedRowsCount) . ' baris gagal diimport.';
        }

        return $body;
    }
}
