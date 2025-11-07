<?php

namespace App\Filament\Admin\Resources\EquipmentBorrowings\Pages;

use App\Filament\Admin\Resources\EquipmentBorrowings\EquipmentBorrowingResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;

class ViewEquipmentBorrowing extends ViewRecord
{
    protected static string $resource = EquipmentBorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn ($record) => $record->status === 'pending'),

            Actions\Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => $record->status === 'pending')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->approve(auth()->id());
                    $record->activate();
                    $this->refreshFormData(['approved_by', 'approved_at', 'status']);
                })
                ->successNotificationTitle('Peminjaman disetujui'),

            Actions\Action::make('return')
                ->label('Kembalikan')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->visible(fn ($record) => in_array($record->status, ['approved', 'active']))
                ->form([
                    Forms\Components\Select::make('condition_returned')
                        ->label('Kondisi Saat Dikembalikan')
                        ->options([
                            'excellent' => 'Sangat Baik',
                            'good' => 'Baik',
                            'fair' => 'Cukup',
                            'poor' => 'Buruk',
                            'damaged' => 'Rusak',
                        ])
                        ->required()
                        ->default('good')
                        ->live(),

                    Forms\Components\Textarea::make('condition_notes_returned')
                        ->label('Catatan Kondisi')
                        ->rows(2),

                    Forms\Components\Toggle::make('is_damaged')
                        ->label('Ada Kerusakan?')
                        ->live(),

                    Forms\Components\Textarea::make('damage_description')
                        ->label('Deskripsi Kerusakan')
                        ->rows(2)
                        ->visible(fn (Forms\Get $get) => $get('is_damaged')),

                    Forms\Components\TextInput::make('damage_cost')
                        ->label('Biaya Perbaikan')
                        ->numeric()
                        ->prefix('Rp')
                        ->visible(fn (Forms\Get $get) => $get('is_damaged')),
                ])
                ->action(function ($record, array $data) {
                    $record->returnEquipment($data);
                })
                ->successNotificationTitle('Peralatan berhasil dikembalikan')
                ->successRedirectUrl(fn () => static::getResource()::getUrl('index')),

            Actions\Action::make('cancel')
                ->label('Batalkan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => in_array($record->status, ['pending', 'approved']))
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->cancel();
                })
                ->successNotificationTitle('Peminjaman dibatalkan')
                ->successRedirectUrl(fn () => static::getResource()::getUrl('index')),

            Actions\DeleteAction::make()
                ->visible(fn ($record) => in_array($record->status, ['pending', 'cancelled'])),
        ];
    }
}
