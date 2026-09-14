@extends('layouts.customer')

@section('title', 'Beranda')

@section('content')
    {{-- Hero Section --}}
    <section class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Selamat Datang di Toko Online</h1>
            <p class="text-xl text-blue-100 mb-8">
                Temukan produk terbaik dengan harga terjangkau
            </p>
            <a href="{{ route('categories.index') }}"
               class="inline-block bg-white text-blue-600 font-semibold px-6 py-3 rounded-lg hover:bg-blue-50 transition">
                Lihat Produk
            </a>
        </div>
    </section>

    {{-- Produk Unggulan --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Produk Unggulan</h2>

            @if($featuredProducts->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-gray-500">Belum ada produk unggulan</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        @include('components.customer.product-card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
