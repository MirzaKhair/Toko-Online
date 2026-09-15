<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmOrderRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Requests\Admin\UpdateShippingCostRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items', 'statusHistories', 'latestPaymentProof', 'latestPaymentProof.verifier');

        return view('admin.orders.show', compact('order'));
    }

    public function confirmOrder(ConfirmOrderRequest $request, Order $order)
    {
        $shippingCost = $request->input('shipping_cost');
        $note = $request->input('note');

        $result = DB::transaction(function () use ($order, $shippingCost, $note) {
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            if ($order->order_status !== 'pending') {
                return back()->with('error', 'Hanya pesanan dengan status "Menunggu" yang dapat dikonfirmasi.');
            }

            if ($order->shipping_finalized_at !== null) {
                return back()->with('error', 'Pesanan ini sudah dikonfirmasi ongkir sebelumnya.');
            }

            $totalAmount = $order->subtotal + $shippingCost;

            $order->update([
                'shipping_cost' => $shippingCost,
                'shipping_finalized_at' => now(),
                'total_amount' => $totalAmount,
                'order_status' => 'confirmed',
            ]);

            $historyNote = 'Pesanan dikonfirmasi. Ongkir: Rp ' . number_format($shippingCost, 0, ',', '.') . '. Total: Rp ' . number_format($totalAmount, 0, ',', '.');
            if ($note) {
                $historyNote .= '. Catatan: ' . $note;
            }

            $order->statusHistories()->create([
                'status' => 'confirmed',
                'note' => $historyNote,
                'changed_by' => Auth::user()->name,
            ]);

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dikonfirmasi. Total pembayaran: Rp ' . number_format($totalAmount, 0, ',', '.'));
        });

        return $result;
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $newStatus = $request->input('order_status');
        $note = $request->input('note');

        $result = DB::transaction(function () use ($order, $newStatus, $note) {
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            $currentStatus = $order->order_status;

            if ($currentStatus === $newStatus) {
                return back()->with('error', 'Status pesanan sudah sama dengan status yang dipilih.');
            }

            $finalStatuses = ['completed', 'cancelled'];
            if (in_array($currentStatus, $finalStatuses)) {
                return back()->with('error', 'Pesanan dengan status "' . ucfirst($currentStatus) . '" tidak dapat diubah lagi.');
            }

            if ($currentStatus === 'pending' && $newStatus === 'confirmed') {
                return back()->with('error', 'Gunakan form "Konfirmasi Pesanan" untuk mengonfirmasi pesanan sekaligus menentukan ongkir.');
            }

            $validTransitions = [
                'confirmed'  => ['processing', 'cancelled'],
                'processing' => ['shipped', 'cancelled'],
                'shipped'    => ['completed'],
            ];

            if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
                return back()->with('error', 'Transisi dari "' . ucfirst($currentStatus) . '" ke "' . ucfirst($newStatus) . '" tidak diperbolehkan.');
            }

            $order->update(['order_status' => $newStatus]);

            $order->statusHistories()->create([
                'status' => $newStatus,
                'note' => $note,
                'changed_by' => Auth::user()->name,
            ]);

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Status pesanan berhasil diubah menjadi "' . ucfirst($newStatus) . '".');
        });

        return $result;
    }

    public function updateShippingCost(UpdateShippingCostRequest $request, Order $order)
    {
        $shippingCost = $request->input('shipping_cost');

        $result = DB::transaction(function () use ($order, $shippingCost) {
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            $finalStatuses = ['completed', 'cancelled'];
            if (in_array($order->order_status, $finalStatuses)) {
                return back()->with('error', 'Ongkos kirim tidak dapat diubah untuk pesanan dengan status "' . ucfirst($order->order_status) . '".');
            }

            if ($order->shipping_finalized_at !== null) {
                return back()->with('error', 'Ongkos kirim sudah final dan tidak dapat diubah.');
            }

            $totalAmount = $order->subtotal + $shippingCost;

            $order->update([
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
            ]);

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Ongkos kirim berhasil diperbarui. Total baru: Rp ' . number_format($totalAmount, 0, ',', '.'));
        });

        return $result;
    }
}
