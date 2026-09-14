@extends('layouts.customer')

@section('title', $category->name)

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('categories.index') }}" class="hover:text-blue-600 transition">Kategori</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">{{ $category->name }}</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
            @if($category->description)
                <p class="mt-2 text-gray-600">{{ $category->description }}</p>
            @endif
        </div>

        {{-- Daftar Produk --}}
        @if($products->isEmpty())
            @component('components.empty-state', ['title' => 'Belum ada produk', 'description' => 'Produk dalam kategori ini belum tersedia.', 'icon' => 'box'])
                <a href="{{ route('home') }}"
                   class="mt-2 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            @endcomponent
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    @include('components.customer.product-card', ['product' => $product])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
