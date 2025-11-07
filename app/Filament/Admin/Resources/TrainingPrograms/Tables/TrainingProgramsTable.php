<?php

namespace App\Filament\Admin\Resources\TrainingPrograms\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class TrainingProgramsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Program')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->cohort?->name),

                Tables\Columns\TextColumn::make('program_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'basic' => 'Dasar',
                        'advanced' => 'Lanjutan',
                        'specialized' => 'Spesialisasi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'basic' => 'info',
                        'advanced' => 'warning',
                        'specialized' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Periode')
                    ->formatStateUsing(fn ($record) =>
                        $record->start_date->format('d M Y') . ' - ' .
                        $record->end_date->format('d M Y')
                    )
                    ->description(fn ($record) => $record->getDurationInDays() . ' hari')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'info',
                        'ongoing' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_participants')
                    ->label('Peserta')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($record) => $record->participants()->count())
                    ->description(fn ($record) =>
                        $record->max_participants
                            ? 'Maks: ' . $record->max_participants
                            : 'Unlimited'
                    ),

                Tables\Columns\TextColumn::make('coordinator.name')
                    ->label('Koordinator')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('registration_status')
                    ->label('Pendaftaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Terbuka',
                        'closed' => 'Ditutup',
                        'full' => 'Penuh',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                        'full' => 'warning',
                        default => 'gray',
                    })
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
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('program_type')
                    ->label('Jenis Program')
                    ->options([
                        'basic' => 'Dasar',
                        'advanced' => 'Lanjutan',
                        'specialized' => 'Spesialisasi',
                    ]),

                Tables\Filters\SelectFilter::make('cohort_id')
                    ->label('Angkatan')
                    ->relationship('cohort', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_mandatory')
                    ->label('Wajib')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Wajib')
                    ->falseLabel('Tidak Wajib'),

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

                    Tables\Actions\Action::make('start_program')
                        ->label('Mulai Program')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->visible(fn ($record) => $record->status === 'scheduled')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'ongoing']))
                        ->successNotificationTitle('Program dimulai'),

                    Tables\Actions\Action::make('complete_program')
                        ->label('Selesaikan Program')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'ongoing')
                        ->requiresConfirmation()
                        ->modalHeading('Selesaikan Program')
                        ->modalDescription('Yakin program ini sudah selesai? Peserta akan dievaluasi.')
                        ->action(function ($record) {
                            $record->update(['status' => 'completed']);

                            // Auto-evaluate participants
                            foreach ($record->trainingParticipants as $participant) {
                                if ($participant->status === 'attending') {
                                    $participant->markAsCompleted();
                                }
                            }
                        })
                        ->successNotificationTitle('Program selesai'),

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
                                    'draft' => 'Draft',
                                    'scheduled' => 'Terjadwal',
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
            ->emptyStateHeading('Belum ada program pelatihan')
            ->emptyStateDescription('Klik "Tambah Program Pelatihan" untuk membuat program baru.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
