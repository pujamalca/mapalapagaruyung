@extends('layouts.modern')

@section('title', 'Kegiatan - Mapala Pagaruyung')
@section('meta_description', 'Eksplorasi kegiatan ekspedisi, kompetisi, dan pelatihan Mapala Pagaruyung')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    .fc {
        font-family: 'Inter', sans-serif;
    }

    .fc .fc-button {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }

    .fc .fc-button:hover {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
    }

    .fc .fc-button-primary:disabled {
        opacity: 0.5;
    }

    .fc-event {
        border-radius: 0.375rem;
        border: none;
        padding: 0.25rem 0.5rem;
    }

    .fc-event-expedition {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }

    .fc-event-competition {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
    }

    .fc-event-training {
        background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative pt-32 pb-20 bg-gradient-to-br from-green-900 via-emerald-800 to-teal-900 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-green-100 mb-6" data-aos="fade-down">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-semibold">Kalender Kegiatan</span>
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6" data-aos="fade-up" data-aos-delay="100">
                Kegiatan<br>
                <span class="gradient-text bg-gradient-to-r from-green-300 via-emerald-300 to-teal-300">Mapala Pagaruyung</span>
            </h1>

            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Eksplorasi berbagai kegiatan ekspedisi, kompetisi, dan pelatihan yang telah dan akan kami laksanakan
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <div class="text-3xl font-black text-white mb-1">{{ $expeditions->count() }}</div>
                    <div class="text-sm text-green-200">Ekspedisi</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-white mb-1">{{ $competitions->count() }}</div>
                    <div class="text-sm text-green-200">Kompetisi</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-white mb-1">{{ $trainings->count() }}</div>
                    <div class="text-sm text-green-200">Pelatihan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white" class="dark:fill-slate-900"/>
        </svg>
    </div>
</section>

<!-- Filter & View Toggle -->
<section class="py-8 bg-white dark:bg-slate-900 sticky top-20 z-40 border-b border-gray-200 dark:border-gray-800 backdrop-blur-md" x-data="{ view: 'calendar' }">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Type Filters -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('activities.index', ['type' => 'all']) }}"
                   class="px-4 py-2 rounded-lg font-semibold transition-all duration-300 {{ $type === 'all' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    Semua Kegiatan
                </a>
                <a href="{{ route('activities.index', ['type' => 'expedition']) }}"
                   class="px-4 py-2 rounded-lg font-semibold transition-all duration-300 {{ $type === 'expedition' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    🏔️ Ekspedisi
                </a>
                <a href="{{ route('activities.index', ['type' => 'competition']) }}"
                   class="px-4 py-2 rounded-lg font-semibold transition-all duration-300 {{ $type === 'competition' ? 'bg-gradient-to-r from-blue-600 to-sky-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    🏆 Kompetisi
                </a>
                <a href="{{ route('activities.index', ['type' => 'training']) }}"
                   class="px-4 py-2 rounded-lg font-semibold transition-all duration-300 {{ $type === 'training' ? 'bg-gradient-to-r from-orange-600 to-red-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    📚 Pelatihan
                </a>
            </div>

            <!-- View Toggle -->
            <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
                <button @click="view = 'calendar'"
                        :class="view === 'calendar' ? 'bg-white dark:bg-gray-700 shadow-md' : 'text-gray-600 dark:text-gray-400'"
                        class="px-4 py-2 rounded-md font-semibold transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="hidden sm:inline">Kalender</span>
                </button>
                <button @click="view = 'list'"
                        :class="view === 'list' ? 'bg-white dark:bg-gray-700 shadow-md' : 'text-gray-600 dark:text-gray-400'"
                        class="px-4 py-2 rounded-md font-semibold transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span class="hidden sm:inline">Daftar</span>
                </button>
                <button @click="view = 'grid'"
                        :class="view === 'grid' ? 'bg-white dark:bg-gray-700 shadow-md' : 'text-gray-600 dark:text-gray-400'"
                        class="px-4 py-2 rounded-md font-semibold transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="hidden sm:inline">Grid</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Calendar View -->
<section x-show="view === 'calendar'" x-transition class="py-16 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6" data-aos="fade-up">
                <div id="calendar"></div>
            </div>

            <!-- Legend -->
            <div class="mt-6 flex flex-wrap items-center justify-center gap-4" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gradient-to-r from-green-600 to-emerald-600"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Ekspedisi</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gradient-to-r from-blue-600 to-sky-600"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Kompetisi</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gradient-to-r from-orange-600 to-red-600"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Pelatihan</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- List View -->
<section x-show="view === 'list'" x-transition class="py-16 bg-gray-50 dark:bg-slate-800">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Expeditions -->
            @if($expeditions->count() > 0 && ($type === 'all' || $type === 'expedition'))
                <div data-aos="fade-up">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-3xl">🏔️</span>
                        Ekspedisi
                    </h2>
                    <div class="space-y-4">
                        @foreach($expeditions as $expedition)
                            <a href="{{ route('activities.expedition', $expedition) }}"
                               class="block bg-white dark:bg-slate-900 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-sm font-semibold">
                                                {{ ucfirst($expedition->status) }}
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $expedition->name }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $expedition->description }}</p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($expedition->start_date)->format('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $expedition->location }}
                                            </span>
                                        </div>
                                    </div>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Competitions -->
            @if($competitions->count() > 0 && ($type === 'all' || $type === 'competition'))
                <div data-aos="fade-up" data-aos-delay="100">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-3xl">🏆</span>
                        Kompetisi
                    </h2>
                    <div class="space-y-4">
                        @foreach($competitions as $competition)
                            <a href="{{ route('activities.competition', $competition) }}"
                               class="block bg-white dark:bg-slate-900 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-sm font-semibold">
                                                {{ ucfirst($competition->status) }}
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $competition->name }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $competition->description }}</p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($competition->start_date)->format('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $competition->location }}
                                            </span>
                                        </div>
                                    </div>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Training Programs -->
            @if($trainings->count() > 0 && ($type === 'all' || $type === 'training'))
                <div data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-3xl">📚</span>
                        Pelatihan
                    </h2>
                    <div class="space-y-4">
                        @foreach($trainings as $training)
                            <a href="{{ route('activities.training', $training) }}"
                               class="block bg-white dark:bg-slate-900 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded-full text-sm font-semibold">
                                                {{ ucfirst($training->status) }}
                                            </span>
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-sm font-semibold">
                                                {{ ucfirst($training->level) }}
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $training->name }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $training->description }}</p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($training->start_date)->format('d M Y') }}
                                            </span>
                                            @if($training->location)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $training->location }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if($expeditions->count() == 0 && $competitions->count() == 0 && $trainings->count() == 0)
                <div class="text-center py-16" data-aos="fade-up">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Kegiatan</h3>
                    <p class="text-gray-600 dark:text-gray-400">Kegiatan untuk kategori ini sedang dalam persiapan</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Grid View -->
<section x-show="view === 'grid'" x-transition class="py-16 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <!-- Expeditions Grid -->
        @if($expeditions->count() > 0 && ($type === 'all' || $type === 'expedition'))
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-2" data-aos="fade-up">
                    <span class="text-4xl">🏔️</span>
                    Ekspedisi
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($expeditions as $index => $expedition)
                        <a href="{{ route('activities.expedition', $expedition) }}"
                           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-600 to-emerald-600 p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2"
                           data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold">
                                        {{ ucfirst($expedition->status) }}
                                    </span>
                                    <svg class="w-6 h-6 text-white transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">{{ $expedition->name }}</h3>
                                <p class="text-green-100 text-sm mb-4 line-clamp-2">{{ $expedition->description }}</p>
                                <div class="flex items-center gap-2 text-white text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($expedition->start_date)->format('d M Y') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Competitions Grid -->
        @if($competitions->count() > 0 && ($type === 'all' || $type === 'competition'))
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-2" data-aos="fade-up">
                    <span class="text-4xl">🏆</span>
                    Kompetisi
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($competitions as $index => $competition)
                        <a href="{{ route('activities.competition', $competition) }}"
                           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-sky-600 p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2"
                           data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold">
                                        {{ ucfirst($competition->status) }}
                                    </span>
                                    <svg class="w-6 h-6 text-white transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">{{ $competition->name }}</h3>
                                <p class="text-blue-100 text-sm mb-4 line-clamp-2">{{ $competition->description }}</p>
                                <div class="flex items-center gap-2 text-white text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($competition->start_date)->format('d M Y') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Training Programs Grid -->
        @if($trainings->count() > 0 && ($type === 'all' || $type === 'training'))
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-2" data-aos="fade-up">
                    <span class="text-4xl">📚</span>
                    Pelatihan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($trainings as $index => $training)
                        <a href="{{ route('activities.training', $training) }}"
                           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-600 to-red-600 p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2"
                           data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold">
                                        {{ ucfirst($training->status) }}
                                    </span>
                                    <svg class="w-6 h-6 text-white transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">{{ $training->name }}</h3>
                                <p class="text-orange-100 text-sm mb-4 line-clamp-2">{{ $training->description }}</p>
                                <div class="flex items-center gap-2 text-white text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($training->start_date)->format('d M Y') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if($expeditions->count() == 0 && $competitions->count() == 0 && $trainings->count() == 0)
            <div class="text-center py-16" data-aos="fade-up">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Kegiatan</h3>
                <p class="text-gray-600 dark:text-gray-400">Kegiatan untuk kategori ini sedang dalam persiapan</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var events = @json($calendarEvents);

        // Add className to events based on type
        events = events.map(event => ({
            ...event,
            className: `fc-event-${event.type}`
        }));

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Daftar'
            },
            locale: 'id',
            events: events,
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            },
            height: 'auto',
            aspectRatio: 1.8
        });

        calendar.render();
    });
</script>
@endpush
