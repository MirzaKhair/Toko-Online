<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])
        ->where('is_active', true)
        ->get();

        return view('customer.categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->paginate(12);

        return view('customer.categories.show', compact('category', 'products'));
    }
}
