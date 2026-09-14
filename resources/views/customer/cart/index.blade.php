@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

        @if(count($items) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Daftar Item --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                        <div class="bg-white rounded-xl border border-gray-100 p-4 md:p-5">
                            <div class="flex items-start space-x-4">
                                {{-- Gambar --}}
                                <div class="w-20 h-20 md:w-24 md:h-24 bg-gray-50 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain">
                                    @else
                                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item['product_name'] }}</p>
                                            @if($item['variant_name'])
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $item['variant_name'] }}</p>
                                            @endif
                                            @if($item['sku'])
                                                <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $item['sku'] }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('cart.destroy', $item['key']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition" title="Hapus item">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex items-center justify-between mt-3">
                                        {{-- Harga --}}
                                        <p class="text-sm font-semibold text-blue-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>

                                        {{-- Jumlah --}}
                                        <div class="flex items-center space-x-1">
                                            <form action="{{ route('cart.update', $item['key']) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition text-sm font-bold">
                                                    -
                                                </button>
                                            </form>
                                            <span class="w-10 text-center text-sm font-semibold text-gray-900">{{ $item['quantity'] }}</span>
                                            <form action="{{ route('cart.update', $item['key']) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition text-sm font-bold">
                                                    +
                                                </button>
                                            </form>
                                        </div>

                                        {{-- Subtotal --}}
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Tombol Kosongkan --}}
                    <div class="flex justify-end">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin mengosongkan keranjang?')"
                                    class="inline-flex items-center text-sm text-gray-500 hover:text-red-500 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Item</span>
                                <span class="text-gray-900 font-medium">{{ collect($items)->sum('quantity') }} produk</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('checkout.index') }}"
                           class="block w-full mt-6 bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition text-center">
                            Lanjut Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            @component('components.empty-state', ['title' => 'Keranjang kosong', 'description' => 'Mulai belanja dan tambahkan produk ke keranjang.', 'icon' => 'cart'])
                <a href="{{ route('home') }}"
                   class="mt-2 inline-flex items-center bg-blue-600 text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Mulai Belanja
                </a>
            @endcomponent
        @endif
    </div>
@endsection
