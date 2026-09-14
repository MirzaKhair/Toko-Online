<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo / Nama Toko --}}
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                Toko Online
            </a>

            {{-- Navigasi Desktop --}}
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                   class="text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                    Beranda
                </a>
                <a href="{{ route('categories.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('categories.*') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                    Kategori
                </a>
                <a href="{{ route('cart.index') }}"
                   class="relative text-sm font-medium {{ request()->routeIs('cart.*') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                    Keranjang
                    <span class="absolute -top-2 -right-4 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        0
                    </span>
                </a>
            </nav>

            {{-- Tombol Menu Mobile --}}
            <button id="mobile-menu-button" class="md:hidden p-2 text-gray-700 hover:text-blue-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Navigasi Mobile --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('home') }}"
                   class="text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700' }}">
                    Beranda
                </a>
                <a href="{{ route('categories.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('categories.*') ? 'text-blue-600' : 'text-gray-700' }}">
                    Kategori
                </a>
                <a href="{{ route('cart.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('cart.*') ? 'text-blue-600' : 'text-gray-700' }}">
                    Keranjang (0)
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
