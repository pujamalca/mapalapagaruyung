<?php

namespace App\Filament\Admin\Resources\Divisions\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $title = 'Anggota Divisi';

    protected static ?string $modelLabel = 'Anggota';

    protected static ?string $pluralModelLabel = 'Anggota';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('user_id')
                    ->label('Pilih Anggota')
                    ->relationship('', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(fn ($livewire) => $livewire instanceof Tables\Actions\EditAction),

            Forms\Components\DatePicker::make('joined_at')
                ->label('Tanggal Bergabung')
                ->default(now())
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y')
                ->maxDate(now()),

            Forms\Components\Select::make('role')
                ->label('Peran dalam Divisi')
                ->options([
                    'Ketua Divisi' => 'Ketua Divisi',
                    'Wakil Ketua' => 'Wakil Ketua',
                    'Sekretaris' => 'Sekretaris',
                    'Bendahara' => 'Bendahara',
                    'Koordinator' => 'Koordinator',
                    'Anggota' => 'Anggota',
                ])
                ->default('Anggota')
                ->searchable()
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->label('Status Aktif')
                ->default(true)
                ->inline(false)
                ->helperText('Anggota aktif akan ditampilkan di website'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pivot.role')
                    ->label('Peran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ketua Divisi' => 'success',
                        'Wakil Ketua' => 'info',
                        'Koordinator' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('pivot.joined_at')
                    ->label('Bergabung')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('pivot.is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Tidak Aktif')
                    ->queries(
                        true: fn ($query) => $query->wherePivot('is_active', true),
                        false: fn ($query) => $query->wherePivot('is_active', false),
                        blank: fn ($query) => $query,
                    ),

                Tables\Filters\SelectFilter::make('role')
                    ->label('Peran')
                    ->options([
                        'Ketua Divisi' => 'Ketua Divisi',
                        'Wakil Ketua' => 'Wakil Ketua',
                        'Sekretaris' => 'Sekretaris',
                        'Bendahara' => 'Bendahara',
                        'Koordinator' => 'Koordinator',
                        'Anggota' => 'Anggota',
                    ])
                    ->query(fn ($query, $state) =>
                        $query->when($state, fn ($q) => $q->wherePivot('role', $state['value']))
                    ),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Tambah Anggota')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Pilih Anggota')
                            ->searchable()
                            ->preload()
                            ->getSearchResultsUsing(fn (string $search) => \App\Models\User::where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->where('is_active', true)
                                ->limit(50)
                                ->pluck('name', 'id')
                            )
                            ->getOptionLabelUsing(fn ($value): ?string => \App\Models\User::find($value)?->name),

                        Forms\Components\DatePicker::make('joined_at')
                            ->label('Tanggal Bergabung')
                            ->default(now())
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('role')
                            ->label('Peran')
                            ->options([
                                'Ketua Divisi' => 'Ketua Divisi',
                                'Wakil Ketua' => 'Wakil Ketua',
                                'Sekretaris' => 'Sekretaris',
                                'Bendahara' => 'Bendahara',
                                'Koordinator' => 'Koordinator',
                                'Anggota' => 'Anggota',
                            ])
                            ->default('Anggota')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->modalHeading('Tambah Anggota ke Divisi')
                    ->modalSubmitActionLabel('Tambah')
                    ->successNotificationTitle('Anggota berhasil ditambahkan ke divisi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Anggota Divisi')
                    ->modalSubmitActionLabel('Simpan')
                    ->successNotificationTitle('Data anggota berhasil diperbarui'),

                Tables\Actions\DetachAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Anggota dari Divisi')
                    ->modalDescription('Yakin ingin menghapus anggota ini dari divisi? Anggota tidak akan dihapus dari sistem, hanya dikeluarkan dari divisi ini.')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->successNotificationTitle('Anggota berhasil dihapus dari divisi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Anggota dari Divisi')
                        ->modalDescription('Yakin ingin menghapus anggota terpilih dari divisi?')
                        ->modalSubmitActionLabel('Ya, Hapus'),
                ]),
            ])
            ->defaultSort('pivot.joined_at', 'desc')
            ->emptyStateHeading('Belum ada anggota')
            ->emptyStateDescription('Klik "Tambah Anggota" untuk menambahkan anggota pertama ke divisi ini.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
