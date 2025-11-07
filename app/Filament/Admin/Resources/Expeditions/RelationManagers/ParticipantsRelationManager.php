<?php

namespace App\Filament\Admin\Resources\Expeditions\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'expeditionParticipants';

    protected static ?string $title = 'Peserta Ekspedisi';

    protected static ?string $modelLabel = 'Peserta';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Peserta')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Pilih member yang akan mengikuti ekspedisi'),

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
                        'approved' => 'Disetujui',
                        'confirmed' => 'Dikonfirmasi',
                        'participating' => 'Mengikuti',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('registered')
                    ->required(),

                Forms\Components\TextInput::make('role')
                    ->label('Peran')
                    ->maxLength(255)
                    ->placeholder('Contoh: Navigator, Medic, Cook, Dokumentasi'),

                Forms\Components\Toggle::make('is_leader')
                    ->label('Ketua Tim')
                    ->default(false)
                    ->inline(false),

                Section::make('Kesehatan & Kebugaran')
                    ->schema([
                        Forms\Components\Textarea::make('health_declaration')
                            ->label('Deklarasi Kesehatan')
                            ->rows(3)
                            ->helperText('Kondisi kesehatan peserta'),

                        Forms\Components\Toggle::make('fitness_verified')
                            ->label('Kebugaran Terverifikasi')
                            ->default(false)
                            ->inline(false),

                        Forms\Components\Textarea::make('medical_notes')
                            ->label('Catatan Medis')
                            ->rows(2)
                            ->helperText('Alergi, riwayat penyakit, dll'),
                    ])
                    ->visible(fn ($record) => $record !== null),

                Section::make('Peralatan')
                    ->schema([
                        Forms\Components\Toggle::make('equipment_verified')
                            ->label('Peralatan Terverifikasi')
                            ->default(false)
                            ->inline(false),

                        Forms\Components\DateTimePicker::make('equipment_checked_at')
                            ->label('Tanggal Pengecekan')
                            ->native(false)
                            ->seconds(false)
                            ->disabled(),

                        Forms\Components\Textarea::make('equipment_notes')
                            ->label('Catatan Peralatan')
                            ->rows(2)
                            ->helperText('Peralatan yang perlu dilengkapi, dll'),
                    ])
                    ->visible(fn ($record) => $record !== null),

                Section::make('Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('payment_amount')
                            ->label('Jumlah Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\Toggle::make('payment_verified')
                            ->label('Pembayaran Terverifikasi')
                            ->default(false)
                            ->inline(false),

                        Forms\Components\DateTimePicker::make('payment_date')
                            ->label('Tanggal Bayar')
                            ->native(false)
                            ->seconds(false)
                            ->disabled(),
                    ])
                    ->visible(fn ($record) => $record !== null),

                Section::make('Evaluasi & Kontribusi')
                    ->schema([
                        Forms\Components\TextInput::make('performance_rating')
                            ->label('Rating Performa')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->suffix('/ 5'),

                        Forms\Components\Textarea::make('performance_notes')
                            ->label('Catatan Performa')
                            ->rows(2),

                        Forms\Components\Repeater::make('tasks_assigned')
                            ->label('Tugas yang Diberikan')
                            ->schema([
                                Forms\Components\TextInput::make('task')
                                    ->label('Tugas')
                                    ->required(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Tugas')
                            ->collapsible(),

                        Forms\Components\Textarea::make('contribution_notes')
                            ->label('Catatan Kontribusi')
                            ->rows(2)
                            ->helperText('Kontribusi peserta selama ekspedisi'),
                    ])
                    ->visible(fn ($record) => $record !== null && in_array($record->status, ['participating', 'completed'])),

                Section::make('Feedback Peserta')
                    ->schema([
                        Forms\Components\Textarea::make('participant_feedback')
                            ->label('Feedback')
                            ->rows(3)
                            ->helperText('Feedback dari peserta tentang ekspedisi'),
                    ])
                    ->visible(fn ($record) => $record !== null && $record->status === 'completed'),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2),
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

                Tables\Columns\TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->color('info')
                    ->default('-'),

                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Tgl Registrasi')
                    ->dateTime('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn ($record) => $record->status_color)
                    ->sortable(),

                Tables\Columns\IconColumn::make('fitness_verified')
                    ->label('Fit')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('equipment_verified')
                    ->label('Alat')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('payment_verified')
                    ->label('Bayar')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('performance_rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => $state ? $state . '/5 ⭐' : '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_leader')
                    ->label('Leader')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'registered' => 'Terdaftar',
                        'approved' => 'Disetujui',
                        'confirmed' => 'Dikonfirmasi',
                        'participating' => 'Mengikuti',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'rejected' => 'Ditolak',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('fitness_verified')
                    ->label('Kebugaran')
                    ->placeholder('Semua')
                    ->trueLabel('Terverifikasi')
                    ->falseLabel('Belum'),

                Tables\Filters\TernaryFilter::make('equipment_verified')
                    ->label('Peralatan')
                    ->placeholder('Semua')
                    ->trueLabel('Terverifikasi')
                    ->falseLabel('Belum'),

                Tables\Filters\TernaryFilter::make('payment_verified')
                    ->label('Pembayaran')
                    ->placeholder('Semua')
                    ->trueLabel('Terverifikasi')
                    ->falseLabel('Belum'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Peserta')
                    ->icon('heroicon-o-plus')
                    ->modalWidth('4xl'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth('4xl'),

                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->visible(fn ($record) => $record->status === 'registered')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->approve())
                        ->successNotificationTitle('Peserta disetujui'),

                    Tables\Actions\Action::make('confirm')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check')
                        ->color('primary')
                        ->visible(fn ($record) => $record->status === 'approved')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->confirm())
                        ->successNotificationTitle('Peserta dikonfirmasi'),

                    Tables\Actions\Action::make('verify_fitness')
                        ->label('Verifikasi Fitness')
                        ->icon('heroicon-o-heart')
                        ->color('success')
                        ->visible(fn ($record) => !$record->fitness_verified)
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->verifyFitness())
                        ->successNotificationTitle('Fitness terverifikasi'),

                    Tables\Actions\Action::make('verify_equipment')
                        ->label('Verifikasi Peralatan')
                        ->icon('heroicon-o-wrench')
                        ->color('success')
                        ->visible(fn ($record) => !$record->equipment_verified)
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->verifyEquipment())
                        ->successNotificationTitle('Peralatan terverifikasi'),

                    Tables\Actions\Action::make('verify_payment')
                        ->label('Verifikasi Pembayaran')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->visible(fn ($record) => !$record->payment_verified)
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->verifyPayment())
                        ->successNotificationTitle('Pembayaran terverifikasi'),

                    Tables\Actions\Action::make('rate_performance')
                        ->label('Beri Rating')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->visible(fn ($record) => $record->status === 'completed')
                        ->form([
                            Forms\Components\TextInput::make('rating')
                                ->label('Rating')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(5)
                                ->required()
                                ->suffix('/ 5'),

                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan')
                                ->rows(3),
                        ])
                        ->action(function ($record, array $data) {
                            $record->ratePerformance($data['rating'], $data['notes']);
                        })
                        ->successNotificationTitle('Rating berhasil diberikan'),

                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn ($record) => in_array($record->status, ['registered', 'rejected', 'cancelled'])),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_participants')
                        ->label('Setujui Peserta')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'registered') {
                                    $record->approve();
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Peserta disetujui'),

                    Tables\Actions\BulkAction::make('confirm_participants')
                        ->label('Konfirmasi Peserta')
                        ->icon('heroicon-o-check')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'approved') {
                                    $record->confirm();
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
                                    'approved' => 'Disetujui',
                                    'confirmed' => 'Dikonfirmasi',
                                    'participating' => 'Mengikuti',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    'rejected' => 'Ditolak',
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
            ->emptyStateHeading('Belum ada peserta')
            ->emptyStateDescription('Tambahkan peserta untuk ekspedisi ini.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
