<?php

namespace App\Filament\Admin\Resources\Equipment\Schemas;

use Filament\Forms;

class EquipmentForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Tabs::make('Equipment')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Kode Peralatan')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('TND-001, CAR-025')
                                    ->helperText('Kode unik untuk identifikasi peralatan'),

                                Forms\Components\Select::make('equipment_category_id')
                                    ->label('Kategori')
                                    ->relationship('equipmentCategory', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Kategori')
                                            ->required(),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Deskripsi'),
                                    ]),
                            ]),

                            Forms\Components\TextInput::make('name')
                                ->label('Nama Peralatan')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('brand')
                                    ->label('Merek')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('model')
                                    ->label('Model/Tipe')
                                    ->maxLength(255),
                            ]),

                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah Total')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1),

                                Forms\Components\TextInput::make('quantity_available')
                                    ->label('Jumlah Tersedia')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(0),

                                Forms\Components\TextInput::make('unit')
                                    ->label('Satuan')
                                    ->default('unit')
                                    ->placeholder('unit, pasang, set'),
                            ]),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Tabs\Tab::make('Kondisi & Status')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Select::make('condition')
                                    ->label('Kondisi')
                                    ->options([
                                        'excellent' => 'Sangat Baik',
                                        'good' => 'Baik',
                                        'fair' => 'Cukup',
                                        'poor' => 'Buruk',
                                        'damaged' => 'Rusak',
                                    ])
                                    ->required()
                                    ->default('good')
                                    ->live(),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'available' => 'Tersedia',
                                        'borrowed' => 'Dipinjam',
                                        'maintenance' => 'Maintenance',
                                        'retired' => 'Tidak Digunakan',
                                    ])
                                    ->required()
                                    ->default('available')
                                    ->live(),
                            ]),

                            Forms\Components\Textarea::make('condition_notes')
                                ->label('Catatan Kondisi')
                                ->rows(3)
                                ->columnSpanFull()
                                ->helperText('Detail kondisi fisik, kerusakan, atau catatan penting lainnya'),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('storage_location')
                                    ->label('Lokasi Penyimpanan')
                                    ->maxLength(255)
                                    ->placeholder('Gudang A, Rak 2'),

                                Forms\Components\TextInput::make('storage_notes')
                                    ->label('Catatan Penyimpanan')
                                    ->maxLength(255),
                            ]),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\DatePicker::make('purchase_date')
                                    ->label('Tanggal Pembelian')
                                    ->displayFormat('d/m/Y'),

                                Forms\Components\TextInput::make('purchase_price')
                                    ->label('Harga Pembelian')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0'),
                            ]),
                        ]),

                    Forms\Components\Tabs\Tab::make('Maintenance')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\DatePicker::make('last_maintenance_date')
                                    ->label('Maintenance Terakhir')
                                    ->displayFormat('d/m/Y')
                                    ->maxDate(now()),

                                Forms\Components\DatePicker::make('next_maintenance_date')
                                    ->label('Maintenance Berikutnya')
                                    ->displayFormat('d/m/Y')
                                    ->minDate(now()),
                            ]),

                            Forms\Components\Textarea::make('maintenance_notes')
                                ->label('Catatan Maintenance')
                                ->rows(4)
                                ->columnSpanFull()
                                ->helperText('Riwayat maintenance, perbaikan, atau perawatan yang dilakukan'),

                            Forms\Components\TextInput::make('maintenance_interval_days')
                                ->label('Interval Maintenance (Hari)')
                                ->numeric()
                                ->suffix('hari')
                                ->helperText('Interval waktu untuk maintenance rutin'),
                        ]),

                    Forms\Components\Tabs\Tab::make('Spesifikasi')
                        ->icon('heroicon-o-list-bullet')
                        ->schema([
                            Forms\Components\KeyValue::make('specifications')
                                ->label('Spesifikasi Teknis')
                                ->keyLabel('Nama Spesifikasi')
                                ->valueLabel('Nilai')
                                ->addActionLabel('Tambah Spesifikasi')
                                ->helperText('Contoh: Kapasitas -> 60L, Berat -> 2.5kg, Material -> Nylon')
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Tabs\Tab::make('Peminjaman')
                        ->icon('heroicon-o-user-circle')
                        ->schema([
                            Forms\Components\Placeholder::make('borrowing_info')
                                ->label('Informasi Peminjaman')
                                ->content(fn ($record) => $record?->status === 'borrowed'
                                    ? 'Peralatan sedang dipinjam'
                                    : 'Peralatan tidak sedang dipinjam')
                                ->columnSpanFull(),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Select::make('current_borrower_id')
                                    ->label('Peminjam Saat Ini')
                                    ->relationship('currentBorrower', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->disabled(fn ($record) => $record?->status !== 'borrowed')
                                    ->helperText('Otomatis terisi saat approval peminjaman'),

                                Forms\Components\DatePicker::make('borrowed_until')
                                    ->label('Dipinjam Sampai')
                                    ->displayFormat('d/m/Y')
                                    ->disabled(fn ($record) => $record?->status !== 'borrowed')
                                    ->helperText('Otomatis terisi saat approval peminjaman'),
                            ]),

                            Forms\Components\Placeholder::make('borrowing_hint')
                                ->content('Gunakan menu Peminjaman Peralatan untuk mengelola proses peminjaman')
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Tabs\Tab::make('Media & Catatan')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\FileUpload::make('photos')
                                ->label('Foto Peralatan')
                                ->image()
                                ->multiple()
                                ->maxFiles(10)
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '16:9',
                                    '4:3',
                                    '1:1',
                                ])
                                ->directory('equipment-photos')
                                ->helperText('Upload foto peralatan (maksimal 10 foto)')
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('documents')
                                ->label('Dokumen Pendukung')
                                ->multiple()
                                ->maxFiles(5)
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->directory('equipment-documents')
                                ->helperText('Manual, warranty, atau dokumen terkait lainnya')
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan Tambahan')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}
