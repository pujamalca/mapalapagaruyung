<?php

namespace App\Filament\Admin\Resources\Applicants\Tables;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicantsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('No. Pendaftaran')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('recruitmentPeriod.name')
                    ->label('Periode')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->nim),

                Tables\Columns\TextColumn::make('major')
                    ->label('Jurusan')
                    ->searchable()
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'registered' => 'Terdaftar',
                        'verified' => 'Terverifikasi',
                        'in_selection' => 'Dalam Seleksi',
                        'passed' => 'Lulus',
                        'failed' => 'Tidak Lulus',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'withdrawn' => 'Mengundurkan Diri',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'gray',
                        'verified' => 'info',
                        'in_selection' => 'warning',
                        'passed' => 'success',
                        'failed' => 'danger',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'withdrawn' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_stage')
                    ->label('Tahap')
                    ->placeholder('Belum dimulai')
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_score')
                    ->label('Nilai')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) =>
                        $state >= 80 ? 'success' :
                        ($state >= 70 ? 'warning' :
                        ($state >= 60 ? 'info' : 'danger'))
                    ),

                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('recruitment_period_id')
                    ->label('Periode Recruitment')
                    ->relationship('recruitmentPeriod', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'registered' => 'Terdaftar',
                        'verified' => 'Terverifikasi',
                        'in_selection' => 'Dalam Seleksi',
                        'passed' => 'Lulus',
                        'failed' => 'Tidak Lulus',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'withdrawn' => 'Mengundurkan Diri',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                Tables\Filters\Filter::make('verified')
                    ->label('Hanya Terverifikasi')
                    ->query(fn ($query) => $query->whereNotNull('verified_at')),

                Tables\Filters\Filter::make('accepted')
                    ->label('Hanya Diterima')
                    ->query(fn ($query) => $query->where('status', 'accepted')),

                Tables\Filters\Filter::make('score_range')
                    ->label('Rentang Nilai')
                    ->form([
                        Grid::make(2)->schema([
                            \Filament\Forms\Components\TextInput::make('min')
                                ->label('Min')
                                ->numeric(),
                            \Filament\Forms\Components\TextInput::make('max')
                                ->label('Max')
                                ->numeric(),
                        ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min'], fn ($q, $score) =>
                                $q->where('total_score', '>=', $score)
                            )
                            ->when($data['max'], fn ($q, $score) =>
                                $q->where('total_score', '<=', $score)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('verify')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'registered')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->markAsVerified();
                        })
                        ->successNotificationTitle('Pendaftar berhasil diverifikasi'),

                    Tables\Actions\Action::make('accept')
                        ->label('Terima')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'passed')
                        ->requiresConfirmation()
                        ->modalHeading('Terima Pendaftar')
                        ->modalDescription('Yakin menerima pendaftar ini sebagai anggota?')
                        ->action(function ($record) {
                            $record->markAsAccepted();
                        })
                        ->successNotificationTitle('Pendaftar berhasil diterima'),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => in_array($record->status, ['registered', 'verified', 'failed']))
                        ->requiresConfirmation()
                        ->form([
                            \Filament\Forms\Components\Textarea::make('status_notes')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function ($record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'status_notes' => $data['status_notes'],
                            ]);
                        })
                        ->successNotificationTitle('Pendaftar berhasil ditolak'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('verify_bulk')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'registered') {
                                    $record->markAsVerified();
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Pendaftar terpilih berhasil diverifikasi'),

                    Tables\Actions\BulkAction::make('assign_bulk')
                        ->label('Assign to BKP')
                        ->icon('heroicon-o-user-plus')
                        ->color('info')
                        ->form([
                            \Filament\Forms\Components\Select::make('assigned_to')
                                ->label('Assign ke')
                                ->relationship('assignedTo', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['assigned_to' => $data['assigned_to']]);
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Pendaftar berhasil di-assign'),

                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pendaftar')
            ->emptyStateDescription('Pendaftar akan muncul setelah melakukan registrasi.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
