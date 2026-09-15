<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function form()
    {
        return view('customer.orders.track');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        $order = Order::where('order_number', $validated['order_number'])
            ->where('customer_phone', $validated['customer_phone'])
            ->first();

        if (!$order) {
            return redirect()->route('tracking.form')
                ->with('error', 'Pesanan tidak ditemukan. Pastikan nomor pesanan dan nomor telepon sudah benar.');
        }

        session(["tracking_access.{$order->order_number}" => true]);

        return redirect()->route('tracking.detail', $order->order_number);
    }

    public function detail(string $order_number)
    {
        if (!session("tracking_access.$order_number")) {
            return redirect()->route('tracking.form')
                ->with('error', 'Anda belum melakukan pencarian untuk pesanan ini. Silakan cari pesanan terlebih dahulu.');
        }

        $order = Order::with('items', 'statusHistories', 'latestPaymentProof')
            ->where('order_number', $order_number)
            ->first();

        if (!$order) {
            return redirect()->route('tracking.form')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        $maskedPhone = $this->maskPhone($order->customer_phone);

        $storeSetting = StoreSetting::getSingleton();

        return view('customer.orders.detail', compact('order', 'maskedPhone', 'storeSetting'));
    }

    private function maskPhone(string $phone): string
    {
        $length = strlen($phone);
        if ($length <= 8) {
            return $phone;
        }
        return substr($phone, 0, 8) . str_repeat('X', $length - 8);
    }
}
