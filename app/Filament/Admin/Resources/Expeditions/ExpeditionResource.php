<?php

namespace App\Filament\Admin\Resources\Expeditions;

use App\Filament\Admin\Resources\Expeditions\Pages;
use App\Filament\Admin\Resources\Expeditions\RelationManagers;
use App\Filament\Admin\Resources\Expeditions\Schemas\ExpeditionForm;
use App\Filament\Admin\Resources\Expeditions\Tables\ExpeditionsTable;
use App\Models\Expedition;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class ExpeditionResource extends Resource
{
    protected static ?string $model = Expedition::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Ekspedisi';

    protected static ?string $modelLabel = 'Ekspedisi';

    protected static ?string $pluralModelLabel = 'Ekspedisi';

    protected static UnitEnum|string|null $navigationGroup = 'Kegiatan & Ekspedisi';

    protected static ?int $navigationSort = 1;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema(ExpeditionForm::schema());
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return ExpeditionsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ParticipantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpeditions::route('/'),
            'create' => Pages\CreateExpedition::route('/create'),
            'edit' => Pages\EditExpedition::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'ongoing')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
