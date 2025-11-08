@extends('layouts.modern')

@section('title', $expedition->title . ' - Ekspedisi Mapala Pagaruyung')
@section('meta_description', Str::limit($expedition->description ?? 'Detail ekspedisi Mapala Pagaruyung.', 155))

@section('content')
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-indigo-700 to-emerald-600 text-white overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="uppercase tracking-[0.4em] text-white/70 text-sm mb-4">Ekspedisi</p>
                    <h1 class="text-5xl font-black mb-6 leading-tight">{{ $expedition->title }}</h1>
                    <p class="text-lg text-white/80 leading-relaxed mb-6">
                        {{ $expedition->description ?? 'Ekspedisi resmi Mapala Pagaruyung.' }}
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Periode</div>
                            <div class="font-semibold">
                                {{ $expedition->start_date?->format('d M Y') }} - {{ $expedition->end_date?->format('d M Y') }}
                            </div>
                        </div>
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Status</div>
                            <div class="font-semibold capitalize">{{ $expedition->status ?? '-' }}</div>
                        </div>
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Tujuan</div>
                            <div class="font-semibold">{{ $expedition->destination ?? '-' }}</div>
                        </div>
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Ketua</div>
                            <div class="font-semibold">{{ $expedition->leader?->name ?? $expedition->leader_name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 border border-white/20 rounded-3xl p-6 backdrop-blur-lg">
                    <h2 class="text-xl font-semibold mb-4">Informasi Peserta</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Jumlah Peserta</span>
                            <span class="font-semibold">{{ $expedition->participants()->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kuota Maksimal</span>
                            <span class="font-semibold">{{ $expedition->max_participants ?? 'Tidak dibatasi' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kuota Minimal</span>
                            <span class="font-semibold">{{ $expedition->min_participants ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status Pendaftaran</span>
                            <span class="font-semibold capitalize">{{ $expedition->registration_status ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50 dark:bg-slate-900">
        <div class="container mx-auto px-4 space-y-12">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-4">Rute & Tantangan</h2>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                    {{ $expedition->route_description ?? 'Belum ada deskripsi rute.' }}
                </p>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Checkpoints</h3>
                        @if($expedition->checkpoints)
                            <ul class="space-y-2 list-disc list-inside text-gray-600 dark:text-gray-300">
                                @foreach($expedition->checkpoints as $checkpoint)
                                    <li>{{ is_array($checkpoint) ? implode(' - ', $checkpoint) : $checkpoint }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">Belum ada data.</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Tantangan & Catatan</h3>
                        @if($expedition->challenges)
                            <ul class="space-y-2 list-disc list-inside text-gray-600 dark:text-gray-300">
                                @foreach($expedition->challenges as $challenge)
                                    <li>{{ is_array($challenge) ? implode(' - ', $challenge) : $challenge }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">Belum ada data.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6">Daftar Peserta</h2>
                @php $participants = $expedition->expeditionParticipants ?? collect(); @endphp
                @if($participants->isEmpty())
                    <p class="text-gray-500">Belum ada peserta terdaftar.</p>
                @else
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($participants as $participant)
                            <div class="border rounded-2xl py-4 px-5 dark:border-slate-700">
                                <div class="font-semibold">{{ $participant->user?->name ?? 'Peserta' }}</div>
                                <div class="text-sm text-gray-500">{{ $participant->role ?? 'Anggota' }}</div>
                                <div class="mt-2 text-sm text-gray-600">
                                    Status: <span class="font-semibold capitalize">{{ $participant->status ?? 'unknown' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
