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

                {{-- Harga (tanpa varian) --}}
                @if(!$product->has_variants)
                    <p class="mt-4 text-3xl font-bold text-blue-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                @endif

                {{-- Deskripsi --}}
                @if($product->description)
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h2>
                        <p class="text-gray-600 whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                @endif

                {{-- Form Add to Cart --}}
                <form action="{{ route('cart.store') }}" method="POST" class="mt-6" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Pilihan Varian --}}
                    @if($product->has_variants && $product->variants->count() > 0)
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Pilih Varian</h2>
                            <div class="space-y-2" id="variant-options">
                                @foreach($product->variants->where('is_active', true) as $variant)
                                    @php
                                        $variantLabel = $variant->optionValues->map(fn($ov) => $ov->option->name . ': ' . $ov->value)->implode(', ');
                                        $isOutOfStock = $variant->stock <= 0;
                                    @endphp
                                    <label class="border rounded-lg p-3 flex items-center cursor-pointer transition {{ $isOutOfStock ? 'opacity-50 cursor-not-allowed bg-gray-50' : 'hover:border-blue-500' }}">
                                        <input type="radio"
                                               name="variant_id"
                                               value="{{ $variant->id }}"
                                               data-price="{{ $variant->price }}"
                                               data-stock="{{ $variant->stock }}"
                                               data-sku="{{ $variant->sku }}"
                                               data-label="{{ $variantLabel }}"
                                               {{ $isOutOfStock ? 'disabled' : '' }}
                                               class="variant-radio text-blue-600 focus:ring-blue-500"
                                               onchange="updateVariantDisplay(this)">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $variantLabel }}</span>
                                                    @if($variant->sku)
                                                        <span class="ml-2 text-xs text-gray-400">SKU: {{ $variant->sku }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-semibold text-blue-600">
                                                        Rp {{ number_format($variant->price, 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-xs {{ $variant->stock > 0 ? 'text-gray-500' : 'text-red-500' }}">
                                                        {{ $variant->stock > 0 ? 'Stok: ' . $variant->stock : 'Stok Habis' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('variant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info Varian Terpilih --}}
                        <div id="selected-variant-info" class="hidden mb-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-sm text-gray-600">Varian dipilih: <span id="selected-variant-label" class="font-medium text-gray-900"></span></p>
                                <p class="text-lg font-bold text-blue-600 mt-1">Rp <span id="selected-variant-price"></span></p>
                                <p class="text-sm text-gray-500">Stok tersedia: <span id="selected-variant-stock"></span></p>
                            </div>
                        </div>
                    @else
                        {{-- Stok Produk Tanpa Varian --}}
                        <div class="mb-4">
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
                    @endif

                    {{-- Jumlah --}}
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="changeQuantity(-1)"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-lg font-bold">
                                -
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                   class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   onchange="validateQuantity()">
                            <button type="button" onclick="changeQuantity(1)"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-lg font-bold">
                                +
                            </button>
                        </div>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Add to Cart --}}
                    @if($product->has_variants)
                        <button type="submit" id="add-to-cart-btn" disabled
                                class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Pilih Varian Terlebih Dahulu
                        </button>
                    @else
                        @if($product->stock > 0)
                            <button type="submit"
                                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
                                Tambah ke Keranjang
                            </button>
                        @else
                            <button type="button" disabled
                                    class="w-full bg-gray-400 text-white font-semibold py-3 rounded-lg cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    @endif
                </form>

                {{-- Tombol Kembali --}}
                <div class="mt-4">
                    <a href="javascript:history.back()"
                       class="inline-block bg-gray-200 text-gray-700 font-semibold px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                        &larr; Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($product->has_variants && $product->variants->count() > 0)
    <script>
        let maxStock = 0;

        function updateVariantDisplay(radio) {
            const price = parseInt(radio.dataset.price);
            const stock = parseInt(radio.dataset.stock);
            const label = radio.dataset.label;

            maxStock = stock;

            document.getElementById('selected-variant-label').textContent = label;
            document.getElementById('selected-variant-price').textContent = price.toLocaleString('id-ID');
            document.getElementById('selected-variant-stock').textContent = stock;

            document.getElementById('selected-variant-info').classList.remove('hidden');

            const qtyInput = document.getElementById('quantity');
            qtyInput.max = stock;
            if (parseInt(qtyInput.value) > stock) {
                qtyInput.value = stock;
            }
            if (stock <= 0) {
                qtyInput.value = 1;
            }

            const btn = document.getElementById('add-to-cart-btn');
            if (stock > 0) {
                btn.disabled = false;
                btn.textContent = 'Tambah ke Keranjang';
                btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                btn.disabled = true;
                btn.textContent = 'Stok Habis';
                btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                btn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        }

        function changeQuantity(delta) {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value) + delta;
            if (value < 1) value = 1;
            if (maxStock > 0 && value > maxStock) value = maxStock;
            input.value = value;
        }

        function validateQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (isNaN(value) || value < 1) value = 1;
            if (maxStock > 0 && value > maxStock) value = maxStock;
            input.value = value;
        }
    </script>
    @endif
@endsection
