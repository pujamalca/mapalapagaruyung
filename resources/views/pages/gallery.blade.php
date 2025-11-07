@extends('layouts.app')

@section('title', 'Galeri - Mapala Pagaruyung')
@section('meta_description', 'Dokumentasi kegiatan dan momen berharga Mapala Pagaruyung dalam foto dan video.')

@section('content')
    {{-- Hero Section --}}
    <section class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Galeri Dokumentasi</h1>
                <p class="text-xl text-purple-100">
                    Momen-momen berharga dari setiap kegiatan yang kami lakukan
                </p>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="py-8 bg-white border-b">
        <div class="container mx-auto px-4">
            <form method="GET" action="{{ route('gallery.index') }}" class="flex flex-col md:flex-row gap-4">
                {{-- Search --}}
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari galeri..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                {{-- Category Filter --}}
                <div class="md:w-64">
                    <select name="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('gallery.index') }}"
                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </section>

    {{-- Gallery Grid --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            @if($galleries->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($galleries as $gallery)
                        <a href="{{ route('gallery.show', $gallery) }}"
                           class="group block bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow border border-gray-200">
                            {{-- Image Placeholder --}}
                            <div class="aspect-video bg-gradient-to-br from-purple-400 to-indigo-500 relative overflow-hidden">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                @if($gallery->is_featured)
                                    <div class="absolute top-3 right-3">
                                        <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">
                                            ⭐ Featured
                                        </span>
                                    </div>
                                @endif

                                @if($gallery->galleryCategory)
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1 bg-white bg-opacity-90 backdrop-blur-sm text-purple-700 text-xs font-semibold rounded-full">
                                            {{ $gallery->galleryCategory->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                    {{ $gallery->title }}
                                </h3>

                                @if($gallery->description)
                                    <p class="text-gray-600 mb-4 line-clamp-2">
                                        {{ Str::limit($gallery->description, 100) }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $gallery->published_at?->format('d M Y') ?? $gallery->created_at->format('d M Y') }}
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ number_format($gallery->view_count) }} views
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $galleries->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xl text-gray-500">Belum ada galeri yang tersedia</p>
                </div>
            @endif
        </div>
    </section>
@endsection
