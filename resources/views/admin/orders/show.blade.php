@extends('layouts.admin')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Daftar Pesanan</a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Info Pesanan --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Pesanan {{ $order->order_number }}</h2>
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
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Tanggal Pesanan</p>
                        <p class="text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Nomor Pesanan</p>
                        <p class="text-gray-900 font-medium">{{ $order->order_number }}</p>
                    </div>
                </div>
            </div>

            {{-- Data Customer --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Data Customer</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Telepon</p>
                        <p class="text-gray-900">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-gray-500">Alamat</p>
                        <p class="text-gray-900">{{ $order->customer_address }}</p>
                    </div>
                    @if($order->customer_note)
                        <div class="sm:col-span-2">
                            <p class="text-gray-500">Catatan</p>
                            <p class="text-gray-900">{{ $order->customer_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Item Pesanan --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Item Pesanan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr>
                                <th class="text-left py-2 font-medium text-gray-600">Produk</th>
                                <th class="text-left py-2 font-medium text-gray-600">Varian</th>
                                <th class="text-right py-2 font-medium text-gray-600">Harga</th>
                                <th class="text-center py-2 font-medium text-gray-600">Qty</th>
                                <th class="text-right py-2 font-medium text-gray-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 text-gray-900">{{ $item->product_name }}</td>
                                    <td class="py-3 text-gray-700">{{ $item->variant_name ?? '-' }}</td>
                                    <td class="py-3 text-right text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-3 text-center text-gray-700">{{ $item->quantity }}</td>
                                    <td class="py-3 text-right font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Riwayat Status --}}
            @if($order->statusHistories->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Status</h2>
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
                                    <p class="text-xs text-gray-400 mt-1">oleh: {{ $history->changed_by }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Ringkasan Biaya --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Biaya</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-2">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pembayaran --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Metode</p>
                        <p class="text-gray-900 font-medium">{{ strtoupper($order->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        @php
                            $paymentColors = [
                                'unpaid' => 'bg-red-100 text-red-800',
                                'waiting_verification' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $paymentColor = $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColor }}">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Ubah Ongkir --}}
            @php
                $finalStatuses = ['completed', 'cancelled'];
                $canEditShipping = !in_array($order->order_status, $finalStatuses);
            @endphp
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ubah Ongkos Kirim</h2>
                @if($canEditShipping)
                    <form action="{{ route('admin.orders.updateShippingCost', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-1">Ongkos Kirim (Rp)</label>
                            <input type="number" name="shipping_cost" id="shipping_cost"
                                   value="{{ $order->shipping_cost }}"
                                   min="0" step="100"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('shipping_cost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                            Simpan Ongkir
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">Ongkos kirim tidak dapat diubah untuk pesanan {{ strtolower($order->order_status) }}.</p>
                @endif
            </div>

            {{-- Ubah Status --}}
            @if($canEditShipping)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ubah Status Pesanan</h2>
                    @php
                        $validTransitions = [
                            'pending'    => ['confirmed', 'cancelled'],
                            'confirmed'  => ['processing', 'cancelled'],
                            'processing' => ['shipped', 'cancelled'],
                            'shipped'    => ['completed'],
                        ];
                        $nextStatuses = $validTransitions[$order->order_status] ?? [];
                    @endphp
                    @if(count($nextStatuses) > 0)
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="order_status" class="block text-sm font-medium text-gray-700 mb-1">Status Baru</label>
                                <select name="order_status" id="order_status"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($nextStatuses as $status)
                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('order_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                <textarea name="note" id="note" rows="2"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Alasan perubahan status...">{{ old('note') }}</textarea>
                                @error('note')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" onclick="return confirm('Yakin ingin mengubah status pesanan?')"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                                Update Status
                            </button>
                        </form>
                    @else
                        <p class="text-sm text-gray-500">Pesanan sudah mencapai status final dan tidak dapat diubah.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
