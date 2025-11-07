<?php

namespace App\Filament\Admin\Resources\TrainingPrograms;

use App\Filament\Admin\Resources\TrainingPrograms\Pages;
use App\Filament\Admin\Resources\TrainingPrograms\RelationManagers;
use App\Filament\Admin\Resources\TrainingPrograms\Schemas\TrainingProgramForm;
use App\Filament\Admin\Resources\TrainingPrograms\Tables\TrainingProgramsTable;
use App\Models\TrainingProgram;
use Filament\Resources\Resource;

class TrainingProgramResource extends Resource
{
    protected static ?string $model = TrainingProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Program Pelatihan';

    protected static ?string $modelLabel = 'Program Pelatihan';

    protected static ?string $pluralModelLabel = 'Program Pelatihan';

    protected static ?string $navigationGroup = 'BKP & Pelatihan';

    protected static ?int $navigationSort = 1;

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema(TrainingProgramForm::schema());
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return TrainingProgramsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SessionsRelationManager::class,
            RelationManagers\ParticipantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingPrograms::route('/'),
            'create' => Pages\CreateTrainingProgram::route('/create'),
            'edit' => Pages\EditTrainingProgram::route('/{record}/edit'),
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
