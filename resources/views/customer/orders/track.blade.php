@extends('layouts.customer')

@section('title', 'Lacak Pesanan')

@section('content')
    <div class="max-w-lg mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Lacak Pesanan</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Masukkan nomor pesanan dan nomor telepon yang digunakan saat checkout.
                </p>
            </div>

                <form action="{{ route('tracking.search') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        {{-- Nomor Pesanan --}}
                        <div>
                            <label for="order_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Pesanan</label>
                            <input type="text" name="order_number" id="order_number"
                                   value="{{ old('order_number') }}"
                                   placeholder="Contoh: ORD-20260914-001"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('order_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                   value="{{ old('customer_phone') }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Cari --}}
                        <button type="submit"
                                class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
                            Cari Pesanan
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">
                        &larr; Kembali ke Beranda
                    </a>
                </div>
        </div>
    </div>
@endsection
