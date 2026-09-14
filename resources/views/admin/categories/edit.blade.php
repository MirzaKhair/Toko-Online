@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <div class="max-w-2xl">
        <div class="mb-6">
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <span class="mx-2 text-gray-300">/</span>
                <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600 transition">Kategori</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-gray-900">Edit</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    @component('components.form.input', [
                        'label' => 'Nama Kategori',
                        'name' => 'name',
                        'required' => true,
                        'value' => $category->name,
                    ])
                    @endcomponent

                    @component('components.form.textarea', [
                        'label' => 'Deskripsi',
                        'name' => 'description',
                        'rows' => 3,
                    ])
                        {{ old('description', $category->description) }}
                    @endcomponent

                    @component('components.form.checkbox', [
                        'label' => 'Aktif',
                        'name' => 'is_active',
                        'value' => '1',
                        'checked' => old('is_active', $category->is_active),
                    ])
                    @endcomponent
                </div>

                <div class="flex items-center space-x-3 mt-6 pt-6 border-t border-gray-100">
                    <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                        Update
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                       class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
