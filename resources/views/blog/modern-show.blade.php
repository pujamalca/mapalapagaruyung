@extends('layouts.modern')

@section('title', $post->title . ' - Blog Mapala Pagaruyung')
@section('meta_description', $post->excerpt ?? strip_tags(substr($post->content, 0, 160)))

@push('styles')
<style>
    /* Article Typography */
    .article-content {
        @apply prose prose-lg dark:prose-invert max-w-none;
    }

    .article-content h2 {
        @apply text-3xl font-bold text-gray-900 dark:text-white mt-12 mb-4;
    }

    .article-content h3 {
        @apply text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-3;
    }

    .article-content p {
        @apply text-gray-700 dark:text-gray-300 leading-relaxed mb-6;
    }

    .article-content a {
        @apply text-green-600 dark:text-green-400 hover:underline;
    }

    .article-content img {
        @apply rounded-2xl my-8 shadow-lg;
    }

    .article-content ul, .article-content ol {
        @apply my-6 space-y-2;
    }

    .article-content blockquote {
        @apply border-l-4 border-green-600 pl-6 italic text-gray-700 dark:text-gray-300 my-6;
    }

    .article-content code {
        @apply bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-sm;
    }

    .article-content pre {
        @apply bg-gray-900 text-gray-100 p-6 rounded-2xl overflow-x-auto my-6;
    }
</style>
@endpush

@section('content')
<!-- Hero Section with Featured Image -->
<section class="relative pt-32 pb-16 bg-gray-900 overflow-hidden">
    @if($post->getFirstMediaUrl('featured_image'))
        <div class="absolute inset-0">
            <img src="{{ $post->getFirstMediaUrl('featured_image') }}"
                 alt="{{ $post->title }}"
                 class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/80 via-gray-900/90 to-gray-900"></div>
        </div>
    @endif

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-8" data-aos="fade-down">
                <ol class="flex items-center gap-2 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
                    <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li class="text-white">{{ Str::limit($post->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 mb-6" data-aos="fade-up">
                @if($post->category)
                    <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm font-semibold">
                        {{ $post->category->name }}
                    </span>
                @endif
                <span class="text-gray-300 text-sm">
                    {{ $post->published_at->format('d F Y') }}
                </span>
                <span class="text-gray-300 text-sm">•</span>
                <span class="text-gray-300 text-sm">
                    {{ $post->reading_time ?? ceil(str_word_count(strip_tags($post->content)) / 200) }} min read
                </span>
                <span class="text-gray-300 text-sm">•</span>
                <span class="text-gray-300 text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($post->view_count) }} views
                </span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-8 leading-tight" data-aos="fade-up" data-aos-delay="100">
                {{ $post->title }}
            </h1>

            <!-- Author Info -->
            <div class="flex items-center gap-4" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-gradient-to-br from-green-600 to-emerald-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($post->author->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-white font-semibold">{{ $post->author->name }}</div>
                    <div class="text-gray-400 text-sm">{{ $post->author->email }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<article class="py-16 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Excerpt -->
            @if($post->excerpt)
                <div class="text-xl text-gray-600 dark:text-gray-400 mb-12 pb-8 border-b-2 border-gray-200 dark:border-gray-800 leading-relaxed" data-aos="fade-up">
                    {{ $post->excerpt }}
                </div>
            @endif

            <!-- Main Content -->
            <div class="article-content" data-aos="fade-up" data-aos-delay="100">
                {!! $post->content !!}
            </div>

            <!-- Tags -->
            @if($post->tags->count() > 0)
                <div class="mt-12 pt-8 border-t-2 border-gray-200 dark:border-gray-800" data-aos="fade-up">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                               class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-sm font-semibold hover:bg-green-100 dark:hover:bg-green-900 hover:text-green-700 dark:hover:text-green-300 transition-colors duration-300">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Share Buttons -->
            <div class="mt-12 pt-8 border-t-2 border-gray-200 dark:border-gray-800" data-aos="fade-up">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Bagikan Artikel</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 text-white rounded-lg font-semibold hover:bg-sky-600 transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <!-- Author Bio -->
            <div class="mt-12 p-8 bg-gray-50 dark:bg-slate-800 rounded-2xl" data-aos="fade-up">
                <div class="flex items-start gap-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-600 to-emerald-600 rounded-full flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                        {{ strtoupper(substr($post->author->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $post->author->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ $post->author->bio ?? 'Penulis di Mapala Pagaruyung' }}
                        </p>
                        @if($post->author->email)
                            <a href="mailto:{{ $post->author->email }}"
                               class="text-green-600 dark:text-green-400 hover:underline text-sm">
                                {{ $post->author->email }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if($relatedPosts->count() > 0)
<section class="py-16 bg-gray-50 dark:bg-slate-800">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8" data-aos="fade-up">
                Artikel Terkait
            </h2>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($relatedPosts as $index => $relatedPost)
                    <article class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2"
                             data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="block aspect-video overflow-hidden relative">
                            @if($relatedPost->getFirstMediaUrl('featured_image'))
                                <img src="{{ $relatedPost->getFirstMediaUrl('featured_image') }}"
                                     alt="{{ $relatedPost->title }}"
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-600"></div>
                            @endif
                        </a>

                        <div class="p-6">
                            @if($relatedPost->category)
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-xs font-semibold">
                                    {{ $relatedPost->category->name }}
                                </span>
                            @endif

                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-3 mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">
                                <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                    {{ $relatedPost->title }}
                                </a>
                            </h3>

                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $relatedPost->published_at->diffForHumans() }}</span>
                                <span>•</span>
                                <span>{{ number_format($relatedPost->view_count) }} views</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-br from-green-900 via-emerald-800 to-teal-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="dots" width="30" height="30" patternUnits="userSpaceOnUse">
                    <circle cx="15" cy="15" r="2" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dots)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-black text-white mb-4">
                Jangan Lewatkan Artikel Terbaru
            </h2>
            <p class="text-xl text-green-100 mb-8">
                Bergabunglah dengan ribuan pembaca lainnya
            </p>
            <a href="{{ route('blog.index') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-700 rounded-full font-bold text-lg hover:bg-green-50 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Lihat Semua Artikel
            </a>
        </div>
    </div>
</section>
@endsection
