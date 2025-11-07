<?php

namespace App\Filament\Admin\Resources\Expeditions\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class ExpeditionsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Ekspedisi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->destination),

                Tables\Columns\TextColumn::make('expedition_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hiking' => 'Hiking',
                        'climbing' => 'Panjat Tebing',
                        'caving' => 'Gua',
                        'rafting' => 'Arung Jeram',
                        'expedition' => 'Ekspedisi',
                        'conservation' => 'Konservasi',
                        'research' => 'Riset',
                        default => $state,
                    })
                    ->color('info'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Periode')
                    ->formatStateUsing(fn ($record) =>
                        $record->start_date->format('d M Y') . ' - ' .
                        $record->end_date->format('d M Y')
                    )
                    ->description(fn ($record) => $record->getDurationFormatted())
                    ->sortable(),

                Tables\Columns\TextColumn::make('difficulty_level')
                    ->label('Tingkat')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->difficulty_label)
                    ->color(fn ($record) => $record->difficulty_color),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn ($record) => $record->status_color)
                    ->sortable(),

                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Peserta')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($record) => $record->getParticipantCount())
                    ->description(fn ($record) =>
                        $record->max_participants
                            ? 'Maks: ' . $record->max_participants
                            : 'Unlimited'
                    ),

                Tables\Columns\TextColumn::make('leader.name')
                    ->label('Ketua')
                    ->default(fn ($record) => $record->leader_name ?? '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('registration_status')
                    ->label('Pendaftaran')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->registration_status_label)
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                        'full' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_official')
                    ->label('Resmi')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'planned' => 'Direncanakan',
                        'preparing' => 'Persiapan',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('expedition_type')
                    ->label('Jenis Ekspedisi')
                    ->options([
                        'hiking' => 'Hiking/Pendakian',
                        'climbing' => 'Panjat Tebing',
                        'caving' => 'Penelusuran Gua',
                        'rafting' => 'Arung Jeram',
                        'expedition' => 'Ekspedisi',
                        'conservation' => 'Konservasi',
                        'research' => 'Riset',
                        'other' => 'Lainnya',
                    ]),

                Tables\Filters\SelectFilter::make('difficulty_level')
                    ->label('Tingkat Kesulitan')
                    ->options([
                        'easy' => 'Mudah',
                        'moderate' => 'Sedang',
                        'hard' => 'Sulit',
                        'extreme' => 'Ekstrim',
                    ]),

                Tables\Filters\SelectFilter::make('division_id')
                    ->label('Divisi')
                    ->relationship('division', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_official')
                    ->label('Resmi')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Resmi')
                    ->falseLabel('Tidak Resmi'),

                Tables\Filters\SelectFilter::make('registration_status')
                    ->label('Status Pendaftaran')
                    ->options([
                        'open' => 'Terbuka',
                        'closed' => 'Ditutup',
                        'full' => 'Penuh',
                    ]),

                Tables\Filters\Filter::make('upcoming')
                    ->label('Akan Datang')
                    ->query(fn ($query) => $query->upcoming()),

                Tables\Filters\Filter::make('ongoing')
                    ->label('Sedang Berlangsung')
                    ->query(fn ($query) => $query->ongoing()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('open_registration')
                        ->label('Buka Pendaftaran')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->registration_status !== 'open')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['registration_status' => 'open']))
                        ->successNotificationTitle('Pendaftaran berhasil dibuka'),

                    Tables\Actions\Action::make('close_registration')
                        ->label('Tutup Pendaftaran')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->registration_status === 'open')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['registration_status' => 'closed']))
                        ->successNotificationTitle('Pendaftaran berhasil ditutup'),

                    Tables\Actions\Action::make('start_expedition')
                        ->label('Mulai Ekspedisi')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->visible(fn ($record) => $record->status === 'preparing' && $record->canStart())
                        ->requiresConfirmation()
                        ->modalHeading('Mulai Ekspedisi')
                        ->modalDescription('Pastikan semua persiapan sudah selesai.')
                        ->action(fn ($record) => $record->markAsOngoing())
                        ->successNotificationTitle('Ekspedisi dimulai'),

                    Tables\Actions\Action::make('complete_expedition')
                        ->label('Selesaikan Ekspedisi')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'ongoing')
                        ->requiresConfirmation()
                        ->modalHeading('Selesaikan Ekspedisi')
                        ->modalDescription('Yakin ekspedisi ini sudah selesai?')
                        ->action(fn ($record) => $record->markAsCompleted())
                        ->successNotificationTitle('Ekspedisi selesai'),

                    Tables\Actions\Action::make('calculate_duration')
                        ->label('Hitung Durasi')
                        ->icon('heroicon-o-calculator')
                        ->color('info')
                        ->action(fn ($record) => $record->calculateDuration())
                        ->successNotificationTitle('Durasi dihitung ulang'),

                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-pencil')
                        ->color('info')
                        ->form([
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'planned' => 'Direncanakan',
                                    'preparing' => 'Persiapan',
                                    'ongoing' => 'Berlangsung',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['status' => $data['status']]);
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Status berhasil diperbarui'),

                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Belum ada ekspedisi')
            ->emptyStateDescription('Klik "Tambah Ekspedisi" untuk membuat ekspedisi baru.')
            ->emptyStateIcon('heroicon-o-map-pin');
    }
}
