@extends('Content.layout.app')

@section('title', $product->name . ' - EPPG Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-20">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">← Kembali ke Produk</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="flex items-start justify-center">
            <div class="w-full max-w-md">
                @if($product->featured_image)
                    <img src="{{ asset('storage/' . $product->featured_image) }}"
                         alt="{{ $product->name }}"
                         class="w-full rounded-lg shadow-lg object-cover">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 mt-4">Tidak ada foto</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="flex flex-col">
            <!-- Categories -->
            @if($product->categories->count() > 0)
                <div class="mb-4 flex gap-2 flex-wrap">
                    @foreach($product->categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                           class="text-xs text-blue-600 font-medium bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Product Name -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

            <!-- Status -->
            <div class="mb-4">
                @if($product->is_active)
                    <span class="inline-block text-green-600 text-sm font-medium bg-green-100 px-4 py-1 rounded-full">
                        ✓ Produk Aktif
                    </span>
                @else
                    <span class="inline-block text-gray-600 text-sm font-medium bg-gray-100 px-4 py-1 rounded-full">
                        Produk Nonaktif
                    </span>
                @endif
            </div>

            <!-- Price -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <p class="text-gray-600 text-sm mb-2">Harga</p>
                <p class="text-5xl font-bold text-blue-600">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>

            <!-- Description -->
            @if($product->description)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Deskripsi Produk</h3>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                        {{ $product->description }}
                    </div>
                </div>
            @endif

            <!-- Product Meta -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">SKU / Slug</p>
                        <p class="text-gray-900 font-medium">{{ $product->slug }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Tanggal Dibuat</p>
                        <p class="text-gray-900 font-medium">{{ $product->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex    ">
                <button class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    🛒 Pesan Sekarang
                </button>
            </div>

            <!-- Share -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-gray-600 text-sm mb-4">Bagikan Produk</p>
                <div class="flex gap-3">
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                        f
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 bg-blue-400 text-white rounded-full hover:bg-blue-500 transition">
                        𝕏
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                        W
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($product->categories->count() > 0)
        <div class="mt-16 pt-12 border-t border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Produk Terkait</h2>

            @php
                $relatedProducts = \App\Models\Product::where('is_active', true)
                    ->whereHas('categories', function($q) {
                        $q->whereIn('category_id', auth()->check() ? [] : $product->categories->pluck('id')->toArray());
                    })
                    ->where('id', '!=', $product->id)
                    ->limit(4)
                    ->get();
            @endphp

            @if($relatedProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <a href="{{ route('products.show', $related->slug) }}">
                                @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}"
                                         alt="{{ $related->name }}"
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                <a href="{{ route('products.show', $related->slug) }}" class="block text-lg font-semibold text-gray-800 hover:text-blue-600">
                                    {{ $related->name }}
                                </a>
                                <p class="text-blue-600 font-bold mt-2">
                                    Rp {{ number_format($related->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Tidak ada produk terkait</p>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
