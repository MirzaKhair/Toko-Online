<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyPaymentProofRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentProofController extends Controller
{
    public function verify(VerifyPaymentProofRequest $request, Order $order)
    {
        $validated = $request->validated();

        $result = DB::transaction(function () use ($order, $validated) {
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            $latestProof = $order->latestPaymentProof;

            if (!$latestProof) {
                return back()->with('error', 'Tidak ada bukti pembayaran yang perlu diverifikasi.');
            }

            if ($latestProof->status !== 'pending') {
                return back()->with('error', 'Bukti pembayaran ini sudah diverifikasi sebelumnya.');
            }

            $latestProof->update([
                'status' => $validated['status'],
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'admin_note' => $validated['admin_note'] ?? null,
            ]);

            $newPaymentStatus = $validated['status'] === 'approved' ? 'paid' : 'rejected';

            $order->update(['payment_status' => $newPaymentStatus]);

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Verifikasi bukti pembayaran berhasil. Status pembayaran: ' . ucfirst($newPaymentStatus) . '.');
        });

        return $result;
    }
}
