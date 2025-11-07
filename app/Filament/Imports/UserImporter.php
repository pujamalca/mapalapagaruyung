<?php

namespace App\Filament\Imports;

use App\Models\Cohort;
use App\Models\Division;
use App\Models\User;
use App\Support\RoleMapper;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255', 'unique:users,email']),

            ImportColumn::make('username')
                ->label('Username')
                ->rules(['nullable', 'string', 'max:255', 'unique:users,username']),

            ImportColumn::make('phone')
                ->label('No. Telepon')
                ->rules(['nullable', 'string', 'max:20']),

            ImportColumn::make('password')
                ->label('Password')
                ->requiredMapping()
                ->rules(['required', 'string', 'min:8'])
                ->example('password123'),

            ImportColumn::make('cohort')
                ->label('Angkatan')
                ->relationship(resolveUsing: 'name')
                ->rules(['nullable', 'exists:cohorts,name'])
                ->example('Kader XXIV'),

            ImportColumn::make('division')
                ->label('Divisi')
                ->relationship(resolveUsing: 'name')
                ->rules(['nullable', 'exists:divisions,name'])
                ->example('Divisi Pendakian'),

            ImportColumn::make('address')
                ->label('Alamat')
                ->rules(['nullable', 'string', 'max:500']),

            ImportColumn::make('blood_type')
                ->label('Golongan Darah')
                ->rules(['nullable', 'string', 'max:5'])
                ->example('A+'),

            ImportColumn::make('emergency_contact_name')
                ->label('Nama Kontak Darurat')
                ->rules(['nullable', 'string', 'max:255']),

            ImportColumn::make('emergency_contact_phone')
                ->label('No. Telepon Kontak Darurat')
                ->rules(['nullable', 'string', 'max:20']),

            ImportColumn::make('emergency_contact_relation')
                ->label('Hubungan Kontak Darurat')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('Orang Tua'),

            ImportColumn::make('allergies')
                ->label('Alergi')
                ->rules(['nullable', 'string', 'max:500']),

            ImportColumn::make('medical_conditions')
                ->label('Kondisi Medis')
                ->rules(['nullable', 'string', 'max:500']),

            ImportColumn::make('bio')
                ->label('Bio')
                ->rules(['nullable', 'string', 'max:1000']),

            ImportColumn::make('is_active')
                ->label('Status Aktif')
                ->boolean()
                ->rules(['nullable', 'boolean'])
                ->example('1'),

            ImportColumn::make('role')
                ->label('Role')
                ->rules(['required', 'string', Rule::in(RoleMapper::validNames())])
                ->example('Anggota'),
        ];
    }

    public function resolveRecord(): ?User
    {
        $cohort = null;
        if (!empty($this->data['cohort'])) {
            $cohort = Cohort::where('name', $this->data['cohort'])->first();
        }

        $division = null;
        if (!empty($this->data['division'])) {
            $division = Division::where('name', $this->data['division'])->first();
        }

        $user = User::firstOrNew([
            'email' => $this->data['email'],
        ]);

        $user->fill([
            'name' => $this->data['name'],
            'username' => $this->data['username'] ?? null,
            'phone' => $this->data['phone'] ?? null,
            'password' => Hash::make($this->data['password']),
            'cohort_id' => $cohort?->id,
            'division_id' => $division?->id,
            'address' => $this->data['address'] ?? null,
            'blood_type' => $this->data['blood_type'] ?? null,
            'emergency_contact_name' => $this->data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $this->data['emergency_contact_phone'] ?? null,
            'emergency_contact_relation' => $this->data['emergency_contact_relation'] ?? null,
            'allergies' => $this->data['allergies'] ?? null,
            'medical_conditions' => $this->data['medical_conditions'] ?? null,
            'bio' => $this->data['bio'] ?? null,
            'is_active' => $this->data['is_active'] ?? true,
        ]);

        $user->save();

        // Assign role
        if (!empty($this->data['role'])) {
            $user->syncRoles([RoleMapper::normalizeSingle($this->data['role'])]);
        }

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import user berhasil diselesaikan dengan ' . number_format($import->successful_rows) . ' baris berhasil diimport';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' dan ' . number_format($failedRowsCount) . ' baris gagal diimport.';
        }

        return $body;
    }
}
