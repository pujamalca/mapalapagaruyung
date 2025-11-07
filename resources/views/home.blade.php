@extends('layouts.app')

@section('title', 'Beranda - Mapala Pagaruyung')
@section('meta_description', 'Mahasiswa Pecinta Alam Pagaruyung - Organisasi Mahasiswa Pecinta Alam yang aktif dalam kegiatan pendakian, ekspedisi, pelatihan, dan konservasi alam.')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-green-700 via-green-800 to-teal-900 text-white overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <polygon points="0,0 100,0 100,100" fill="currentColor" />
            </svg>
        </div>

        {{-- Mountain Silhouette --}}
        <div class="absolute bottom-0 left-0 right-0 opacity-20">
            <svg viewBox="0 0 1440 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M0,192L48,176C96,160,192,128,288,128C384,128,480,160,576,165.3C672,171,768,149,864,138.7C960,128,1056,128,1152,144C1248,160,1344,192,1392,208L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
            </svg>
        </div>

        <div class="container mx-auto px-4 py-20 md:py-32 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-6">
                    <span class="inline-block px-4 py-2 bg-green-600 bg-opacity-50 backdrop-blur-sm rounded-full text-sm font-semibold">
                        🏔️ Mahasiswa Pecinta Alam
                    </span>
                </div>

                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Mapala Pagaruyung
                </h1>

                <p class="text-xl md:text-2xl text-green-100 mb-8 leading-relaxed max-w-3xl mx-auto">
                    Organisasi Mahasiswa Pecinta Alam yang aktif dalam kegiatan pendakian, ekspedisi,
                    pelatihan survival, konservasi alam, dan pengembangan karakter anggota.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @if($activeRecruitment)
                        <a href="{{ route('recruitment.register') }}"
                           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-700 rounded-lg font-bold hover:bg-green-50 transition-all transform hover:scale-105 shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Daftar Sekarang
                        </a>
                    @endif

                    <a href="#activities"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-green-600 bg-opacity-30 backdrop-blur-sm border-2 border-white border-opacity-30 text-white rounded-lg font-bold hover:bg-opacity-40 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Lihat Kegiatan
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mt-16">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">{{ $stats['members'] }}+</div>
                        <div class="text-green-200 mt-2">Anggota</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">{{ $stats['expeditions'] }}+</div>
                        <div class="text-green-200 mt-2">Ekspedisi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">{{ $stats['competitions'] }}+</div>
                        <div class="text-green-200 mt-2">Kompetisi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">{{ $stats['training_programs'] }}+</div>
                        <div class="text-green-200 mt-2">Pelatihan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">{{ $stats['equipment'] }}+</div>
                        <div class="text-green-200 mt-2">Peralatan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Recruitment Banner (if active) --}}
    @if($activeRecruitment)
        <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white py-4">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <div>
                            <div class="font-bold text-lg">{{ $activeRecruitment->name }}</div>
                            <div class="text-sm opacity-90">
                                Pendaftaran ditutup: {{ $activeRecruitment->registration_end?->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('recruitment.register') }}"
                       class="px-6 py-3 bg-white text-orange-600 rounded-lg font-bold hover:bg-orange-50 transition-colors whitespace-nowrap">
                        Daftar Sekarang →
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Recent Activities --}}
    <section id="activities" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Kegiatan Terkini</h2>
                <p class="text-xl text-gray-600">Ekspedisi, pelatihan, dan kompetisi yang kami lakukan</p>
            </div>

            {{-- Expeditions --}}
            @if($recentExpeditions->count() > 0)
                <div class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Ekspedisi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($recentExpeditions as $expedition)
                            <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow group">
                                <div class="aspect-video bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center relative overflow-hidden">
                                    <svg class="w-16 h-16 text-white opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="absolute top-3 right-3">
                                        <span class="px-3 py-1 bg-white bg-opacity-90 backdrop-blur-sm rounded-full text-xs font-semibold
                                            {{ $expedition->status === 'completed' ? 'text-green-700' : 'text-orange-700' }}">
                                            {{ $expedition->getStatusLabelAttribute() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">
                                        {{ $expedition->title }}
                                    </h4>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $expedition->destination }}</p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $expedition->start_date->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            {{ $expedition->expeditionParticipants()->count() }} peserta
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Training Programs --}}
            @if($recentTraining->count() > 0)
                <div class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Program Pelatihan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($recentTraining as $training)
                            <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow group">
                                <div class="aspect-video bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center relative overflow-hidden">
                                    <svg class="w-16 h-16 text-white opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <div class="absolute top-3 right-3">
                                        <span class="px-3 py-1 bg-white bg-opacity-90 backdrop-blur-sm rounded-full text-xs font-semibold
                                            {{ $training->status === 'completed' ? 'text-green-700' : 'text-blue-700' }}">
                                            {{ $training->getStatusLabelAttribute() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                        {{ $training->name }}
                                    </h4>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($training->description, 100) }}</p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $training->start_date->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            {{ $training->trainingParticipants()->count() }} peserta
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Competitions --}}
            @if($recentCompetitions->count() > 0)
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        Kompetisi & Event
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($recentCompetitions as $competition)
                            <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow group">
                                <div class="aspect-video bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center relative overflow-hidden">
                                    <svg class="w-16 h-16 text-white opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    <div class="absolute top-3 right-3">
                                        <span class="px-3 py-1 bg-white bg-opacity-90 backdrop-blur-sm rounded-full text-xs font-semibold
                                            {{ $competition->status === 'completed' ? 'text-green-700' : 'text-yellow-700' }}">
                                            {{ $competition->getStatusLabelAttribute() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-yellow-600 transition-colors">
                                        {{ $competition->title }}
                                    </h4>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $competition->location }}</p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $competition->start_date->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            {{ $competition->competitionParticipants()->count() }} peserta
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Gallery Preview --}}
    @if($featuredGalleries->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Galeri Dokumentasi</h2>
                    <p class="text-xl text-gray-600">Momen-momen berharga dari kegiatan kami</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($featuredGalleries as $gallery)
                        <div class="relative group overflow-hidden rounded-lg aspect-square bg-gradient-to-br from-gray-200 to-gray-300">
                            {{-- Placeholder for now since we're using media library --}}
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h4 class="text-white font-bold text-lg mb-1">{{ $gallery->title }}</h4>
                                    @if($gallery->galleryCategory)
                                        <span class="text-xs text-white bg-white bg-opacity-20 backdrop-blur-sm px-2 py-1 rounded">
                                            {{ $gallery->galleryCategory->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- About Section --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Tentang Kami</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-green-50 rounded-xl p-8 border border-green-200">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Visi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Menjadi organisasi pecinta alam yang unggul dalam pengembangan karakter,
                            pelestarian lingkungan, dan kontribusi nyata bagi masyarakat.
                        </p>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-8 border border-blue-200">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Misi</h3>
                        <ul class="text-gray-600 leading-relaxed space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 mt-1">•</span>
                                <span>Melaksanakan kegiatan pendidikan dan pelatihan alam</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 mt-1">•</span>
                                <span>Melestarikan lingkungan dan kearifan lokal</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 mt-1">•</span>
                                <span>Mengembangkan karakter dan jiwa kepemimpinan</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-16 bg-gradient-to-r from-green-600 to-teal-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Bergabunglah Bersama Kami
                </h2>
                <p class="text-xl opacity-90 mb-8 leading-relaxed">
                    Jadilah bagian dari keluarga besar Mapala Pagaruyung dan rasakan petualangan yang sesungguhnya
                </p>
                @if($activeRecruitment)
                    <a href="{{ route('recruitment.register') }}"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-700 rounded-lg font-bold hover:bg-green-50 transition-all transform hover:scale-105 shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Daftar Sekarang
                    </a>
                @else
                    <div class="text-lg opacity-90">
                        Pendaftaran akan dibuka kembali. Pantau terus informasi dari kami!
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
