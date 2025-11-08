<nav
    x-data="{
        scrolled: false,
        mobileMenuOpen: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 50;
            });
        }
    }"
    :class="{
        'bg-white/90 dark:bg-slate-900/90 backdrop-blur-md shadow-lg': scrolled,
        'bg-transparent': !scrolled
    }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-emerald-600 rounded-lg flex items-center justify-center transform group-hover:rotate-6 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-xl" :class="scrolled ? 'text-gray-900 dark:text-white' : 'text-white'">
                        Mapala Pagaruyung
                    </div>
                    <div class="text-xs" :class="scrolled ? 'text-gray-600 dark:text-gray-400' : 'text-green-100'">
                        Mahasiswa Pecinta Alam
                    </div>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                   class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                   :class="scrolled ? 'text-gray-700 dark:text-gray-300 hover:text-green-600' : 'text-white hover:text-green-300'">
                    Beranda
                </a>
                <a href="{{ route('about') }}"
                   class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                   :class="scrolled ? 'text-gray-700 dark:text-gray-300 hover:text-green-600' : 'text-white hover:text-green-300'">
                    Tentang
                </a>
                <a href="{{ route('activities.index') }}"
                   class="nav-link {{ request()->routeIs('activities*') ? 'active' : '' }}"
                   :class="scrolled ? 'text-gray-700 dark:text-gray-300 hover:text-green-600' : 'text-white hover:text-green-300'">
                    Kegiatan
                </a>
                <a href="{{ route('gallery.index') }}"
                   class="nav-link {{ request()->routeIs('gallery*') ? 'active' : '' }}"
                   :class="scrolled ? 'text-gray-700 dark:text-gray-300 hover:text-green-600' : 'text-white hover:text-green-300'">
                    Galeri
                </a>
                <a href="{{ route('blog.index') }}"
                   class="nav-link {{ request()->routeIs('blog*') ? 'active' : '' }}"
                   :class="scrolled ? 'text-gray-700 dark:text-gray-300 hover:text-green-600' : 'text-white hover:text-green-300'">
                    Blog
                </a>
                <a href="{{ route('contact') }}"
                   class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                   :class="scrolled ? 'text-gray-700 dark:text-gray-300 hover:text-green-600' : 'text-white hover:text-green-300'">
                    Kontak
                </a>

                <!-- CTA Button -->
                <a href="/admin"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Portal Member
                </a>

                <!-- Dark Mode Toggle -->
                <button
                    @click="darkMode = !darkMode"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    :class="scrolled ? 'text-gray-700 dark:text-gray-300' : 'text-white'"
                >
                    <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 rounded-lg"
                :class="scrolled ? 'text-gray-700 dark:text-gray-300' : 'text-white'"
            >
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="lg:hidden bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-gray-800"
        x-cloak
    >
        <div class="container mx-auto px-4 py-6 space-y-4">
            <a href="{{ route('home') }}"
               class="block py-2 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-500 font-medium {{ request()->routeIs('home') ? 'text-green-600 dark:text-green-500' : '' }}">
                🏠 Beranda
            </a>
            <a href="{{ route('about') }}"
               class="block py-2 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-500 font-medium {{ request()->routeIs('about') ? 'text-green-600 dark:text-green-500' : '' }}">
                ℹ️ Tentang
            </a>
            <a href="{{ route('activities.index') }}"
               class="block py-2 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-500 font-medium {{ request()->routeIs('activities*') ? 'text-green-600 dark:text-green-500' : '' }}">
                📅 Kegiatan
            </a>
            <a href="{{ route('gallery.index') }}"
               class="block py-2 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-500 font-medium {{ request()->routeIs('gallery*') ? 'text-green-600 dark:text-green-500' : '' }}">
                📸 Galeri
            </a>
            <a href="{{ route('blog.index') }}"
               class="block py-2 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-500 font-medium {{ request()->routeIs('blog*') ? 'text-green-600 dark:text-green-500' : '' }}">
                📝 Blog
            </a>
            <a href="{{ route('contact') }}"
               class="block py-2 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-500 font-medium {{ request()->routeIs('contact') ? 'text-green-600 dark:text-green-500' : '' }}">
                ✉️ Kontak
            </a>

            <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                <a href="/admin"
                   class="block w-full text-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                    Portal Member
                </a>
            </div>

            <button
                @click="darkMode = !darkMode"
                class="flex items-center justify-center w-full gap-2 py-3 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-500"
            >
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
            </button>
        </div>
    </div>
</nav>

<style>
    .nav-link {
        position: relative;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #059669, #10b981);
        transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }
</style>
