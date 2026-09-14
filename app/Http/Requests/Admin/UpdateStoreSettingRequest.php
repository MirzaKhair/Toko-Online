<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'store_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];

        if ($this->hasFile('qris_image')) {
            $rules['qris_image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'Nama toko wajib diisi.',
            'store_name.max' => 'Nama toko maksimal 255 karakter.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'qris_image.image' => 'File harus berupa gambar.',
            'qris_image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'qris_image.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
