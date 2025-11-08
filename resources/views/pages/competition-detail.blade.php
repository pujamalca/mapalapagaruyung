@extends('layouts.modern')

@section('title', $competition->title . ' - Kompetisi Mapala Pagaruyung')
@section('meta_description', Str::limit($competition->description ?? 'Detail kompetisi Mapala Pagaruyung.', 155))

@section('content')
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="uppercase tracking-[0.4em] text-white/70 text-sm mb-4">Kompetisi & Event</p>
                    <h1 class="text-5xl font-black mb-6 leading-tight">{{ $competition->title }}</h1>
                    <p class="text-lg text-white/80 leading-relaxed mb-6">
                        {{ $competition->description ?? 'Kompetisi resmi Mapala Pagaruyung.' }}
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Periode</div>
                            <div class="font-semibold">
                                {{ $competition->start_date?->format('d M Y') }} - {{ $competition->end_date?->format('d M Y') }}
                            </div>
                        </div>
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Status</div>
                            <div class="font-semibold capitalize">{{ $competition->status ?? '-' }}</div>
                        </div>
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Lokasi</div>
                            <div class="font-semibold">{{ $competition->location ?? '-' }}</div>
                        </div>
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <div class="text-white/80 mb-1">Koordinator</div>
                            <div class="font-semibold">{{ $competition->coordinator?->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 border border-white/20 rounded-3xl p-6 backdrop-blur-lg">
                    <h2 class="text-xl font-semibold mb-4">Informasi Peserta</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Jumlah Peserta</span>
                            <span class="font-semibold">{{ $competition->participants()->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status Pendaftaran</span>
                            <span class="font-semibold capitalize">{{ $competition->registration_status ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kategori</span>
                            <span class="font-semibold">{{ $competition->competition_type ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50 dark:bg-slate-900">
        <div class="container mx-auto px-4 space-y-12">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-4">Detail Acara</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Highlight</h3>
                        @if($competition->highlights)
                            <ul class="space-y-2 list-disc list-inside text-gray-600 dark:text-gray-300">
                                @foreach($competition->highlights as $highlight)
                                    <li>{{ is_array($highlight) ? implode(' - ', $highlight) : $highlight }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">Belum ada data.</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Prestasi</h3>
                        @if($competition->awards)
                            <ul class="space-y-2 list-disc list-inside text-gray-600 dark:text-gray-300">
                                @foreach($competition->awards as $award)
                                    <li>{{ is_array($award) ? implode(' - ', $award) : $award }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">Belum ada data.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6">Peserta</h2>
                @php $participants = $competition->competitionParticipants ?? collect(); @endphp
                @if($participants->isEmpty())
                    <p class="text-gray-500">Belum ada peserta terdaftar.</p>
                @else
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($participants as $participant)
                            <div class="border rounded-2xl py-4 px-5 dark:border-slate-700">
                                <div class="font-semibold">{{ $participant->user?->name ?? 'Peserta' }}</div>
                                <div class="text-sm text-gray-500">{{ $participant->team_name ?? $participant->role ?? '-' }}</div>
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
