@php
    $storeSetting = \App\Models\StoreSetting::getSingleton();
@endphp

<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Brand --}}
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    @if($storeSetting && $storeSetting->logo)
                        <img src="{{ asset('storage/' . $storeSetting->logo) }}"
                             alt="{{ $storeSetting->store_name }}"
                             class="h-8 w-auto object-contain">
                    @endif
                    <span class="text-xl font-bold text-white">{{ $storeSetting->store_name ?? 'Toko Online' }}</span>
                </div>
                @if($storeSetting && $storeSetting->description)
                    <p class="text-sm text-gray-400">{{ $storeSetting->description }}</p>
                @endif
            </div>

            {{-- Navigasi --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigasi</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition">Beranda</a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="text-sm text-gray-400 hover:text-white transition">Kategori</a>
                    </li>
                    <li>
                        <a href="{{ route('tracking.form') }}" class="text-sm text-gray-400 hover:text-white transition">Lacak Pesanan</a>
                    </li>
                </ul>
            </div>

            {{-- Hubungi Kami --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Hubungi Kami</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    @if($storeSetting && $storeSetting->address)
                        <li class="flex items-start space-x-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $storeSetting->address }}</span>
                        </li>
                    @endif
                    @if($storeSetting && $storeSetting->phone)
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $storeSetting->phone }}</span>
                        </li>
                    @endif
                    @if($storeSetting && $storeSetting->email)
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $storeSetting->email }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ $storeSetting->store_name ?? 'Toko Online' }}. Hak cipta dilindungi.
            </p>
        </div>
    </div>
</footer>
