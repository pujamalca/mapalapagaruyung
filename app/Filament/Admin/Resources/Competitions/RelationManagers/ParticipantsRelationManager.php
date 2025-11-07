<?php

namespace App\Filament\Admin\Resources\Competitions\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'competitionParticipants';

    protected static ?string $title = 'Peserta';

    protected static ?string $modelLabel = 'Peserta';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Tabs::make('Participant')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Data Dasar')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Peserta')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\DateTimePicker::make('registered_at')
                                    ->label('Tanggal Daftar')
                                    ->required()
                                    ->native(false)
                                    ->default(now()),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'registered' => 'Terdaftar',
                                        'confirmed' => 'Dikonfirmasi',
                                        'participating' => 'Mengikuti',
                                        'completed' => 'Selesai',
                                        'withdrawn' => 'Mengundurkan Diri',
                                        'disqualified' => 'Diskualifikasi',
                                    ])
                                    ->default('registered')
                                    ->required(),

                                Forms\Components\TextInput::make('category')
                                    ->label('Kategori Lomba')
                                    ->placeholder('Speed Climbing, Lead Climbing, dll'),

                                Forms\Components\TextInput::make('bib_number')
                                    ->label('Nomor Dada/BIB'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Tim')
                            ->schema([
                                Forms\Components\TextInput::make('team_name')
                                    ->label('Nama Tim'),

                                Forms\Components\Toggle::make('is_team_leader')
                                    ->label('Ketua Tim')
                                    ->inline(false),

                                Forms\Components\Repeater::make('team_members')
                                    ->label('Anggota Tim')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama')
                                            ->required(),
                                    ])
                                    ->collapsible(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Hasil & Prestasi')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('rank')
                                        ->label('Peringkat')
                                        ->placeholder('Juara 1, 2, 3, dll'),

                                    Forms\Components\TextInput::make('position')
                                        ->label('Posisi (Numerik)')
                                        ->numeric(),
                                ]),

                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('score')
                                        ->label('Skor')
                                        ->numeric(),

                                    Forms\Components\TextInput::make('time_record')
                                        ->label('Catatan Waktu')
                                        ->placeholder('HH:MM:SS'),
                                ]),

                                Forms\Components\Select::make('medal_type')
                                    ->label('Medali')
                                    ->options([
                                        'gold' => '🥇 Emas',
                                        'silver' => '🥈 Perak',
                                        'bronze' => '🥉 Perunggu',
                                    ]),

                                Forms\Components\Repeater::make('achievements')
                                    ->label('Prestasi Lainnya')
                                    ->schema([
                                        Forms\Components\TextInput::make('achievement')->required(),
                                    ])
                                    ->collapsible(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Sertifikat')
                            ->schema([
                                Forms\Components\Toggle::make('certificate_issued')
                                    ->label('Sertifikat Diterbitkan')
                                    ->inline(false),

                                Forms\Components\TextInput::make('certificate_number')
                                    ->label('Nomor Sertifikat'),

                                Forms\Components\DateTimePicker::make('certificate_issued_at')
                                    ->label('Tanggal Terbit')
                                    ->native(false),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('rank')
                    ->label('Peringkat')
                    ->badge()
                    ->color('warning')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('medal_type')
                    ->label('Medali')
                    ->formatStateUsing(fn ($record) => $record->medal_label)
                    ->badge()
                    ->color(fn ($record) => $record->medal_color),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn ($record) => $record->status_color),

                Tables\Columns\IconColumn::make('certificate_issued')
                    ->label('Sertifikat')
                    ->boolean(),

                Tables\Columns\TextColumn::make('team_name')
                    ->label('Tim')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'registered' => 'Terdaftar',
                        'confirmed' => 'Dikonfirmasi',
                        'participating' => 'Mengikuti',
                        'completed' => 'Selesai',
                    ]),

                Tables\Filters\SelectFilter::make('medal_type')
                    ->label('Medali')
                    ->options([
                        'gold' => 'Emas',
                        'silver' => 'Perak',
                        'bronze' => 'Perunggu',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Peserta')
                    ->modalWidth('5xl'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth('5xl'),

                    Tables\Actions\Action::make('record_result')
                        ->label('Catat Hasil')
                        ->icon('heroicon-o-trophy')
                        ->color('warning')
                        ->form([
                            Forms\Components\TextInput::make('rank')->label('Peringkat')->required(),
                            Forms\Components\TextInput::make('position')->label('Posisi')->numeric(),
                            Forms\Components\TextInput::make('score')->label('Skor')->numeric(),
                            Forms\Components\Select::make('medal_type')
                                ->label('Medali')
                                ->options([
                                    'gold' => '🥇 Emas',
                                    'silver' => '🥈 Perak',
                                    'bronze' => '🥉 Perunggu',
                                ]),
                        ])
                        ->action(fn ($record, array $data) => $record->recordResult($data)),

                    Tables\Actions\Action::make('issue_certificate')
                        ->label('Terbitkan Sertifikat')
                        ->icon('heroicon-o-document-check')
                        ->color('success')
                        ->visible(fn ($record) => !$record->certificate_issued)
                        ->form([
                            Forms\Components\TextInput::make('certificate_number')
                                ->label('Nomor Sertifikat')
                                ->required(),
                        ])
                        ->action(fn ($record, array $data) => $record->issueCertificate($data['certificate_number'])),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('confirm')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->confirm()),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada peserta')
            ->emptyStateIcon('heroicon-o-users');
    }
}
