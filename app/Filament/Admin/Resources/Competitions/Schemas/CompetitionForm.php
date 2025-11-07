<?php

namespace App\Filament\Admin\Resources\Competitions\Schemas;

use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;

class CompetitionForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('Competition')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Event/Kompetisi')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', \Illuminate\Support\Str::slug($state))
                                    ),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(3)
                                ->maxLength(1000),

                            Grid::make(3)->schema([
                                Forms\Components\Select::make('event_type')
                                    ->label('Jenis Event')
                                    ->options([
                                        'competition' => 'Kompetisi',
                                        'workshop' => 'Workshop',
                                        'seminar' => 'Seminar',
                                        'gathering' => 'Gathering',
                                        'festival' => 'Festival',
                                    ])
                                    ->default('competition')
                                    ->required(),

                                Forms\Components\Select::make('competition_type')
                                    ->label('Tingkat Kompetisi')
                                    ->options([
                                        'internal' => 'Internal',
                                        'external' => 'Eksternal',
                                        'regional' => 'Regional',
                                        'national' => 'Nasional',
                                        'international' => 'Internasional',
                                    ])
                                    ->default('external')
                                    ->required(),

                                Forms\Components\TextInput::make('sport_category')
                                    ->label('Kategori Olahraga')
                                    ->placeholder('Climbing, Hiking, SAR, dll'),
                            ]),

                            Grid::make(3)->schema([
                                Forms\Components\Select::make('participation_type')
                                    ->label('Tipe Partisipasi')
                                    ->options([
                                        'individual' => 'Individual',
                                        'team' => 'Tim',
                                        'both' => 'Individual & Tim',
                                    ])
                                    ->default('individual')
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'planned' => 'Direncanakan',
                                        'registration_open' => 'Pendaftaran Dibuka',
                                        'ongoing' => 'Berlangsung',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->default('planned')
                                    ->required(),

                                Forms\Components\Toggle::make('is_official_event')
                                    ->label('Event Resmi Mapala')
                                    ->default(false)
                                    ->inline(false),
                            ]),
                        ]),

                    Tabs\Tab::make('Penyelenggara & Lokasi')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('organizer')
                                    ->label('Penyelenggara')
                                    ->required()
                                    ->placeholder('Contoh: FPTI, MAPALA Indonesia'),

                                Forms\Components\Select::make('division_id')
                                    ->label('Divisi Pengelola')
                                    ->relationship('division', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Divisi yang mengelola event ini'),
                            ]),

                            Forms\Components\TextInput::make('location')
                                ->label('Lokasi')
                                ->required()
                                ->placeholder('Kota/Kabupaten'),

                            Forms\Components\Textarea::make('venue_details')
                                ->label('Detail Venue')
                                ->rows(3)
                                ->helperText('Alamat lengkap tempat penyelenggaraan'),

                            Forms\Components\Select::make('coordinator_id')
                                ->label('Koordinator Mapala')
                                ->relationship('coordinator', 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('Koordinator dari pihak Mapala'),

                            Forms\Components\Repeater::make('contact_persons')
                                ->label('Kontak Person')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama')
                                            ->required(),

                                        Forms\Components\TextInput::make('phone')
                                            ->label('Telepon')
                                            ->tel()
                                            ->required(),

                                        Forms\Components\TextInput::make('role')
                                            ->label('Jabatan')
                                            ->placeholder('Ketua Panitia, dll'),
                                    ]),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Kontak')
                                ->collapsible(),
                        ]),

                    Tabs\Tab::make('Jadwal & Pendaftaran')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make('Jadwal Pelaksanaan')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Forms\Components\DateTimePicker::make('start_date')
                                            ->label('Tanggal Mulai')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false),

                                        Forms\Components\DateTimePicker::make('end_date')
                                            ->label('Tanggal Selesai')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false)
                                            ->after('start_date'),

                                        Forms\Components\TextInput::make('duration_days')
                                            ->label('Durasi (hari)')
                                            ->numeric()
                                            ->disabled()
                                            ->helperText('Akan dihitung otomatis'),
                                    ]),
                                ]),

                            Section::make('Pendaftaran')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Forms\Components\DateTimePicker::make('registration_open')
                                            ->label('Buka Pendaftaran')
                                            ->native(false)
                                            ->seconds(false),

                                        Forms\Components\DateTimePicker::make('registration_close')
                                            ->label('Tutup Pendaftaran')
                                            ->native(false)
                                            ->seconds(false)
                                            ->after('registration_open'),

                                        Forms\Components\Select::make('registration_status')
                                            ->label('Status Pendaftaran')
                                            ->options([
                                                'open' => 'Terbuka',
                                                'closed' => 'Ditutup',
                                                'full' => 'Penuh',
                                            ])
                                            ->default('open'),
                                    ]),

                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('max_participants')
                                            ->label('Maks Peserta')
                                            ->numeric()
                                            ->minValue(1),

                                        Forms\Components\TextInput::make('min_participants')
                                            ->label('Min Peserta')
                                            ->numeric()
                                            ->minValue(1),
                                    ]),
                                ]),

                            Section::make('Link & URL')
                                ->schema([
                                    Forms\Components\TextInput::make('website_url')
                                        ->label('Website Event')
                                        ->url()
                                        ->placeholder('https://'),

                                    Forms\Components\TextInput::make('registration_url')
                                        ->label('Link Pendaftaran')
                                        ->url()
                                        ->placeholder('https://'),
                                ]),
                        ]),

                    Tabs\Tab::make('Biaya & Kategori')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make('Biaya Pendaftaran')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('registration_fee')
                                            ->label('Biaya Pendaftaran')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0),

                                        Forms\Components\Toggle::make('fee_covered_by_mapala')
                                            ->label('Ditanggung Mapala')
                                            ->default(false)
                                            ->inline(false)
                                            ->helperText('Biaya ditanggung organisasi'),
                                    ]),

                                    Forms\Components\Textarea::make('fee_details')
                                        ->label('Detail Biaya')
                                        ->rows(3)
                                        ->helperText('Rincian biaya yang harus dibayar'),
                                ]),

                            Section::make('Kategori & Kelas')
                                ->schema([
                                    Forms\Components\Repeater::make('categories')
                                        ->label('Kategori Lomba')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nama Kategori')
                                                    ->required()
                                                    ->placeholder('Contoh: Speed Climbing, Lead Climbing'),

                                                Forms\Components\TextInput::make('description')
                                                    ->label('Deskripsi')
                                                    ->placeholder('Keterangan singkat'),
                                            ]),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('Tambah Kategori')
                                        ->collapsible(),
                                ]),

                            Section::make('Hadiah & Penghargaan')
                                ->schema([
                                    Forms\Components\Repeater::make('prizes')
                                        ->label('Hadiah')
                                        ->schema([
                                            Grid::make(3)->schema([
                                                Forms\Components\TextInput::make('position')
                                                    ->label('Posisi')
                                                    ->required()
                                                    ->placeholder('Juara 1, 2, 3'),

                                                Forms\Components\TextInput::make('prize')
                                                    ->label('Hadiah')
                                                    ->required()
                                                    ->placeholder('Trophy, Uang, dll'),

                                                Forms\Components\TextInput::make('amount')
                                                    ->label('Nominal')
                                                    ->numeric()
                                                    ->prefix('Rp'),
                                            ]),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('Tambah Hadiah')
                                        ->collapsible(),
                                ]),
                        ]),

                    Tabs\Tab::make('Persyaratan')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\Repeater::make('requirements')
                                ->label('Persyaratan Peserta')
                                ->schema([
                                    Forms\Components\TextInput::make('requirement')
                                        ->label('Persyaratan')
                                        ->required(),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Persyaratan')
                                ->collapsible(),
                        ]),

                    Tabs\Tab::make('Laporan & Hasil')
                        ->icon('heroicon-o-trophy')
                        ->schema([
                            Forms\Components\RichEditor::make('event_report')
                                ->label('Laporan Event')
                                ->toolbarButtons([
                                    'bold',
                                    'bulletList',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'orderedList',
                                    'redo',
                                    'undo',
                                ]),

                            Forms\Components\Repeater::make('achievements_summary')
                                ->label('Ringkasan Prestasi')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('achievement')
                                            ->label('Prestasi')
                                            ->required(),

                                        Forms\Components\TextInput::make('winner')
                                            ->label('Pemenang'),
                                    ]),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Prestasi')
                                ->collapsible(),

                            Forms\Components\Repeater::make('highlights')
                                ->label('Highlight Event')
                                ->schema([
                                    Forms\Components\TextInput::make('highlight')
                                        ->label('Highlight')
                                        ->required(),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Highlight')
                                ->collapsible(),

                            Forms\Components\DateTimePicker::make('completed_at')
                                ->label('Tanggal Selesai')
                                ->native(false)
                                ->seconds(false)
                                ->visible(fn (Forms\Get $get) => $get('status') === 'completed'),
                        ]),

                    Tabs\Tab::make('Media & Dokumentasi')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('posters')
                                ->label('Poster Event')
                                ->collection('posters')
                                ->multiple()
                                ->maxFiles(5)
                                ->image()
                                ->imageEditor(),

                            SpatieMediaLibraryFileUpload::make('photos')
                                ->label('Foto Dokumentasi')
                                ->collection('photos')
                                ->multiple()
                                ->maxFiles(50)
                                ->image()
                                ->imageEditor(),

                            SpatieMediaLibraryFileUpload::make('certificates')
                                ->label('Sertifikat/Piagam')
                                ->collection('certificates')
                                ->multiple()
                                ->maxFiles(20)
                                ->acceptedFileTypes(['image/*', 'application/pdf']),

                            SpatieMediaLibraryFileUpload::make('documents')
                                ->label('Dokumen')
                                ->collection('documents')
                                ->multiple()
                                ->maxFiles(10)
                                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),

                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan')
                                ->rows(4),
                        ]),
                ]),
        ];
    }
}
