@extends('layouts.customer')

@section('title', $product->name)

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-8 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span class="mx-2">/</span>
            @if($product->category)
                <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-blue-600">
                    {{ $product->category->name }}
                </a>
                <span class="mx-2">/</span>
            @endif
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Gambar Produk --}}
            <div class="bg-gray-100 rounded-lg flex items-center justify-center h-96">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="h-full w-full object-contain rounded-lg">
                @else
                    <div class="flex flex-col items-center text-gray-400">
                        <svg class="h-24 w-24 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            {{-- Info Produk --}}
            <div>
                {{-- Kategori --}}
                @if($product->category)
                    <a href="{{ route('categories.show', $product->category->slug) }}"
                       class="text-sm text-blue-600 hover:underline">
                        {{ $product->category->name }}
                    </a>
                @endif

                {{-- Nama Produk --}}
                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                {{-- Harga --}}
                <p class="mt-4 text-3xl font-bold text-blue-600">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                {{-- Stok --}}
                <div class="mt-4">
                    @if($product->stock > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Stok: {{ $product->stock }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Stok Habis
                        </span>
                    @endif
                </div>

                {{-- Deskripsi --}}
                @if($product->description)
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h2>
                        <p class="text-gray-600 whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                @endif

                {{-- Info Varian --}}
                @if($product->has_variants && $product->variants->count() > 0)
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Varian Tersedia</h2>
                        <div class="space-y-2">
                            @foreach($product->variants->where('is_active', true) as $variant)
                                <div class="border rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($variant->optionValues->count() > 0)
                                                <span class="text-sm text-gray-600">
                                                    {{ $variant->optionValues->map(fn($ov) => $ov->option->name . ': ' . $ov->value)->implode(', ') }}
                                                </span>
                                            @endif
                                            @if($variant->sku)
                                                <span class="ml-2 text-xs text-gray-400">SKU: {{ $variant->sku }}</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-blue-600">
                                                Rp {{ number_format($variant->price, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Stok: {{ $variant->stock > 0 ? $variant->stock : 'Habis' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tombol Kembali --}}
                <div class="mt-8">
                    <a href="javascript:history.back()"
                       class="inline-block bg-gray-200 text-gray-700 font-semibold px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                        &larr; Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
