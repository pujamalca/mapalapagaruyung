<?php

namespace App\Filament\Admin\Resources\Divisions;

use App\Filament\Admin\Resources\Divisions\Pages;
use App\Filament\Admin\Resources\Divisions\RelationManagers;
use App\Filament\Admin\Resources\Divisions\Schemas\DivisionForm;
use App\Filament\Admin\Resources\Divisions\Tables\DivisionsTable;
use App\Models\Division;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class DivisionResource extends Resource
{
    protected static ?string $model = Division::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Divisi';

    protected static ?string $modelLabel = 'Divisi';

    protected static ?string $pluralModelLabel = 'Divisi';

    protected static ?string $navigationGroup = 'Keanggotaan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema(DivisionForm::schema());
    }

    public static function table(Table $table): Table
    {
        return DivisionsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDivisions::route('/'),
            'create' => Pages\CreateDivision::route('/create'),
            'edit' => Pages\EditDivision::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
