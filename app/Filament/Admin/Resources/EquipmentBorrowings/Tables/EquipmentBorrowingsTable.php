<?php

namespace App\Filament\Admin\Resources\EquipmentBorrowings\Tables;

use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class EquipmentBorrowingsTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('borrowing_code')
                ->label('Kode')
                ->searchable()
                ->sortable()
                ->copyable()
                ->weight('medium'),

            Tables\Columns\TextColumn::make('equipment.name')
                ->label('Peralatan')
                ->searchable()
                ->sortable()
                ->limit(25)
                ->tooltip(fn ($record) => $record->equipment->name),

            Tables\Columns\TextColumn::make('user.name')
                ->label('Peminjam')
                ->searchable()
                ->sortable()
                ->limit(20),

            Tables\Columns\TextColumn::make('borrow_date')
                ->label('Tanggal Pinjam')
                ->date('d/m/Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('due_date')
                ->label('Jatuh Tempo')
                ->date('d/m/Y')
                ->sortable()
                ->color(fn ($record) => $record->isOverdue() && $record->status !== 'returned' ? 'danger' : 'gray'),

            Tables\Columns\TextColumn::make('return_date')
                ->label('Tgl. Kembali')
                ->sortable()
                ->toggleable()
                ->formatStateUsing(fn ($state) => $state instanceof \DateTimeInterface ? $state->format('d/m/Y') : '-'),

            Tables\Columns\TextColumn::make('quantity_borrowed')
                ->label('Jumlah')
                ->alignCenter()
                ->sortable(),

            Tables\Columns\TextColumn::make('purpose')
                ->label('Tujuan')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'expedition' => 'Ekspedisi',
                    'training' => 'Pelatihan',
                    'competition' => 'Kompetisi',
                    'event' => 'Event',
                    'personal' => 'Pribadi',
                    'other' => 'Lainnya',
                    default => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'expedition' => 'success',
                    'training' => 'info',
                    'competition' => 'warning',
                    'event' => 'primary',
                    default => 'gray',
                })
                ->toggleable(),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn ($record): string => $record->getStatusLabelAttribute())
                ->color(fn ($record): string => $record->getStatusColorAttribute())
                ->sortable(),

            Tables\Columns\IconColumn::make('is_late')
                ->label('Terlambat')
                ->boolean()
                ->toggleable(),

            Tables\Columns\TextColumn::make('penalty_amount')
                ->label('Denda')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),

            Tables\Columns\TextColumn::make('approver.name')
                ->label('Disetujui Oleh')
                ->default('-')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('approved_at')
                ->label('Tgl. Approval')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Menunggu Persetujuan',
                    'approved' => 'Disetujui',
                    'active' => 'Sedang Dipinjam',
                    'returned' => 'Sudah Dikembalikan',
                    'overdue' => 'Terlambat',
                    'cancelled' => 'Dibatalkan',
                ])
                ->multiple(),

            Tables\Filters\SelectFilter::make('equipment_id')
                ->label('Peralatan')
                ->relationship('equipment', 'name')
                ->searchable()
                ->preload()
                ->multiple(),

            Tables\Filters\SelectFilter::make('user_id')
                ->label('Peminjam')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->multiple(),

            Tables\Filters\SelectFilter::make('purpose')
                ->label('Tujuan')
                ->options([
                    'expedition' => 'Ekspedisi',
                    'training' => 'Pelatihan',
                    'competition' => 'Kompetisi',
                    'event' => 'Event/Kegiatan',
                    'personal' => 'Pribadi',
                    'other' => 'Lainnya',
                ])
                ->multiple(),

            Tables\Filters\Filter::make('overdue')
                ->label('Terlambat')
                ->query(fn (Builder $query): Builder => $query
                    ->where('status', '!=', 'returned')
                    ->where('due_date', '<', now())
                ),

            Tables\Filters\Filter::make('has_penalty')
                ->label('Ada Denda')
                ->query(fn (Builder $query): Builder => $query->where('penalty_amount', '>', 0)),

            Tables\Filters\Filter::make('damaged')
                ->label('Ada Kerusakan')
                ->query(fn (Builder $query): Builder => $query->where('is_damaged', true)),

            Tables\Filters\Filter::make('borrow_date')
                ->form([
                    Forms\Components\DatePicker::make('borrowed_from')
                        ->label('Dari Tanggal'),
                    Forms\Components\DatePicker::make('borrowed_until')
                        ->label('Sampai Tanggal'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['borrowed_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('borrow_date', '>=', $date),
                        )
                        ->when(
                            $data['borrowed_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('borrow_date', '<=', $date),
                        );
                }),
        ];
    }

    public static function actions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->status === 'pending'),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman')
                    ->modalDescription(fn ($record) => "Setujui peminjaman {$record->equipment->name} oleh {$record->user->name}?")
                    ->action(function ($record) {
                        $record->approve(auth()->id());
                        $record->activate();
                    })
                    ->successNotificationTitle('Peminjaman disetujui'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Peminjaman')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'cancelled',
                            'admin_notes' => 'Ditolak: ' . $data['rejection_reason'],
                        ]);
                    })
                    ->successNotificationTitle('Peminjaman ditolak'),

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
                            ->default('good')
                            ->live(),

                        Forms\Components\Textarea::make('condition_notes_returned')
                            ->label('Catatan Kondisi')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_damaged')
                            ->label('Ada Kerusakan?')
                            ->live()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('damage_description')
                            ->label('Deskripsi Kerusakan')
                            ->rows(2)
                            ->visible(fn (Get $get) => $get('is_damaged'))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('damage_cost')
                            ->label('Biaya Perbaikan')
                            ->numeric()
                            ->prefix('Rp')
                            ->visible(fn (Get $get) => $get('is_damaged')),

                        Forms\Components\Placeholder::make('penalty_info')
                            ->label('Informasi Denda')
                            ->content(function ($record) {
                                if ($record->isOverdue()) {
                                    $days = $record->getDaysLate();
                                    $penalty = $record->calculatePenalty();
                                    return "Terlambat {$days} hari. Denda: Rp " . number_format($penalty, 0, ',', '.');
                                }
                                return 'Tidak ada denda keterlambatan';
                            })
                            ->columnSpanFull(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->returnEquipment($data);
                    })
                    ->successNotificationTitle('Peralatan berhasil dikembalikan'),

                Tables\Actions\Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'approved']))
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Peminjaman')
                    ->action(function ($record) {
                        $record->cancel();
                    })
                    ->successNotificationTitle('Peminjaman dibatalkan'),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'cancelled'])),
            ]),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\BulkAction::make('approve_selected')
                    ->label('Setujui Terpilih')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            if ($record->status === 'pending') {
                                $record->approve(auth()->id());
                                $record->activate();
                            }
                        }
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
