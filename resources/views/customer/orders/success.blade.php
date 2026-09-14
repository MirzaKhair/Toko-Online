@extends('layouts.customer')

@section('title', 'Pesanan Berhasil')

@section('content')
    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            {{-- Icon Centang --}}
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h1>
            <p class="text-gray-600 mb-6">Terima kasih atas pesanan Anda. Berikut detail pesanan Anda:</p>

            {{-- Detail Pesanan --}}
            <div class="bg-gray-50 rounded-lg p-6 text-left mb-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Pesanan</span>
                        <span class="font-semibold text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama</span>
                        <span class="text-gray-900">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Telepon</span>
                        <span class="text-gray-900">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="text-gray-900">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status Pesanan</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Item yang Dipesan --}}
            <div class="text-left mb-6">
                <h2 class="text-sm font-semibold text-gray-900 mb-3">Item yang Dipesan:</h2>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <span class="text-gray-900">{{ $item->product_name }}</span>
                                @if($item->variant_name)
                                    <span class="text-gray-500"> ({{ $item->variant_name }})</span>
                                @endif
                                <span class="text-gray-500"> x{{ $item->quantity }}</span>
                            </div>
                            <span class="text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('home') }}"
                   class="flex-1 inline-block bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition text-center">
                    Kembali Belanja
                </a>
                <button type="button" disabled
                        class="flex-1 inline-block bg-gray-200 text-gray-500 font-semibold py-3 rounded-lg cursor-not-allowed text-center">
                    Lacak Pesanan (Segera Hadir)
                </button>
            </div>
        </div>
    </div>
@endsection
