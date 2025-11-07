<?php

namespace App\Filament\Admin\Resources\EquipmentBorrowings;

use App\Filament\Admin\Resources\EquipmentBorrowings\Pages;
use App\Filament\Admin\Resources\EquipmentBorrowings\Schemas\EquipmentBorrowingForm;
use App\Filament\Admin\Resources\EquipmentBorrowings\Tables\EquipmentBorrowingsTable;
use App\Models\EquipmentBorrowing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EquipmentBorrowingResource extends Resource
{
    protected static ?string $model = EquipmentBorrowing::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Peminjaman Peralatan';

    protected static ?string $modelLabel = 'Peminjaman Peralatan';

    protected static ?string $pluralModelLabel = 'Peminjaman Peralatan';

    protected static UnitEnum|string|null $navigationGroup = 'Inventaris';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema(EquipmentBorrowingForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(EquipmentBorrowingsTable::columns())
            ->filters(EquipmentBorrowingsTable::filters())
            ->actions(EquipmentBorrowingsTable::actions())
            ->bulkActions(EquipmentBorrowingsTable::bulkActions())
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipmentBorrowings::route('/'),
            'create' => Pages\CreateEquipmentBorrowing::route('/create'),
            'view' => Pages\ViewEquipmentBorrowing::route('/{record}'),
            'edit' => Pages\EditEquipmentBorrowing::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = EquipmentBorrowing::where('status', 'pending')->count();
        $overdueCount = EquipmentBorrowing::where('status', '!=', 'returned')
            ->where('due_date', '<', now())
            ->count();

        $totalBadge = $pendingCount + $overdueCount;

        return $totalBadge > 0 ? (string) $totalBadge : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueCount = EquipmentBorrowing::where('status', '!=', 'returned')
            ->where('due_date', '<', now())
            ->count();

        return $overdueCount > 0 ? 'danger' : 'warning';
    }
}
