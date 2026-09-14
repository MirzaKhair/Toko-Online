@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Form Data Diri --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Diri</h2>

                        <div class="space-y-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" id="customer_name"
                                       value="{{ old('customer_name') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor Telepon --}}
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_phone" id="customer_phone"
                                       value="{{ old('customer_phone') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                @error('customer_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat Pengiriman --}}
                            <div>
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman <span class="text-red-500">*</span></label>
                                <textarea name="customer_address" id="customer_address" rows="3"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          required>{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="customer_note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                <textarea name="customer_note" id="customer_note" rows="2"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Contoh: Jam pengiriman, titip satpam, dll.">{{ old('customer_note') }}</textarea>
                                @error('customer_note')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-blue-500 transition {{ old('payment_method') === 'cash' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                        <input type="radio" name="payment_method" value="cash"
                                               {{ old('payment_method') === 'cash' ? 'checked' : '' }}
                                               class="text-blue-600 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <span class="font-medium text-gray-900">Cash</span>
                                            <p class="text-sm text-gray-500">Bayar secara tunai saat barang diterima</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-blue-500 transition {{ old('payment_method') === 'qris' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                        <input type="radio" name="payment_method" value="qris"
                                               {{ old('payment_method') === 'qris' ? 'checked' : '' }}
                                               class="text-blue-600 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <span class="font-medium text-gray-900">QRIS</span>
                                            <p class="text-sm text-gray-500">Bayar menggunakan QRIS</p>
                                        </div>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- QRIS Section --}}
                            <div id="qris-section" class="hidden">
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    @if($storeSetting && $storeSetting->qris_image)
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $storeSetting->qris_image) }}"
                                                 alt="QRIS Toko"
                                                 class="mx-auto max-w-[250px] max-h-[250px] object-contain rounded-lg">
                                            <p class="mt-3 text-base font-semibold text-gray-900">
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
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            <p class="text-sm text-yellow-600 font-medium">
                                                QRIS toko belum tersedia.
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Silakan pilih metode pembayaran lain atau hubungi admin.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit Mobile --}}
                    <div class="mt-6 lg:hidden">
                        <button type="submit"
                                class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
                            Buat Pesanan
                        </button>
                    </div>
                </div>

                {{-- Ringkasan Belanja --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>

                        {{-- Daftar Item --}}
                        <div class="space-y-3 mb-4">
                            @foreach($items as $item)
                                <div class="flex items-start space-x-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain">
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product_name'] }}</p>
                                        @if($item['variant_name'])
                                            <p class="text-xs text-gray-500 truncate">{{ $item['variant_name'] }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format($item['item_subtotal'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="text-gray-900">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Submit Desktop --}}
                        <div class="hidden lg:block mt-6">
                            <button type="submit"
                                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
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
