@extends('Content.layout.app')

@section('title', 'Produk - EPPG Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-30">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Semua Produk</h1>
        <p class="text-gray-600 mt-2">Temukan produk yang Anda butuhkan</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
            <form action="{{ route('products.index') }}" method="GET" class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Filter</h3>

                <!-- Search -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama produk..."
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Harga</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                               placeholder="Min"
                               class="w-1/2 px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                               placeholder="Max"
                               class="w-1/2 px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <!-- Sort -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Harga</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-[#006666] text-white py-2 rounded-lg hover:bg-[#004d4d] transition">
                    Terapkan Filter
                </button>

                @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                <a href="{{ route('products.index') }}" class="block text-center mt-2 text-gray-600 hover:text-gray-800">
                    Reset Filter
                </a>
                @endif
            </form>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->featured_image)
                        <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                    </a>
                    <div class="p-4">
                        @if($product->categories->count() > 0)
                        <div class="flex gap-2 flex-wrap mb-2">
                            @foreach($product->categories as $cat)
                            <span class="text-xs text-blue-600 font-medium bg-blue-50 px-2 py-1 rounded">{{ $cat->name }}</span>
                            @endforeach
                        </div>
                        @endif
                        <a href="{{ route('products.show', $product->slug) }}" class="block text-lg font-semibold text-gray-800 hover:text-blue-600 mt-1">{{ $product->name }}</a>
                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $product->description ?? 'No description' }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                <span class="text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            @if($product->is_active)
                            <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded">Aktif</span>
                            @else
                            <span class="text-gray-600 text-xs bg-gray-100 px-2 py-1 rounded">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada produk</h3>
                <p class="mt-2 text-gray-500">Coba ubah filter pencarian Anda</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
