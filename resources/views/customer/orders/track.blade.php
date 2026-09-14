@extends('layouts.customer')

@section('title', 'Lacak Pesanan')

@section('content')
    <div class="max-w-lg mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl border border-gray-100 p-8">
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Lacak Pesanan</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Masukkan nomor pesanan dan nomor telepon yang digunakan saat checkout.
                </p>
            </div>

            <form action="{{ route('tracking.search') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    @component('components.form.input', [
                        'label' => 'Nomor Pesanan',
                        'name' => 'order_number',
                        'required' => true,
                        'placeholder' => 'Contoh: ORD-20260914-A7K9X2',
                    ])
                    @endcomponent

                    @component('components.form.input', [
                        'label' => 'Nomor Telepon',
                        'name' => 'customer_phone',
                        'required' => true,
                        'placeholder' => '08xxxxxxxxxx',
                    ])
                    @endcomponent

                    <button type="submit"
                            class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">
                        Cari Pesanan
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
