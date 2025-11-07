<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('name')
                ->label('Nama'),

            ExportColumn::make('email')
                ->label('Email'),

            ExportColumn::make('username')
                ->label('Username'),

            ExportColumn::make('phone')
                ->label('No. Telepon'),

            ExportColumn::make('cohort.name')
                ->label('Angkatan'),

            ExportColumn::make('division.name')
                ->label('Divisi'),

            ExportColumn::make('address')
                ->label('Alamat'),

            ExportColumn::make('blood_type')
                ->label('Golongan Darah'),

            ExportColumn::make('emergency_contact_name')
                ->label('Nama Kontak Darurat'),

            ExportColumn::make('emergency_contact_phone')
                ->label('No. Telepon Kontak Darurat'),

            ExportColumn::make('emergency_contact_relation')
                ->label('Hubungan Kontak Darurat'),

            ExportColumn::make('allergies')
                ->label('Alergi'),

            ExportColumn::make('medical_conditions')
                ->label('Kondisi Medis'),

            ExportColumn::make('bio')
                ->label('Bio'),

            ExportColumn::make('is_active')
                ->label('Status Aktif')
                ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif'),

            ExportColumn::make('roles')
                ->label('Role')
                ->formatStateUsing(fn ($record) => $record->roles->pluck('name')->join(', ')),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '-'),

            ExportColumn::make('updated_at')
                ->label('Terakhir Diupdate')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '-'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data anggota selesai dengan ' . number_format($export->successful_rows) . ' baris berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }

        return $body;
    }
}
