<?php

namespace App\Filament\Admin\Resources\RecruitmentPeriods\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;

class RecruitmentPeriodForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('Recruitment Period')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Periode')
                                    ->placeholder('Open Recruitment Kader XXIV 2025/2026')
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
                                    ->unique(ignoreRecord: true)
                                    ->helperText('URL-friendly identifier'),
                            ]),

                            Forms\Components\Select::make('cohort_id')
                                ->label('Target Angkatan')
                                ->relationship('cohort', 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('Angkatan tujuan untuk pendaftar yang diterima'),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(3)
                                ->maxLength(1000)
                                ->helperText('Deskripsi singkat tentang periode recruitment ini'),

                            Grid::make(2)->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'open' => 'Terbuka',
                                        'selection' => 'Seleksi',
                                        'closed' => 'Ditutup',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->live(),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(false)
                                    ->inline(false)
                                    ->helperText('Hanya 1 periode yang boleh aktif'),
                            ]),
                        ]),

                    Tabs\Tab::make('Jadwal')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make('Periode Pendaftaran')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\DateTimePicker::make('registration_start')
                                            ->label('Pendaftaran Dibuka')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false)
                                            ->default(now()),

                                        Forms\Components\DateTimePicker::make('registration_end')
                                            ->label('Pendaftaran Ditutup')
                                            ->required()
                                            ->native(false)
                                            ->seconds(false)
                                            ->after('registration_start'),
                                    ]),
                                ]),

                            Section::make('Periode Seleksi')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\DateTimePicker::make('selection_start')
                                            ->label('Seleksi Dimulai')
                                            ->native(false)
                                            ->seconds(false)
                                            ->after('registration_end'),

                                        Forms\Components\DateTimePicker::make('selection_end')
                                            ->label('Seleksi Selesai')
                                            ->native(false)
                                            ->seconds(false)
                                            ->after('selection_start'),
                                    ]),

                                    Forms\Components\DateTimePicker::make('announcement_date')
                                        ->label('Tanggal Pengumuman')
                                        ->native(false)
                                        ->seconds(false)
                                        ->after('selection_end'),
                                ]),
                        ]),

                    Tabs\Tab::make('Kuota & Biaya')
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            Section::make('Kuota Pendaftar')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('max_applicants')
                                            ->label('Maksimal Pendaftar')
                                            ->numeric()
                                            ->minValue(1)
                                            ->helperText('Kosongkan untuk unlimited'),

                                        Forms\Components\TextInput::make('target_accepted')
                                            ->label('Target Diterima')
                                            ->numeric()
                                            ->minValue(1)
                                            ->helperText('Target jumlah anggota yang akan diterima'),
                                    ]),
                                ]),

                            Section::make('Biaya Pendaftaran')
                                ->schema([
                                    Forms\Components\TextInput::make('registration_fee')
                                        ->label('Biaya Pendaftaran')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->default(0)
                                        ->minValue(0)
                                        ->helperText('Masukkan 0 jika gratis'),

                                    Forms\Components\RichEditor::make('payment_instructions')
                                        ->label('Instruksi Pembayaran')
                                        ->toolbarButtons([
                                            'bold',
                                            'bulletList',
                                            'italic',
                                            'orderedList',
                                            'redo',
                                            'undo',
                                        ])
                                        ->helperText('Instruksi cara pembayaran dan upload bukti'),
                                ]),
                        ]),

                    Tabs\Tab::make('Persyaratan')
                        ->icon('heroicon-o-document-check')
                        ->schema([
                            Forms\Components\Repeater::make('requirements')
                                ->label('Daftar Persyaratan')
                                ->schema([
                                    Forms\Components\TextInput::make('requirement')
                                        ->label('Persyaratan')
                                        ->required()
                                        ->placeholder('Contoh: Mahasiswa aktif Universitas Andalas'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Persyaratan')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['requirement'] ?? null),

                            Forms\Components\Repeater::make('selection_stages')
                                ->label('Tahapan Seleksi')
                                ->schema([
                                    Forms\Components\TextInput::make('stage')
                                        ->label('Nama Tahap')
                                        ->required()
                                        ->placeholder('Contoh: Wawancara, Praktik Lapangan'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('Tambah Tahap Seleksi')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['stage'] ?? null)
                                ->helperText('Tahapan seleksi yang akan dilalui pendaftar'),
                        ]),

                    Tabs\Tab::make('Formulir Kustom')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\Placeholder::make('form_fields_info')
                                ->label('')
                                ->content('Field formulir kustom akan dikonfigurasi di sini. Untuk sementara, formulir menggunakan field default.'),

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
