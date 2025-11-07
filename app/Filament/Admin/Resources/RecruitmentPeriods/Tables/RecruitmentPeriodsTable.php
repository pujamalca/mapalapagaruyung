<?php

namespace App\Filament\Admin\Resources\RecruitmentPeriods\Tables;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;

class RecruitmentPeriodsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Periode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->cohort?->name),

                Tables\Columns\TextColumn::make('registration_start')
                    ->label('Periode Pendaftaran')
                    ->formatStateUsing(fn ($record) =>
                        $record->registration_start->format('d M Y') . ' - ' .
                        $record->registration_end->format('d M Y')
                    )
                    ->description(fn ($record) =>
                        $record->registration_start->diffInDays($record->registration_end) . ' hari'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'open' => 'Terbuka',
                        'selection' => 'Seleksi',
                        'closed' => 'Ditutup',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'open' => 'success',
                        'selection' => 'warning',
                        'closed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_applicants')
                    ->label('Pendaftar')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($record) => $record->applicants()->count())
                    ->description(fn ($record) =>
                        $record->max_applicants
                            ? 'Maks: ' . $record->max_applicants
                            : 'Unlimited'
                    ),

                Tables\Columns\TextColumn::make('verified_applicants')
                    ->label('Terverifikasi')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($record) => $record->applicants()->verified()->count())
                    ->toggleable(),

                Tables\Columns\TextColumn::make('accepted_applicants')
                    ->label('Diterima')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($record) => $record->applicants()->accepted()->count())
                    ->description(fn ($record) =>
                        $record->target_accepted
                            ? 'Target: ' . $record->target_accepted
                            : ''
                    )
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('registration_start', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'open' => 'Terbuka',
                        'selection' => 'Seleksi',
                        'closed' => 'Ditutup',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Tidak Aktif'),

                Tables\Filters\SelectFilter::make('cohort_id')
                    ->label('Angkatan')
                    ->relationship('cohort', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('registration_period')
                    ->label('Periode Pendaftaran')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) =>
                                $q->where('registration_start', '>=', $date)
                            )
                            ->when($data['until'], fn ($q, $date) =>
                                $q->where('registration_end', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('activate')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn ($record) => $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Periode Recruitment')
                    ->modalDescription('Ini akan menonaktifkan periode recruitment lain yang sedang aktif.')
                    ->action(function ($record) {
                        // Deactivate all other periods
                        \App\Models\RecruitmentPeriod::where('id', '!=', $record->id)
                            ->update(['is_active' => false]);

                        // Activate this period
                        $record->update(['is_active' => true]);
                    })
                    ->successNotificationTitle('Periode recruitment berhasil diaktifkan'),

                Tables\Actions\Action::make('deactivate')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->is_active)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['is_active' => false]))
                    ->successNotificationTitle('Periode recruitment berhasil dinonaktifkan'),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Periode Recruitment')
                    ->modalDescription('Yakin ingin menghapus periode ini? Semua data pendaftar juga akan terhapus!'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Periode Recruitment')
                        ->modalDescription('Yakin ingin menghapus periode yang dipilih? Semua data pendaftar juga akan terhapus!'),
                ]),
            ])
            ->emptyStateHeading('Belum ada periode recruitment')
            ->emptyStateDescription('Klik "Tambah Periode Recruitment" untuk membuat periode baru.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
