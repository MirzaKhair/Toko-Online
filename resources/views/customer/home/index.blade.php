@extends('layouts.customer')

@section('title', 'Beranda')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-30"></div>
        <div class="relative max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                    Belanja Mudah,<br>Hasil Memuaskan
                </h1>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Temukan produk berkualitas dengan harga terjangkau. Belanja dari mana saja, kapan saja.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('categories.index') }}"
                       class="inline-flex items-center justify-center bg-white text-blue-700 font-semibold px-8 py-3.5 rounded-xl hover:bg-blue-50 transition shadow-lg shadow-blue-900/20">
                        Mulai Belanja
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('tracking.form') }}"
                       class="inline-flex items-center justify-center border-2 border-white/30 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 transition">
                        Lacak Pesanan
                    </a>
                </div>
            </div>
        </div>
    </section>

    @php
        $categories = \App\Models\Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])->where('is_active', true)->take(6)->get();
        $latestProducts = \App\Models\Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();
    @endphp

    {{-- Kategori --}}
    @if($categories->isNotEmpty())
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Belanja per Kategori</h2>
                    <p class="mt-2 text-gray-500">Pilih kategori sesuai kebutuhan Anda</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="group bg-white rounded-xl border border-gray-100 p-5 text-center hover:shadow-md hover:border-blue-200 transition-all duration-200">
                            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-100 transition">
                                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition">{{ $category->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }} produk</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Produk Unggulan --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Produk Unggulan</h2>
                    <p class="mt-2 text-gray-500">Pilihan produk terbaik untuk Anda</p>
                </div>
                <a href="{{ route('categories.index') }}"
                   class="hidden sm:inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($featuredProducts->isEmpty())
                @component('components.empty-state', ['title' => 'Belum ada produk unggulan', 'description' => 'Produk unggulan akan muncul di sini setelah ditambahkan.', 'icon' => 'box'])
                @endcomponent
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($featuredProducts as $product)
                        @include('components.customer.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-8 text-center sm:hidden">
                    <a href="{{ route('categories.index') }}"
                       class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                        Lihat Semua Produk
                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Produk Terbaru --}}
    @if($latestProducts->isNotEmpty())
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Produk Terbaru</h2>
                    <p class="mt-2 text-gray-500">Barang baru yang baru saja tersedia</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($latestProducts as $product)
                        @include('components.customer.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
