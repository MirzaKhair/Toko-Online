@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Form Data Diri --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Data Diri --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center space-x-2">
                            <span class="w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                            <span>Data Diri</span>
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                @component('components.form.input', [
                                    'label' => 'Nama Lengkap',
                                    'name' => 'customer_name',
                                    'required' => true,
                                    'placeholder' => 'Masukkan nama lengkap',
                                ])
                                @endcomponent
                            </div>
                            <div>
                                @component('components.form.input', [
                                    'label' => 'Nomor Telepon',
                                    'name' => 'customer_phone',
                                    'required' => true,
                                    'placeholder' => '08xxxxxxxxxx',
                                ])
                                @endcomponent
                            </div>
                            <div>
                                @component('components.form.input', [
                                    'label' => 'Email (Opsional)',
                                    'name' => 'customer_email',
                                    'placeholder' => 'email@contoh.com',
                                ])
                                @endcomponent
                            </div>
                            <div class="md:col-span-2">
                                @component('components.form.textarea', [
                                    'label' => 'Alamat Pengiriman',
                                    'name' => 'customer_address',
                                    'rows' => 3,
                                    'required' => true,
                                    'placeholder' => 'Masukkan alamat lengkap pengiriman',
                                ])
                                @endcomponent
                            </div>
                            <div class="md:col-span-2">
                                @component('components.form.textarea', [
                                    'label' => 'Catatan (Opsional)',
                                    'name' => 'customer_note',
                                    'rows' => 2,
                                    'placeholder' => 'Contoh: Jam pengiriman, titip satpam, dll.',
                                ])
                                @endcomponent
                            </div>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center space-x-2">
                            <span class="w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                            <span>Metode Pembayaran</span>
                        </h2>

                        <div class="space-y-3">
                            <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition {{ old('payment_method') === 'cash' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="payment_method" value="cash"
                                       {{ old('payment_method') === 'cash' ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <div class="ml-3">
                                    <span class="font-semibold text-gray-900">Cash</span>
                                    <p class="text-sm text-gray-500 mt-0.5">Bayar secara tunai saat barang diterima</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition {{ old('payment_method') === 'qris' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="payment_method" value="qris"
                                       {{ old('payment_method') === 'qris' ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <div class="ml-3">
                                    <span class="font-semibold text-gray-900">QRIS</span>
                                    <p class="text-sm text-gray-500 mt-0.5">Bayar menggunakan QRIS</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- QRIS Section --}}
                        <div id="qris-section" class="hidden mt-4">
                            <div class="p-5 bg-gray-50 border border-gray-200 rounded-xl">
                                @if($storeSetting && $storeSetting->qris_image)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $storeSetting->qris_image) }}"
                                             alt="QRIS Toko"
                                             class="mx-auto max-w-[250px] max-h-[250px] object-contain rounded-lg">
                                        <p class="mt-4 text-xl font-bold text-blue-600">
                                            Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                        </p>
                                        <p class="mt-2 text-sm text-gray-600">
                                            Silakan scan QRIS dan bayar sesuai total pesanan.
                                        </p>
                                        <p class="mt-1 text-xs text-yellow-600 font-medium">
                                            Pastikan nominal pembayaran sesuai dengan total pesanan.
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <p class="text-sm text-yellow-600 font-medium">QRIS toko belum tersedia</p>
                                        <p class="text-xs text-gray-500 mt-1">Silakan pilih metode pembayaran lain atau hubungi admin.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit Mobile --}}
                    <div class="lg:hidden">
                        <button type="submit"
                                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl hover:bg-blue-700 transition">
                            Buat Pesanan
                        </button>
                    </div>
                </div>

                {{-- Ringkasan Belanja --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>

                        {{-- Daftar Item --}}
                        <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                            @foreach($items as $item)
                                <div class="flex items-start space-x-3">
                                    <div class="w-12 h-12 bg-gray-50 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain">
                                        @else
                                            <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product_name'] }}</p>
                                        @if($item['variant_name'])
                                            <p class="text-xs text-gray-500 truncate">{{ $item['variant_name'] }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 whitespace-nowrap">Rp {{ number_format($item['item_subtotal'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span class="text-gray-900">{{ $shippingCost > 0 ? 'Rp ' . number_format($shippingCost, 0, ',', '.') : 'Gratis' }}</span>
                            </div>
                            <div class="border-t border-gray-100 pt-2">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Submit Desktop --}}
                        <div class="hidden lg:block mt-6">
                            <button type="submit"
                                    class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl hover:bg-blue-700 transition">
                                Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleQrisSection() {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            const qrisSection = document.getElementById('qris-section');
            if (selectedMethod && selectedMethod.value === 'qris') {
                qrisSection.classList.remove('hidden');
            } else {
                qrisSection.classList.add('hidden');
            }
        }

        document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
            radio.addEventListener('change', toggleQrisSection);
        });

        toggleQrisSection();
    </script>
@endsection
