<?php

namespace App\Filament\Admin\Resources\Divisions\Schemas;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class DivisionForm
{
    public static function schema(): array
    {
        return [
            Section::make('Informasi Dasar')
                ->description('Data dasar divisi')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Divisi')
                        ->placeholder('Gunung & Rimba')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Str::slug($state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->helperText('URL-friendly version dari nama divisi')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('head_id')
                        ->label('Ketua Divisi')
                        ->options(fn () => User::query()
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText('Ketua/koordinator divisi')
                        ->columnSpan(2),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->inline(false)
                        ->helperText('Divisi aktif akan ditampilkan di website')
                        ->columnSpan(1),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->placeholder('Deskripsi singkat tentang divisi ini...')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('work_program')
                        ->label('Program Kerja')
                        ->placeholder('Rencana kegiatan dan program kerja divisi...')
                        ->toolbarButtons([
                            'bold',
                            'bulletList',
                            'italic',
                            'orderedList',
                            'redo',
                            'undo',
                        ])
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Tampilan & Identitas')
                ->description('Icon, warna, dan visual divisi')
                ->schema([
                    Forms\Components\TextInput::make('icon')
                        ->label('Icon')
                        ->placeholder('🏔️')
                        ->maxLength(10)
                        ->helperText('Emoji atau nama icon (contoh: 🏔️ atau heroicon-o-mountain)')
                        ->columnSpan(1),

                    Forms\Components\ColorPicker::make('color')
                        ->label('Warna')
                        ->default('#3B82F6')
                        ->helperText('Warna identitas divisi')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan Tampilan')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Urutan tampilan di website (angka lebih kecil = lebih awal)')
                        ->columnSpan(1),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Logo & Foto')
                ->description('Upload logo dan foto kegiatan divisi')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('logo')
                        ->collection('logo')
                        ->label('Logo Divisi')
                        ->image()
                        ->imageEditor()
                        ->maxSize(1024)
                        ->helperText('Logo divisi (Maksimal 1MB, Format: JPG, PNG, WebP, SVG)')
                        ->columnSpan(2),

                    SpatieMediaLibraryFileUpload::make('photos')
                        ->collection('photos')
                        ->label('Foto Kegiatan')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->maxFiles(10)
                        ->maxSize(2048)
                        ->helperText('Foto-foto kegiatan divisi (Maksimal 10 foto, 2MB per foto)')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }
}
