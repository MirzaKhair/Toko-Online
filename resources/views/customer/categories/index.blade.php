@extends('layouts.customer')

@section('title', 'Kategori')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Semua Kategori</h1>
            <p class="mt-2 text-gray-500">Temukan produk sesuai kebutuhan Anda</p>
        </div>

        @if($categories->isEmpty())
            @component('components.empty-state', ['title' => 'Belum ada kategori', 'description' => 'Kategori akan muncul di sini setelah ditambahkan.', 'icon' => 'folder'])
            @endcomponent
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="group bg-white rounded-xl border border-gray-100 p-6 text-center hover:shadow-md hover:border-blue-200 transition-all duration-200">
                        <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                            <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }} produk</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
