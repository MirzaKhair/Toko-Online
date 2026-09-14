@extends('layouts.admin')

@section('title', 'Pengaturan Toko')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Toko</h1>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Informasi Toko --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Toko</h2>

                <div class="space-y-4">
                    {{-- Nama Toko --}}
                    <div>
                        <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko <span class="text-red-500">*</span></label>
                        <input type="text" name="store_name" id="store_name"
                               value="{{ old('store_name', $storeSetting->store_name) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('store_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone"
                               value="{{ old('phone', $storeSetting->phone) }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Toko</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Alamat lengkap toko...">{{ old('address', $storeSetting->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- QRIS --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Gambar QRIS</h2>

                {{-- Preview QRIS --}}
                @if($storeSetting->qris_image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">QRIS saat ini:</p>
                        <div class="border border-gray-200 rounded-lg p-4 flex justify-center">
                            <img src="{{ asset('storage/' . $storeSetting->qris_image) }}"
                                 alt="QRIS Toko"
                                 class="max-w-[250px] max-h-[250px] object-contain">
                        </div>
                    </div>
                @else
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-700">QRIS belum diunggah. Silakan unggah gambar QRIS di bawah ini.</p>
                    </div>
                @endif

                {{-- Upload QRIS --}}
                <div>
                    <label for="qris_image" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $storeSetting->qris_image ? 'Ganti Gambar QRIS' : 'Unggah Gambar QRIS' }}
                    </label>
                    <input type="file" name="qris_image" id="qris_image"
                           accept="image/jpeg,image/png,image/webp"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.</p>
                    @error('qris_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info --}}
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-700">
                        Gambar QRIS akan ditampilkan kepada customer saat mereka memilih metode pembayaran QRIS di halaman checkout.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="mt-6">
            <button type="submit"
                    class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Simpan Pengaturan
            </button>
        </div>
    </form>
@endsection
