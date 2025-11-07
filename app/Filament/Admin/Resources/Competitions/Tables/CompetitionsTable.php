<?php

namespace App\Filament\Admin\Resources\Competitions\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class CompetitionsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->organizer),

                Tables\Columns\TextColumn::make('event_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->event_type_label)
                    ->color('info'),

                Tables\Columns\TextColumn::make('competition_type')
                    ->label('Tingkat')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->competition_type_label)
                    ->color(fn (string $state): string => match ($state) {
                        'internal' => 'gray',
                        'external' => 'info',
                        'regional' => 'warning',
                        'national' => 'success',
                        'international' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn ($record) => $record->location),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn ($record) => $record->status_color)
                    ->sortable(),

                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Peserta')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($record) => $record->getParticipantCount()),

                Tables\Columns\TextColumn::make('medals')
                    ->label('Medali')
                    ->formatStateUsing(function ($record) {
                        $medals = $record->getMedalsCount();
                        return "🥇{$medals['gold']} 🥈{$medals['silver']} 🥉{$medals['bronze']}";
                    })
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_official_event')
                    ->label('Resmi')
                    ->boolean()
                    ->toggleable(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'planned' => 'Direncanakan',
                        'registration_open' => 'Pendaftaran Dibuka',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('event_type')
                    ->label('Jenis Event')
                    ->options([
                        'competition' => 'Kompetisi',
                        'workshop' => 'Workshop',
                        'seminar' => 'Seminar',
                        'gathering' => 'Gathering',
                        'festival' => 'Festival',
                    ]),

                Tables\Filters\SelectFilter::make('competition_type')
                    ->label('Tingkat')
                    ->options([
                        'internal' => 'Internal',
                        'external' => 'Eksternal',
                        'regional' => 'Regional',
                        'national' => 'Nasional',
                        'international' => 'Internasional',
                    ]),

                Tables\Filters\TernaryFilter::make('is_official_event')
                    ->label('Resmi')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Resmi')
                    ->falseLabel('Non-Resmi'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('start_event')
                        ->label('Mulai Event')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->visible(fn ($record) => in_array($record->status, ['planned', 'registration_open']))
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->markAsOngoing()),

                    Tables\Actions\Action::make('complete_event')
                        ->label('Selesaikan Event')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'ongoing')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->markAsCompleted()),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada kompetisi/event')
            ->emptyStateIcon('heroicon-o-trophy');
    }
}
