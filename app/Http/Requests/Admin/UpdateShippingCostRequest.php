<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingCostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_cost' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_cost.required' => 'Ongkos kirim wajib diisi.',
            'shipping_cost.numeric' => 'Ongkos kirim harus berupa angka.',
            'shipping_cost.min' => 'Ongkos kirim minimal 0.',
        ];
    }
}
