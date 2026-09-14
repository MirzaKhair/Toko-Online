@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-6">
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <span class="mx-2 text-gray-300">/</span>
                <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600 transition">Produk</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-gray-900">Edit</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    @component('components.form.select', [
                        'label' => 'Kategori',
                        'name' => 'category_id',
                        'required' => true,
                        'options' => $categories->pluck('name', 'id')->toArray(),
                        'selected' => $product->category_id,
                    ])
                    @endcomponent

                    @component('components.form.input', [
                        'label' => 'Nama Produk',
                        'name' => 'name',
                        'required' => true,
                        'value' => $product->name,
                    ])
                    @endcomponent

                    @component('components.form.textarea', [
                        'label' => 'Deskripsi',
                        'name' => 'description',
                        'rows' => 4,
                    ])
                        {{ old('description', $product->description) }}
                    @endcomponent

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @component('components.form.input', [
                            'label' => 'Harga (Rp)',
                            'name' => 'price',
                            'type' => 'number',
                            'required' => true,
                            'value' => $product->price,
                        ])
                        @endcomponent

                        @component('components.form.input', [
                            'label' => 'Stok',
                            'name' => 'stock',
                            'type' => 'number',
                            'required' => true,
                            'value' => $product->stock,
                        ])
                        @endcomponent
                    </div>

                    {{-- Gambar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Produk</label>

                        @if($product->image)
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-1.5">Gambar saat ini:</p>
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="h-24 w-24 object-cover rounded-xl border border-gray-200">
                            </div>
                        @endif

                        <input type="file"
                               id="image"
                               name="image"
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               class="w-full text-sm text-gray-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF, Webp. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.</p>

                        <div id="image-preview" class="mt-3 hidden">
                            <p class="text-xs text-gray-500 mb-1.5">Preview gambar baru:</p>
                            <img id="preview-img" src="#" alt="Preview" class="h-32 w-32 object-cover rounded-xl border border-gray-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @component('components.form.checkbox', [
                            'label' => 'Aktif',
                            'name' => 'is_active',
                            'value' => '1',
                            'checked' => old('is_active', $product->is_active),
                        ])
                        @endcomponent

                        @component('components.form.checkbox', [
                            'label' => 'Produk Unggulan',
                            'name' => 'is_featured',
                            'value' => '1',
                            'checked' => old('is_featured', $product->is_featured),
                        ])
                        @endcomponent
                    </div>
                </div>

                <div class="flex items-center space-x-3 mt-6 pt-6 border-t border-gray-100">
                    <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                        Update
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                       class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('image-preview').classList.add('hidden');
            }
        });
    </script>
@endsection
