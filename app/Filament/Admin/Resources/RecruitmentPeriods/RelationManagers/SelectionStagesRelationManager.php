<?php

namespace App\Filament\Admin\Resources\RecruitmentPeriods\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SelectionStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'selectionStages';

    protected static ?string $title = 'Tahapan Seleksi';

    protected static ?string $modelLabel = 'Tahap Seleksi';

    protected static ?string $pluralModelLabel = 'Tahapan Seleksi';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Tahap')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Contoh: Wawancara, Praktik Lapangan')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', \Illuminate\Support\Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->helperText('URL-friendly identifier'),
                ]),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(2)
                    ->maxLength(500),

                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required()
                        ->helperText('Urutan pelaksanaan tahap'),

                    Forms\Components\DateTimePicker::make('scheduled_date')
                        ->label('Jadwal')
                        ->native(false)
                        ->seconds(false),

                    Forms\Components\TextInput::make('location')
                        ->label('Lokasi')
                        ->maxLength(255)
                        ->placeholder('Basecamp Mapala'),
                ]),

                Forms\Components\Textarea::make('instructions')
                    ->label('Instruksi')
                    ->rows(3)
                    ->maxLength(1000)
                    ->helperText('Instruksi untuk peserta pada tahap ini'),

                Forms\Components\Section::make('Penilaian')
                    ->schema([
                        Forms\Components\Toggle::make('is_scored')
                            ->label('Dinilai')
                            ->default(true)
                            ->live()
                            ->helperText('Apakah tahap ini memiliki penilaian?'),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('max_score')
                                ->label('Nilai Maksimal')
                                ->numeric()
                                ->default(100)
                                ->minValue(1)
                                ->required(fn (Forms\Get $get) => $get('is_scored'))
                                ->visible(fn (Forms\Get $get) => $get('is_scored')),

                            Forms\Components\TextInput::make('passing_score')
                                ->label('Nilai Lulus')
                                ->numeric()
                                ->default(70)
                                ->minValue(0)
                                ->required(fn (Forms\Get $get) => $get('is_scored'))
                                ->visible(fn (Forms\Get $get) => $get('is_scored'))
                                ->helperText('Nilai minimal untuk lulus'),
                        ]),

                        Forms\Components\Repeater::make('criteria')
                            ->label('Kriteria Penilaian')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Kriteria')
                                        ->required()
                                        ->placeholder('Contoh: Fisik, Mental, Teamwork'),

                                    Forms\Components\TextInput::make('weight')
                                        ->label('Bobot (%)')
                                        ->numeric()
                                        ->default(25)
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->suffix('%'),

                                    Forms\Components\TextInput::make('max_score')
                                        ->label('Skor Maks')
                                        ->numeric()
                                        ->default(100),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Kriteria')
                            ->reorderable()
                            ->collapsible()
                            ->visible(fn (Forms\Get $get) => $get('is_scored'))
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                    ]),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->inline(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tahap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->description),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Jadwal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum dijadwalkan'),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_scored')
                    ->label('Dinilai')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('max_score')
                    ->label('Nilai Maks')
                    ->alignCenter()
                    ->placeholder('-')
                    ->formatStateUsing(fn ($record, $state) =>
                        $record->is_scored ? $state : '-'
                    ),

                Tables\Columns\TextColumn::make('passing_score')
                    ->label('Nilai Lulus')
                    ->alignCenter()
                    ->placeholder('-')
                    ->formatStateUsing(fn ($record, $state) =>
                        $record->is_scored ? $state : '-'
                    ),

                Tables\Columns\TextColumn::make('total_applicants')
                    ->label('Peserta')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($record) => $record->progress()->count()),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_scored')
                    ->label('Dinilai')
                    ->placeholder('Semua')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Tahap Seleksi')
                    ->modalHeading('Tambah Tahap Seleksi')
                    ->modalSubmitActionLabel('Simpan')
                    ->successNotificationTitle('Tahap seleksi berhasil ditambahkan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Tahap Seleksi')
                    ->modalSubmitActionLabel('Simpan')
                    ->successNotificationTitle('Tahap seleksi berhasil diperbarui'),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Tahap Seleksi')
                    ->modalDescription('Yakin ingin menghapus tahap seleksi ini? Data penilaian peserta akan terhapus!')
                    ->successNotificationTitle('Tahap seleksi berhasil dihapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Belum ada tahap seleksi')
            ->emptyStateDescription('Klik "Tambah Tahap Seleksi" untuk menambahkan tahap pertama.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
