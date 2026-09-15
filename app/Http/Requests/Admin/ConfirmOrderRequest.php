<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_cost' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_cost.required' => 'Ongkos kirim wajib diisi.',
            'shipping_cost.numeric' => 'Ongkos kirim harus berupa angka.',
            'shipping_cost.min' => 'Ongkos kirim minimal 0.',
            'note.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
