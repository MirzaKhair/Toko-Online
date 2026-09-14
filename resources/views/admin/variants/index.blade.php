@extends('layouts.admin')

@section('title', 'Kelola Varian - ' . $product->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Produk</a>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Varian</h1>
            <p class="text-gray-600">{{ $product->name }}</p>
        </div>
        <div class="flex items-center space-x-2">
            @if($product->has_variants)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    Menggunakan Varian
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    Tanpa Varian
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Bagian 1: Opsi Produk --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Opsi Produk</h2>

                {{-- Form Tambah Opsi --}}
                <form action="{{ route('admin.variants.storeOption', $product->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex space-x-2">
                        <input type="text"
                               name="name"
                               placeholder="Nama opsi (contoh: Warna)"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                               required>
                        <button type="submit"
                                class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                            Tambah
                        </button>
                    </div>
                </form>

                {{-- Daftar Opsi --}}
                @if($product->options->isEmpty())
                    <p class="text-gray-500 text-sm">Belum ada opsi.</p>
                @else
                    <div class="space-y-4">
                        @foreach($product->options as $option)
                            <div class="border rounded-lg p-3">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium text-gray-800">{{ $option->name }}</h3>
                                    <form action="{{ route('admin.variants.destroyOption', [$product->id, $option->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus opsi ini beserta semua nilainya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                {{-- Daftar Nilai --}}
                                @if($option->values->isEmpty())
                                    <p class="text-gray-400 text-xs">Belum ada nilai.</p>
                                @else
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($option->values as $value)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                                {{ $value->value }}
                                                <form action="{{ route('admin.variants.destroyValue', [$product->id, $value->id]) }}"
                                                      method="POST"
                                                      class="ml-1"
                                                      onsubmit="return confirm('Hapus nilai ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">&times;</button>
                                                </form>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Form Tambah Nilai --}}
                                <form action="{{ route('admin.variants.storeValue', [$product->id, $option->id]) }}" method="POST">
                                    @csrf
                                    <div class="flex space-x-1">
                                        <input type="text"
                                               name="name"
                                               placeholder="Nilai baru"
                                               class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500"
                                               required>
                                        <button type="submit"
                                                class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300">
                                            +
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Bagian 2: Daftar Varian --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar Varian</h2>

                @if($product->options->isEmpty())
                    <p class="text-gray-500 text-sm mb-4">Buat opsi terlebih dahulu sebelum menambahkan varian.</p>
                @else
                    {{-- Form Tambah Varian --}}
                    <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                        <h3 class="font-medium text-gray-700 mb-3">Tambah Varian Baru</h3>
                        <form action="{{ route('admin.variants.store', $product->id) }}" method="POST">
                            @csrf

                            {{-- Pilih Kombinasi Nilai Opsi --}}
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kombinasi Nilai Opsi</label>
                                @foreach($product->options as $option)
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500">{{ $option->name }}:</span>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($option->values as $value)
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox"
                                                           name="option_value_ids[]"
                                                           value="{{ $value->id }}"
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-sm">{{ $value->value }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                @error('option_value_ids')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                {{-- SKU --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                    <input type="text"
                                           name="sku"
                                           placeholder="Opsional"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('sku') border-red-500 @enderror">
                                    @error('sku')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Harga --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                                    <input type="number"
                                           name="price"
                                           value="{{ $product->price }}"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('price') border-red-500 @enderror"
                                           required>
                                    @error('price')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Stok --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                                    <input type="number"
                                           name="stock"
                                           value="0"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('stock') border-red-500 @enderror"
                                           required>
                                    @error('stock')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           checked
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                </label>
                                <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                    Tambah Varian
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Tabel Varian --}}
                @if($product->variants->isEmpty())
                    <p class="text-gray-500 text-sm">Belum ada varian.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kombinasi</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($product->variants as $variant)
                                    <tr class="hover:bg-gray-50" id="variant-{{ $variant->id }}">
                                        <td class="px-3 py-2 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($variant->optionValues as $value)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
                                                        {{ $value->option->name }}: {{ $value->value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-500">{{ $variant->sku ?? '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-500">Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-500">{{ $variant->stock }}</td>
                                        <td class="px-3 py-2">
                                            @if($variant->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-sm space-x-2">
                                            <button onclick="editVariant({{ $variant->id }})"
                                                    class="text-blue-600 hover:text-blue-800">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.variants.destroy', [$product->id, $variant->id]) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus varian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Edit Varian --}}
    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Varian</h3>
            <form id="edit-variant-form" method="POST">
                @csrf
                @method('PUT')

                {{-- Pilih Kombinasi Nilai Opsi --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kombinasi Nilai Opsi</label>
                    @foreach($product->options as $option)
                        <div class="mb-2">
                            <span class="text-xs text-gray-500">{{ $option->name }}:</span>
                            <div class="flex flex-wrap gap-2 mt-1" id="edit-options-{{ $option->id }}">
                                @foreach($option->values as $value)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox"
                                               name="option_value_ids[]"
                                               value="{{ $value->id }}"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 edit-option-value">
                                        <span class="ml-1 text-sm">{{ $value->value }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    {{-- SKU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text"
                               name="sku"
                               id="edit-sku"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number"
                               name="price"
                               id="edit-price"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                               required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    {{-- Stok --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number"
                               name="stock"
                               id="edit-stock"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                               required>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-end pb-2">
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   id="edit-is-active"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        @php
            $variantsJson = $product->variants->map(fn($v) => [
                'id' => $v->id,
                'sku' => $v->sku,
                'price' => $v->price,
                'stock' => $v->stock,
                'is_active' => $v->is_active,
                'option_value_ids' => $v->optionValues->pluck('id')->toArray(),
            ])->values()->toJson();
        @endphp
        const variants = {!! $variantsJson !!};

        function editVariant(id) {
            const variant = variants.find(v => v.id === id);
            if (!variant) return;

            document.getElementById('edit-variant-form').action = '{{ route('admin.variants.update', [$product->id, '__ID__']) }}'.replace('__ID__', id);
            document.getElementById('edit-sku').value = variant.sku || '';
            document.getElementById('edit-price').value = variant.price;
            document.getElementById('edit-stock').value = variant.stock;
            document.getElementById('edit-is-active').checked = variant.is_active;

            // Set option values
            document.querySelectorAll('.edit-option-value').forEach(checkbox => {
                checkbox.checked = variant.option_value_ids.includes(parseInt(checkbox.value));
            });

            const modal = document.getElementById('edit-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
