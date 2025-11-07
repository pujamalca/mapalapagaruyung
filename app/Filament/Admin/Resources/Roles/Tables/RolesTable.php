<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Models\Role;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(fn (Role $record): string => RoleResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->tooltip(fn (Role $record) => $record->permissions->pluck('name')->join(', '))
                    ->sortable(),
            IconColumn::make('is_system')
                ->label('Role Sistem')
                ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }

}
