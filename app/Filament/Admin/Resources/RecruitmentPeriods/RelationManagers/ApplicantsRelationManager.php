<?php

namespace App\Filament\Admin\Resources\RecruitmentPeriods\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicantsRelationManager extends RelationManager
{
    protected static string $relationship = 'applicants';

    protected static ?string $title = 'Daftar Pendaftar';

    protected static ?string $modelLabel = 'Pendaftar';

    protected static ?string $pluralModelLabel = 'Pendaftar';

    protected static ?string $recordTitleAttribute = 'full_name';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('No. Pendaftaran')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->nim . ' - ' . $record->major),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone')
                    ->toggleable(),

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
                    ->label('Tahap Saat Ini')
                    ->placeholder('Belum dimulai')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_score')
                    ->label('Total Nilai')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state >= 70 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),

                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To')
                    ->placeholder('Belum ditugaskan')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Tanggal Verifikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
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
                    ]),

                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                Tables\Filters\Filter::make('verified')
                    ->label('Terverifikasi')
                    ->query(fn ($query) => $query->whereNotNull('verified_at')),

                Tables\Filters\Filter::make('score_range')
                    ->label('Rentang Nilai')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('min_score')
                            ->label('Nilai Minimal')
                            ->numeric(),
                        \Filament\Forms\Components\TextInput::make('max_score')
                            ->label('Nilai Maksimal')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min_score'], fn ($q, $score) =>
                                $q->where('total_score', '>=', $score)
                            )
                            ->when($data['max_score'], fn ($q, $score) =>
                                $q->where('total_score', '<=', $score)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_details')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) =>
                        route('filament.admin.resources.applicants.edit', $record)
                    ),

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
                    ->modalDescription('Yakin ingin menerima pendaftar ini sebagai anggota?')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('verify_selected')
                        ->label('Verifikasi Terpilih')
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

                    Tables\Actions\BulkAction::make('export_selected')
                        ->label('Export Data')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(function ($records) {
                            // TODO: Implement export functionality
                        }),
                ]),
            ])
            ->emptyStateHeading('Belum ada pendaftar')
            ->emptyStateDescription('Pendaftar akan muncul di sini setelah melakukan registrasi.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
