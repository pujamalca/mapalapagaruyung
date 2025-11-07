<?php

namespace App\Filament\Admin\Resources\Applicants\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Spatie\MediaLibraryPro\Filament\Forms\Components\MediaLibraryFileUpload;

class ApplicantForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('Applicant Details')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Data Pribadi')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(3)->schema([
                                Forms\Components\TextInput::make('registration_number')
                                    ->label('No. Pendaftaran')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'registered' => 'Terdaftar',
                                        'verified' => 'Terverifikasi',
                                        'in_selection' => 'Dalam Seleksi',
                                        'passed' => 'Lulus',
                                        'failed' => 'Tidak Lulus',
                                        'accepted' => 'Diterima',
                                        'rejected' => 'Ditolak',
                                        'withdrawn' => 'Mengundurkan Diri',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('assigned_to')
                                    ->label('Assigned To (BKP)')
                                    ->relationship('assignedTo', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('BKP yang bertanggung jawab'),
                            ]),

                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('full_name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                            ]),

                            Grid::make(3)->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Telepon')
                                    ->tel()
                                    ->required(),

                                Forms\Components\TextInput::make('blood_type')
                                    ->label('Golongan Darah')
                                    ->placeholder('A/B/AB/O'),
                            ]),

                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('birth_place')
                                    ->label('Tempat Lahir'),

                                Forms\Components\DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->native(false)
                                    ->displayFormat('d/m/Y'),
                            ]),

                            Forms\Components\Textarea::make('address')
                                ->label('Alamat')
                                ->rows(2),
                        ]),

                    Tabs\Tab::make('Data Akademik')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('nim')
                                    ->label('NIM')
                                    ->required(),

                                Forms\Components\TextInput::make('major')
                                    ->label('Jurusan')
                                    ->required(),
                            ]),

                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('faculty')
                                    ->label('Fakultas')
                                    ->required(),

                                Forms\Components\TextInput::make('enrollment_year')
                                    ->label('Tahun Masuk')
                                    ->numeric()
                                    ->minValue(2000)
                                    ->maxValue(date('Y')),
                            ]),

                            Forms\Components\TextInput::make('gpa')
                                ->label('IPK')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0)
                                ->maxValue(4)
                                ->helperText('Indeks Prestasi Kumulatif'),
                        ]),

                    Tabs\Tab::make('Kesehatan & Kontak Darurat')
                        ->icon('heroicon-o-heart')
                        ->schema([
                            Forms\Components\Textarea::make('medical_history')
                                ->label('Riwayat Penyakit')
                                ->rows(3)
                                ->helperText('Riwayat penyakit atau kondisi kesehatan yang perlu diketahui'),

                            Section::make('Kontak Darurat')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('emergency_contact.name')
                                            ->label('Nama Kontak Darurat')
                                            ->required(),

                                        Forms\Components\Select::make('emergency_contact.relationship')
                                            ->label('Hubungan')
                                            ->options([
                                                'Ayah' => 'Ayah',
                                                'Ibu' => 'Ibu',
                                                'Saudara' => 'Saudara',
                                                'Wali' => 'Wali',
                                                'Lainnya' => 'Lainnya',
                                            ])
                                            ->required(),

                                        Forms\Components\TextInput::make('emergency_contact.phone')
                                            ->label('No. Telepon')
                                            ->tel()
                                            ->required(),
                                    ]),
                                ]),
                        ]),

                    Tabs\Tab::make('Pengalaman & Motivasi')
                        ->icon('heroicon-o-trophy')
                        ->schema([
                            Forms\Components\Textarea::make('organization_experience')
                                ->label('Pengalaman Organisasi')
                                ->rows(3)
                                ->helperText('Pengalaman organisasi yang pernah diikuti'),

                            Forms\Components\Textarea::make('outdoor_experience')
                                ->label('Pengalaman Alam Terbuka')
                                ->rows(3)
                                ->helperText('Pengalaman kegiatan outdoor/alam terbuka'),

                            Forms\Components\Textarea::make('motivation')
                                ->label('Motivasi Bergabung')
                                ->rows(4)
                                ->required()
                                ->helperText('Alasan dan motivasi bergabung dengan Mapala'),

                            Forms\Components\Repeater::make('skills')
                                ->label('Skills & Keahlian')
                                ->schema([
                                    Forms\Components\TextInput::make('skill')
                                        ->label('Skill')
                                        ->required(),
                                ])
                                ->addActionLabel('Tambah Skill')
                                ->collapsed()
                                ->itemLabel(fn (array $state): ?string => $state['skill'] ?? null),
                        ]),

                    Tabs\Tab::make('Dokumen')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            MediaLibraryFileUpload::make('photo')
                                ->label('Pas Foto')
                                ->collection('photo')
                                ->image()
                                ->imageEditor()
                                ->maxSize(2048)
                                ->helperText('Format: JPG, PNG, WebP. Maksimal 2MB'),

                            Grid::make(2)->schema([
                                MediaLibraryFileUpload::make('ktp')
                                    ->label('KTP')
                                    ->collection('ktp')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->maxSize(2048),

                                MediaLibraryFileUpload::make('ktm')
                                    ->label('KTM (Kartu Tanda Mahasiswa)')
                                    ->collection('ktm')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->maxSize(2048),
                            ]),

                            Grid::make(2)->schema([
                                MediaLibraryFileUpload::make('form')
                                    ->label('Formulir Pendaftaran')
                                    ->collection('form')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->maxSize(2048),

                                MediaLibraryFileUpload::make('payment_proof')
                                    ->label('Bukti Pembayaran')
                                    ->collection('payment_proof')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->maxSize(2048),
                            ]),

                            MediaLibraryFileUpload::make('documents')
                                ->label('Dokumen Lainnya')
                                ->collection('documents')
                                ->multiple()
                                ->maxFiles(5)
                                ->acceptedFileTypes(['image/*', 'application/pdf'])
                                ->maxSize(2048),
                        ]),

                    Tabs\Tab::make('Catatan & Status')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            Forms\Components\Textarea::make('status_notes')
                                ->label('Catatan Status')
                                ->rows(3)
                                ->helperText('Catatan admin tentang status pendaftar'),

                            Grid::make(3)->schema([
                                Forms\Components\TextInput::make('total_score')
                                    ->label('Total Nilai')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->suffix('poin'),

                                Forms\Components\TextInput::make('current_stage')
                                    ->label('Tahap Saat Ini')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\DateTimePicker::make('verified_at')
                                    ->label('Tanggal Verifikasi')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->native(false),
                            ]),
                        ]),
                ]),
        ];
    }
}
