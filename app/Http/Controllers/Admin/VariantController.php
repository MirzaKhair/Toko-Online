<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOptionRequest;
use App\Http\Requests\Admin\StoreVariantRequest;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;

class VariantController extends Controller
{
    public function index($productId)
    {
        $product = Product::with(['options.values', 'variants.optionValues'])->findOrFail($productId);

        return view('admin.variants.index', compact('product'));
    }

    public function storeOption(StoreOptionRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $product->options()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.variants.index', $productId)
            ->with('success', 'Opsi berhasil ditambahkan.');
    }

    public function destroyOption($productId, $optionId)
    {
        $option = ProductOption::where('product_id', $productId)->findOrFail($optionId);
        $option->delete();

        return redirect()->route('admin.variants.index', $productId)
            ->with('success', 'Opsi berhasil dihapus.');
    }

    public function storeValue(StoreOptionRequest $request, $productId, $optionId)
    {
        $option = ProductOption::where('product_id', $productId)->findOrFail($optionId);

        $option->values()->create([
            'value' => $request->name,
        ]);

        return redirect()->route('admin.variants.index', $productId)
            ->with('success', 'Nilai opsi berhasil ditambahkan.');
    }

    public function destroyValue($productId, $valueId)
    {
        $value = ProductOptionValue::whereHas('option', fn($q) => $q->where('product_id', $productId))
            ->findOrFail($valueId);
        $value->delete();

        return redirect()->route('admin.variants.index', $productId)
            ->with('success', 'Nilai opsi berhasil dihapus.');
    }

    public function store(StoreVariantRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // Cek kombinasi duplikat
        if ($this->isDuplicateCombination($productId, $request->option_value_ids)) {
            return back()->with('error', 'Kombinasi varian ini sudah ada.');
        }

        // Cek SKU duplikat
        if ($request->sku) {
            $skuExists = ProductVariant::where('sku', $request->sku)
                ->where('product_id', $productId)
                ->exists();
            if ($skuExists) {
                return back()->with('error', 'SKU sudah digunakan oleh varian lain.');
            }
        }

        $variant = $product->variants()->create([
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $variant->optionValues()->sync($request->option_value_ids);

        // Update has_variants jika belum aktif
        if (!$product->has_variants) {
            $product->update(['has_variants' => true]);
        }

        return redirect()->route('admin.variants.index', $productId)
            ->with('success', 'Varian berhasil ditambahkan.');
    }

    public function update(StoreVariantRequest $request, $productId, $variantId)
    {
        $variant = ProductVariant::where('product_id', $productId)->findOrFail($variantId);

        // Cek kombinasi duplikat (kecuali varian ini sendiri)
        if ($this->isDuplicateCombination($productId, $request->option_value_ids, $variantId)) {
            return back()->with('error', 'Kombinasi varian ini sudah ada.');
        }

        // Cek SKU duplikat
        if ($request->sku) {
            $skuExists = ProductVariant::where('sku', $request->sku)
                ->where('product_id', $productId)
                ->where('id', '!=', $variantId)
                ->exists();
            if ($skuExists) {
                return back()->with('error', 'SKU sudah digunakan oleh varian lain.');
            }
        }

        $variant->update([
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $variant->optionValues()->sync($request->option_value_ids);

        return redirect()->route('admin.variants.index', $productId)
            ->with('success', 'Varian berhasil diperbarui.');
    }

    public function destroy($productId, $variantId)
    {
        $variant = ProductVariant::where('product_id', $productId)->findOrFail($variantId);
        $variant->delete();

        // Cek apakah masih ada varian lain
        $hasVariants = ProductVariant::where('product_id', $productId)->exists();
        if (!$hasVariants) {
            Product::where('id', $productId)->update(['has_variants' => false]);
        }

        return redirect()->route('admin.variants.index', $productId)
            ->with('success', 'Varian berhasil dihapus.');
    }

    private function isDuplicateCombination(int $productId, array $valueIds, ?int $ignoreVariantId = null): bool
    {
        $variants = ProductVariant::where('product_id', $productId)
            ->when($ignoreVariantId, fn($q) => $q->where('id', '!=', $ignoreVariantId))
            ->get();

        foreach ($variants as $variant) {
            $variantValueIds = $variant->optionValues->pluck('id')->sort()->values()->toArray();
            $sortedInput = collect($valueIds)->sort()->values()->toArray();

            if ($variantValueIds === $sortedInput) {
                return true;
            }
        }

        return false;
    }
}
