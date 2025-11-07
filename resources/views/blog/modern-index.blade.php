@extends('layouts.modern')

@section('title', 'Blog & Berita - Mapala Pagaruyung')
@section('meta_description', 'Baca artikel, berita, dan cerita petualangan dari Mapala Pagaruyung')

@section('content')
<!-- Hero Section -->
<section class="relative pt-32 pb-20 bg-gradient-to-br from-green-900 via-emerald-800 to-teal-900 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="blog-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#blog-grid)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-green-100 mb-6" data-aos="fade-down">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span class="font-semibold">Blog & Berita</span>
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6" data-aos="fade-up" data-aos-delay="100">
                Cerita &<br>
                <span class="gradient-text bg-gradient-to-r from-green-300 via-emerald-300 to-teal-300">Petualangan</span>
            </h1>

            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Jelajahi kisah-kisah inspiratif, tips petualangan, dan update terbaru dari Mapala Pagaruyung
            </p>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('blog.index') }}" class="max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="300">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari artikel..."
                           class="w-full px-6 py-4 pr-12 rounded-full bg-white/95 backdrop-blur-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-xl">
                    <button type="submit"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-full hover:shadow-lg transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white" class="dark:fill-slate-900"/>
        </svg>
    </div>
</section>

<!-- Category Filter -->
<section class="py-8 bg-white dark:bg-slate-900 sticky top-20 z-40 border-b border-gray-200 dark:border-gray-800 backdrop-blur-md">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-center gap-2">
            <a href="{{ route('blog.index') }}"
               class="px-4 py-2 rounded-full font-semibold transition-all duration-300 {{ !request('category') ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                Semua
            </a>
            @foreach($categories as $category)
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                   class="px-4 py-2 rounded-full font-semibold transition-all duration-300 {{ request('category') == $category->slug ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    {{ $category->name }}
                    <span class="text-xs opacity-75">({{ $category->posts_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Post -->
@if($featuredPost && !request('search') && !request('category'))
<section class="py-16 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8 flex items-center gap-2" data-aos="fade-up">
                <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Artikel Unggulan
            </h2>

            <a href="{{ route('blog.show', $featuredPost->slug) }}"
               class="group relative overflow-hidden rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500 block"
               data-aos="fade-up" data-aos-delay="100">
                <div class="aspect-[21/9] relative overflow-hidden">
                    @if($featuredPost->getFirstMediaUrl('featured_image'))
                        <img src="{{ $featuredPost->getFirstMediaUrl('featured_image') }}"
                             alt="{{ $featuredPost->title }}"
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center">
                            <svg class="w-32 h-32 text-white/20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                    <div class="flex items-center gap-3 mb-4">
                        @if($featuredPost->category)
                            <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm font-semibold">
                                {{ $featuredPost->category->name }}
                            </span>
                        @endif
                        <span class="text-white/80 text-sm">
                            {{ $featuredPost->published_at->format('d M Y') }}
                        </span>
                    </div>

                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 group-hover:text-green-300 transition-colors duration-300">
                        {{ $featuredPost->title }}
                    </h2>

                    @if($featuredPost->excerpt)
                        <p class="text-lg text-gray-200 mb-6 line-clamp-2 max-w-3xl">
                            {{ $featuredPost->excerpt }}
                        </p>
                    @endif

                    <div class="flex items-center gap-4 text-white/80">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $featuredPost->author->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>{{ number_format($featuredPost->view_count) }} views</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Blog Posts Grid -->
<section class="py-16 bg-gray-50 dark:bg-slate-800">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="flex-1">
                    @if($posts->count() > 0)
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($posts as $index => $post)
                                <article class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2"
                                         data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                                    <!-- Image -->
                                    <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-video overflow-hidden relative">
                                        @if($post->getFirstMediaUrl('featured_image'))
                                            <img src="{{ $post->getFirstMediaUrl('featured_image') }}"
                                                 alt="{{ $post->title }}"
                                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white/20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </a>

                                    <!-- Content -->
                                    <div class="p-6">
                                        <div class="flex items-center gap-2 mb-3">
                                            @if($post->category)
                                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-xs font-semibold">
                                                    {{ $post->category->name }}
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $post->published_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300 line-clamp-2">
                                            <a href="{{ route('blog.show', $post->slug) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h3>

                                        @if($post->excerpt)
                                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                                                {{ $post->excerpt }}
                                            </p>
                                        @endif

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-green-600 to-emerald-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ strtoupper(substr($post->author->name, 0, 1)) }}
                                                </div>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $post->author->name }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ number_format($post->view_count) }} views
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16" data-aos="fade-up">
                            <div class="text-6xl mb-4">📝</div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Tidak Ada Artikel</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ request('search') ? 'Hasil pencarian tidak ditemukan' : 'Belum ada artikel yang dipublikasikan' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <aside class="lg:w-80 space-y-8">
                    <!-- Popular Posts -->
                    @if($popularPosts->count() > 0)
                        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-lg" data-aos="fade-left">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Populer
                            </h3>
                            <div class="space-y-4">
                                @foreach($popularPosts as $popularPost)
                                    <a href="{{ route('blog.show', $popularPost->slug) }}"
                                       class="block group">
                                        <div class="flex gap-3">
                                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden">
                                                @if($popularPost->getFirstMediaUrl('featured_image'))
                                                    <img src="{{ $popularPost->getFirstMediaUrl('featured_image') }}"
                                                         alt="{{ $popularPost->title }}"
                                                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-600"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2 mb-1">
                                                    {{ $popularPost->title }}
                                                </h4>
                                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                    <span>{{ number_format($popularPost->view_count) }} views</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-green-600 to-emerald-600 rounded-2xl p-6 shadow-lg text-white" data-aos="fade-left" data-aos-delay="100">
                        <h3 class="text-xl font-bold mb-2">📧 Newsletter</h3>
                        <p class="text-green-100 text-sm mb-4">Dapatkan update artikel terbaru langsung di inbox Anda</p>
                        <form class="space-y-3" x-data="{ email: '', success: false }">
                            <input type="email"
                                   x-model="email"
                                   placeholder="Email Anda"
                                   class="w-full px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-white/50">
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-white text-green-700 rounded-lg font-semibold hover:bg-green-50 transition-colors duration-300">
                                Berlangganan
                            </button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
