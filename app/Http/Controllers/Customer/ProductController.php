<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with(['category', 'variants.optionValues.option'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('customer.products.show', compact('product'));
    }
}
