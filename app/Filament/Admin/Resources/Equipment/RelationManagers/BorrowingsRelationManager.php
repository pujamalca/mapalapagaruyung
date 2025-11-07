<?php

namespace App\Filament\Admin\Resources\Equipment\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;

class BorrowingsRelationManager extends RelationManager
{
    protected static string $relationship = 'equipmentBorrowings';

    protected static ?string $title = 'Riwayat Peminjaman';

    protected static ?string $recordTitleAttribute = 'borrowing_code';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('borrowing_code')
                    ->label('Kode Peminjaman')
                    ->disabled()
                    ->default(fn () => \App\Models\EquipmentBorrowing::generateBorrowingCode()),

                Forms\Components\Select::make('user_id')
                    ->label('Peminjam')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Grid::make(2)->schema([
                    Forms\Components\DatePicker::make('borrow_date')
                        ->label('Tanggal Pinjam')
                        ->required()
                        ->default(now())
                        ->displayFormat('d/m/Y'),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Tanggal Kembali')
                        ->required()
                        ->minDate(fn ($get) => $get('borrow_date') ?? now())
                        ->displayFormat('d/m/Y'),
                ]),

                Forms\Components\TextInput::make('quantity_borrowed')
                    ->label('Jumlah Dipinjam')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1),

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
                    ->rows(2)
                    ->columnSpanFull(),

                Forms\Components\Select::make('condition_borrowed')
                    ->label('Kondisi Saat Dipinjam')
                    ->options([
                        'excellent' => 'Sangat Baik',
                        'good' => 'Baik',
                        'fair' => 'Cukup',
                        'poor' => 'Buruk',
                    ])
                    ->default('good'),

                Forms\Components\Textarea::make('condition_notes_borrowed')
                    ->label('Catatan Kondisi')
                    ->rows(2)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('borrower_notes')
                    ->label('Catatan Peminjam')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('borrowing_code')
            ->columns([
                Tables\Columns\TextColumn::make('borrowing_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Tanggal Kembali')
                    ->date('d/m/Y')
                    ->sortable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('quantity_borrowed')
                    ->label('Jumlah')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record): string => $record->getStatusLabelAttribute())
                    ->color(fn ($record): string => $record->getStatusColorAttribute()),

                Tables\Columns\IconColumn::make('is_late')
                    ->label('Terlambat')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('penalty_amount')
                    ->label('Denda')
                    ->money('IDR')
                    ->toggleable()
                    ->default('Rp 0'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'active' => 'Sedang Dipinjam',
                        'returned' => 'Sudah Dikembalikan',
                        'overdue' => 'Terlambat',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Peminjaman')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['equipment_id'] = $this->ownerRecord->id;
                        $data['status'] = 'pending';
                        $data['borrowing_code'] = \App\Models\EquipmentBorrowing::generateBorrowingCode();
                        return $data;
                    })
                    ->after(function () {
                        // Notification or additional logic can be added here
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->approve(auth()->id());
                            $record->activate();
                        })
                        ->successNotificationTitle('Peminjaman disetujui'),

                    Tables\Actions\Action::make('return')
                        ->label('Kembalikan')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('warning')
                        ->visible(fn ($record) => in_array($record->status, ['approved', 'active']))
                        ->form([
                            Forms\Components\Select::make('condition_returned')
                                ->label('Kondisi Saat Dikembalikan')
                                ->options([
                                    'excellent' => 'Sangat Baik',
                                    'good' => 'Baik',
                                    'fair' => 'Cukup',
                                    'poor' => 'Buruk',
                                    'damaged' => 'Rusak',
                                ])
                                ->required()
                                ->live(),

                            Forms\Components\Textarea::make('condition_notes_returned')
                                ->label('Catatan Kondisi')
                                ->rows(2),

                            Forms\Components\Toggle::make('is_damaged')
                                ->label('Ada Kerusakan?')
                                ->live(),

                            Forms\Components\Textarea::make('damage_description')
                                ->label('Deskripsi Kerusakan')
                                ->rows(2)
                                ->visible(fn ($get) => $get('is_damaged')),

                            Forms\Components\TextInput::make('damage_cost')
                                ->label('Biaya Perbaikan')
                                ->numeric()
                                ->prefix('Rp')
                                ->visible(fn ($get) => $get('is_damaged')),
                        ])
                        ->action(function ($record, array $data) {
                            $record->returnEquipment($data);
                        })
                        ->successNotificationTitle('Peralatan dikembalikan'),

                    Tables\Actions\Action::make('cancel')
                        ->label('Batalkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => in_array($record->status, ['pending', 'approved']))
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->cancel();
                        })
                        ->successNotificationTitle('Peminjaman dibatalkan'),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
