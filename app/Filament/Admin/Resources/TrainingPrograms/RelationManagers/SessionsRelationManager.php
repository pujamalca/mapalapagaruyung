<?php

namespace App\Filament\Admin\Resources\TrainingPrograms\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'trainingSessions';

    protected static ?string $title = 'Sesi Pelatihan';

    protected static ?string $modelLabel = 'Sesi';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Sesi')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', \Illuminate\Support\Str::slug($state))
                        )
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->columnSpan(1),
                ]),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->maxLength(1000),

                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('order')
                        ->label('Urutan')
                        ->numeric()
                        ->required()
                        ->default(fn () => $this->getOwnerRecord()->trainingSessions()->max('order') + 1)
                        ->minValue(1)
                        ->helperText('Urutan sesi dalam program'),

                    Forms\Components\DateTimePicker::make('scheduled_date')
                        ->label('Jadwal')
                        ->required()
                        ->native(false)
                        ->seconds(false)
                        ->default(now()),

                    Forms\Components\TextInput::make('duration_minutes')
                        ->label('Durasi (menit)')
                        ->numeric()
                        ->required()
                        ->default(120)
                        ->minValue(15)
                        ->suffix('menit'),
                ]),

                Forms\Components\TextInput::make('location')
                    ->label('Lokasi')
                    ->maxLength(255)
                    ->placeholder('Contoh: Basecamp, Lapangan, Gunung Singgalang'),

                Forms\Components\Textarea::make('learning_objectives')
                    ->label('Tujuan Pembelajaran')
                    ->rows(3)
                    ->helperText('Apa yang akan dipelajari peserta di sesi ini?'),

                Forms\Components\RichEditor::make('content')
                    ->label('Materi')
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'italic',
                        'orderedList',
                        'redo',
                        'undo',
                    ])
                    ->helperText('Konten materi yang akan diajarkan'),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('instructor_id')
                        ->label('Instruktur')
                        ->relationship('instructor', 'name')
                        ->searchable()
                        ->preload()
                        ->helperText('Pilih dari member BKP'),

                    Forms\Components\TextInput::make('instructor_name')
                        ->label('Nama Instruktur (Eksternal)')
                        ->maxLength(255)
                        ->helperText('Jika instruktur dari luar'),
                ]),

                Forms\Components\Repeater::make('materials')
                    ->label('Materi yang Diperlukan')
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->label('Item')
                            ->required()
                            ->placeholder('Contoh: Modul Navigasi, Slide Presentasi'),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel('Tambah Materi')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),

                Forms\Components\Repeater::make('equipment_needed')
                    ->label('Peralatan yang Dibutuhkan')
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->label('Item')
                            ->required()
                            ->placeholder('Contoh: Kompas, Peta, GPS'),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel('Tambah Peralatan')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),

                Forms\Components\Section::make('Penilaian')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make('has_quiz')
                                ->label('Ada Kuis')
                                ->default(false)
                                ->inline(false),

                            Forms\Components\Toggle::make('has_practical')
                                ->label('Ada Praktik')
                                ->default(true)
                                ->inline(false),

                            Forms\Components\TextInput::make('max_score')
                                ->label('Nilai Maksimal')
                                ->numeric()
                                ->default(100)
                                ->minValue(0)
                                ->maxValue(100),
                        ]),
                    ]),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Terjadwal',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('scheduled')
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Sesi')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->description),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Jadwal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->getDurationFormatted()),

                Tables\Columns\TextColumn::make('instructor.name')
                    ->label('Instruktur')
                    ->default(fn ($record) => $record->instructor_name ?? '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'scheduled' => 'Terjadwal',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'ongoing' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('attendance_count')
                    ->label('Kehadiran')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($record) => $record->attendance()->count())
                    ->description(fn ($record) => $record->getAttendanceRate() . '% hadir'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Terjadwal',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\TernaryFilter::make('has_quiz')
                    ->label('Ada Kuis')
                    ->placeholder('Semua')
                    ->trueLabel('Ada Kuis')
                    ->falseLabel('Tidak Ada Kuis'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Sesi')
                    ->icon('heroicon-o-plus')
                    ->modalWidth('5xl'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth('5xl'),

                    Tables\Actions\Action::make('manage_attendance')
                        ->label('Kelola Kehadiran')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->color('info')
                        ->url(fn ($record) => route('filament.admin.resources.training-sessions.edit', $record))
                        ->visible(fn ($record) => in_array($record->status, ['ongoing', 'completed'])),

                    Tables\Actions\Action::make('start_session')
                        ->label('Mulai Sesi')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'ongoing']))
                        ->visible(fn ($record) => $record->status === 'scheduled'),

                    Tables\Actions\Action::make('complete_session')
                        ->label('Selesaikan Sesi')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'completed']))
                        ->visible(fn ($record) => $record->status === 'ongoing'),

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
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
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
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Belum ada sesi pelatihan')
            ->emptyStateDescription('Tambahkan sesi pelatihan untuk program ini.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}
