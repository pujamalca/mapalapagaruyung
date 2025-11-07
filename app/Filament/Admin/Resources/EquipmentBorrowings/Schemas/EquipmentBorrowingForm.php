<?php

namespace App\Filament\Admin\Resources\EquipmentBorrowings\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class EquipmentBorrowingForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('Borrowing')
                ->tabs([
                    Tab::make('Informasi Peminjaman')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\TextInput::make('borrowing_code')
                                ->label('Kode Peminjaman')
                                ->disabled()
                                ->dehydrated()
                                ->default(fn () => \App\Models\EquipmentBorrowing::generateBorrowingCode())
                                ->required(),

                            Grid::make(2)->schema([
                                Forms\Components\Select::make('equipment_id')
                                    ->label('Peralatan')
                                    ->relationship('equipment', 'name', function ($query) {
                                        return $query->where('status', 'available')
                                            ->where('quantity_available', '>', 0);
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $equipment = \App\Models\Equipment::find($state);
                                            if ($equipment) {
                                                $set('max_quantity', $equipment->quantity_available);
                                            }
                                        }
                                    })
                                    ->helperText('Hanya menampilkan peralatan yang tersedia'),

                                Forms\Components\Select::make('user_id')
                                    ->label('Peminjam')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Pilih anggota yang akan meminjam'),
                            ]),

                            Grid::make(3)->schema([
                                Forms\Components\TextInput::make('quantity_borrowed')
                                    ->label('Jumlah Dipinjam')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->live()
                                    ->helperText(fn (Forms\Get $get) =>
                                        $get('equipment_id')
                                            ? 'Max: ' . \App\Models\Equipment::find($get('equipment_id'))?->quantity_available
                                            : 'Pilih peralatan terlebih dahulu'
                                    ),

                                Forms\Components\DatePicker::make('borrow_date')
                                    ->label('Tanggal Pinjam')
                                    ->required()
                                    ->default(now())
                                    ->displayFormat('d/m/Y')
                                    ->live(),

                                Forms\Components\DatePicker::make('due_date')
                                    ->label('Tanggal Kembali')
                                    ->required()
                                    ->minDate(fn (Forms\Get $get) => $get('borrow_date') ?? now())
                                    ->displayFormat('d/m/Y')
                                    ->helperText('Tanggal jatuh tempo pengembalian'),
                            ]),

                            Forms\Components\Select::make('purpose')
                                ->label('Tujuan Peminjaman')
                                ->options([
                                    'expedition' => 'Ekspedisi',
                                    'training' => 'Pelatihan',
                                    'competition' => 'Kompetisi',
                                    'event' => 'Event/Kegiatan',
                                    'personal' => 'Pribadi',
                                    'other' => 'Lainnya',
                                ])
                                ->required()
                                ->live(),

                            Forms\Components\Textarea::make('purpose_details')
                                ->label('Detail Tujuan')
                                ->rows(3)
                                ->columnSpanFull()
                                ->helperText('Jelaskan tujuan peminjaman secara detail'),
                        ]),

                    Tab::make('Kondisi & Catatan')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->schema([
                            Section::make('Kondisi Saat Dipinjam')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\Select::make('condition_borrowed')
                                            ->label('Kondisi Saat Dipinjam')
                                            ->options([
                                                'excellent' => 'Sangat Baik',
                                                'good' => 'Baik',
                                                'fair' => 'Cukup',
                                                'poor' => 'Buruk',
                                            ])
                                            ->default('good')
                                            ->helperText('Catat kondisi peralatan sebelum dipinjam'),

                                        Forms\Components\Placeholder::make('info_kondisi')
                                            ->label('Informasi')
                                            ->content('Pastikan memeriksa kondisi peralatan sebelum diserahkan'),
                                    ]),

                                    Forms\Components\Textarea::make('condition_notes_borrowed')
                                        ->label('Catatan Kondisi')
                                        ->rows(3)
                                        ->columnSpanFull()
                                        ->helperText('Catat detail kondisi, kerusakan kecil, atau catatan penting lainnya'),
                                ]),

                            Section::make('Catatan Tambahan')
                                ->schema([
                                    Forms\Components\Textarea::make('borrower_notes')
                                        ->label('Catatan dari Peminjam')
                                        ->rows(3)
                                        ->columnSpanFull(),

                                    Forms\Components\Textarea::make('admin_notes')
                                        ->label('Catatan dari Admin')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tab::make('Approval & Status')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'pending' => 'Menunggu Persetujuan',
                                    'approved' => 'Disetujui',
                                    'active' => 'Sedang Dipinjam',
                                    'returned' => 'Sudah Dikembalikan',
                                    'overdue' => 'Terlambat',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->default('pending')
                                ->disabled(fn ($record) => $record !== null)
                                ->helperText('Status akan diupdate otomatis saat approval'),

                            Grid::make(2)->schema([
                                Forms\Components\Select::make('approved_by')
                                    ->label('Disetujui Oleh')
                                    ->relationship('approver', 'name')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\DateTimePicker::make('approved_at')
                                    ->label('Tanggal Disetujui')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->displayFormat('d/m/Y H:i'),
                            ]),

                            Forms\Components\Placeholder::make('approval_info')
                                ->label('Informasi Approval')
                                ->content('Gunakan tombol action untuk approve atau reject peminjaman')
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Pengembalian')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->schema([
                            Forms\Components\DatePicker::make('return_date')
                                ->label('Tanggal Dikembalikan')
                                ->displayFormat('d/m/Y')
                                ->disabled()
                                ->dehydrated(false),

                            Grid::make(2)->schema([
                                Forms\Components\Select::make('condition_returned')
                                    ->label('Kondisi Saat Dikembalikan')
                                    ->options([
                                        'excellent' => 'Sangat Baik',
                                        'good' => 'Baik',
                                        'fair' => 'Cukup',
                                        'poor' => 'Buruk',
                                        'damaged' => 'Rusak',
                                    ])
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\TextInput::make('days_late')
                                    ->label('Hari Terlambat')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->suffix('hari'),
                            ]),

                            Forms\Components\Textarea::make('condition_notes_returned')
                                ->label('Catatan Kondisi Pengembalian')
                                ->rows(3)
                                ->columnSpanFull()
                                ->disabled()
                                ->dehydrated(false),

                            Section::make('Denda & Kerusakan')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\Toggle::make('is_late')
                                            ->label('Terlambat?')
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\TextInput::make('penalty_amount')
                                            ->label('Jumlah Denda')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->dehydrated(false),
                                    ]),

                                    Grid::make(2)->schema([
                                        Forms\Components\Toggle::make('is_damaged')
                                            ->label('Ada Kerusakan?')
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\TextInput::make('damage_cost')
                                            ->label('Biaya Perbaikan')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->dehydrated(false),
                                    ]),

                                    Forms\Components\Textarea::make('damage_description')
                                        ->label('Deskripsi Kerusakan')
                                        ->rows(3)
                                        ->columnSpanFull()
                                        ->disabled()
                                        ->dehydrated(false),
                                ]),

                            Forms\Components\Placeholder::make('return_info')
                                ->label('Informasi Pengembalian')
                                ->content('Gunakan tombol action untuk memproses pengembalian peralatan')
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}

