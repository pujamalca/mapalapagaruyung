@extends('layouts.app')

@section('title', 'Dashboard Anggota - Mapala Pagaruyung')

@section('content')
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-600 to-teal-700 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Dashboard Anggota</h1>
                    <p class="text-green-100">Selamat datang, {{ auth()->user()->name }}!</p>
                </div>
                <a href="{{ route('member.profile') }}"
                   class="px-6 py-3 bg-white text-green-700 rounded-lg font-semibold hover:bg-green-50 transition-colors">
                    Lihat Profil
                </a>
            </div>
        </div>
    </div>

    {{-- Overdue Warning --}}
    @if($overdueEquipment->count() > 0)
        <div class="bg-red-50 border-l-4 border-red-500 py-4">
            <div class="container mx-auto px-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-red-800 mb-1">Peringatan: Peminjaman Terlambat!</h3>
                        <p class="text-red-700">
                            Anda memiliki {{ $overdueEquipment->count() }} peralatan yang belum dikembalikan.
                            Segera kembalikan untuk menghindari denda.
                        </p>
                        <a href="{{ route('member.activities', ['type' => 'equipment']) }}"
                           class="inline-block mt-2 text-red-800 font-semibold underline hover:text-red-900">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['expeditions'] }}</div>
                            <div class="text-sm text-gray-600">Ekspedisi</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['training'] }}</div>
                            <div class="text-sm text-gray-600">Pelatihan</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['competitions'] }}</div>
                            <div class="text-sm text-gray-600">Kompetisi</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['equipment_borrowed'] }}</div>
                            <div class="text-sm text-gray-600">Peminjaman</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activities --}}
            <div class="grid md:grid-cols-2 gap-8">
                {{-- Recent Expeditions --}}
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ekspedisi Terbaru
                        </h2>
                        <a href="{{ route('member.activities', ['type' => 'expedition']) }}"
                           class="text-sm text-green-600 hover:text-green-700 font-semibold">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentExpeditions->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentExpeditions as $participant)
                                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 truncate">{{ $participant->expedition->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $participant->expedition->destination }}</p>
                                        <div class="flex items-center gap-4 mt-1">
                                            <span class="text-xs px-2 py-1 rounded-full {{ $participant->getStatusColorAttribute() === 'success' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $participant->getStatusLabelAttribute() }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $participant->expedition->start_date->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Belum ada ekspedisi</p>
                        </div>
                    @endif
                </div>

                {{-- Recent Training --}}
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Pelatihan Terbaru
                        </h2>
                        <a href="{{ route('member.activities', ['type' => 'training']) }}"
                           class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentTraining->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentTraining as $participant)
                                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 truncate">{{ $participant->trainingProgram->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ Str::limit($participant->trainingProgram->description, 50) }}</p>
                                        <div class="flex items-center gap-4 mt-1">
                                            <span class="text-xs px-2 py-1 rounded-full {{ $participant->getStatusColorAttribute() === 'success' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $participant->getStatusLabelAttribute() }}
                                            </span>
                                            @if($participant->total_score)
                                                <span class="text-xs text-blue-600 font-semibold">
                                                    Nilai: {{ $participant->total_score }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p>Belum ada pelatihan</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Active Equipment --}}
            @if($activeEquipment->count() > 0)
                <div class="mt-8 bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Peralatan Dipinjam
                        </h2>
                        <a href="{{ route('member.activities', ['type' => 'equipment']) }}"
                           class="text-sm text-purple-600 hover:text-purple-700 font-semibold">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($activeEquipment as $borrowing)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $borrowing->isOverdue() ? 'border-red-300 bg-red-50' : '' }}">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900">{{ $borrowing->equipment->name }}</h3>
                                    <span class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-700">
                                        {{ $borrowing->borrowing_code }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Jumlah:</span>
                                        <span class="font-semibold">{{ $borrowing->quantity_borrowed }} {{ $borrowing->equipment->unit }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Dipinjam:</span>
                                        <span>{{ $borrowing->borrow_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Jatuh Tempo:</span>
                                        <span class="{{ $borrowing->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                            {{ $borrowing->due_date->format('d M Y') }}
                                        </span>
                                    </div>
                                    @if($borrowing->isOverdue())
                                        <div class="mt-2 text-red-600 font-semibold">
                                            ⚠️ Terlambat {{ $borrowing->getDaysLate() }} hari
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
