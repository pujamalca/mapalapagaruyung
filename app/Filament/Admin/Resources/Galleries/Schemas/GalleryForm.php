<?php

namespace App\Filament\Admin\Resources\Galleries\Schemas;

use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;

class GalleryForm
{
    public static function schema(): array
    {
        return [
            Tabs::make('Gallery')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Album')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', \Illuminate\Support\Str::slug($state))
                                    ),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ]),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(4)
                                ->maxLength(1000),

                            Grid::make(3)->schema([
                                Forms\Components\Select::make('gallery_category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\DatePicker::make('event_date')
                                    ->label('Tanggal Event')
                                    ->native(false)
                                    ->default(now()),

                                Forms\Components\TextInput::make('location')
                                    ->label('Lokasi')
                                    ->placeholder('Contoh: Padang, Sumatera Barat'),
                            ]),

                            Grid::make(3)->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Dipublikasikan',
                                        'archived' => 'Diarsipkan',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Unggulan')
                                    ->default(false)
                                    ->inline(false),

                                Forms\Components\Toggle::make('is_public')
                                    ->label('Publik')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                        ]),

                    Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('cover')
                                ->label('Cover Album')
                                ->collection('cover')
                                ->image()
                                ->imageEditor()
                                ->helperText('Gambar cover untuk album ini'),

                            SpatieMediaLibraryFileUpload::make('images')
                                ->label('Foto')
                                ->collection('images')
                                ->multiple()
                                ->maxFiles(100)
                                ->image()
                                ->imageEditor()
                                ->reorderable()
                                ->helperText('Upload foto-foto untuk galeri ini'),

                            SpatieMediaLibraryFileUpload::make('videos')
                                ->label('Video')
                                ->collection('videos')
                                ->multiple()
                                ->maxFiles(20)
                                ->acceptedFileTypes(['video/*'])
                                ->helperText('Upload video jika ada'),
                        ]),

                    Tabs\Tab::make('Relasi')
                        ->icon('heroicon-o-link')
                        ->schema([
                            Forms\Components\Select::make('galleryable_type')
                                ->label('Tipe Relasi')
                                ->options([
                                    'App\\Models\\Expedition' => 'Ekspedisi',
                                    'App\\Models\\Competition' => 'Kompetisi/Event',
                                    'App\\Models\\TrainingProgram' => 'Program Pelatihan',
                                ])
                                ->live()
                                ->helperText('Hubungkan galeri ini dengan kegiatan tertentu'),

                            Forms\Components\Select::make('galleryable_id')
                                ->label('Pilih Kegiatan')
                                ->options(function (Forms\Get $get) {
                                    $type = $get('galleryable_type');
                                    if (!$type || !class_exists($type)) {
                                        return [];
                                    }
                                    return $type::pluck('title', 'id')->toArray();
                                })
                                ->searchable()
                                ->visible(fn (Forms\Get $get) => filled($get('galleryable_type'))),
                        ]),

                    Tabs\Tab::make('Fotografer & Tags')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\Select::make('uploaded_by')
                                    ->label('Diupload Oleh')
                                    ->relationship('uploader', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(fn () => auth()->id()),

                                Forms\Components\TextInput::make('photographer_name')
                                    ->label('Nama Fotografer')
                                    ->helperText('Jika fotografer berbeda dengan uploader'),
                            ]),

                            Forms\Components\TagsInput::make('tags')
                                ->label('Tags')
                                ->helperText('Pisahkan dengan Enter')
                                ->suggestions([
                                    'Pendakian',
                                    'Pelatihan',
                                    'Event',
                                    'Kompetisi',
                                    'Gathering',
                                    'Workshop',
                                ]),
                        ]),

                    Tabs\Tab::make('SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->maxLength(60)
                                ->helperText('Untuk SEO (maks 60 karakter)'),

                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Untuk SEO (maks 160 karakter)'),
                        ]),
                ]),
        ];
    }
}
