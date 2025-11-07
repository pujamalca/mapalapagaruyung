<?php

namespace App\Filament\Admin\Resources\Applicants;

use App\Filament\Admin\Resources\Applicants\Pages;
use App\Filament\Admin\Resources\Applicants\RelationManagers;
use App\Filament\Admin\Resources\Applicants\Schemas\ApplicantForm;
use App\Filament\Admin\Resources\Applicants\Tables\ApplicantsTable;
use App\Models\Applicant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Rekrutmen';

    protected static ?string $navigationLabel = 'Pendaftar';

    protected static ?string $modelLabel = 'Pendaftar';

    protected static ?string $pluralModelLabel = 'Pendaftar';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(ApplicantForm::schema());
    }

    public static function table(Table $table): Table
    {
        return ApplicantsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProgressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicants::route('/'),
            'edit' => Pages\EditApplicant::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'registered')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view-applicants') ?? false;
    }

    public static function canCreate(): bool
    {
        return false; // Applicants register via public form
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('manage-applicants') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('manage-applicants') ?? false;
    }
}
