<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_note' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,qris',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_name.max' => 'Nama lengkap maksimal 255 karakter.',
            'customer_phone.required' => 'Nomor telepon wajib diisi.',
            'customer_phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'customer_address.required' => 'Alamat pengiriman wajib diisi.',
            'customer_note.max' => 'Catatan maksimal 1000 karakter.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran hanya boleh Cash atau QRIS.',
        ];
    }
}
