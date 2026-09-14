<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-8">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-blue-600">
                    Toko Online - Admin
                </a>
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Kategori
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Produk
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Pesanan
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Pengaturan
                    </a>
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
