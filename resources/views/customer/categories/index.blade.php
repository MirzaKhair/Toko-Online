@extends('layouts.customer')

@section('title', 'Kategori')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Kategori</h1>

        @if($categories->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="text-gray-500">Belum ada kategori</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="block bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $category->products_count }} produk
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
