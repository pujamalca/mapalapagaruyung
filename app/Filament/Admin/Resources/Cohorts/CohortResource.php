<?php

namespace App\Filament\Admin\Resources\Cohorts;

use App\Filament\Admin\Resources\Cohorts\Pages;
use App\Filament\Admin\Resources\Cohorts\Schemas\CohortForm;
use App\Filament\Admin\Resources\Cohorts\Tables\CohortsTable;
use App\Models\Cohort;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CohortResource extends Resource
{
    protected static ?string $model = Cohort::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Angkatan';

    protected static ?string $modelLabel = 'Angkatan';

    protected static ?string $pluralModelLabel = 'Angkatan';

    protected static UnitEnum|string|null $navigationGroup = 'Keanggotaan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CohortForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CohortsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\MembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCohorts::route('/'),
            'create' => Pages\CreateCohort::route('/create'),
            'edit' => Pages\EditCohort::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
