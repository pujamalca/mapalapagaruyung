@extends('layouts.modern')

@section('title', $gallery->title . ' - Galeri Mapala Pagaruyung')
@section('meta_description', Str::limit($gallery->description ?? 'Dokumentasi kegiatan Mapala Pagaruyung.', 155))

@section('content')
    @php
        $cover = $gallery->getFirstMediaUrl('cover');
        $images = $gallery->getMedia('images');
        $videos = $gallery->getMedia('videos');
    @endphp

    {{-- Hero --}}
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-emerald-600 to-teal-600 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\\'60\\' height=\\'60\\' viewBox=\\'0 0 60 60\\' xmlns=\\'http://www.w3.org/2000/svg\\'%3E%3Cg fill=\\'none\\' fill-rule=\\'evenodd\\'%3E%3Cg fill=\\'%23ffffff\\' fill-opacity=\\'1\\'%3E%3Cpath d=\\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="uppercase tracking-[0.4em] text-green-200 text-sm mb-4">Galeri Foto</p>
                    <h1 class="text-5xl font-black mb-6 leading-tight">{{ $gallery->title }}</h1>
                    @if($gallery->description)
                        <p class="text-lg text-green-100 leading-relaxed">{{ $gallery->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 mt-10 text-sm">
                        @if($gallery->galleryCategory)
                            <span class="px-4 py-2 rounded-full bg-white/15 border border-white/30 font-semibold">
                                {{ $gallery->galleryCategory->name }}
                            </span>
                        @endif
                        <span class="px-4 py-2 rounded-full bg-white/15 border border-white/30">
                            📅 {{ $gallery->event_date?->format('d M Y') ?? $gallery->published_at?->format('d M Y') ?? $gallery->created_at->format('d M Y') }}
                        </span>
                        @if($gallery->location)
                            <span class="px-4 py-2 rounded-full bg-white/15 border border-white/30">
                                📍 {{ $gallery->location }}
                            </span>
                        @endif
                        @if($gallery->photographer_name)
                            <span class="px-4 py-2 rounded-full bg-white/15 border border-white/30">
                                📸 {{ $gallery->photographer_name }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-video rounded-3xl overflow-hidden shadow-2xl border border-white/20">
                        @if($cover)
                            <img src="{{ $cover }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                        @elseif($images->isNotEmpty())
                            <img src="{{ $images->first()->getUrl() }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-4xl font-black text-white/80">
                                MAPALA
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white/20 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/30 shadow-xl">
                        <div class="text-3xl font-black">{{ $gallery->view_count }}</div>
                        <div class="text-sm uppercase tracking-wide text-white/70">Total View</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Photo Grid --}}
    <section class="py-16 bg-gray-50 dark:bg-slate-900">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Foto Dokumentasi</h2>
                    <p class="text-gray-500 dark:text-gray-400">Total {{ $images->count() }} foto</p>
                </div>
            </div>

            @if($images->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-800">
                    <svg class="w-24 h-24 text-gray-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Belum ada foto</h3>
                    <p class="text-gray-500 dark:text-gray-400">Tim kami akan segera memperbarui dokumentasi galeri ini.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($images as $media)
                        <a href="{{ $media->getUrl() }}"
                           class="glightbox group block relative rounded-2xl overflow-hidden shadow-lg hover-lift"
                           data-gallery="gallery-{{ $gallery->id }}"
                           data-title="{{ $gallery->title }}"
                           data-description="{{ $media->getCustomProperty('caption') ?? $gallery->description }}"
                        >
                            <img src="{{ $media->getUrl() }}" alt="{{ $gallery->title }}" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            @if($media->getCustomProperty('caption'))
                                <div class="absolute bottom-0 left-0 right-0 p-4 text-white text-sm">
                                    {{ $media->getCustomProperty('caption') }}
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Videos --}}
    @if($videos->isNotEmpty())
        <section class="py-12 bg-white dark:bg-slate-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Video Dokumentasi</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    @foreach($videos as $video)
                        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-100 dark:border-slate-700">
                            <video controls class="w-full">
                                <source src="{{ $video->getUrl() }}" type="{{ $video->mime_type }}">
                                Browser Anda tidak mendukung pemutar video.
                            </video>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Related Galleries --}}
    @if($relatedGalleries->isNotEmpty())
        <section class="py-16 bg-gray-100 dark:bg-slate-900">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Galeri Terkait</h2>
                        <p class="text-gray-500 dark:text-gray-400">Dokumentasi lain yang mungkin Anda sukai</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedGalleries as $related)
                        <a href="{{ route('gallery.show', $related) }}" class="block bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow hover-lift border border-gray-100 dark:border-slate-800">
                            <div class="aspect-video">
                                <img src="{{ $related->getFirstMediaUrl('images') ?: $related->getFirstMediaUrl('cover') }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $related->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $related->event_date?->format('d M Y') ?? $related->published_at?->format('d M Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
        });
    </script>
@endpush
