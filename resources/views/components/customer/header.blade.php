@php
    $storeSetting = \App\Models\StoreSetting::getSingleton();
    $cartCount = session('cart') ? collect(session('cart'))->sum('quantity') : 0;
@endphp

<header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo / Nama Toko --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 flex-shrink-0">
                @if($storeSetting && $storeSetting->logo)
                    <img src="{{ asset('storage/' . $storeSetting->logo) }}"
                         alt="{{ $storeSetting->store_name }}"
                         class="h-8 w-auto object-contain">
                @endif
                <span class="text-xl font-bold text-blue-600">
                    {{ $storeSetting->store_name ?? 'Toko Online' }}
                </span>
            </a>

            {{-- Navigasi Desktop --}}
            <nav class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    Beranda
                </a>
                <a href="{{ route('categories.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    Kategori
                </a>
                <a href="{{ route('tracking.form') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('tracking.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    Lacak Pesanan
                </a>
                <div class="w-px h-6 bg-gray-200 mx-1"></div>
                <a href="{{ route('cart.index') }}"
                   class="relative flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('cart.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Keranjang</span>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold rounded-full h-5 min-w-[20px] flex items-center justify-center px-1">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </nav>

            {{-- Tombol Mobile --}}
            <div class="flex items-center space-x-2 md:hidden">
                <a href="{{ route('cart.index') }}"
                   class="relative p-2 text-gray-600 hover:text-blue-600 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold rounded-full h-5 min-w-[20px] flex items-center justify-center px-1">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <button id="mobile-menu-button"
                        class="p-2 text-gray-600 hover:text-blue-600 rounded-lg hover:bg-gray-50 transition"
                        aria-label="Toggle menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Navigasi Mobile --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 py-3 space-y-1">
            <a href="{{ route('home') }}"
               class="block px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50' }}">
                Beranda
            </a>
            <a href="{{ route('categories.index') }}"
               class="block px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50' }}">
                Kategori
            </a>
            <a href="{{ route('tracking.form') }}"
               class="block px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('tracking.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50' }}">
                Lacak Pesanan
            </a>
            <a href="{{ route('cart.index') }}"
               class="block px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('cart.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50' }}">
                Keranjang @if($cartCount > 0)({{ $cartCount }})@endif
            </a>
        </div>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
