<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        return view('customer.home.index', compact('featuredProducts'));
    }
}
