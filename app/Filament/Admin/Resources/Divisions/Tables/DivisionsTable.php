<?php

namespace App\Filament/Admin/Resources/Divisions/Tables;

use Filament\Tables;
use Filament\Tables\Table;

class DivisionsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->label('Logo')
                    ->collection('logo')
                    ->circular()
                    ->defaultImageUrl(fn($record) => "https://ui-avatars.com/api/?name=" . urlencode($record->name) . "&color=fff&background=" . ltrim($record->color ?? '3B82F6', '#'))
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Divisi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon(fn ($record) => !empty($record->icon) && mb_strlen($record->icon) <= 2 ? null : $record->icon)
                    ->prefix(fn ($record) => !empty($record->icon) && mb_strlen($record->icon) <= 2 ? $record->icon . ' ' : '')
                    ->color(fn ($record) => $record->color),

                Tables\Columns\TextColumn::make('head.name')
                    ->label('Ketua Divisi')
                    ->searchable()
                    ->sortable()
                    ->default('Belum ditentukan')
                    ->icon('heroicon-o-user-circle')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('active_member_count')
                    ->label('Anggota Aktif')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state, $record) => $record->active_member_count . ' orang'),

                Tables\Columns\TextColumn::make('total_member_count')
                    ->label('Total Anggota')
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state, $record) => $record->total_member_count . ' orang')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Tidak Aktif'),

                Tables\Filters\SelectFilter::make('head_id')
                    ->label('Ketua Divisi')
                    ->relationship('head', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Divisi')
                    ->modalDescription('Yakin ingin menghapus divisi ini? Data anggota divisi akan tetap ada namun hubungan dengan divisi akan dihapus.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->poll('30s')
            ->emptyStateHeading('Belum ada data divisi')
            ->emptyStateDescription('Klik tombol "Buat" untuk menambahkan divisi pertama.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
