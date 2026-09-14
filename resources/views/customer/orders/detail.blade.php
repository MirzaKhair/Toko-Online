@extends('layouts.customer')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
            <p class="text-gray-600">Nomor Pesanan: <span class="font-semibold">{{ $order->order_number }}</span></p>
        </div>

        {{-- Status Pesanan --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Status Pesanan</h2>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'processing' => 'bg-indigo-100 text-indigo-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $statusColor = $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>
        </div>

        {{-- Info Pelanggan --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelanggan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p class="text-gray-900">{{ $maskedPhone }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="text-gray-900">{{ $order->customer_address }}</p>
                </div>
                @if($order->customer_note)
                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-600">Catatan</p>
                        <p class="text-gray-900">{{ $order->customer_note }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Daftar Item --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Item yang Dipesan</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-start justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                            @if($item->variant_name)
                                <p class="text-sm text-gray-500">{{ $item->variant_name }}</p>
                            @endif
                            <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ringkasan Biaya --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Biaya</h2>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Ongkos Kirim</span>
                    <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-200 pt-2">
                    <div class="flex justify-between">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Pembayaran --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Metode Pembayaran</p>
                    <p class="text-gray-900 font-medium">{{ strtoupper($order->payment_method) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status Pembayaran</p>
                    @php
                        $paymentColors = [
                            'unpaid' => 'bg-red-100 text-red-800',
                            'waiting_verification' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-gray-100 text-gray-800',
                        ];
                        $paymentColor = $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColor }}">
                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Bukti Pembayaran --}}
        @if($order->payment_method === 'qris')
            @php
                $latestProof = $order->latestPaymentProof;
            @endphp
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Bukti Pembayaran</h2>

                @if($order->payment_status === 'paid')
                    <div class="flex items-center space-x-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <p class="font-medium text-green-800">Pembayaran telah dikonfirmasi.</p>
                            @if($latestProof && $latestProof->verified_at)
                                <p class="text-sm text-green-600">Diverifikasi pada {{ $latestProof->verified_at->format('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>
                @elseif($order->payment_status === 'waiting_verification' && $latestProof && $latestProof->status === 'pending')
                    <div class="flex items-center space-x-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-yellow-800">Bukti pembayaran sedang diverifikasi oleh admin.</p>
                            <p class="text-sm text-yellow-600">Silakan tunggu konfirmasi dari admin.</p>
                        </div>
                    </div>
                    @if($latestProof->file_path)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Bukti yang diunggah:</p>
                            <img src="{{ asset('storage/' . $latestProof->file_path) }}"
                                 alt="Bukti pembayaran"
                                 class="rounded-lg border border-gray-200 max-w-[300px]">
                        </div>
                    @endif
                @elseif($order->payment_status === 'rejected' && $latestProof && $latestProof->status === 'rejected')
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg mb-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <div>
                                <p class="font-medium text-red-800">Bukti pembayaran ditolak.</p>
                                @if($latestProof->admin_note)
                                    <p class="text-sm text-red-600 mt-1">Catatan: {{ $latestProof->admin_note }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Silakan unggah bukti pembayaran baru:</p>
                    <form action="{{ route('payment-proof.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.webp" required
                                   class="flex-1 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                Unggah Bukti Baru
                            </button>
                        </div>
                        @error('proof')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                @else
                    @if($latestProof)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600">Bukti terakhir: <span class="font-medium">{{ ucfirst($latestProof->status) }}</span></p>
                        </div>
                    @endif
                    <p class="text-sm text-gray-600 mb-3">Unggah bukti pembayaran QRIS Anda:</p>
                    <form action="{{ route('payment-proof.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.webp" required
                                   class="flex-1 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                Unggah Bukti
                            </button>
                        </div>
                        @error('proof')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                @endif
            </div>
        @endif

        {{-- Riwayat Status --}}
        @if($order->statusHistories->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Status</h2>
                <div class="space-y-4">
                    @foreach($order->statusHistories->sortByDesc('created_at') as $history)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-600"></div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900">{{ ucfirst($history->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                @if($history->note)
                                    <p class="text-sm text-gray-600 mt-1">{{ $history->note }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('home') }}"
               class="flex-1 inline-block bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition text-center">
                Kembali ke Beranda
            </a>
            <a href="{{ route('tracking.form') }}"
               class="flex-1 inline-block bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-300 transition text-center">
                Lacak Pesanan Lain
            </a>
        </div>
    </div>
@endsection
