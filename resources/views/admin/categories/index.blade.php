@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Kategori</h1>
            <p class="mt-1 text-sm text-gray-500">Total {{ $categories->count() }} kategori</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        @if($categories->isEmpty())
            @component('components.empty-state', ['title' => 'Belum ada kategori', 'description' => 'Kategori akan muncul di sini setelah ditambahkan.', 'icon' => 'folder'])
                <a href="{{ route('admin.categories.create') }}" class="mt-2 inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    Tambah Kategori
                </a>
            @endcomponent
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3.5 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-3.5 text-sm font-semibold text-gray-900">{{ $category->name }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-700">{{ $category->products_count }} produk</td>
                                <td class="px-6 py-3.5">
                                @if($category->is_active)
                                    <x-badge type="success">Aktif</x-badge>
                                @else
                                    <x-badge type="default">Nonaktif</x-badge>
                                @endif
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                           class="p-1.5 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
        @endif
    </div>
@endsection
