<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Keputusan verifikasi wajib dipilih.',
            'status.in' => 'Keputusan verifikasi hanya boleh Approve atau Reject.',
            'admin_note.max' => 'Catatan admin maksimal 1000 karakter.',
        ];
    }
}
