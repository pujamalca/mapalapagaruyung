<?php

namespace App\Filament\Admin\Resources\Applicants\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'progress';

    protected static ?string $title = 'Progress Seleksi';

    protected static ?string $modelLabel = 'Progress';

    protected static ?string $pluralModelLabel = 'Progress Tahapan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('selection_stage_id')
                        ->label('Tahap Seleksi')
                        ->relationship('selectionStage', 'name')
                        ->required()
                        ->disabled(fn ($record) => $record !== null),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Menunggu',
                            'scheduled' => 'Terjadwal',
                            'in_progress' => 'Sedang Berlangsung',
                            'passed' => 'Lulus',
                            'failed' => 'Tidak Lulus',
                            'skipped' => 'Dilewati',
                        ])
                        ->required()
                        ->live(),
                ]),

                Forms\Components\Toggle::make('attended')
                    ->label('Hadir')
                    ->live()
                    ->helperText('Apakah peserta hadir pada tahap ini?'),

                Forms\Components\DateTimePicker::make('attended_at')
                    ->label('Waktu Kehadiran')
                    ->native(false)
                    ->seconds(false)
                    ->visible(fn (Forms\Get $get) => $get('attended')),

                Forms\Components\Textarea::make('absence_reason')
                    ->label('Alasan Tidak Hadir')
                    ->rows(2)
                    ->visible(fn (Forms\Get $get) => !$get('attended')),

                Forms\Components\Section::make('Penilaian')
                    ->schema([
                        Forms\Components\TextInput::make('score')
                            ->label('Nilai')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(fn ($record) => $record?->selectionStage?->max_score ?? 100)
                            ->suffix('poin')
                            ->helperText(fn ($record) =>
                                $record?->selectionStage?->is_scored
                                    ? 'Nilai lulus: ' . $record->selectionStage->passing_score
                                    : 'Tahap ini tidak dinilai'
                            )
                            ->visible(fn ($record) =>
                                $record?->selectionStage?->is_scored ?? true
                            ),

                        Forms\Components\KeyValue::make('detailed_scores')
                            ->label('Nilai Detail per Kriteria')
                            ->keyLabel('Kriteria')
                            ->valueLabel('Nilai')
                            ->addActionLabel('Tambah Kriteria')
                            ->visible(fn ($record) =>
                                $record?->selectionStage?->is_scored ?? true
                            ),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Evaluator')
                            ->rows(3)
                            ->helperText('Catatan internal untuk evaluator'),

                        Forms\Components\Textarea::make('feedback')
                            ->label('Feedback untuk Peserta')
                            ->rows(3)
                            ->helperText('Feedback yang akan diberikan kepada peserta'),
                    ])
                    ->visible(fn (Forms\Get $get) =>
                        in_array($get('status'), ['passed', 'failed', 'in_progress'])
                    ),

                Forms\Components\Select::make('evaluated_by')
                    ->label('Evaluator')
                    ->relationship('evaluator', 'name')
                    ->default(fn () => auth()->id())
                    ->searchable()
                    ->preload()
                    ->visible(fn (Forms\Get $get) =>
                        in_array($get('status'), ['passed', 'failed'])
                    ),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('selectionStage.name')
            ->defaultSort('selectionStage.order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('selectionStage.order')
                    ->label('#')
                    ->sortable()
                    ->width(50)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('selectionStage.name')
                    ->label('Tahap Seleksi')
                    ->weight('bold')
                    ->description(fn ($record) =>
                        $record->selectionStage->scheduled_date
                            ? 'Jadwal: ' . $record->selectionStage->scheduled_date->format('d M Y H:i')
                            : null
                    ),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'scheduled' => 'Terjadwal',
                        'in_progress' => 'Berlangsung',
                        'passed' => 'Lulus',
                        'failed' => 'Tidak Lulus',
                        'skipped' => 'Dilewati',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'scheduled' => 'info',
                        'in_progress' => 'warning',
                        'passed' => 'success',
                        'failed' => 'danger',
                        'skipped' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('attended')
                    ->label('Hadir')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('score')
                    ->label('Nilai')
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($record, $state) => {
                        if (!$record->selectionStage->is_scored || $state === null) {
                            return 'gray';
                        }
                        return $state >= $record->selectionStage->passing_score ? 'success' : 'danger';
                    })
                    ->formatStateUsing(fn ($record, $state) =>
                        $record->selectionStage->is_scored
                            ? ($state ?? '-')
                            : 'N/A'
                    ),

                Tables\Columns\TextColumn::make('evaluator.name')
                    ->label('Evaluator')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('evaluated_at')
                    ->label('Tgl Evaluasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'scheduled' => 'Terjadwal',
                        'in_progress' => 'Berlangsung',
                        'passed' => 'Lulus',
                        'failed' => 'Tidak Lulus',
                        'skipped' => 'Dilewati',
                    ]),

                Tables\Filters\TernaryFilter::make('attended')
                    ->label('Kehadiran')
                    ->placeholder('Semua')
                    ->trueLabel('Hadir')
                    ->falseLabel('Tidak Hadir'),

                Tables\Filters\TernaryFilter::make('evaluated')
                    ->label('Evaluasi')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Dievaluasi')
                    ->falseLabel('Belum Dievaluasi')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('evaluated_at'),
                        false: fn ($query) => $query->whereNull('evaluated_at'),
                    ),
            ])
            ->headerActions([
                // Progress is automatically created when applicant enters selection
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Evaluasi Tahap Seleksi')
                    ->modalSubmitActionLabel('Simpan')
                    ->successNotificationTitle('Evaluasi berhasil disimpan')
                    ->after(function ($record) {
                        // Recalculate total score
                        $record->applicant->calculateTotalScore();

                        // Update current stage
                        if ($record->status === 'in_progress') {
                            $record->applicant->updateCurrentStage($record->selectionStage);
                        }
                    }),

                Tables\Actions\Action::make('mark_passed')
                    ->label('Lulus')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['in_progress', 'pending']))
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('score')
                            ->label('Nilai')
                            ->numeric()
                            ->required()
                            ->visible(fn ($record) => $record->selectionStage->is_scored),

                        Forms\Components\Textarea::make('feedback')
                            ->label('Feedback')
                            ->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        $score = $data['score'] ?? null;
                        $feedback = $data['feedback'] ?? null;

                        $record->markAsPassed($score, $feedback);
                        $record->update(['evaluated_by' => auth()->id()]);
                    })
                    ->successNotificationTitle('Peserta berhasil diluluskan'),

                Tables\Actions\Action::make('mark_failed')
                    ->label('Tidak Lulus')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['in_progress', 'pending']))
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('feedback')
                            ->label('Alasan & Feedback')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->markAsFailed($data['feedback']);
                        $record->update(['evaluated_by' => auth()->id()]);
                    })
                    ->successNotificationTitle('Status berhasil diperbarui'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_attended')
                        ->label('Tandai Hadir')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->markAsAttended();
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Kehadiran berhasil dicatat'),
                ]),
            ])
            ->emptyStateHeading('Belum ada progress')
            ->emptyStateDescription('Progress akan otomatis dibuat saat pendaftar memasuki tahap seleksi.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
