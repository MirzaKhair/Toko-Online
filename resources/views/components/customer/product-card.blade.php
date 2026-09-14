@props(['product'])

<a href="{{ route('products.show', $product->slug) }}" class="block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
    {{-- Gambar Produk --}}
    <div class="h-48 bg-gray-100 flex items-center justify-center">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}"
                 alt="{{ $product->name }}"
                 class="h-full w-full object-cover">
        @else
            <div class="flex flex-col items-center text-gray-400">
                <svg class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs">Tidak ada gambar</span>
            </div>
        @endif
    </div>

    {{-- Info Produk --}}
    <div class="p-4">
        {{-- Kategori --}}
        @if($product->category)
            <span class="text-xs text-blue-600 font-medium">{{ $product->category->name }}</span>
        @endif

        {{-- Nama Produk --}}
        <h3 class="mt-1 text-sm font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>

        {{-- Harga --}}
        <p class="mt-2 text-lg font-bold text-blue-600">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>

        {{-- Stok --}}
        <p class="mt-1 text-xs text-gray-500">
            @if($product->stock > 0)
                Stok: {{ $product->stock }}
            @else
                <span class="text-red-500">Habis</span>
            @endif
        </p>
    </div>
</a>
