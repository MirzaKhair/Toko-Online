<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_status' => 'required|string|in:pending,confirmed,processing,shipped,completed,cancelled',
            'note' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'order_status.required' => 'Status pesanan wajib dipilih.',
            'order_status.in' => 'Status pesanan tidak valid.',
            'note.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
