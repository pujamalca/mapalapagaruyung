<?php

namespace App\Filament\Admin\Resources\Cohorts\Tables;

use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CohortsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->getStateUsing(function (\App\Models\Cohort $record): string {
                        $mediaUrl = $record->getFirstMediaUrl('photo');

                        if ($mediaUrl) {
                            return $mediaUrl;
                        }

                        $initials = Str::of($record->name)
                            ->trim()
                            ->explode(' ')
                            ->filter()
                            ->map(fn ($segment) => Str::upper(Str::substr($segment, 0, 1)))
                            ->take(2)
                            ->implode('');

                        if (blank($initials)) {
                            $initials = 'CO';
                        }

                        $color = substr(md5($record->name ?? 'cohort'), 0, 6);

                        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128">
    <rect width="128" height="128" rx="64" fill="#{$color}"/>
    <text x="50%" y="52%" dominant-baseline="middle" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="48" fill="#ffffff" font-weight="600">{$initials}</text>
</svg>
SVG;

                        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
                    })
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Angkatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('theme')
                    ->label('Tema')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('member_count')
                    ->label('Anggota')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'alumni' => 'Alumni',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'alumni' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'alumni' => 'Alumni',
                    ]),

                Tables\Filters\Filter::make('year')
                    ->form([
                        TextInput::make('year_from')
                            ->label('Dari Tahun')
                            ->numeric(),
                        TextInput::make('year_to')
                            ->label('Sampai Tahun')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['year_from'],
                                fn (Builder $query, $year): Builder => $query->where('year', '>=', $year),
                            )
                            ->when(
                                $data['year_to'],
                                fn (Builder $query, $year): Builder => $query->where('year', '<=', $year),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('year', 'desc')
            ->poll('30s')
            ->emptyStateHeading('Belum ada data angkatan')
            ->emptyStateDescription('Klik tombol "Buat" untuk menambahkan angkatan pertama.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
