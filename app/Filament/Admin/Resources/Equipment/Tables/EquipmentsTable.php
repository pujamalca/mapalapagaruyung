<?php

namespace App\Filament\Admin\Resources\Equipment\Tables;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class EquipmentsTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('code')
                ->label('Kode')
                ->searchable()
                ->sortable()
                ->copyable()
                ->weight('medium'),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Peralatan')
                ->searchable()
                ->sortable()
                ->limit(30)
                ->tooltip(fn ($record) => $record->name),

            Tables\Columns\TextColumn::make('equipmentCategory.name')
                ->label('Kategori')
                ->badge()
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('brand')
                ->label('Merek')
                ->searchable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('quantity')
                ->label('Jumlah')
                ->alignCenter()
                ->sortable(),

            Tables\Columns\TextColumn::make('quantity_available')
                ->label('Tersedia')
                ->alignCenter()
                ->sortable()
                ->badge()
                ->color(fn ($state, $record) => $state === 0 ? 'danger' : ($state < $record->quantity / 2 ? 'warning' : 'success')),

            Tables\Columns\TextColumn::make('condition')
                ->label('Kondisi')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'excellent' => 'Sangat Baik',
                    'good' => 'Baik',
                    'fair' => 'Cukup',
                    'poor' => 'Buruk',
                    'damaged' => 'Rusak',
                    default => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'excellent' => 'success',
                    'good' => 'info',
                    'fair' => 'warning',
                    'poor' => 'danger',
                    'damaged' => 'danger',
                    default => 'gray',
                }),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'available' => 'Tersedia',
                    'borrowed' => 'Dipinjam',
                    'maintenance' => 'Maintenance',
                    'retired' => 'Tidak Digunakan',
                    default => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'available' => 'success',
                    'borrowed' => 'warning',
                    'maintenance' => 'info',
                    'retired' => 'gray',
                    default => 'gray',
                }),

            Tables\Columns\TextColumn::make('currentBorrower.name')
                ->label('Peminjam')
                ->searchable()
                ->toggleable()
                ->default('-'),

            Tables\Columns\TextColumn::make('borrowed_until')
                ->label('Sampai Tanggal')
                ->sortable()
                ->toggleable()
                ->formatStateUsing(fn ($state) => $state instanceof \DateTimeInterface ? $state->format('d/m/Y') : '-'),

            Tables\Columns\TextColumn::make('storage_location')
                ->label('Lokasi')
                ->searchable()
                ->toggleable()
                ->default('-'),

            Tables\Columns\IconColumn::make('needs_maintenance')
                ->label('Perlu Maintenance')
                ->boolean()
                ->toggleable()
                ->getStateUsing(fn ($record) => $record->needsMaintenance()),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Ditambahkan')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('equipment_category_id')
                ->label('Kategori')
                ->relationship('equipmentCategory', 'name')
                ->multiple()
                ->preload(),

            Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'available' => 'Tersedia',
                    'borrowed' => 'Dipinjam',
                    'maintenance' => 'Maintenance',
                    'retired' => 'Tidak Digunakan',
                ])
                ->multiple(),

            Tables\Filters\SelectFilter::make('condition')
                ->label('Kondisi')
                ->options([
                    'excellent' => 'Sangat Baik',
                    'good' => 'Baik',
                    'fair' => 'Cukup',
                    'poor' => 'Buruk',
                    'damaged' => 'Rusak',
                ])
                ->multiple(),

            Tables\Filters\Filter::make('needs_maintenance')
                ->label('Perlu Maintenance')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('next_maintenance_date')
                    ->where(function ($q) {
                        $q->where('next_maintenance_date', '<=', now())
                          ->orWhere('next_maintenance_date', '<=', now()->addDays(30));
                    })
                ),

            Tables\Filters\Filter::make('low_stock')
                ->label('Stok Rendah')
                ->query(fn (Builder $query): Builder => $query->whereColumn('quantity_available', '<', 'quantity')
                    ->whereRaw('quantity_available < quantity / 2')
                ),

            Tables\Filters\Filter::make('out_of_stock')
                ->label('Stok Habis')
                ->query(fn (Builder $query): Builder => $query->where('quantity_available', 0)),
        ];
    }

    public static function actions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('mark_maintenance')
                    ->label('Set Maintenance')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'available')
                    ->form([
                        Tables\Actions\Action::make('confirm')
                            ->label('Apakah peralatan ini perlu maintenance?'),
                    ])
                    ->action(function ($record) {
                        $record->update(['status' => 'maintenance']);
                    })
                    ->successNotificationTitle('Peralatan ditandai sedang maintenance'),

                Tables\Actions\Action::make('mark_available')
                    ->label('Tandai Tersedia')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'maintenance')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'available',
                            'last_maintenance_date' => now(),
                        ]);
                    })
                    ->successNotificationTitle('Peralatan ditandai tersedia'),

                Tables\Actions\Action::make('retire')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status !== 'retired')
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan Peralatan')
                    ->modalDescription('Peralatan akan ditandai sebagai tidak digunakan.')
                    ->action(function ($record) {
                        $record->update(['status' => 'retired']);
                    })
                    ->successNotificationTitle('Peralatan dinonaktifkan'),

                Tables\Actions\DeleteAction::make(),
            ]),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),

                Tables\Actions\BulkAction::make('mark_maintenance')
                    ->label('Set Maintenance')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $records->each->update(['status' => 'maintenance']);
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkAction::make('mark_available')
                    ->label('Tandai Tersedia')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $records->each->update(['status' => 'available']);
                    })
                    ->deselectRecordsAfterCompletion(),
            ]),
        ];
    }
}
