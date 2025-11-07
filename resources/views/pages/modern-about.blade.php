@extends('layouts.modern')

@section('title', 'Tentang Kami - Mapala Pagaruyung')
@section('meta_description', 'Kenali lebih dalam tentang Mapala Pagaruyung - sejarah, visi misi, dan tim kami')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Parallax Background -->
    <div class="absolute inset-0 parallax" data-speed="0.5">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop"
             alt="Mountain Background"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-green-900/90 via-emerald-800/85 to-teal-900/90"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-green-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 pt-20">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-green-100 mb-6" data-aos="fade-down">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold">Tentang Kami</span>
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-8" data-aos="fade-up" data-aos-delay="100">
                Mapala<br>
                <span class="gradient-text bg-gradient-to-r from-green-300 via-emerald-300 to-teal-300">Pagaruyung</span>
            </h1>

            <p class="text-xl md:text-2xl text-green-100 mb-12 max-w-3xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Organisasi Mahasiswa Pecinta Alam yang berkomitmen untuk mengembangkan jiwa petualang, kepedulian lingkungan, dan semangat kebersamaan
            </p>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="text-4xl font-black text-white mb-2">{{ $stats['total_members'] }}+</div>
                    <div class="text-sm text-green-200">Total Anggota</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="text-4xl font-black text-white mb-2">{{ $stats['cohorts'] }}+</div>
                    <div class="text-sm text-green-200">Angkatan</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="text-4xl font-black text-white mb-2">{{ $stats['divisions'] }}</div>
                    <div class="text-sm text-green-200">Divisi</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="text-4xl font-black text-white mb-2">14+</div>
                    <div class="text-sm text-green-200">Tahun Berdiri</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-24 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Visi & Misi
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-600 to-emerald-600 mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Vision Card -->
                <div class="group perspective" data-aos="fade-right">
                    <div class="relative preserve-3d duration-1000 hover:rotate-y-180">
                        <div class="backface-hidden bg-gradient-to-br from-green-600 to-emerald-600 rounded-3xl p-8 md:p-12 shadow-2xl min-h-[400px] flex flex-col justify-center">
                            <div class="text-6xl mb-6">🎯</div>
                            <h3 class="text-3xl font-black text-white mb-4">Visi</h3>
                            <p class="text-green-50 text-lg leading-relaxed">
                                Menjadi organisasi mahasiswa pecinta alam yang unggul dalam pengembangan karakter, pelestarian lingkungan, dan pengabdian kepada masyarakat
                            </p>
                            <div class="mt-6 text-white/60 text-sm">
                                Hover untuk detail →
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission Card -->
                <div class="group perspective" data-aos="fade-left">
                    <div class="relative preserve-3d duration-1000 hover:rotate-y-180">
                        <div class="backface-hidden bg-gradient-to-br from-blue-600 to-sky-600 rounded-3xl p-8 md:p-12 shadow-2xl min-h-[400px]">
                            <div class="text-6xl mb-6">🚀</div>
                            <h3 class="text-3xl font-black text-white mb-6">Misi</h3>
                            <ul class="space-y-3 text-blue-50">
                                <li class="flex items-start gap-3">
                                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Mengembangkan jiwa petualang dan kepemimpinan anggota</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Melakukan pelestarian dan konservasi lingkungan</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Memberikan kontribusi nyata kepada masyarakat</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Membangun jaringan dan kolaborasi luas</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- History Timeline -->
<section class="py-24 bg-gray-50 dark:bg-slate-800">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Perjalanan Kami
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-600 to-emerald-600 mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Jejak langkah Mapala Pagaruyung dari masa ke masa
                </p>
            </div>

            <!-- Timeline -->
            <div class="relative">
                <!-- Center Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-green-600 via-emerald-600 to-teal-600 hidden md:block"></div>

                <div class="space-y-12">
                    @foreach($timeline as $index => $item)
                        <div class="relative" data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}" data-aos-delay="{{ $index * 100 }}">
                            <div class="md:flex items-center {{ $index % 2 == 0 ? '' : 'flex-row-reverse' }}">
                                <!-- Content -->
                                <div class="md:w-1/2 {{ $index % 2 == 0 ? 'md:pr-12' : 'md:pl-12' }}">
                                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                                        <div class="inline-block px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-full text-sm font-bold mb-4">
                                            {{ $item['year'] }}
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                                            {{ $item['title'] }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                            {{ $item['description'] }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Center Dot -->
                                <div class="absolute left-1/2 transform -translate-x-1/2 hidden md:flex items-center justify-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-green-600 to-emerald-600 rounded-full border-4 border-white dark:border-slate-800 shadow-lg z-10"></div>
                                </div>

                                <!-- Spacer -->
                                <div class="hidden md:block md:w-1/2"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-24 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Nilai-Nilai Kami
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-600 to-emerald-600 mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group bg-gradient-to-br from-green-50 to-emerald-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="0">
                    <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">🌱</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Peduli Lingkungan</h3>
                    <p class="text-gray-600 dark:text-gray-400">Berkomitmen dalam pelestarian dan konservasi alam</p>
                </div>

                <div class="group bg-gradient-to-br from-blue-50 to-sky-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">🤝</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Kebersamaan</h3>
                    <p class="text-gray-600 dark:text-gray-400">Membangun solidaritas dan kerjasama tim yang kuat</p>
                </div>

                <div class="group bg-gradient-to-br from-orange-50 to-red-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">💪</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Tangguh</h3>
                    <p class="text-gray-600 dark:text-gray-400">Menghadapi tantangan dengan semangat pantang menyerah</p>
                </div>

                <div class="group bg-gradient-to-br from-purple-50 to-pink-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">🎯</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Profesional</h3>
                    <p class="text-gray-600 dark:text-gray-400">Menjalankan setiap kegiatan dengan dedikasi tinggi</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team -->
@if($leadership->count() > 0)
<section class="py-24 bg-gray-50 dark:bg-slate-800">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Tim Pengurus
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-600 to-emerald-600 mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Para pemimpin yang membawa Mapala Pagaruyung menuju masa depan
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($leadership as $index => $leader)
                    <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                            <div class="aspect-square bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center">
                                @if($leader->getFirstMediaUrl('avatar'))
                                    <img src="{{ $leader->getFirstMediaUrl('avatar') }}"
                                         alt="{{ $leader->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="text-6xl text-white">
                                        {{ strtoupper(substr($leader->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $leader->name }}</h3>
                                <p class="text-green-600 dark:text-green-400 font-semibold mb-2">
                                    {{ $leader->getRoleNames()->first() }}
                                </p>
                                @if($leader->email)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $leader->email }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Divisions Section -->
@if($divisions->count() > 0)
<section class="py-24 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Divisi Kami
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-600 to-emerald-600 mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Struktur organisasi yang solid dan terorganisir
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($divisions as $index => $division)
                    <div class="group bg-gradient-to-br from-gray-50 to-white dark:from-slate-800 dark:to-slate-700 rounded-2xl p-6 border-2 border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                         data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-black text-white">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $division->name }}</h3>
                                @if($division->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $division->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-24 bg-gradient-to-br from-green-900 via-emerald-800 to-teal-900 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="cta-pattern" width="60" height="60" patternUnits="userSpaceOnUse">
                    <circle cx="30" cy="30" r="2" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#cta-pattern)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                Bergabung Bersama Kami
            </h2>
            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                Mari menjadi bagian dari keluarga besar Mapala Pagaruyung dan wujudkan petualangan impianmu
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('recruitment.register') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-700 rounded-full font-bold text-lg hover:bg-green-50 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Daftar Sekarang
                </a>
                <a href="{{ route('activities.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-transparent border-2 border-white text-white rounded-full font-bold text-lg hover:bg-white hover:text-green-700 transform hover:scale-105 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Kegiatan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
