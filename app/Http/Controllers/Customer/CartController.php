<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddToCartRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $key => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $items[] = [
                'key' => $key,
                ...$item,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        return view('customer.cart.index', compact('items', 'total'));
    }

    public function store(AddToCartRequest $request)
    {
        $productId = $request->input('product_id');
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity');

        $product = Product::where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan atau tidak aktif.');
        }

        if ($product->has_variants) {
            if (!$variantId) {
                return redirect()->back()->with('error', 'Silakan pilih varian produk.');
            }

            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $productId)
                ->where('is_active', true)
                ->first();

            if (!$variant) {
                return redirect()->back()->with('error', 'Varian tidak ditemukan atau tidak aktif.');
            }

            $availableStock = $variant->stock;
            $price = $variant->price;
            $sku = $variant->sku;
            $variantName = $variant->optionValues
                ->map(fn($ov) => $ov->option->name . ': ' . $ov->value)
                ->implode(', ');
            $image = $variant->image ?: $product->image;
            $cartKey = $productId . '_' . $variantId;
        } else {
            $variantId = null;
            $availableStock = $product->stock;
            $price = $product->price;
            $sku = null;
            $variantName = null;
            $image = $product->image;
            $cartKey = (string) $productId;
        }

        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQuantity > $availableStock) {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia. Stok tersisa: ' . $availableStock . '.');
            }
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            if ($quantity > $availableStock) {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia. Stok tersisa: ' . $availableStock . '.');
            }
            $cart[$cartKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'product_name' => $product->name,
                'variant_name' => $variantName,
                'sku' => $sku,
                'price' => (float) $price,
                'quantity' => $quantity,
                'image' => $image,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan di keranjang.');
        }

        $item = $cart[$key];
        $quantity = $request->input('quantity');

        if ($item['variant_id']) {
            $variant = ProductVariant::where('id', $item['variant_id'])
                ->where('is_active', true)
                ->first();

            if (!$variant) {
                unset($cart[$key]);
                session(['cart' => $cart]);
                return redirect()->route('cart.index')->with('error', 'Varian produk tidak lagi tersedia. Item dihapus dari keranjang.');
            }

            $availableStock = $variant->stock;
        } else {
            $product = Product::where('id', $item['product_id'])
                ->where('is_active', true)
                ->first();

            if (!$product) {
                unset($cart[$key]);
                session(['cart' => $cart]);
                return redirect()->route('cart.index')->with('error', 'Produk tidak lagi tersedia. Item dihapus dari keranjang.');
            }

            $availableStock = $product->stock;
        }

        if ($quantity > $availableStock) {
            return redirect()->route('cart.index')->with('error', 'Jumlah melebihi stok yang tersedia. Stok tersisa: ' . $availableStock . '.');
        }

        $cart[$key]['quantity'] = $quantity;
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Jumlah item berhasil diperbarui.');
    }

    public function destroy(string $key)
    {
        $cart = session('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan di keranjang.');
        }

        unset($cart[$key]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
