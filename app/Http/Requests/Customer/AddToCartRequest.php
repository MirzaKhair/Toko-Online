<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => ' Produk wajib dipilih.',
            'product_id.exists' => ' Produk tidak ditemukan.',
            'variant_id.exists' => ' Varian tidak ditemukan.',
            'quantity.required' => ' Jumlah wajib diisi.',
            'quantity.integer' => ' Jumlah harus berupa angka bulat.',
            'quantity.min' => ' Jumlah minimal 1.',
        ];
    }
}
