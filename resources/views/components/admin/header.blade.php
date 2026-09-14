<header class="bg-white border-b border-gray-100 sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 py-3">
        {{-- Tombol Toggle Sidebar --}}
        <button id="sidebar-toggle" onclick="toggleSidebar()"
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Notifikasi / Aksi --}}
        <div class="flex items-center space-x-2">
            <a href="{{ route('home') }}" target="_blank"
               class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 text-sm text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Lihat Toko</span>
            </a>
            <div class="w-px h-6 bg-gray-200 hidden sm:block"></div>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <span class="hidden sm:inline text-sm text-gray-700 font-medium">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
