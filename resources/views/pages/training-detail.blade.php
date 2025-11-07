@extends('layouts.modern')

@section('title', $training->name . ' - Program Pelatihan')
@section('meta_description', Str::limit($training->description ?? 'Program pelatihan Mapala Pagaruyung.', 155))

@section('content')
    @php
        $formatList = function ($items) {
            return collect($items ?? [])
                ->map(function ($item) {
                    if (is_array($item)) {
                        return collect($item)
                            ->filter(fn ($value) => is_scalar($value))
                            ->implode(' ');
                    }

                    return $item;
                })
                ->filter()
                ->values();
        };

        $learningObjectives = $formatList($training->learning_objectives);
        $materialsNeeded = $formatList($training->materials_needed);
    @endphp
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-emerald-600 to-green-600 text-white overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="uppercase tracking-[0.4em] text-green-200 text-sm mb-4">Program Pelatihan</p>
                    <h1 class="text-5xl font-black mb-6 leading-tight">{{ $training->name }}</h1>
                    <p class="text-lg text-green-50 leading-relaxed mb-6">
                        {{ $training->description ?? 'Program pelatihan resmi Mapala Pagaruyung.' }}
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                        <div class="bg-white/15 rounded-2xl p-4 border border-white/20">
                            <div class="text-green-100 mb-1">Periode</div>
                            <div class="font-semibold">
                                {{ $training->start_date?->format('d M Y') }} - {{ $training->end_date?->format('d M Y') }}
                            </div>
                        </div>
                        <div class="bg-white/15 rounded-2xl p-4 border border-white/20">
                            <div class="text-green-100 mb-1">Status</div>
                            <div class="font-semibold capitalize">{{ $training->status }}</div>
                        </div>
                        <div class="bg-white/15 rounded-2xl p-4 border border-white/20">
                            <div class="text-green-100 mb-1">Lokasi</div>
                            <div class="font-semibold">{{ $training->location ?? '-' }}</div>
                        </div>
                        <div class="bg-white/15 rounded-2xl p-4 border border-white/20">
                            <div class="text-green-100 mb-1">Koordinator</div>
                            <div class="font-semibold">{{ $training->coordinator?->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 border border-white/20 rounded-3xl p-6 backdrop-blur-lg">
                    <h2 class="text-xl font-semibold mb-4">Informasi Kuota</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Kuota Maksimal</span>
                            <span class="font-semibold">{{ $training->max_participants ?? 'Tidak dibatasi' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Minimal Peserta</span>
                            <span class="font-semibold">{{ $training->min_participants ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status Pendaftaran</span>
                            <span class="font-semibold capitalize">{{ $training->registration_status }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pelatihan</span>
                            <span class="font-semibold">
                                {{ $training->training_fee ? 'Rp ' . number_format($training->training_fee, 0, ',', '.') : 'Gratis' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50 dark:bg-slate-900">
        <div class="container mx-auto px-4 space-y-12">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-4">Tujuan & Materi</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Tujuan Pembelajaran</h3>
                        @if($learningObjectives->isNotEmpty())
                            <ul class="space-y-2 list-disc list-inside text-gray-600 dark:text-gray-300">
                                @foreach($learningObjectives as $objective)
                                    <li>{{ $objective }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">Tidak ada data.</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Perlengkapan yang Dibutuhkan</h3>
                        @if($materialsNeeded->isNotEmpty())
                            <ul class="space-y-2 list-disc list-inside text-gray-600 dark:text-gray-300">
                                @foreach($materialsNeeded as $material)
                                    <li>{{ $material }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">Tidak ada data.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6">Jadwal Sesi</h2>
                @php $sessions = $training->trainingSessions ?? collect(); @endphp
                @if($sessions->isEmpty())
                    <p class="text-gray-500">Belum ada sesi terjadwal.</p>
                @else
                    <div class="space-y-4">
                        @foreach($sessions as $session)
                            <div class="flex flex-col md:flex-row md:items-center justify-between border rounded-2xl p-4 dark:border-slate-700">
                                <div>
                                    <div class="text-sm text-gray-500">Sesi {{ $session->order }}</div>
                                    <div class="text-lg font-semibold">{{ $session->title }}</div>
                                    <div class="text-gray-500">{{ $session->scheduled_date?->format('d M Y H:i') }}</div>
                                </div>
                                <div class="text-sm text-gray-500 mt-2 md:mt-0">
                                    Durasi: {{ $session->duration_minutes }} menit
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6">Peserta</h2>
                @php $participants = $training->trainingParticipants ?? collect(); @endphp
                @if($participants->isEmpty())
                    <p class="text-gray-500">Belum ada peserta terdaftar.</p>
                @else
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($participants as $participant)
                            <div class="border rounded-2xl py-4 px-5 dark:border-slate-700">
                                <div class="font-semibold">{{ $participant->user?->name ?? 'Peserta' }}</div>
                                <div class="text-sm text-gray-500 capitalize">{{ $participant->status }}</div>
                                @if($participant->average_score)
                                    <div class="mt-2 text-sm text-gray-600">
                                        Nilai rata-rata: <span class="font-semibold">{{ $participant->average_score }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
