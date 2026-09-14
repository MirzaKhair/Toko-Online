@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Produk</h1>
            <p class="mt-1 text-sm text-gray-500">Total {{ $products->total() }} produk</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        @if($products->isEmpty())
            @component('components.empty-state', ['title' => 'Belum ada produk', 'description' => 'Produk akan muncul di sini setelah ditambahkan.', 'icon' => 'box'])
                <a href="{{ route('admin.products.create') }}" class="mt-2 inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    Tambah Produk
                </a>
            @endcomponent
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Gambar</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5 text-sm text-gray-500">{{ $products->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3.5">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="h-10 w-10 rounded-lg object-cover border border-gray-100">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="text-sm font-semibold text-gray-900">{{ $product->name }}</p>
                                    @if($product->has_variants)
                                        <p class="text-[11px] text-gray-400 mt-0.5">Memiliki varian</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-sm text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-sm font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3.5 text-sm text-gray-700">{{ $product->stock }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-wrap gap-1">
                                        @if($product->is_active)
                                            @component('components.badge', ['type' => 'success', 'size' => 'xs'])Aktif@endcomponent
                                        @else
                                            @component('components.badge', ['type' => 'default', 'size' => 'xs'])Nonaktif@endcomponent
                                        @endif
                                        @if($product->is_featured)
                                            @component('components.badge', ['type' => 'warning', 'size' => 'xs'])Unggulan@endcomponent
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ route('admin.variants.index', $product->id) }}"
                                           class="p-1.5 text-gray-500 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition" title="Varian">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                           class="p-1.5 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
