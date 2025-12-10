@extends('Content.layout.app')

@section('content')

<!-- Blog Header Section -->
<section class="pt-32 pb-20 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Blog & Insights</h1>
    <p class="text-xl text-slate-300 max-w-2xl mx-auto">Temukan tips, tren, dan insights terbaru dari tim kami</p>
  </div>
</section>

<!-- Search & Filter Section -->
<section class="py-12 bg-slate-50 border-b">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
      <!-- Search Box -->
      <div class="w-full md:w-96">
        <form method="GET" action="{{ route('blog.index') }}" class="relative">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari artikel..."
            class="w-full px-5 py-3 bg-white border-2 border-slate-200 rounded-lg focus:outline-none focus:border-teal-500 transition"
          >
          <button type="submit" class="absolute right-3 top-3 text-slate-400 hover:text-teal-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
        </form>
      </div>

      <!-- Sort -->
      <form method="GET" action="{{ route('blog.index') }}" class="flex gap-2 items-center">
        <label class="text-slate-700 font-medium">Urutkan:</label>
        <select name="sort" onchange="this.form.submit()" class="px-4 py-2 border-2 border-slate-200 rounded-lg focus:outline-none focus:border-teal-500">
          <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
          <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
          <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling Dibaca</option>
        </select>
      </form>
    </div>
  </div>
</section>

<!-- Blog Posts Grid -->
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if($posts->isEmpty())
      <div class="text-center py-20">
        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <p class="text-2xl text-slate-500 font-medium">Artikel tidak ditemukan</p>
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posts as $post)
          <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition transform hover:-translate-y-1 group">
            <!-- Featured Image -->
            @if($post->featured_image)
              <div class="relative h-48 overflow-hidden bg-slate-200">
                <img
                  src="{{ asset('storage/' . $post->featured_image) }}"
                  alt="{{ $post->title }}"
                  class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                >
                <!-- Status Badge -->
                <div class="absolute top-4 left-4">
                  <span class="inline-block bg-teal-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    Artikel
                  </span>
                </div>
              </div>
            @else
              <div class="relative h-48 bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center group-hover:scale-110 transition duration-300">
                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </div>
            @endif

            <!-- Content -->
            <div class="p-6">
              <!-- Meta Info -->
              <div class="flex items-center gap-3 text-sm text-slate-500 mb-3">
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                  </svg>
                  {{ $post->created_at->format('d M Y') }}
                </span>
                @if($post->author)
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $post->author->name }}
                  </span>
                @endif
              </div>

              <!-- Title -->
              <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-teal-600 transition">
                {{ $post->title }}
              </h3>

              <!-- Excerpt -->
              <p class="text-slate-600 text-sm mb-4 line-clamp-3">
                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}
              </p>

              <!-- Categories -->
              @if($post->categories->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-4">
                  @foreach($post->categories as $category)
                    <span class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded">
                      {{ $category->name }}
                    </span>
                  @endforeach
                </div>
              @endif

              <!-- Read More Button -->
              <a
                href="{{ route('blog.show', $post->slug) }}"
                class="inline-flex items-center gap-2 text-teal-600 font-semibold group/btn hover:text-teal-700 transition"
              >
                Baca Selengkapnya
                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </article>
        @endforeach
      </div>

      <!-- Pagination -->
      @if($posts->hasPages())
        <div class="mt-12 flex justify-center">
          {{ $posts->links('pagination::tailwind') }}
        </div>
      @endif
    @endif
  </div>
</section>

<!-- Newsletter Section -->
<section class="py-20 bg-gradient-to-r from-teal-600 to-teal-700">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl font-bold text-white mb-4">Berlangganan Newsletter</h2>
    <p class="text-teal-100 mb-8">Dapatkan artikel terbaru langsung ke email Anda</p>
    <form class="flex gap-2">
      <input
        type="email"
        placeholder="Email Anda..."
        class="flex-1 px-4 py-3 rounded-lg focus:outline-none"
      >
      <button type="submit" class="px-8 py-3 bg-white text-teal-600 font-bold rounded-lg hover:bg-slate-50 transition">
        Berlangganan
      </button>
    </form>
  </div>
</section>

@endsection
