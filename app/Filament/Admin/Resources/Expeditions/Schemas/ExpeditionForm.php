<?php

namespace App\Filament\Admin\Resources\Expeditions\Schemas;

use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;

class ExpeditionForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('Expedition')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Ekspedisi')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', \Illuminate\Support\Str::slug($state))
                                    )
                                    ->placeholder('Contoh: Pendakian Gunung Kerinci'),

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
                                ->helperText('Deskripsi singkat tentang ekspedisi'),

                            Grid::make(3)->schema([
                                Forms\Components\Select::make('expedition_type')
                                    ->label('Jenis Ekspedisi')
                                    ->options([
                                        'hiking' => 'Hiking/Pendakian',
                                        'climbing' => 'Panjat Tebing',
                                        'caving' => 'Penelusuran Gua',
                                        'rafting' => 'Arung Jeram',
                                        'expedition' => 'Ekspedisi',
                                        'conservation' => 'Konservasi',
                                        'research' => 'Riset',
                                        'other' => 'Lainnya',
                                    ])
                                    ->default('hiking')
                                    ->required(),

                                Forms\Components\Select::make('difficulty_level')
                                    ->label('Tingkat Kesulitan')
                                    ->options([
                                        'easy' => 'Mudah',
                                        'moderate' => 'Sedang',
                                        'hard' => 'Sulit',
                                        'extreme' => 'Ekstrim',
                                    ])
                                    ->default('moderate')
                                    ->required()
                                    ->helperText('Tingkat kesulitan fisik & teknis'),

                                Forms\Components\Select::make('division_id')
                                    ->label('Divisi')
                                    ->relationship('division', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Divisi penyelenggara'),
                            ]),

                            Grid::make(2)->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'planned' => 'Direncanakan',
                                        'preparing' => 'Persiapan',
                                        'ongoing' => 'Berlangsung',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->default('planned')
                                    ->required()
                                    ->live(),

                                Forms\Components\Toggle::make('is_official')
                                    ->label('Ekspedisi Resmi')
                                    ->default(true)
                                    ->inline(false)
                                    ->helperText('Ekspedisi resmi Mapala'),
                            ]),
                        ]),

                    Tabs\Tab::make('Destinasi & Rute')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Section::make('Destinasi')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('destination')
                                            ->label('Tujuan')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Contoh: Gunung Kerinci'),

                                        Forms\Components\TextInput::make('location')
                                            ->label('Lokasi')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Contoh: Jambi, Sumatera'),
                                    ]),

                                    Forms\Components\Textarea::make('route_description')
                                        ->label('Deskripsi Rute')
                                        ->rows(4)
                                        ->helperText('Penjelasan jalur yang akan ditempuh'),
                                ]),

                            Section::make('Detail Rute')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('distance_km')
                                            ->label('Jarak (km)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix('km')
                                            ->helperText('Total jarak tempuh'),

                                        Forms\Components\TextInput::make('elevation_gain_m')
                                            ->label('Elevasi Gain (m)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix('mdpl')
                                            ->helperText('Total kenaikan ketinggian'),

                                        Forms\Components\TextInput::make('duration_days')
                                            ->label('Durasi (hari)')
                                            ->numeric()
                                            ->minValue(1)
                                            ->suffix('hari')
                                            ->helperText('Akan dihitung otomatis'),
                                    ]),

                                    Forms\Components\Repeater::make('checkpoints')
                                        ->label('Pos/Checkpoint')
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->label('Nama Pos')
                                                ->required()
                                                ->placeholder('Contoh: Pos 1, Camp 1'),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('Tambah Checkpoint')
                                        ->reorderable()
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                                ]),

                            Section::make('Cuaca & Kondisi')
                                ->schema([
                                    Forms\Components\TextInput::make('best_season')
                                        ->label('Musim Terbaik')
                                        ->maxLength(255)
                                        ->placeholder('Contoh: April - September'),

                                    Forms\Components\Textarea::make('weather_notes')
                                        ->label('Catatan Cuaca')
                                        ->rows(3)
                                        ->helperText('Informasi cuaca dan kondisi alam'),
                                ]),
                        ]),

                    Tabs\Tab::make('Jadwal & Peserta')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make('Jadwal')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\DateTimePicker::make('start_date')
                                            ->label('Tanggal Mulai')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false)
                                            ->default(now()->addWeek()),

                                        Forms\Components\DateTimePicker::make('end_date')
                                            ->label('Tanggal Selesai')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false)
                                            ->after('start_date'),
                                    ]),
                                ]),

                            Section::make('Pimpinan Ekspedisi')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\Select::make('leader_id')
                                            ->label('Ketua Ekspedisi')
                                            ->relationship('leader', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Pilih dari member Mapala'),

                                        Forms\Components\TextInput::make('leader_name')
                                            ->label('Nama Ketua (Eksternal)')
                                            ->maxLength(255)
                                            ->helperText('Jika ketua dari luar Mapala'),
                                    ]),
                                ]),

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
                                            ->default(4)
                                            ->minValue(1)
                                            ->helperText('Minimal peserta agar ekspedisi jalan'),

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

                                    Forms\Components\DateTimePicker::make('registration_deadline')
                                        ->label('Batas Pendaftaran')
                                        ->native(false)
                                        ->seconds(false)
                                        ->helperText('Batas akhir pendaftaran peserta'),
                                ]),
                        ]),

                    Tabs\Tab::make('Biaya & Kebutuhan')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make('Biaya')
                                ->schema([
                                    Forms\Components\TextInput::make('estimated_cost_per_person')
                                        ->label('Estimasi Biaya per Orang')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->default(0)
                                        ->minValue(0),

                                    Forms\Components\Textarea::make('cost_breakdown')
                                        ->label('Rincian Biaya')
                                        ->rows(4)
                                        ->helperText('Detail pengeluaran (transportasi, konsumsi, etc)'),
                                ]),

                            Section::make('Persyaratan')
                                ->schema([
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

                                    Forms\Components\Repeater::make('equipment_list')
                                        ->label('Peralatan yang Dibutuhkan')
                                        ->schema([
                                            Forms\Components\TextInput::make('equipment')
                                                ->label('Peralatan')
                                                ->required()
                                                ->placeholder('Contoh: Carrier, Sleeping bag, Kompas'),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('Tambah Peralatan')
                                        ->reorderable()
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['equipment'] ?? null),
                                ]),

                            Section::make('Kontak Darurat')
                                ->schema([
                                    Forms\Components\Repeater::make('emergency_contacts')
                                        ->label('Kontak Darurat')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nama')
                                                    ->required(),

                                                Forms\Components\TextInput::make('phone')
                                                    ->label('Nomor Telepon')
                                                    ->tel()
                                                    ->required(),
                                            ]),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('Tambah Kontak')
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                                ]),
                        ]),

                    Tabs\Tab::make('Laporan & Dokumentasi')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\RichEditor::make('trip_report')
                                ->label('Laporan Ekspedisi')
                                ->toolbarButtons([
                                    'bold',
                                    'bulletList',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'orderedList',
                                    'redo',
                                    'undo',
                                ])
                                ->helperText('Laporan lengkap setelah ekspedisi selesai'),

                            Forms\Components\Repeater::make('highlights')
                                ->label('Highlight & Pencapaian')
                                ->schema([
                                    Forms\Components\TextInput::make('highlight')
                                        ->label('Highlight')
                                        ->required()
                                        ->placeholder('Contoh: Berhasil mencapai puncak'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Highlight')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['highlight'] ?? null),

                            Forms\Components\Repeater::make('challenges')
                                ->label('Tantangan & Kendala')
                                ->schema([
                                    Forms\Components\TextInput::make('challenge')
                                        ->label('Tantangan')
                                        ->required()
                                        ->placeholder('Contoh: Cuaca buruk di hari ke-2'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Tantangan')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['challenge'] ?? null),

                            Forms\Components\Repeater::make('lessons_learned')
                                ->label('Pelajaran & Rekomendasi')
                                ->schema([
                                    Forms\Components\TextInput::make('lesson')
                                        ->label('Pelajaran')
                                        ->required()
                                        ->placeholder('Contoh: Perlu persiapan lebih matang'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Pelajaran')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['lesson'] ?? null),

                            Forms\Components\DateTimePicker::make('completed_at')
                                ->label('Tanggal Selesai')
                                ->native(false)
                                ->seconds(false)
                                ->visible(fn (Forms\Get $get) => $get('status') === 'completed'),
                        ]),

                    Tabs\Tab::make('Media & Catatan')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('route_maps')
                                ->label('Peta Rute')
                                ->collection('route_maps')
                                ->multiple()
                                ->maxFiles(5)
                                ->acceptedFileTypes(['image/*', 'application/pdf'])
                                ->helperText('Upload peta rute atau jalur ekspedisi'),

                            SpatieMediaLibraryFileUpload::make('photos')
                                ->label('Foto Dokumentasi')
                                ->collection('photos')
                                ->multiple()
                                ->maxFiles(50)
                                ->image()
                                ->imageEditor()
                                ->helperText('Upload foto kegiatan ekspedisi'),

                            SpatieMediaLibraryFileUpload::make('documents')
                                ->label('Dokumen')
                                ->collection('documents')
                                ->multiple()
                                ->maxFiles(10)
                                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->helperText('Upload proposal, perizinan, dll'),

                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan')
                                ->rows(4)
                                ->helperText('Catatan tambahan tentang ekspedisi'),

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
