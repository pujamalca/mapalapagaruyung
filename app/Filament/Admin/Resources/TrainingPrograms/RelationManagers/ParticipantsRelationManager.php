<?php

namespace App\Filament\Admin\Resources\TrainingPrograms\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'trainingParticipants';

    protected static ?string $title = 'Peserta Pelatihan';

    protected static ?string $modelLabel = 'Peserta';

    protected static ?string $icon = 'heroicon-o-users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Peserta')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Pilih member yang akan mengikuti program'),

                Forms\Components\DateTimePicker::make('registered_at')
                    ->label('Tanggal Registrasi')
                    ->required()
                    ->native(false)
                    ->seconds(false)
                    ->default(now()),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'registered' => 'Terdaftar',
                        'confirmed' => 'Dikonfirmasi',
                        'attending' => 'Mengikuti',
                        'completed' => 'Selesai',
                        'passed' => 'Lulus',
                        'failed' => 'Tidak Lulus',
                        'dropped' => 'Mengundurkan Diri',
                        'absent' => 'Tidak Hadir',
                    ])
                    ->default('registered')
                    ->required(),

                Forms\Components\Section::make('Penilaian')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('total_score')
                                ->label('Total Nilai')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->disabled()
                                ->helperText('Otomatis dihitung dari attendance'),

                            Forms\Components\TextInput::make('average_score')
                                ->label('Nilai Rata-rata')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->maxValue(100)
                                ->disabled()
                                ->helperText('Otomatis dihitung dari attendance'),
                        ]),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('attendance_count')
                                ->label('Jumlah Hadir')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->disabled(),

                            Forms\Components\TextInput::make('total_sessions')
                                ->label('Total Sesi')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->disabled(),
                        ]),

                        Forms\Components\Textarea::make('evaluation_notes')
                            ->label('Catatan Evaluasi')
                            ->rows(3)
                            ->helperText('Catatan dari instruktur/BKP'),
                    ])
                    ->visible(fn ($record) => $record !== null),

                Forms\Components\Section::make('Feedback Peserta')
                    ->schema([
                        Forms\Components\Textarea::make('participant_feedback')
                            ->label('Feedback')
                            ->rows(3)
                            ->helperText('Feedback dari peserta tentang program'),

                        Forms\Components\TextInput::make('participant_rating')
                            ->label('Rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->suffix('/ 5'),
                    ])
                    ->visible(fn ($record) => $record !== null && in_array($record->status, ['completed', 'passed', 'failed'])),

                Forms\Components\Section::make('Sertifikat')
                    ->schema([
                        Forms\Components\Toggle::make('certificate_issued')
                            ->label('Sertifikat Diterbitkan')
                            ->default(false)
                            ->inline(false)
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('certificate_issued_at')
                            ->label('Tanggal Penerbitan Sertifikat')
                            ->native(false)
                            ->seconds(false)
                            ->disabled(),
                    ])
                    ->visible(fn ($record) => $record !== null && $record->status === 'passed'),

                Forms\Components\KeyValue::make('metadata')
                    ->label('Metadata Tambahan')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->reorderable()
                    ->addActionLabel('Tambah Metadata'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->defaultSort('registered_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->user->email),

                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Tgl Registrasi')
                    ->dateTime('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'registered' => 'Terdaftar',
                        'confirmed' => 'Dikonfirmasi',
                        'attending' => 'Mengikuti',
                        'completed' => 'Selesai',
                        'passed' => 'Lulus',
                        'failed' => 'Tidak Lulus',
                        'dropped' => 'Mengundurkan Diri',
                        'absent' => 'Tidak Hadir',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'gray',
                        'confirmed' => 'info',
                        'attending' => 'warning',
                        'completed' => 'primary',
                        'passed' => 'success',
                        'failed' => 'danger',
                        'dropped' => 'gray',
                        'absent' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('average_score')
                    ->label('Nilai')
                    ->badge()
                    ->color(fn ($record) => $record->isPassed() ? 'success' : 'danger')
                    ->formatStateUsing(fn ($record) =>
                        $record->average_score
                            ? number_format($record->average_score, 1)
                            : '-'
                    )
                    ->description(fn ($record) =>
                        $record->average_score && $this->getOwnerRecord()->has_evaluation
                            ? ($record->isPassed() ? 'Lulus' : 'Tidak Lulus') . ' (Min: ' . $this->getOwnerRecord()->passing_score . ')'
                            : null
                    )
                    ->toggleable(),

                Tables\Columns\TextColumn::make('attendance_rate')
                    ->label('Kehadiran')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($record) =>
                        $record->total_sessions > 0
                            ? $record->getAttendanceRateAttribute() . '%'
                            : '-'
                    )
                    ->description(fn ($record) =>
                        $record->total_sessions > 0
                            ? $record->attendance_count . ' dari ' . $record->total_sessions . ' sesi'
                            : null
                    )
                    ->toggleable(),

                Tables\Columns\IconColumn::make('certificate_issued')
                    ->label('Sertifikat')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('participant_rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => $state ? $state . '/5 ⭐' : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'registered' => 'Terdaftar',
                        'confirmed' => 'Dikonfirmasi',
                        'attending' => 'Mengikuti',
                        'completed' => 'Selesai',
                        'passed' => 'Lulus',
                        'failed' => 'Tidak Lulus',
                        'dropped' => 'Mengundurkan Diri',
                        'absent' => 'Tidak Hadir',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('certificate_issued')
                    ->label('Sertifikat')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Diterbitkan')
                    ->falseLabel('Belum Diterbitkan'),

                Tables\Filters\Filter::make('passed')
                    ->label('Lulus')
                    ->query(fn ($query) => $query->where('status', 'passed')),

                Tables\Filters\Filter::make('failed')
                    ->label('Tidak Lulus')
                    ->query(fn ($query) => $query->where('status', 'failed')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Peserta')
                    ->icon('heroicon-o-plus')
                    ->modalWidth('3xl'),

                Tables\Actions\Action::make('recalculate_scores')
                    ->label('Hitung Ulang Nilai')
                    ->icon('heroicon-o-calculator')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function () {
                        foreach ($this->getOwnerRecord()->trainingParticipants as $participant) {
                            $participant->calculateScores();
                        }
                    })
                    ->successNotificationTitle('Nilai berhasil dihitung ulang'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth('3xl'),

                    Tables\Actions\Action::make('confirm_registration')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->visible(fn ($record) => $record->status === 'registered')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'confirmed']))
                        ->successNotificationTitle('Peserta dikonfirmasi'),

                    Tables\Actions\Action::make('start_attending')
                        ->label('Mulai Mengikuti')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->visible(fn ($record) => $record->status === 'confirmed')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'attending']))
                        ->successNotificationTitle('Status diperbarui'),

                    Tables\Actions\Action::make('mark_completed')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'attending')
                        ->requiresConfirmation()
                        ->modalHeading('Tandai Peserta Selesai')
                        ->modalDescription('Nilai akan dihitung otomatis berdasarkan attendance.')
                        ->action(function ($record) {
                            $record->calculateScores();
                            $record->markAsCompleted();
                        })
                        ->successNotificationTitle('Peserta ditandai selesai'),

                    Tables\Actions\Action::make('issue_certificate')
                        ->label('Terbitkan Sertifikat')
                        ->icon('heroicon-o-document-check')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'passed' && !$record->certificate_issued)
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->issueCertificate())
                        ->successNotificationTitle('Sertifikat diterbitkan'),

                    Tables\Actions\Action::make('recalculate_score')
                        ->label('Hitung Ulang Nilai')
                        ->icon('heroicon-o-calculator')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->calculateScores())
                        ->successNotificationTitle('Nilai dihitung ulang'),

                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn ($record) => in_array($record->status, ['registered', 'confirmed'])),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('confirm_participants')
                        ->label('Konfirmasi Peserta')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'registered') {
                                    $record->update(['status' => 'confirmed']);
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Peserta dikonfirmasi'),

                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-pencil')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'registered' => 'Terdaftar',
                                    'confirmed' => 'Dikonfirmasi',
                                    'attending' => 'Mengikuti',
                                    'completed' => 'Selesai',
                                    'passed' => 'Lulus',
                                    'failed' => 'Tidak Lulus',
                                    'dropped' => 'Mengundurkan Diri',
                                    'absent' => 'Tidak Hadir',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['status' => $data['status']]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('recalculate_scores')
                        ->label('Hitung Ulang Nilai')
                        ->icon('heroicon-o-calculator')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->calculateScores();
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Nilai dihitung ulang'),

                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Belum ada peserta')
            ->emptyStateDescription('Tambahkan peserta untuk program pelatihan ini.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
