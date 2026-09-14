@props(['product'])

@php
    $minPrice = $product->price;
    $maxPrice = $product->price;

    if ($product->has_variants && $product->variants->isNotEmpty()) {
        $activeVariants = $product->variants->where('is_active', true);
        if ($activeVariants->isNotEmpty()) {
            $minPrice = $activeVariants->min('price');
            $maxPrice = $activeVariants->max('price');
        }
    }
@endphp

<a href="{{ route('products.show', $product->slug) }}"
   class="group bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg hover:border-blue-100 transition-all duration-200 flex flex-col">
    <div class="aspect-square bg-gray-50 relative overflow-hidden">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        @endif

        @if($product->is_featured)
            <div class="absolute top-2 left-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-yellow-400 text-xs font-semibold text-yellow-900 shadow-sm">
                    Unggulan
                </span>
            </div>
        @endif
    </div>

    <div class="p-3 flex-1 flex flex-col">
        @if($product->category)
            <span class="text-[11px] font-medium text-blue-600 uppercase tracking-wider">{{ $product->category->name }}</span>
        @endif

        <h3 class="text-sm font-semibold text-gray-900 mt-1 line-clamp-2 group-hover:text-blue-600 transition">
            {{ $product->name }}
        </h3>

        <div class="mt-auto pt-2">
            @if($product->has_variants && $minPrice != $maxPrice)
                <p class="text-sm font-bold text-blue-600">
                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                    <span class="text-xs font-normal text-gray-400">- Rp {{ number_format($maxPrice, 0, ',', '.') }}</span>
                </p>
            @else
                <p class="text-sm font-bold text-blue-600">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
            @endif

            @php
                $totalStock = $product->has_variants
                    ? $product->variants->where('is_active', true)->sum('stock')
                    : $product->stock;
            @endphp
            @if($totalStock > 0)
                <p class="text-[11px] text-green-600 mt-0.5">Stok tersedia</p>
            @else
                <p class="text-[11px] text-red-500 mt-0.5">Stok habis</p>
            @endif
        </div>
    </div>
</a>
