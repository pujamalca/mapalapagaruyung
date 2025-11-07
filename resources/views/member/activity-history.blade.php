@extends('layouts.app')

@section('title', 'Riwayat Kegiatan - Mapala Pagaruyung')

@section('content')
    <div class="bg-gradient-to-r from-purple-600 to-pink-700 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Riwayat Kegiatan</h1>
                <a href="{{ route('member.dashboard') }}"
                   class="px-6 py-3 bg-white text-purple-700 rounded-lg font-semibold hover:bg-purple-50 transition-colors">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="bg-white border-b sticky top-0 z-10 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center gap-2 flex-wrap">
                <a href="{{ route('member.activities') }}"
                   class="px-6 py-2 rounded-lg font-semibold transition-colors {{ $type === 'all' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </a>
                <a href="{{ route('member.activities', ['type' => 'expedition']) }}"
                   class="px-6 py-2 rounded-lg font-semibold transition-colors {{ $type === 'expedition' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Ekspedisi
                </a>
                <a href="{{ route('member.activities', ['type' => 'training']) }}"
                   class="px-6 py-2 rounded-lg font-semibold transition-colors {{ $type === 'training' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pelatihan
                </a>
                <a href="{{ route('member.activities', ['type' => 'competition']) }}"
                   class="px-6 py-2 rounded-lg font-semibold transition-colors {{ $type === 'competition' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Kompetisi
                </a>
                <a href="{{ route('member.activities', ['type' => 'equipment']) }}"
                   class="px-6 py-2 rounded-lg font-semibold transition-colors {{ $type === 'equipment' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Peminjaman
                </a>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto space-y-8">
                {{-- Expeditions --}}
                @if($type === 'all' || $type === 'expedition')
                    @if($expeditions->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Ekspedisi ({{ $expeditions->total() }})
                            </h2>

                            <div class="space-y-4">
                                @foreach($expeditions as $participant)
                                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $participant->expedition->title }}</h3>
                                                <p class="text-gray-600">{{ $participant->expedition->destination }}</p>
                                            </div>
                                            <span class="text-xs px-3 py-1 rounded-full {{ $participant->getStatusColorAttribute() === 'success' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $participant->getStatusLabelAttribute() }}
                                            </span>
                                        </div>

                                        <div class="grid md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Peran:</span>
                                                <span class="font-semibold text-gray-900">{{ $participant->getRoleLabelAttribute() }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Tanggal:</span>
                                                <span class="font-semibold text-gray-900">{{ $participant->expedition->start_date->format('d M Y') }}</span>
                                            </div>
                                            @if($participant->performance_rating)
                                                <div>
                                                    <span class="text-gray-600">Rating:</span>
                                                    <span class="font-semibold text-yellow-600">⭐ {{ $participant->performance_rating }}/5</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($type === 'expedition')
                                <div class="mt-6">
                                    {{ $expeditions->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- Training --}}
                @if($type === 'all' || $type === 'training')
                    @if($training->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Pelatihan ({{ $training->total() }})
                            </h2>

                            <div class="space-y-4">
                                @foreach($training as $participant)
                                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $participant->trainingProgram->name }}</h3>
                                                <p class="text-gray-600">{{ Str::limit($participant->trainingProgram->description, 100) }}</p>
                                            </div>
                                            <span class="text-xs px-3 py-1 rounded-full {{ $participant->getStatusColorAttribute() === 'success' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $participant->getStatusLabelAttribute() }}
                                            </span>
                                        </div>

                                        <div class="grid md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Tanggal:</span>
                                                <span class="font-semibold text-gray-900">{{ $participant->trainingProgram->start_date->format('d M Y') }}</span>
                                            </div>
                                            @if($participant->total_score)
                                                <div>
                                                    <span class="text-gray-600">Nilai:</span>
                                                    <span class="font-semibold text-blue-600">{{ $participant->total_score }}</span>
                                                </div>
                                            @endif
                                            @if($participant->certificate_number)
                                                <div>
                                                    <span class="text-gray-600">Sertifikat:</span>
                                                    <span class="font-semibold text-green-600">✓ {{ $participant->certificate_number }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($type === 'training')
                                <div class="mt-6">
                                    {{ $training->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- Competitions --}}
                @if($type === 'all' || $type === 'competition')
                    @if($competitions->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                Kompetisi ({{ $competitions->total() }})
                            </h2>

                            <div class="space-y-4">
                                @foreach($competitions as $participant)
                                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $participant->competition->title }}</h3>
                                                <p class="text-gray-600">{{ $participant->competition->location }}</p>
                                            </div>
                                            <span class="text-xs px-3 py-1 rounded-full {{ $participant->getStatusColorAttribute() === 'success' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $participant->getStatusLabelAttribute() }}
                                            </span>
                                        </div>

                                        <div class="grid md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Tanggal:</span>
                                                <span class="font-semibold text-gray-900">{{ $participant->competition->start_date->format('d M Y') }}</span>
                                            </div>
                                            @if($participant->rank)
                                                <div>
                                                    <span class="text-gray-600">Peringkat:</span>
                                                    <span class="font-semibold text-yellow-600">{{ $participant->rank }}</span>
                                                </div>
                                            @endif
                                            @if($participant->medal_type)
                                                <div>
                                                    <span class="text-gray-600">Medali:</span>
                                                    <span class="font-semibold">
                                                        @if($participant->medal_type === 'gold') 🥇 Emas
                                                        @elseif($participant->medal_type === 'silver') 🥈 Perak
                                                        @elseif($participant->medal_type === 'bronze') 🥉 Perunggu
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($type === 'competition')
                                <div class="mt-6">
                                    {{ $competitions->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- Equipment --}}
                @if($type === 'equipment')
                    @if($equipment->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Peminjaman Peralatan ({{ $equipment->total() }})
                            </h2>

                            <div class="space-y-4">
                                @foreach($equipment as $borrowing)
                                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow {{ $borrowing->isOverdue() && $borrowing->status !== 'returned' ? 'border-red-300' : '' }}">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $borrowing->equipment->name }}</h3>
                                                <p class="text-gray-600">Kode: {{ $borrowing->borrowing_code }}</p>
                                            </div>
                                            <span class="text-xs px-3 py-1 rounded-full
                                                {{ $borrowing->status === 'returned' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }}">
                                                {{ $borrowing->getStatusLabelAttribute() }}
                                            </span>
                                        </div>

                                        <div class="grid md:grid-cols-4 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Jumlah:</span>
                                                <span class="font-semibold text-gray-900">{{ $borrowing->quantity_borrowed }} {{ $borrowing->equipment->unit }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Pinjam:</span>
                                                <span class="font-semibold text-gray-900">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Jatuh Tempo:</span>
                                                <span class="font-semibold {{ $borrowing->isOverdue() && $borrowing->status !== 'returned' ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ $borrowing->due_date->format('d M Y') }}
                                                </span>
                                            </div>
                                            @if($borrowing->return_date)
                                                <div>
                                                    <span class="text-gray-600">Kembali:</span>
                                                    <span class="font-semibold text-gray-900">{{ $borrowing->return_date->format('d M Y') }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($borrowing->is_late && $borrowing->penalty_amount > 0)
                                            <div class="mt-4 p-3 bg-red-50 rounded-lg">
                                                <span class="text-red-700 font-semibold">
                                                    ⚠️ Denda: Rp {{ number_format($borrowing->penalty_amount, 0, ',', '.') }}
                                                    (Terlambat {{ $borrowing->days_late }} hari)
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $equipment->links() }}
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Empty State --}}
                @if(
                    ($type === 'all' && $expeditions->count() === 0 && $training->count() === 0 && $competitions->count() === 0) ||
                    ($type === 'expedition' && $expeditions->count() === 0) ||
                    ($type === 'training' && $training->count() === 0) ||
                    ($type === 'competition' && $competitions->count() === 0) ||
                    ($type === 'equipment' && $equipment->count() === 0)
                )
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="text-xl text-gray-500">Belum ada riwayat kegiatan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
