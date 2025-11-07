<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;

class UsersTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('member_number')
                    ->label('No. Anggota')
                    ->searchable()
                    ->badge()
                    ->color('success')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('member_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'prospective' => 'Calon',
                        'junior' => 'Junior',
                        'member' => 'Anggota',
                        'alumni' => 'Alumni',
                        default => 'Tidak Diketahui',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'prospective' => 'warning',
                        'junior' => 'info',
                        'member' => 'success',
                        'alumni' => 'gray',
                        default => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('cohort.name')
                    ->label('Angkatan')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('major')
                    ->label('Jurusan')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('primary')
                    ->separator(',')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Login Terakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('member_status')
                    ->label('Status Keanggotaan')
                    ->options([
                        'prospective' => 'Calon Anggota',
                        'junior' => 'Anggota Muda',
                        'member' => 'Anggota',
                        'alumni' => 'Alumni',
                    ]),

                Tables\Filters\SelectFilter::make('cohort_id')
                    ->label('Angkatan')
                    ->relationship('cohort', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Tidak Aktif'),

                Tables\Filters\TernaryFilter::make('email_verified')
                    ->label('Email Terverifikasi')
                    ->placeholder('Semua')
                    ->trueLabel('Terverifikasi')
                    ->falseLabel('Belum Terverifikasi')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('email_verified_at'),
                        false: fn ($query) => $query->whereNull('email_verified_at'),
                    ),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('verify_email')
                    ->label('Verifikasi Email')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record): bool => is_null($record->email_verified_at))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Email')
                    ->modalDescription('Yakin ingin memverifikasi email user ini secara manual?')
                    ->modalSubmitActionLabel('Ya, Verifikasi')
                    ->action(function ($record) {
                        $record->markEmailAsVerified();

                        Notification::make()
                            ->success()
                            ->title('Email Terverifikasi')
                            ->body('Email berhasil diverifikasi.')
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ])->visible(fn (): bool => auth()->user()?->can('manage-users') ?? false),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->emptyStateHeading('Belum ada data anggota')
            ->emptyStateDescription('Klik tombol "Buat" untuk menambahkan anggota pertama.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
