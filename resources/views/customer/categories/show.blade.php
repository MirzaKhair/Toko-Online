@extends('layouts.customer')

@section('title', $category->name)

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Header Kategori --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
            @if($category->description)
                <p class="mt-2 text-gray-600">{{ $category->description }}</p>
            @endif
        </div>

        {{-- Daftar Produk --}}
        @if($products->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-gray-500">Belum ada produk dalam kategori ini</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @include('components.customer.product-card', ['product' => $product])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
