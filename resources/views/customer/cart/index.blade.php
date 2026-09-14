@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

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
    </div>
@endsection
