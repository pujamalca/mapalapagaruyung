@extends('layouts.app')

@section('title', 'Tentang Kami - Mapala Pagaruyung')
@section('meta_description', 'Pelajari lebih lanjut tentang Mapala Pagaruyung - visi, misi, struktur organisasi, dan divisi-divisi yang ada.')

@section('content')
    {{-- Hero Section --}}
    <section class="bg-gradient-to-r from-green-600 to-teal-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang Mapala Pagaruyung</h1>
                <p class="text-xl text-green-100">
                    Mengenal lebih dekat organisasi mahasiswa pecinta alam yang berdedikasi
                    untuk pelestarian lingkungan dan pengembangan karakter
                </p>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600">{{ $stats['total_members'] }}+</div>
                    <div class="text-gray-600 mt-2">Total Anggota</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600">{{ $stats['active_members'] }}+</div>
                    <div class="text-gray-600 mt-2">Anggota Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600">{{ $stats['alumni'] }}+</div>
                    <div class="text-gray-600 mt-2">Alumni</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600">{{ $stats['cohorts'] }}</div>
                    <div class="text-gray-600 mt-2">Angkatan</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600">{{ $stats['divisions'] }}</div>
                    <div class="text-gray-600 mt-2">Divisi</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Vision & Mission --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-8 shadow-lg border border-green-100">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Visi</h2>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Menjadi organisasi mahasiswa pecinta alam yang unggul dalam pengembangan
                            karakter, keterampilan survival, dan pelestarian lingkungan dengan
                            kontribusi nyata bagi masyarakat dan alam Indonesia.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-8 shadow-lg border border-blue-100">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Misi</h2>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-gray-600">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold">1</span>
                                <span>Melaksanakan pendidikan dan pelatihan kepecintaalaman</span>
                            </li>
                            <li class="flex items-start gap-3 text-gray-600">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold">2</span>
                                <span>Mengembangkan karakter dan jiwa kepemimpinan anggota</span>
                            </li>
                            <li class="flex items-start gap-3 text-gray-600">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold">3</span>
                                <span>Melestarikan lingkungan dan kearifan lokal</span>
                            </li>
                            <li class="flex items-start gap-3 text-gray-600">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold">4</span>
                                <span>Melakukan kegiatan konservasi dan penelitian alam</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Divisions --}}
    @if($divisions->count() > 0)
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-5xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Divisi Organisasi</h2>
                        <p class="text-xl text-gray-600">Struktur divisi yang menunjang kegiatan organisasi</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($divisions as $division)
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-shadow">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $division->name }}</h3>
                                @if($division->description)
                                    <p class="text-gray-600 leading-relaxed">{{ $division->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Leadership (if available) --}}
    @if($leadership->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-5xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Pengurus Inti</h2>
                        <p class="text-xl text-gray-600">Tim yang memimpin dan menggerakkan organisasi</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($leadership as $leader)
                            <div class="bg-white rounded-xl p-6 text-center border border-gray-200 hover:shadow-lg transition-shadow">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                                    {{ substr($leader->name, 0, 1) }}
                                </div>
                                <h3 class="font-bold text-gray-900 mb-1">{{ $leader->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $leader->getRoleNames()->first() }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Values --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Nilai-Nilai Kami</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Kebersamaan</h3>
                        <p class="text-gray-600">Membangun solidaritas dan kekeluargaan yang kuat</p>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Integritas</h3>
                        <p class="text-gray-600">Menjunjung tinggi kejujuran dan tanggung jawab</p>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Peduli Alam</h3>
                        <p class="text-gray-600">Berkomitmen dalam pelestarian lingkungan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-gradient-to-r from-green-600 to-teal-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Tertarik Bergabung?</h2>
                <p class="text-xl opacity-90 mb-8">
                    Jadilah bagian dari keluarga besar Mapala Pagaruyung
                </p>
                <a href="{{ route('recruitment.register') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-700 rounded-lg font-bold hover:bg-green-50 transition-all transform hover:scale-105 shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Lihat Info Pendaftaran
                </a>
            </div>
        </div>
    </section>
@endsection
