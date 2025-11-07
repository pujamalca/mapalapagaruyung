<?php

namespace App\Filament\Admin\Resources\RecruitmentPeriods;

use App\Filament\Admin\Resources\RecruitmentPeriods\Pages;
use App\Filament\Admin\Resources\RecruitmentPeriods\RelationManagers;
use App\Filament\Admin\Resources\RecruitmentPeriods\Schemas\RecruitmentPeriodForm;
use App\Filament\Admin\Resources\RecruitmentPeriods\Tables\RecruitmentPeriodsTable;
use App\Models\RecruitmentPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class RecruitmentPeriodResource extends Resource
{
    protected static ?string $model = RecruitmentPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static UnitEnum|string|null $navigationGroup = 'Rekrutmen';

    protected static ?string $navigationLabel = 'Periode Recruitment';

    protected static ?string $modelLabel = 'Periode Recruitment';

    protected static ?string $pluralModelLabel = 'Periode Recruitment';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(RecruitmentPeriodForm::schema());
    }

    public static function table(Table $table): Table
    {
        return RecruitmentPeriodsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SelectionStagesRelationManager::class,
            RelationManagers\ApplicantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecruitmentPeriods::route('/'),
            'create' => Pages\CreateRecruitmentPeriod::route('/create'),
            'edit' => Pages\EditRecruitmentPeriod::route('/{record}/edit'),
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

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage-recruitment') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage-recruitment') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('manage-recruitment') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('manage-recruitment') ?? false;
    }
}
