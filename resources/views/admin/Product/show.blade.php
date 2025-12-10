@extends('admin.layout.app')

@section('content')

<div style="margin-bottom: 24px;">
    <a href="{{ route('product.index') }}" style="display:inline-flex; align-items:center; gap:8px; color:#252C45; text-decoration:none; font-weight:500;">
        ← Kembali ke Produk
    </a>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">

    <!-- LEFT COLUMN: PRODUCT IMAGE -->
    <div>
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04); overflow:hidden;">
            @if ($product->featured_image)
                <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}"
                    style="width:100%; height:auto; border-radius:8px; display:block;">
            @else
                <div style="width:100%; height:300px; background:#f5f7fa; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#999; font-size:14px;">
                    Tidak ada foto
                </div>
            @endif
        </div>
    </div>

    <!-- RIGHT COLUMN: PRODUCT INFO -->
    <div>
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04); margin-bottom:20px;">
            <h1 style="color:#252C45; font-size:26px; margin:0 0 8px 0; font-weight:700;">{{ $product->name }}</h1>
            <p style="color:#666; font-size:13px; margin:0 0 16px 0;">
                <strong>Slug:</strong> <code style="background:#f5f7fa; padding:4px 8px; border-radius:4px; font-family:'Courier New';">{{ $product->slug }}</code>
            </p>

            <!-- Status -->
            <div style="margin-bottom:16px;">
                <span style="display:inline-block; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;
                    {{ $product->is_active ? 'background:#d4f5e4; color:#166534;' : 'background:#fef2e8; color:#92400e;' }}">
                    {{ $product->is_active ? '✓ Aktif' : '⊘ Tidak Aktif' }}
                </span>
            </div>

            <!-- Price -->
            <div style="margin-bottom:12px;">
                <p style="color:#666; font-size:13px; margin:0 0 4px 0;"><strong>Harga:</strong></p>
                <p style="color:#252C45; font-size:24px; font-weight:700; margin:0;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>

            <!-- Categories -->
            <div style="margin-top:20px; padding-top:20px; border-top:1px solid #e0e0e0;">
                <p style="color:#666; font-size:13px; margin:0 0 10px 0;"><strong>Kategori:</strong></p>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    @forelse ($product->categories as $cat)
                        <span style="display:inline-block; padding:6px 12px; background:#e8f3ff; color:#1e40af; border-radius:20px; font-size:12px;">
                            {{ $cat->name }}
                        </span>
                    @empty
                        <p style="color:#999; font-size:13px;">Tidak ada kategori</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Description -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04); margin-bottom:20px;">
            <h3 style="color:#252C45; font-size:16px; margin:0 0 12px 0; font-weight:700;">Deskripsi</h3>
            <p style="color:#555; font-size:14px; line-height:1.6; white-space:pre-wrap; margin:0;">
                {{ $product->description ?: 'Tidak ada deskripsi' }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div style="display:flex; gap:10px;">
            <a href="{{ route('product.edit', $product->slug) }}"
                style="flex:1; display:flex; align-items:center; justify-content:center; padding:12px 16px; background:#252C45; color:white; text-decoration:none; border-radius:8px; font-weight:600; font-size:14px; transition:all 0.18s;">
                ✏️ Edit
            </a>
            <form method="POST" action="{{ route('product.destroy', $product->slug) }}" style="flex:1; margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                    style="width:100%; padding:12px 16px; background:#dc2626; color:white; border:none; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; transition:all 0.18s;">
                    🗑️ Hapus
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
