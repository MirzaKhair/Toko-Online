<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $order->load('items', 'statusHistories');

        return view('admin.orders.show', compact('order'));
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

            $validTransitions = [
                'pending'    => ['confirmed', 'cancelled'],
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
