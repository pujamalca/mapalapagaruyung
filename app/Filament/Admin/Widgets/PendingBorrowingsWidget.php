<?php

namespace App\Filament\Admin\Widgets;

use App\Models\EquipmentBorrowing;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingBorrowingsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Peminjaman yang Perlu Diproses')
            ->query(
                EquipmentBorrowing::query()
                    ->where(function ($query) {
                        $query->where('status', 'pending')
                            ->orWhere(function ($q) {
                                $q->where('status', '!=', 'returned')
                                    ->where('due_date', '<', now());
                            });
                    })
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('borrowing_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('equipment.name')
                    ->label('Peralatan')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('quantity_borrowed')
                    ->label('Jumlah')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state, EquipmentBorrowing $record): string => {
                        if ($record->isOverdue()) {
                            return 'danger';
                        }
                        return match ($state) {
                            'pending' => 'warning',
                            'approved' => 'info',
                            'active' => 'success',
                            'returned' => 'success',
                            'cancelled' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->formatStateUsing(fn (string $state, EquipmentBorrowing $record): string => {
                        if ($record->isOverdue()) {
                            return 'Terlambat ' . $record->getDaysLate() . ' hari';
                        }
                        return match ($state) {
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'active' => 'Sedang Dipinjam',
                            'returned' => 'Dikembalikan',
                            'cancelled' => 'Dibatalkan',
                            default => $state,
                        };
                    }),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn (EquipmentBorrowing $record): string =>
                        $record->isOverdue() ? 'danger' : 'gray'
                    ),

                Tables\Columns\TextColumn::make('late_penalty')
                    ->label('Denda')
                    ->money('IDR')
                    ->alignEnd()
                    ->visible(fn (EquipmentBorrowing $record): bool => $record->late_penalty > 0),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (EquipmentBorrowing $record): string =>
                        route('filament.admin.resources.equipment-borrowings.equipment-borrowings.edit', $record)
                    )
                    ->icon('heroicon-o-eye'),
            ])
            ->emptyStateHeading('Tidak ada peminjaman yang perlu diproses')
            ->emptyStateDescription('Semua peminjaman sudah diproses dengan baik!')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated([10]);
    }
}
