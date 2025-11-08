@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between md:justify-center px-4 py-6">
        <div class="flex-1 flex justify-between md:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 rounded-lg bg-white shadow hover:bg-gray-50 transition">Sebelumnya</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 rounded-lg bg-white shadow hover:bg-gray-50 transition">Berikutnya</a>
            @else
                <span class="px-4 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">Berikutnya</span>
            @endif
        </div>

        <div class="hidden md:flex items-center gap-2 bg-white dark:bg-slate-900 px-4 py-3 rounded-2xl shadow border border-gray-100 dark:border-slate-800">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="p-2 rounded-lg text-gray-400 bg-gray-100 dark:bg-slate-800 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="p-2 rounded-lg text-gray-600 hover:text-green-600 bg-gray-50 hover:bg-green-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1 text-gray-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold shadow">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 rounded-lg text-gray-600 hover:text-green-600 hover:bg-green-50 transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="p-2 rounded-lg text-gray-600 hover:text-green-600 bg-gray-50 hover:bg-green-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="p-2 rounded-lg text-gray-400 bg-gray-100 dark:bg-slate-800 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
