<?php

namespace App\Filament\Admin\Resources\EquipmentBorrowings\Pages;

use App\Filament\Admin\Resources\EquipmentBorrowings\EquipmentBorrowingResource;
use App\Models\EquipmentBorrowing;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEquipmentBorrowings extends ListRecords
{
    protected static string $resource = EquipmentBorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Peminjaman'),

            'pending' => Tab::make('Menunggu Persetujuan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(fn () => EquipmentBorrowing::where('status', 'pending')->count())
                ->badgeColor('warning'),

            'active' => Tab::make('Sedang Dipinjam')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['approved', 'active']))
                ->badge(fn () => EquipmentBorrowing::whereIn('status', ['approved', 'active'])->count())
                ->badgeColor('info'),

            'overdue' => Tab::make('Terlambat')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('status', '!=', 'returned')
                    ->where('due_date', '<', now())
                )
                ->badge(fn () => EquipmentBorrowing::where('status', '!=', 'returned')
                    ->where('due_date', '<', now())
                    ->count()
                )
                ->badgeColor('danger'),

            'returned' => Tab::make('Sudah Dikembalikan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'returned'))
                ->badge(fn () => EquipmentBorrowing::where('status', 'returned')->count())
                ->badgeColor('success'),

            'cancelled' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled'))
                ->badge(fn () => EquipmentBorrowing::where('status', 'cancelled')->count())
                ->badgeColor('gray'),
        ];
    }
}
