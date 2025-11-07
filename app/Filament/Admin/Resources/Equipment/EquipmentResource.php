<?php

namespace App\Filament\Admin\Resources\Equipment;

use App\Filament\Admin\Resources\Equipment\Pages;
use App\Filament\Admin\Resources\Equipment\RelationManagers;
use App\Filament\Admin\Resources\Equipment\Schemas\EquipmentForm;
use App\Filament\Admin\Resources\Equipment\Tables\EquipmentsTable;
use App\Models\Equipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Peralatan';

    protected static ?string $modelLabel = 'Peralatan';

    protected static ?string $pluralModelLabel = 'Peralatan';

    protected static UnitEnum|string|null $navigationGroup = 'Inventaris';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema(EquipmentForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(EquipmentsTable::columns())
            ->filters(EquipmentsTable::filters())
            ->actions(EquipmentsTable::actions())
            ->bulkActions(EquipmentsTable::bulkActions())
            ->defaultSort('code', 'asc')
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BorrowingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $needsMaintenance = Equipment::whereNotNull('next_maintenance_date')
            ->where(function ($query) {
                $query->where('next_maintenance_date', '<=', now())
                    ->orWhere('next_maintenance_date', '<=', now()->addDays(30));
            })
            ->count();

        return $needsMaintenance > 0 ? (string) $needsMaintenance : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
