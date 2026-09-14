<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use App\Services\OrderNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $itemSubtotal = $item['price'] * $item['quantity'];
            $items[] = [
                'key' => $key,
                ...$item,
                'item_subtotal' => $itemSubtotal,
            ];
            $subtotal += $itemSubtotal;
        }

        $shippingCost = 0;
        $totalAmount = $subtotal + $shippingCost;

        $storeSetting = StoreSetting::getSingleton();

        return view('customer.checkout.index', compact('items', 'subtotal', 'shippingCost', 'totalAmount', 'storeSetting'));
    }

    public function store(CheckoutRequest $request)
    {
        $validated = $request->validated();
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $order = DB::transaction(function () use ($cart, $validated) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $key => $item) {
                $product = Product::where('id', $item['product_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    abort(422, 'Produk "' . $item['product_name'] . '" tidak ditemukan atau tidak aktif.');
                }

                if ($product->has_variants) {
                    if (!$item['variant_id']) {
                        abort(422, 'Produk "' . $product->name . '" memerlukan pemilihan varian.');
                    }

                    $variant = ProductVariant::where('id', $item['variant_id'])
                        ->where('product_id', $product->id)
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->first();

                    if (!$variant) {
                        abort(422, 'Varian untuk produk "' . $product->name . '" tidak ditemukan atau tidak aktif.');
                    }

                    $availableStock = $variant->stock;
                    $price = $variant->price;
                    $variantName = $variant->optionValues
                        ->map(fn($ov) => $ov->option->name . ': ' . $ov->value)
                        ->implode(', ');
                    $productVariantId = $variant->id;
                } else {
                    $availableStock = $product->stock;
                    $price = $product->price;
                    $variantName = null;
                    $productVariantId = null;
                }

                $quantity = $item['quantity'];
                if ($quantity < 1) {
                    abort(422, 'Jumlah item minimal 1.');
                }
                if ($quantity > $availableStock) {
                    abort(422, 'Stok tidak cukup untuk produk "' . $product->name . '"' . ($variantName ? ' (' . $variantName . ')' : '') . '. Stok tersisa: ' . $availableStock . '.');
                }

                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $productVariantId,
                    'product_name' => $product->name,
                    'variant_name' => $variantName,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $shippingCost = 0;
            $totalAmount = $subtotal + $shippingCost;

            $order = Order::create([
                'order_number' => OrderNumberService::generate(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'customer_note' => $validated['customer_note'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            foreach ($orderItems as $item) {
                if ($item['product_variant_id']) {
                    ProductVariant::where('id', $item['product_variant_id'])
                        ->decrement('stock', $item['quantity']);
                } else {
                    Product::where('id', $item['product_id'])
                        ->decrement('stock', $item['quantity']);
                }
            }

            $order->statusHistories()->create([
                'status' => 'pending',
                'note' => 'Pesanan berhasil dibuat.',
                'changed_by' => 'customer',
            ]);

            session()->forget('cart');

            return $order;
        });

        session(["order_access.{$order->order_number}" => true]);

        return redirect()->route('orders.success', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    public function success(string $order_number)
    {
        if (!session("order_access.{$order_number}")) {
            return redirect()->route('tracking.form')
                ->with('error', 'Silakan lacak pesanan Anda menggunakan nomor pesanan dan nomor telepon.');
        }

        $order = Order::where('order_number', $order_number)
            ->with('items')
            ->first();

        if (!$order) {
            abort(404);
        }

        return view('customer.orders.success', compact('order'));
    }
}
