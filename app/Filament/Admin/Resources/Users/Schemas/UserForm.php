<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\Cohort;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class UserForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('User Profile')
                ->tabs([
                    Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Section::make('Data Akun')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Lengkap')
                                        ->required()
                                        ->maxLength(255),

                                    Forms\Components\TextInput::make('username')
                                        ->label('Username')
                                        ->maxLength(50)
                                        ->unique(ignoreRecord: true)
                                        ->alphaDash(),

                                    Forms\Components\TextInput::make('email')
                                        ->label('Email')
                                        ->email()
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),

                                    Forms\Components\TextInput::make('phone')
                                        ->label('No. Telepon')
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('08xx-xxxx-xxxx'),

                                    Forms\Components\TextInput::make('password')
                                        ->label('Password')
                                        ->password()
                                        ->minLength(8)
                                        ->required(fn (string $operation): bool => $operation === 'create')
                                        ->dehydrated(fn ($state): bool => filled($state))
                                        ->revealable()
                                        ->helperText('Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.'),

                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Status Aktif')
                                        ->default(true)
                                        ->inline(false)
                                        ->helperText('User aktif dapat login ke sistem'),
                                ])
                                ->columns(2),

                            Section::make('Profil')
                                ->schema([
                                    Forms\Components\Textarea::make('bio')
                                        ->label('Bio')
                                        ->rows(3)
                                        ->maxLength(500)
                                        ->placeholder('Ceritakan tentang diri Anda...'),

                                    SpatieMediaLibraryFileUpload::make('avatar')
                                        ->collection('avatar')
                                        ->label('Foto Profil')
                                        ->image()
                                        ->imageEditor()
                                        ->circleCropper()
                                        ->maxSize(1024)
                                        ->helperText('Maksimal 1MB. Format: JPG, PNG, WebP'),
                                ]),
                        ]),

                    Tab::make('Data Mahasiswa')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            Section::make('Informasi Akademik')
                                ->schema([
                                    Forms\Components\TextInput::make('nim')
                                        ->label('NIM/NPM')
                                        ->maxLength(20)
                                        ->placeholder('2024010001'),

                                    Forms\Components\TextInput::make('major')
                                        ->label('Jurusan')
                                        ->maxLength(100)
                                        ->placeholder('Teknik Informatika'),

                                    Forms\Components\TextInput::make('faculty')
                                        ->label('Fakultas')
                                        ->maxLength(100)
                                        ->placeholder('Fakultas Teknik'),

                                    Forms\Components\TextInput::make('enrollment_year')
                                        ->label('Tahun Masuk Kampus')
                                        ->numeric()
                                        ->minValue(2000)
                                        ->maxValue(now()->year + 1)
                                        ->placeholder(now()->year),
                                ])
                                ->columns(2),

                            Section::make('Alamat & Kontak')
                                ->schema([
                                    Forms\Components\Textarea::make('address')
                                        ->label('Alamat Lengkap')
                                        ->rows(3)
                                        ->maxLength(500),
                                ]),
                        ]),

                    Tab::make('Keanggotaan Mapala')
                        ->icon('heroicon-o-users')
                        ->schema([
                            Section::make('Data Anggota')
                                ->schema([
                                    Forms\Components\Select::make('cohort_id')
                                        ->label('Angkatan/Kader')
                                        ->options(Cohort::orderBy('year', 'desc')->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Pilih angkatan Mapala'),

                                    Forms\Components\TextInput::make('member_number')
                                        ->label('Nomor Anggota')
                                        ->maxLength(50)
                                        ->unique(ignoreRecord: true)
                                        ->placeholder('MAP-2024-001')
                                        ->helperText('Format: MAP-TAHUN-NOMOR'),

                                    Forms\Components\TextInput::make('mapala_join_year')
                                        ->label('Tahun Bergabung Mapala')
                                        ->numeric()
                                        ->minValue(2000)
                                        ->maxValue(now()->year + 1)
                                        ->placeholder(now()->year),

                                    Forms\Components\Select::make('member_status')
                                        ->label('Status Keanggotaan')
                                        ->options([
                                            'prospective' => 'Calon Anggota',
                                            'junior' => 'Anggota Muda',
                                            'member' => 'Anggota',
                                            'alumni' => 'Alumni',
                                        ])
                                        ->default('prospective')
                                        ->required()
                                        ->native(false),
                                ])
                                ->columns(2),
                        ]),

                    Tab::make('Kesehatan')
                        ->icon('heroicon-o-heart')
                        ->schema([
                            Section::make('Data Kesehatan')
                                ->description('Data ini penting untuk keselamatan dalam kegiatan')
                                ->schema([
                                    Forms\Components\Select::make('blood_type')
                                        ->label('Golongan Darah')
                                        ->options([
                                            'A' => 'A',
                                            'B' => 'B',
                                            'AB' => 'AB',
                                            'O' => 'O',
                                        ])
                                        ->native(false)
                                        ->searchable(),

                                    Forms\Components\Textarea::make('medical_history')
                                        ->label('Riwayat Penyakit')
                                        ->rows(3)
                                        ->maxLength(1000)
                                        ->placeholder('Alergi, penyakit kronis, atau kondisi medis khusus...'),
                                ])
                                ->columns(1),

                            Section::make('Kontak Darurat')
                                ->description('Kontak yang dapat dihubungi saat keadaan darurat')
                                ->schema([
                                    Forms\Components\TextInput::make('emergency_contact.name')
                                        ->label('Nama Kontak Darurat')
                                        ->maxLength(255)
                                        ->placeholder('Nama orang tua/wali'),

                                    Forms\Components\Select::make('emergency_contact.relationship')
                                        ->label('Hubungan')
                                        ->options([
                                            'Ayah' => 'Ayah',
                                            'Ibu' => 'Ibu',
                                            'Orang Tua' => 'Orang Tua',
                                            'Saudara' => 'Saudara',
                                            'Kerabat' => 'Kerabat',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->searchable()
                                        ->placeholder('Pilih hubungan'),

                                    Forms\Components\TextInput::make('emergency_contact.phone')
                                        ->label('No. Telepon Darurat')
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('08xx-xxxx-xxxx'),
                                ])
                                ->columns(3),
                        ]),

                    Tab::make('Skills & Sertifikat')
                        ->icon('heroicon-o-trophy')
                        ->schema([
                            Section::make('Keterampilan')
                                ->schema([
                                    Forms\Components\Repeater::make('skills')
                                        ->label('Daftar Keterampilan')
                                        ->schema([
                                            Forms\Components\TextInput::make('skill')
                                                ->label('Keterampilan')
                                                ->required()
                                                ->placeholder('Navigasi Darat, Rock Climbing, dll'),

                                            Forms\Components\Select::make('level')
                                                ->label('Level')
                                                ->options([
                                                    'Basic' => 'Basic',
                                                    'Intermediate' => 'Intermediate',
                                                    'Advanced' => 'Advanced',
                                                    'Expert' => 'Expert',
                                                ])
                                                ->default('Basic')
                                                ->required(),

                                            Forms\Components\Toggle::make('certified')
                                                ->label('Bersertifikat')
                                                ->default(false)
                                                ->inline(false),

                                            Forms\Components\DatePicker::make('certificate_date')
                                                ->label('Tanggal Sertifikat')
                                                ->native(false)
                                                ->displayFormat('d/m/Y'),
                                        ])
                                        ->columns(4)
                                        ->defaultItems(0)
                                        ->reorderable()
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['skill'] ?? null),
                                ]),

                            Section::make('Dokumen Sertifikat')
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('certificates')
                                        ->collection('certificates')
                                        ->label('Upload Sertifikat')
                                        ->multiple()
                                        ->reorderable()
                                        ->maxFiles(10)
                                        ->maxSize(2048)
                                        ->acceptedFileTypes(['image/*', 'application/pdf'])
                                        ->helperText('Upload sertifikat pelatihan (Maks. 10 file, 2MB per file)'),
                                ])
                                ->collapsible()
                                ->collapsed(),
                        ]),

                    Tab::make('Roles & Permissions')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Section::make('Role & Izin Akses')
                                ->description('Atur role dan permission untuk akses sistem')
                                ->schema([
                                    Forms\Components\Select::make('roles')
                                        ->label('Roles')
                                        ->relationship('roles', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->searchable()
                                        ->helperText('Pilih role untuk menentukan hak akses user'),
                                ])
                                ->visible(fn (): bool => auth()->user()?->can('manage-roles') ?? false),

                            Section::make('Informasi Sistem')
                                ->schema([
                                    Forms\Components\DateTimePicker::make('email_verified_at')
                                        ->label('Email Terverifikasi Pada')
                                        ->native(false)
                                        ->disabled(),

                                    Forms\Components\DateTimePicker::make('last_login_at')
                                        ->label('Login Terakhir')
                                        ->native(false)
                                        ->disabled(),

                                    Forms\Components\TextInput::make('last_login_ip')
                                        ->label('IP Login Terakhir')
                                        ->disabled(),
                                ])
                                ->columns(3)
                                ->collapsible()
                                ->collapsed(),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}
