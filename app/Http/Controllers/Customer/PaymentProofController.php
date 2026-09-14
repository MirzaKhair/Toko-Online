<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UploadPaymentProofRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    public function store(UploadPaymentProofRequest $request, Order $order)
    {
        if (!session("tracking_access.{$order->order_number}")) {
            return redirect()->route('tracking.form')
                ->with('error', 'Silakan lacak pesanan menggunakan nomor pesanan dan nomor telepon terlebih dahulu.');
        }

        if ($order->payment_method !== 'qris') {
            return back()->with('error', 'Hanya pesanan dengan metode pembayaran QRIS yang dapat mengunggah bukti pembayaran.');
        }

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Pembayaran pesanan ini sudah dikonfirmasi. Tidak dapat mengunggah bukti lagi.');
        }

        $file = $request->file('proof');
        $filename = $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();

        DB::transaction(function () use ($order, $file, $filename) {
            $filePath = $file->storeAs('payment-proofs', $filename, 'public');

            $order->paymentProofs()->create([
                'file_path' => $filePath,
                'status' => 'pending',
            ]);

            $order->update(['payment_status' => 'waiting_verification']);
        });

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi dari admin.');
    }
}
