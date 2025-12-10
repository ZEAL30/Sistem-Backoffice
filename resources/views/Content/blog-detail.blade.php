@extends('Content.layout.app')

@section('content')

<!-- Blog Detail Header -->
<section class="pt-32 pb-12 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
      @if($post->categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-4">
          @foreach($post->categories as $category)
            <a href="{{ route('blog.index') }}?category={{ $category->slug }}" class="text-teal-400 hover:text-teal-300 text-sm font-semibold uppercase tracking-wider">
              {{ $category->name }}
            </a>
          @endforeach
        </div>
      @endif
    </div>

    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">{{ $post->title }}</h1>

    <!-- Meta Info -->
    <div class="flex flex-wrap items-center gap-6 text-slate-300">
      @if($post->author)
        <div class="flex items-center gap-3">
          <img
            src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=0ea5e9&color=fff"
            alt="{{ $post->author->name }}"
            class="w-12 h-12 rounded-full"
          >
          <div>
            <p class="font-semibold text-white">{{ $post->author->name }}</p>
            <p class="text-sm text-slate-400">Author</p>
          </div>
        </div>
      @endif

      <div class="flex items-center gap-2 text-slate-400">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
        </svg>
        {{ $post->created_at->format('d F Y') }}
      </div>

      <div class="flex items-center gap-2 text-slate-400">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"></path>
        </svg>
        <span id="readTime">5</span> min read
      </div>
    </div>
  </div>
</section>

<!-- Featured Image -->
@if($post->featured_image)
  <section class="bg-slate-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <img
        src="{{ asset('storage/' . $post->featured_image) }}"
        alt="{{ $post->title }}"
        class="w-full rounded-lg shadow-lg"
      >
    </div>
  </section>
@endif

<!-- Blog Content -->
<section class="py-20 bg-white">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-3">
        <article class="prose prose-lg max-w-none">
          {!! $post->content !!}
        </article>

        <!-- Share Section -->
        <div class="mt-12 pt-8 border-t-2 border-slate-200">
          <h3 class="text-xl font-bold text-slate-900 mb-6">Bagikan Artikel Ini</h3>
          <div class="flex gap-4">
            <a
              href="https://www.facebook.com/sharer/sharer.php?u={{ route('blog.show', $post->slug) }}"
              target="_blank"
              class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
              </svg>
              Facebook
            </a>
            <a
              href="https://twitter.com/intent/tweet?url={{ route('blog.show', $post->slug) }}&text={{ urlencode($post->title) }}"
              target="_blank"
              class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.953 4.57a10 10 0 002.856-3.15 9.95 9.95 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path>
              </svg>
              Twitter
            </a>
            <a
              href="https://www.linkedin.com/sharing/share-offsite/?url={{ route('blog.show', $post->slug) }}"
              target="_blank"
              class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"></path>
              </svg>
              LinkedIn
            </a>
            <button
              onclick="copyToClipboard()"
              class="flex items-center gap-2 px-4 py-2 bg-slate-300 text-slate-700 rounded-lg hover:bg-slate-400 transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
              Copy Link
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1">
        <!-- Table of Contents -->
        <div class="sticky top-24 space-y-8">
          <!-- Categories -->
          @if($post->categories->isNotEmpty())
            <div class="bg-slate-50 p-6 rounded-lg">
              <h4 class="font-bold text-slate-900 mb-4">Kategori</h4>
              <div class="space-y-2">
                @foreach($post->categories as $category)
                  <a
                    href="{{ route('blog.index') }}?category={{ $category->slug }}"
                    class="block text-slate-600 hover:text-teal-600 transition font-medium"
                  >
                    {{ $category->name }}
                  </a>
                @endforeach
              </div>
            </div>
          @endif

          <!-- Related Posts -->
          @php
            $relatedPosts = \App\Models\Post::where('status', 'published')
              ->where('id', '!=', $post->id)
              ->latest()
              ->limit(3)
              ->get();
          @endphp

          @if($relatedPosts->isNotEmpty())
            <div class="bg-slate-50 p-6 rounded-lg">
              <h4 class="font-bold text-slate-900 mb-4">Artikel Lainnya</h4>
              <div class="space-y-4">
                @foreach($relatedPosts as $relPost)
                  <a
                    href="{{ route('blog.show', $relPost->slug) }}"
                    class="group block hover:bg-white p-3 rounded-lg transition"
                  >
                    <h5 class="text-sm font-semibold text-slate-900 group-hover:text-teal-600 line-clamp-2 transition">
                      {{ $relPost->title }}
                    </h5>
                    <p class="text-xs text-slate-500 mt-2">
                      {{ $relPost->created_at->format('d M Y') }}
                    </p>
                  </a>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Comments Section -->
<section class="py-20 bg-white border-t">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-slate-900 mb-12">💬 Komentar ({{ $post->comments()->where('is_approved', true)->count() }})</h2>

    <!-- Display Approved Comments -->
    <div class="space-y-8 mb-12">
      @php
        $approvedComments = $post->comments()->where('is_approved', true)->latest()->get();
      @endphp

      @if($approvedComments->isEmpty())
        <div class="text-center py-12">
          <p class="text-slate-500 text-lg">Belum ada komentar. Jadilah yang pertama!</p>
        </div>
      @else
        @foreach($approvedComments as $comment)
          <div class="flex gap-4 pb-8 border-b border-slate-200">
            <!-- Avatar -->
            <div class="flex-shrink-0">
              <img
                src="https://ui-avatars.com/api/?name={{ urlencode($comment->author_name) }}&background=14b8a6&color=fff"
                alt="{{ $comment->author_name }}"
                class="w-12 h-12 rounded-full"
              >
            </div>

            <!-- Comment Content -->
            <div class="flex-1">
              <div class="flex items-center justify-between mb-2">
                <h4 class="font-bold text-slate-900">{{ $comment->author_name }}</h4>
                <span class="text-sm text-slate-500">{{ $comment->created_at->format('d M Y H:i') }}</span>
              </div>
              <p class="text-slate-700 leading-relaxed">{{ $comment->content }}</p>
            </div>
          </div>
        @endforeach
      @endif
    </div>

    <!-- Add Comment Form -->
    <div class="bg-slate-50 p-8 rounded-lg border border-slate-200">
      <h3 class="text-2xl font-bold text-slate-900 mb-6">Tulis Komentar</h3>

      @if(session('success'))
        <div class="mb-6 p-4 bg-teal-50 border border-teal-200 rounded-lg text-teal-700">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="{{ route('comments.store', $post->id) }}" class="space-y-4">
        @csrf

        <div>
          <label for="author_name" class="block font-semibold text-slate-900 mb-2">Nama</label>
          <input
            type="text"
            id="author_name"
            name="author_name"
            value="{{ old('author_name') }}"
            placeholder="Masukkan nama Anda"
            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 transition"
            required
          >
          @error('author_name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="author_email" class="block font-semibold text-slate-900 mb-2">Email</label>
          <input
            type="email"
            id="author_email"
            name="author_email"
            value="{{ old('author_email') }}"
            placeholder="Masukkan email Anda"
            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 transition"
            required
          >
          @error('author_email')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="content" class="block font-semibold text-slate-900 mb-2">Komentar</label>
          <textarea
            id="content"
            name="content"
            placeholder="Tulis komentar Anda di sini..."
            rows="5"
            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 transition resize-none"
            required
          >{{ old('content') }}</textarea>
          @error('content')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
          <p class="text-sm text-slate-500 mt-2">Minimal 5 karakter, maksimal 1000 karakter</p>
        </div>

        <div>
          <button
            type="submit"
            class="px-8 py-3 bg-teal-600 text-white font-bold rounded-lg hover:bg-teal-700 transition transform hover:scale-105"
          >
            🚀 Kirim Komentar
          </button>
        </div>

        <p class="text-sm text-slate-600 mt-4">
          💡 Komentar Anda akan ditampilkan setelah disetujui oleh admin.
        </p>
      </form>
    </div>
  </div>
</section>

<!-- Navigation -->
<section class="py-12 bg-slate-50 border-t border-b">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Previous Post -->
      @php
        $prevPost = \App\Models\Post::where('status', 'published')
          ->where('created_at', '<', $post->created_at)
          ->latest()
          ->first();
      @endphp

      @if($prevPost)
        <a href="{{ route('blog.show', $prevPost->slug) }}" class="group hover:bg-white p-6 rounded-lg transition">
          <p class="text-sm text-slate-500 mb-2">← Artikel Sebelumnya</p>
          <h3 class="text-lg font-bold text-slate-900 group-hover:text-teal-600 transition line-clamp-2">
            {{ $prevPost->title }}
          </h3>
        </a>
      @else
        <div></div>
      @endif

      <!-- Next Post -->
      @php
        $nextPost = \App\Models\Post::where('status', 'published')
          ->where('created_at', '>', $post->created_at)
          ->oldest()
          ->first();
      @endphp

      @if($nextPost)
        <a href="{{ route('blog.show', $nextPost->slug) }}" class="group hover:bg-white p-6 rounded-lg transition text-right">
          <p class="text-sm text-slate-500 mb-2">Artikel Berikutnya →</p>
          <h3 class="text-lg font-bold text-slate-900 group-hover:text-teal-600 transition line-clamp-2">
            {{ $nextPost->title }}
          </h3>
        </a>
      @endif
    </div>
  </div>
</section>

<script>
  // Calculate read time
  function calculateReadTime() {
    const content = document.querySelector('article').textContent;
    const wordsPerMinute = 200;
    const wordCount = content.split(/\s+/).length;
    const readTime = Math.ceil(wordCount / wordsPerMinute);
    document.getElementById('readTime').textContent = readTime;
  }

  // Copy URL to clipboard
  function copyToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
      alert('✅ Link berhasil disalin!');
    }).catch(() => {
      alert('❌ Gagal menyalin link');
    });
  }

  // Initialize
  document.addEventListener('DOMContentLoaded', calculateReadTime);

  // Add smooth scroll behavior
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
</script>

@endsection
