<?php

namespace App\Filament\Admin\Resources\Cohorts\Schemas;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;

class CohortForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Dasar')
                ->description('Data dasar angkatan/kader')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Angkatan')
                        ->placeholder('Kader XXIII')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('year')
                        ->label('Tahun')
                        ->required()
                        ->numeric()
                        ->minValue(2000)
                        ->maxValue(now()->year + 1)
                        ->default(now()->year)
                        ->columnSpan(1),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Aktif',
                            'alumni' => 'Alumni',
                        ])
                        ->default('active')
                        ->required()
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('theme')
                        ->label('Tema')
                        ->placeholder('Menggapai Puncak Tertinggi')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->placeholder('Cerita tentang angkatan ini...')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('member_count')
                        ->label('Jumlah Anggota')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Jumlah anggota dihitung otomatis dari data anggota')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0)
                        ->helperText('Urutan tampilan (angka lebih kecil = lebih awal)')
                        ->columnSpan(1),
                ])
                ->columns(2),

            Section::make('Foto Angkatan')
                ->description('Upload foto bersama angkatan')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('photo')
                        ->collection('photo')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->maxSize(2048)
                        ->helperText('Maksimal 2MB. Format: JPG, PNG, WebP')
                        ->columnSpanFull(),
                ])
                ->collapsible(),
        ]);
    }
}
