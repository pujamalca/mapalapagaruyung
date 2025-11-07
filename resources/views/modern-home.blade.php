@extends('layouts.modern')

@section('title', 'Beranda - Mapala Pagaruyung')
@section('meta_description', 'Jelajahi alam Sumatera bersama Mapala Pagaruyung. Organisasi Mahasiswa Pecinta Alam yang aktif dalam pendakian, ekspedisi, dan konservasi.')

@section('content')

{{-- Hero Section dengan Parallax --}}
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    {{-- Background Image dengan Parallax --}}
    <div class="absolute inset-0 parallax" data-speed="0.5">
        <img
            src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80"
            alt="Mountain Background"
            class="w-full h-full object-cover"
        >
        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-green-800/70 to-teal-900/80"></div>
    </div>

    {{-- Floating Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-green-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
    </div>

    {{-- Content --}}
    <div class="container mx-auto px-4 relative z-10 text-center text-white">
        <div data-aos="fade-down" data-aos-duration="1000">
            <span class="inline-block px-6 py-2 bg-white/10 backdrop-blur-md rounded-full text-sm font-semibold mb-6 border border-white/20">
                🏔️ Mahasiswa Pecinta Alam
            </span>
        </div>

        <h1
            class="text-5xl md:text-7xl lg:text-8xl font-black mb-6 leading-tight"
            data-aos="fade-up"
            data-aos-duration="1000"
            data-aos-delay="200"
        >
            Mapala
            <span class="block bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">
                Pagaruyung
            </span>
        </h1>

        <p
            class="text-xl md:text-2xl text-green-100 mb-12 max-w-3xl mx-auto leading-relaxed"
            data-aos="fade-up"
            data-aos-duration="1000"
            data-aos-delay="400"
        >
            Jelajahi keindahan alam Sumatera bersama kami.<br>
            Bergabunglah dalam petualangan yang mengubah hidup.
        </p>

        <div
            class="flex flex-col sm:flex-row items-center justify-center gap-4"
            data-aos="fade-up"
            data-aos-duration="1000"
            data-aos-delay="600"
        >
            @if($activeRecruitment)
                <a
                    href="{{ route('recruitment.register') }}"
                    class="group inline-flex items-center gap-3 px-8 py-4 bg-white text-green-700 rounded-full font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Daftar Sekarang
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @endif

            <a
                href="{{ route('activities.index') }}"
                class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-bold text-lg border-2 border-white/30 hover:bg-white/20 hover:scale-105 transition-all duration-300"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Lihat Kegiatan
            </a>
        </div>

        {{-- Scroll Indicator --}}
        <div
            class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce"
            data-aos="fade"
            data-aos-duration="1000"
            data-aos-delay="1000"
        >
            <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </div>
</section>

{{-- Statistics Section --}}
<section class="py-20 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-slate-800 dark:to-slate-900" data-stats>
    <div class="container mx-auto px-4">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Kami Dalam Angka
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Perjalanan kami dalam mengeksplorasi alam
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Members --}}
            <div class="text-center group" data-aos="fade-up" data-aos-delay="0">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2" data-counter data-target="{{ $stats['members'] }}">0</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">Anggota Aktif</div>
            </div>

            {{-- Expeditions --}}
            <div class="text-center group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <div class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2" data-counter data-target="{{ $stats['expeditions'] }}">0</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">Ekspedisi</div>
            </div>

            {{-- Training --}}
            <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2" data-counter data-target="{{ $stats['training'] }}">0</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">Program Pelatihan</div>
            </div>

            {{-- Achievements --}}
            <div class="text-center group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2" data-counter data-target="{{ $stats['competitions'] }}">0</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">Prestasi</div>
            </div>
        </div>
    </div>
</section>

{{-- Activities Carousel --}}
<section class="py-20 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-12" data-aos="fade-up">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    Kegiatan <span class="gradient-text">Terbaru</span>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Ikuti petualangan dan kegiatan kami
                </p>
            </div>
            <a href="{{ route('activities.index') }}" class="hidden md:inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                Lihat Semua
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

        <div class="swiper activitiesSwiper" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper">
                @foreach($recentExpeditions->take(6) as $expedition)
                    <div class="swiper-slide">
                        <div class="group relative overflow-hidden rounded-2xl shadow-xl hover-lift bg-white dark:bg-slate-800">
                            {{-- Image --}}
                            <div class="relative h-64 overflow-hidden">
                                @if($expedition->getFirstMediaUrl('featured'))
                                    <img
                                        src="{{ $expedition->getFirstMediaUrl('featured') }}"
                                        alt="{{ $expedition->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                    >
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Gradient Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                                {{-- Badge --}}
                                <div class="absolute top-4 left-4">
                                    <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                                        Ekspedisi
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <h3 class="text-xl font-bold mb-2 line-clamp-2">{{ $expedition->title }}</h3>
                                <div class="flex items-center gap-4 text-sm text-green-200">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $expedition->start_date->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $expedition->participants->count() }} Peserta
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Navigation --}}
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            {{-- Pagination --}}
            <div class="swiper-pagination mt-8"></div>
        </div>
    </div>
</section>

{{-- Gallery Showcase --}}
<section class="py-20 bg-gradient-to-br from-slate-50 to-gray-100 dark:from-slate-900 dark:to-slate-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Galeri <span class="gradient-text">Foto</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                Dokumentasi visual perjalanan kami
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" data-aos="fade-up" data-aos-delay="200">
            @foreach($featuredGalleries->take(8) as $gallery)
                <a
                    href="{{ $gallery->getFirstMediaUrl('images') ?: 'https://via.placeholder.com/800' }}"
                    class="glightbox group relative overflow-hidden rounded-xl aspect-square hover-lift"
                    data-gallery="gallery"
                >
                    @if($gallery->getFirstMediaUrl('images'))
                        <img
                            src="{{ $gallery->getFirstMediaUrl('images') }}"
                            alt="{{ $gallery->title }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                        >
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-700 dark:to-gray-800"></div>
                    @endif

                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-colors duration-300 flex items-center justify-center">
                        <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12" data-aos="fade-up">
            <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-full font-bold hover:shadow-xl hover:scale-105 transition-all duration-300">
                Lihat Semua Foto
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="relative py-32 overflow-hidden">
    {{-- Animated Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600"></div>

    {{-- Pattern Overlay --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 text-center text-white">
        <div data-aos="zoom-in">
            <h2 class="text-4xl md:text-6xl font-black mb-6">
                Siap Bergabung<br>Dengan Kami?
            </h2>
            <p class="text-xl md:text-2xl text-green-100 mb-12 max-w-2xl mx-auto">
                Jadilah bagian dari petualangan yang akan mengubah hidupmu. Bersama kami, kamu akan belajar, bertumbuh, dan menciptakan kenangan yang tak terlupakan.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('member.dashboard') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-green-700 rounded-full font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Portal Member
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-bold text-lg border-2 border-white/30 hover:bg-white/20 hover:scale-105 transition-all duration-300">
                    Tentang Kami
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Initialize Swiper
    const swiper = new Swiper('.activitiesSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });

    // Initialize GLightbox
    const lightbox = GLightbox({
        selector: '.glightbox'
    });
</script>
@endpush
