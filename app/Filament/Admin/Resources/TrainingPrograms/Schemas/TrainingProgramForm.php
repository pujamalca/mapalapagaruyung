<?php

namespace App\Filament\Admin\Resources\TrainingPrograms\Schemas;

use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;

class TrainingProgramForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('Training Program')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Program')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', \Illuminate\Support\Str::slug($state))
                                    )
                                    ->placeholder('Contoh: Diklatsar Kader XXIV'),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(3)
                                ->maxLength(1000)
                                ->helperText('Deskripsi singkat tentang program pelatihan'),

                            Grid::make(3)->schema([
                                Forms\Components\Select::make('cohort_id')
                                    ->label('Target Angkatan')
                                    ->relationship('cohort', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Angkatan yang menjadi peserta'),

                                Forms\Components\Select::make('program_type')
                                    ->label('Jenis Program')
                                    ->options([
                                        'basic' => 'Dasar',
                                        'advanced' => 'Lanjutan',
                                        'specialized' => 'Spesialisasi',
                                    ])
                                    ->default('basic')
                                    ->required(),

                                Forms\Components\Select::make('level')
                                    ->label('Level')
                                    ->options([
                                        'beginner' => 'Pemula',
                                        'intermediate' => 'Menengah',
                                        'advanced' => 'Lanjut',
                                    ])
                                    ->default('beginner'),
                            ]),

                            Grid::make(2)->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'scheduled' => 'Terjadwal',
                                        'ongoing' => 'Berlangsung',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->live(),

                                Forms\Components\Toggle::make('is_mandatory')
                                    ->label('Wajib')
                                    ->default(false)
                                    ->inline(false)
                                    ->helperText('Wajib untuk calon anggota baru'),
                            ]),
                        ]),

                    Tabs\Tab::make('Jadwal & Lokasi')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make('Jadwal Pelaksanaan')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\DateTimePicker::make('start_date')
                                            ->label('Tanggal Mulai')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false)
                                            ->default(now()),

                                        Forms\Components\DateTimePicker::make('end_date')
                                            ->label('Tanggal Selesai')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false)
                                            ->after('start_date'),
                                    ]),
                                ]),

                            Section::make('Lokasi')
                                ->schema([
                                    Forms\Components\TextInput::make('location')
                                        ->label('Lokasi')
                                        ->maxLength(255)
                                        ->placeholder('Contoh: Basecamp Mapala, Gunung Singgalang'),

                                    Forms\Components\Textarea::make('location_details')
                                        ->label('Detail Lokasi')
                                        ->rows(3)
                                        ->helperText('Alamat lengkap atau petunjuk arah'),
                                ]),
                        ]),

                    Tabs\Tab::make('Peserta & Pendaftaran')
                        ->icon('heroicon-o-users')
                        ->schema([
                            Section::make('Kuota Peserta')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('max_participants')
                                            ->label('Maksimal Peserta')
                                            ->numeric()
                                            ->minValue(1)
                                            ->helperText('Kosongkan untuk unlimited'),

                                        Forms\Components\TextInput::make('min_participants')
                                            ->label('Minimal Peserta')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->helperText('Minimal peserta agar program jalan'),

                                        Forms\Components\Select::make('registration_status')
                                            ->label('Status Pendaftaran')
                                            ->options([
                                                'open' => 'Terbuka',
                                                'closed' => 'Ditutup',
                                                'full' => 'Penuh',
                                            ])
                                            ->default('open')
                                            ->required(),
                                    ]),
                                ]),

                            Section::make('Biaya')
                                ->schema([
                                    Forms\Components\TextInput::make('training_fee')
                                        ->label('Biaya Pelatihan')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->default(0)
                                        ->minValue(0)
                                        ->helperText('Masukkan 0 jika gratis'),
                                ]),
                        ]),

                    Tabs\Tab::make('Instruktur & Koordinator')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            Forms\Components\Select::make('coordinator_id')
                                ->label('Koordinator Program')
                                ->relationship('coordinator', 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('BKP yang bertanggung jawab'),

                            Forms\Components\Repeater::make('instructors')
                                ->label('Instruktur')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Instruktur')
                                            ->required(),

                                        Forms\Components\TextInput::make('expertise')
                                            ->label('Keahlian')
                                            ->placeholder('Contoh: Navigasi, P3K, SAR'),
                                    ]),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Instruktur')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                        ]),

                    Tabs\Tab::make('Materi & Persyaratan')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\Repeater::make('learning_objectives')
                                ->label('Tujuan Pembelajaran')
                                ->schema([
                                    Forms\Components\TextInput::make('objective')
                                        ->label('Tujuan')
                                        ->required()
                                        ->placeholder('Contoh: Memahami dasar-dasar navigasi alam'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Tujuan')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['objective'] ?? null),

                            Forms\Components\Repeater::make('requirements')
                                ->label('Persyaratan Peserta')
                                ->schema([
                                    Forms\Components\TextInput::make('requirement')
                                        ->label('Persyaratan')
                                        ->required()
                                        ->placeholder('Contoh: Sehat jasmani dan rohani'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Persyaratan')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['requirement'] ?? null),

                            Forms\Components\Repeater::make('materials_needed')
                                ->label('Peralatan yang Dibutuhkan')
                                ->schema([
                                    Forms\Components\TextInput::make('material')
                                        ->label('Peralatan')
                                        ->required()
                                        ->placeholder('Contoh: Kompas, Peta, GPS'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Peralatan')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['material'] ?? null),
                        ]),

                    Tabs\Tab::make('Evaluasi')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            Forms\Components\Toggle::make('has_evaluation')
                                ->label('Ada Evaluasi')
                                ->default(true)
                                ->live()
                                ->inline(false)
                                ->helperText('Program ini memiliki penilaian/evaluasi?'),

                            Forms\Components\TextInput::make('passing_score')
                                ->label('Nilai Lulus')
                                ->numeric()
                                ->default(70)
                                ->minValue(0)
                                ->maxValue(100)
                                ->required(fn (Get $get) => $get('has_evaluation'))
                                ->visible(fn (Get $get) => $get('has_evaluation'))
                                ->helperText('Nilai minimal untuk lulus program'),

                            Forms\Components\Repeater::make('evaluation_criteria')
                                ->label('Kriteria Evaluasi')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('criteria')
                                            ->label('Kriteria')
                                            ->required()
                                            ->placeholder('Contoh: Kehadiran, Skill Praktik, Ujian Tertulis'),

                                        Forms\Components\TextInput::make('weight')
                                            ->label('Bobot (%)')
                                            ->numeric()
                                            ->default(25)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%'),
                                    ]),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Kriteria')
                                ->reorderable()
                                ->collapsible()
                                ->visible(fn (Get $get) => $get('has_evaluation'))
                                ->itemLabel(fn (array $state): ?string => $state['criteria'] ?? null),
                        ]),

                    Tabs\Tab::make('Media & Catatan')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('materials')
                                ->label('File Materi')
                                ->collection('materials')
                                ->multiple()
                                ->maxFiles(10)
                                ->acceptedFileTypes(['application/pdf', 'image/*', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                                ->helperText('Upload materi pelatihan (PDF, PPT, gambar)'),

                            SpatieMediaLibraryFileUpload::make('photos')
                                ->label('Foto Kegiatan')
                                ->collection('photos')
                                ->multiple()
                                ->maxFiles(20)
                                ->image()
                                ->imageEditor()
                                ->helperText('Upload foto dokumentasi kegiatan'),

                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan')
                                ->rows(4)
                                ->helperText('Catatan tambahan tentang program'),

                            Forms\Components\KeyValue::make('metadata')
                                ->label('Metadata Tambahan')
                                ->keyLabel('Key')
                                ->valueLabel('Value')
                                ->reorderable()
                                ->addActionLabel('Tambah Metadata'),
                        ]),
                ]),
        ];
    }
}
