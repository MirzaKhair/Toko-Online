@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Pesanan</h1>
        <p class="mt-1 text-sm text-gray-500">Total {{ $orders->total() }} pesanan</p>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nomor pesanan atau nama..."
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>
            <div class="min-w-[150px]">
                <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <select name="payment_status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Semua Pembayaran</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="waiting_verification" {{ request('payment_status') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="rejected" {{ request('payment_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'payment_status']))
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        @if($orders->isEmpty())
            @component('components.empty-state', ['title' => 'Belum ada pesanan', 'description' => 'Pesanan dari customer akan muncul di sini.', 'icon' => 'order'])
            @endcomponent
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Nomor Pesanan</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-4 py-3 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status Bayar</th>
                            <th class="px-4 py-3 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                            <th class="px-4 py-3 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3.5 text-sm text-gray-700">{{ $order->customer_name }}</td>
                                <td class="px-4 py-3.5 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3.5 text-right text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3.5 text-center text-sm text-gray-700">{{ strtoupper($order->payment_method) }}</td>
                                <td class="px-4 py-3.5 text-center">
                                    @php
                                        $paymentColors = [
                                            'unpaid' => 'bg-red-100 text-red-800',
                                            'waiting_verification' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $paymentColor = $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800';
                                        $paymentLabels = [
                                            'unpaid' => 'Belum Dibayar',
                                            'waiting_verification' => 'Menunggu',
                                            'paid' => 'Dibayar',
                                            'rejected' => 'Ditolak',
                                        ];
                                        $paymentLabel = $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status);
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $paymentColor }}">
                                        {{ $paymentLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
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
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $statusColor }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="p-1.5 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition inline-flex" title="Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
