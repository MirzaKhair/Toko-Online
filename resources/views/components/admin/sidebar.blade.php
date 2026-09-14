@php
    $storeSetting = \App\Models\StoreSetting::getSingleton();
@endphp

<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-gray-300 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
    <div class="flex flex-col h-full">
        {{-- Logo --}}
        <div class="flex items-center space-x-3 px-6 py-5 border-b border-gray-800">
            @if($storeSetting && $storeSetting->logo)
                <img src="{{ asset('storage/' . $storeSetting->logo) }}"
                     alt="{{ $storeSetting->store_name }}"
                     class="h-8 w-auto object-contain">
            @endif
            <div>
                <span class="text-lg font-bold text-white block leading-tight">{{ $storeSetting->store_name ?? 'Toko Online' }}</span>
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Admin Panel</span>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Pesanan</span>
            </a>

            <div class="pt-4 pb-2 px-3">
                <p class="text-[10px] font-semibold text-gray-600 uppercase tracking-wider">Pengaturan</p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Pengaturan Toko</span>
            </a>
        </nav>

        {{-- Footer --}}
        <div class="px-3 py-4 border-t border-gray-800">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Lihat Toko</span>
            </a>
            <div class="mt-3 px-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-xs font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <span class="text-sm text-gray-400 truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-gray-500 hover:text-red-400 rounded-lg hover:bg-gray-800 transition" title="Logout">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- Overlay untuk mobile --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>
