@extends('admin.layout.app')

@section('content')

<div style="margin-bottom: 24px;">
    <a href="{{ route('post.index') }}" style="display:inline-flex; align-items:center; gap:8px; color:#252C45; text-decoration:none; font-weight:500;">
        ← Kembali ke Artikel
    </a>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px;">

    <!-- LEFT COLUMN: CONTENT -->
    <div>
        <div style="background:#fff; border-radius:12px; padding:30px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">

            <!-- Title -->
            <h1 style="color:#252C45; font-size:28px; margin:0 0 16px 0; font-weight:700; line-height:1.3;">
                {{ $post->title }}
            </h1>

            <!-- Meta Info -->
            <div style="display:flex; gap:16px; align-items:center; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid #f0f0f0; flex-wrap:wrap;">
                <span style="color:#666; font-size:14px;">
                    ✍️ {{ $post->author?->name ?? 'Unknown' }}
                </span>
                <span style="color:#999; font-size:14px;">
                    📅 {{ $post->created_at->format('d M Y H:i') }}
                </span>
                <span style="display:inline-block; background:#{{ $post->status === 'published' ? '4FD1C5' : 'ffc107' }}; color:white; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                    {{ ucfirst($post->status) }}
                </span>
            </div>

            <!-- Featured Image -->
            @if($post->featured_image)
                <div style="margin-bottom:24px; border-radius:8px; overflow:hidden; background:#f5f7fa;">
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                        style="width:100%; height:auto; display:block;">
                </div>
            @endif

            <!-- Excerpt -->
            @if($post->excerpt)
                <p style="color:#555; font-size:16px; line-height:1.6; margin:0 0 20px 0; font-style:italic; padding:16px; background:#f9f9f9; border-left:4px solid #4FD1C5; border-radius:4px;">
                    {{ $post->excerpt }}
                </p>
            @endif

            <!-- Content -->
            <div style="color:#333; font-size:15px; line-height:1.8; margin-bottom:24px;">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- Categories -->
            @if($post->categories->isNotEmpty())
                <div style="padding-top:24px; border-top:1px solid #f0f0f0;">
                    <h4 style="color:#252C45; font-size:14px; font-weight:600; margin:0 0 12px 0;">Kategori</h4>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        @foreach($post->categories as $cat)
                            <span style="display:inline-block; padding:6px 14px; background:#f0f4f8; color:#252C45; border-radius:20px; font-size:13px; font-weight:500;">
                                {{ $cat->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- RIGHT COLUMN: ACTIONS -->
    <div>
        <!-- Action Buttons -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04); margin-bottom:20px;">
            <h3 style="color:#252C45; font-size:14px; font-weight:700; margin:0 0 16px 0;">Aksi</h3>

            <div style="display:flex; flex-direction:column; gap:10px;">
                <a href="{{ route('post.edit', $post->slug) }}"
                    style="display:block; text-align:center; padding:12px 16px; background:#252C45; color:white; text-decoration:none; border-radius:8px; font-weight:600; font-size:14px; transition:all 0.2s;">
                    ✏️ Edit Artikel
                </a>

                <form method="POST" action="{{ route('post.destroy', $post->slug) }}" onsubmit="return confirm('Hapus artikel ini?');" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="width:100%; padding:12px 16px; background:#ff4444; color:white; border:none; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; transition:all 0.2s;">
                        🗑️ Hapus Artikel
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="color:#252C45; font-size:14px; font-weight:700; margin:0 0 16px 0;">Informasi</h3>

            <div style="display:flex; flex-direction:column; gap:12px;">
                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px;">ID Artikel</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:14px;">{{ $post->id }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px;">URL Slug</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:13px; font-family:'Courier New', monospace; word-break:break-all;">{{ $post->slug }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px;">Penulis</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:14px;">{{ $post->author?->name ?? 'Unknown' }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px;">Dibuat</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:14px;">{{ $post->created_at->format('d M Y H:i') }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px;">Diubah</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:14px;">{{ $post->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
