@extends('layouts.modern')

@section('title', 'Galeri Foto - Mapala Pagaruyung')
@section('meta_description', 'Dokumentasi visual perjalanan dan kegiatan Mapala Pagaruyung. Lihat foto-foto ekspedisi, training, dan kompetisi kami.')

@section('content')

{{-- Hero Section --}}
<section class="relative pt-32 pb-20 bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600 overflow-hidden">
    {{-- Pattern --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white" data-aos="fade-up">
            <h1 class="text-5xl md:text-6xl font-black mb-6">
                Galeri <span class="text-green-200">Foto</span>
            </h1>
            <p class="text-xl md:text-2xl text-green-100 max-w-3xl mx-auto mb-8">
                Dokumentasi Visual Petualangan Kami
            </p>
        </div>

        {{-- Search & Filter --}}
        <div class="max-w-4xl mx-auto mt-12" data-aos="fade-up" data-aos-delay="200">
            <div class="flex flex-col md:flex-row gap-4">
                {{-- Search --}}
                <div class="flex-1">
                    <form action="{{ route('gallery') }}" method="GET" class="relative" x-data="{ search: '{{ request('search') }}' }">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input
                            type="text"
                            name="search"
                            x-model="search"
                            placeholder="Cari foto..."
                            class="w-full px-6 py-4 pl-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all"
                        >
                        <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button
                            type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2 bg-white text-green-700 rounded-xl font-semibold hover:bg-green-50 transition-all"
                        >
                            Cari
                        </button>
                    </form>
                </div>

                {{-- View Toggle --}}
                <div class="flex gap-2" x-data="{ view: 'grid' }">
                    <button
                        @click="view = 'grid'"
                        :class="view === 'grid' ? 'bg-white text-green-700' : 'bg-white/10 text-white border-white/20'"
                        class="px-6 py-4 rounded-2xl border transition-all hover:bg-white/20"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button
                        @click="view = 'masonry'"
                        :class="view === 'masonry' ? 'bg-white text-green-700' : 'bg-white/10 text-white border-white/20'"
                        class="px-6 py-4 rounded-2xl border transition-all hover:bg-white/20"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 12a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Category Filter --}}
<section class="py-8 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-gray-800 sticky top-20 z-30 backdrop-blur-md bg-white/90 dark:bg-slate-900/90">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-4 overflow-x-auto pb-2 scrollbar-hide" data-aos="fade-up">
            <a
                href="{{ route('gallery') }}"
                class="px-6 py-2.5 rounded-full font-semibold whitespace-nowrap transition-all {{ !request('category') ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
            >
                Semua
            </a>
            @foreach($categories as $category)
                <a
                    href="{{ route('gallery', ['category' => $category->id, 'search' => request('search')]) }}"
                    class="px-6 py-2.5 rounded-full font-semibold whitespace-nowrap transition-all {{ request('category') == $category->id ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                >
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Gallery Grid --}}
<section class="py-16 bg-gray-50 dark:bg-slate-800">
    <div class="container mx-auto px-4">
        @if($galleries->count() > 0)
            {{-- Grid View --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galleries as $index => $gallery)
                    <div
                        class="group relative overflow-hidden rounded-2xl shadow-lg hover-lift bg-white dark:bg-slate-900"
                        data-aos="fade-up"
                        data-aos-delay="{{ $index * 50 }}"
                    >
                        {{-- Image --}}
                        <a
                            href="{{ $gallery->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800' }}"
                            class="glightbox block relative aspect-square overflow-hidden"
                            data-gallery="gallery"
                            data-title="{{ $gallery->title }}"
                            data-description="{{ $gallery->description }}"
                        >
                            @if($gallery->getFirstMediaUrl('images'))
                                <img
                                    src="{{ $gallery->getFirstMediaUrl('images') }}"
                                    alt="{{ $gallery->title }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            {{-- Zoom Icon --}}
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center transform scale-50 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Category Badge --}}
                            <div class="absolute top-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                @if($gallery->galleryCategory)
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                        {{ $gallery->galleryCategory->name }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        {{-- Info --}}
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1 line-clamp-1">
                                {{ $gallery->title }}
                            </h3>
                            @if($gallery->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $gallery->description }}
                                </p>
                            @endif
                            <div class="flex items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ $gallery->view_count ?? 0 }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $gallery->published_at?->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12" data-aos="fade-up">
                {{ $galleries->links('vendor.pagination.modern') }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-20" data-aos="fade-up">
                <div class="w-32 h-32 mx-auto mb-6 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Tidak Ada Foto
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ request('search') ? 'Tidak ada hasil untuk pencarian "' . request('search') . '"' : 'Belum ada foto di galeri ini' }}
                </p>
                @if(request('search') || request('category'))
                    <a
                        href="{{ route('gallery') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Hapus Filter
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Initialize GLightbox
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
    });

    // Smooth scroll for sticky nav
    window.addEventListener('scroll', () => {
        const stickyNav = document.querySelector('.sticky');
        if (stickyNav && window.scrollY > 100) {
            stickyNav.classList.add('shadow-lg');
        } else if (stickyNav) {
            stickyNav.classList.remove('shadow-lg');
        }
    });
</script>
@endpush

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
