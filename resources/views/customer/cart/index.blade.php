@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

        @if(count($items) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Daftar Item --}}
                <div class="lg:col-span-2">
                    {{-- Desktop Table --}}
                    <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($items as $item)
                                    <tr class="hover:bg-gray-50">
                                        {{-- Produk --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                                    @if($item['image'])
                                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain">
                                                    @else
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $item['product_name'] }}</p>
                                                    @if($item['variant_name'])
                                                        <p class="text-sm text-gray-500">{{ $item['variant_name'] }}</p>
                                                    @endif
                                                    @if($item['sku'])
                                                        <p class="text-xs text-gray-400">SKU: {{ $item['sku'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        {{-- Harga --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-sm text-gray-900">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                        </td>
                                        {{-- Jumlah --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center space-x-2">
                                                <form action="{{ route('cart.update', $item['key']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">
                                                        -
                                                    </button>
                                                </form>
                                                <span class="w-10 text-center text-sm font-medium text-gray-900">{{ $item['quantity'] }}</span>
                                                <form action="{{ route('cart.update', $item['key']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">
                                                        +
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        {{-- Subtotal --}}
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                        </td>
                                        {{-- Hapus --}}
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('cart.destroy', $item['key']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus item">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="md:hidden space-y-4">
                        @foreach($items as $item)
                            <div class="bg-white rounded-lg shadow-md p-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain">
                                        @else
                                            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">{{ $item['product_name'] }}</p>
                                        @if($item['variant_name'])
                                            <p class="text-sm text-gray-500 truncate">{{ $item['variant_name'] }}</p>
                                        @endif
                                        @if($item['sku'])
                                            <p class="text-xs text-gray-400">SKU: {{ $item['sku'] }}</p>
                                        @endif
                                        <p class="text-sm text-blue-600 font-semibold mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <form action="{{ route('cart.destroy', $item['key']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus item">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('cart.update', $item['key']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">
                                                -
                                            </button>
                                        </form>
                                        <span class="w-10 text-center text-sm font-medium text-gray-900">{{ $item['quantity'] }}</span>
                                        <form action="{{ route('cart.update', $item['key']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Kosongkan --}}
                    <div class="mt-4 flex justify-end">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin mengosongkan keranjang?')"
                                    class="text-sm text-red-600 hover:text-red-800 transition">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Item</span>
                                <span class="text-gray-900">{{ collect($items)->sum('quantity') }} produk</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" disabled
                                class="w-full mt-6 bg-gray-400 text-white font-semibold py-3 rounded-lg cursor-not-allowed">
                            Lanjut Checkout
                        </button>
                        <p class="text-xs text-gray-400 text-center mt-2">Fitur checkout akan segera tersedia</p>
                    </div>
                </div>
            </div>
        @else
            {{-- Kosong --}}
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-lg">Keranjang Anda kosong</p>
                <p class="mt-2">Mulai belanja dan tambahkan produk ke keranjang</p>
                <a href="{{ route('home') }}"
                   class="inline-block mt-4 bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
@endsection
