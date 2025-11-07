<?php

namespace App\Filament\Admin\Resources\Competitions;

use App\Filament\Admin\Resources\Competitions\Pages;
use App\Filament\Admin\Resources\Competitions\RelationManagers;
use App\Filament\Admin\Resources\Competitions\Schemas\CompetitionForm;
use App\Filament\Admin\Resources\Competitions\Tables\CompetitionsTable;
use App\Models\Competition;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Kompetisi & Event';

    protected static ?string $modelLabel = 'Kompetisi/Event';

    protected static ?string $pluralModelLabel = 'Kompetisi & Event';

    protected static UnitEnum|string|null $navigationGroup = 'Kegiatan & Ekspedisi';

    protected static ?int $navigationSort = 2;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema(CompetitionForm::schema());
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return CompetitionsTable::table($table);
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
            'index' => Pages\ListCompetitions::route('/'),
            'create' => Pages\CreateCompetition::route('/create'),
            'edit' => Pages\EditCompetition::route('/{record}/edit'),
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
