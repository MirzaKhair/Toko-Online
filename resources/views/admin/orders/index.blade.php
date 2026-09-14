@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Pesanan</h1>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nomor pesanan atau nama..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="min-w-[150px]">
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                <select name="payment_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Pembayaran</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="waiting_verification" {{ request('payment_status') === 'waiting_verification' ? 'selected' : '' }}>Waiting Verification</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="rejected" {{ request('payment_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'payment_status']))
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    @if($orders->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Nomor Pesanan</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Customer</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">Total</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-600">Pembayaran</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-600">Status Bayar</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-600">Status Pesanan</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $order->customer_name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center text-gray-700">{{ strtoupper($order->payment_method) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $paymentColors = [
                                            'unpaid' => 'bg-red-100 text-red-800',
                                            'waiting_verification' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $paymentColor = $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $paymentColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
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
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500 text-lg">Belum ada pesanan</p>
            <p class="text-gray-400 text-sm mt-1">Pesanan dari customer akan muncul di sini.</p>
        </div>
    @endif
@endsection
