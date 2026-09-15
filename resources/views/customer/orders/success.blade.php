@extends('layouts.customer')

@section('title', 'Pesanan Berhasil')

@section('content')
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
            {{-- Icon Centang --}}
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h1>
            <p class="text-gray-500 mb-4">Terima kasih atas pesanan Anda. Berikut detail pesanan Anda:</p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-8 text-left">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">Simpan atau screenshot halaman ini!</p>
                        <p class="text-sm text-yellow-700 mt-0.5">Nomor pesanan Anda adalah <span class="font-bold">{{ $order->order_number }}</span>. Simpan nomor ini untuk melacak pesanan Anda nanti.</p>
                    </div>
                </div>
            </div>

            {{-- Detail Pesanan --}}
            <div class="bg-gray-50 rounded-xl p-6 text-left mb-8">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Nomor Pesanan</span>
                        <span class="text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Nama</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Telepon</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Metode Pembayaran</span>
                        <span class="text-sm font-medium text-gray-900">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Subtotal</span>
                            <span class="text-sm text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Ongkos Kirim</span>
                            <span class="text-sm text-gray-500 italic">Menunggu konfirmasi admin</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Status Pesanan</span>
                        <x-badge type="warning">{{ ucfirst($order->order_status) }}</x-badge>
                    </div>
                </div>
            </div>

            {{-- Info QRIS --}}
            @if($order->payment_method === 'qris')
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8 text-left">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-800">Pembayaran QRIS</p>
                            <p class="text-sm text-blue-700 mt-0.5">Admin akan menentukan ongkir dan mengonfirmasi total pembayaran. QRIS akan tersedia pada halaman Lacak Pesanan setelah pesanan dikonfirmasi.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Item yang Dipesan --}}
            <div class="text-left mb-8">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Item yang Dipesan</h2>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-sm py-2 border-b border-gray-100 last:border-0 last:pb-0 first:pt-0">
                            <div>
                                <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                                @if($item->variant_name)
                                    <span class="text-gray-500"> ({{ $item->variant_name }})</span>
                                @endif
                                <span class="text-gray-400"> x{{ $item->quantity }}</span>
                            </div>
                            <span class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('tracking.form') }}"
                   class="flex-1 inline-flex items-center justify-center bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Lacak Pesanan
                </a>
                <a href="{{ route('home') }}"
                   class="flex-1 inline-flex items-center justify-center bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-200 transition">
                    Kembali Belanja
                </a>
            </div>
        </div>
    </div>
@endsection
