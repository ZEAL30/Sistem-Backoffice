@extends('admin.layout.app')

@section('content')
<div class="header">
    <div>
        <h1 style="margin: 0; font-size: 32px; color: #1f2d3d;">📊 Dashboard</h1>
        @php $user = Auth::user(); @endphp
        <p class="welcome-msg" style="margin: 6px 0 0 0;">Selamat datang kembali, <strong>{{ $user?->name ?? $user?->username ?? 'User' }}</strong>!</p>
    </div>
    <div style="text-align: right; font-size: 14px; color: #94a3b8;">
        <div style="font-size: 16px; font-weight: 600; color: #252C45;">{{ now()->format('d F Y') }}</div>
        <div style="margin-top: 4px;">{{ now()->format('H:i') }}</div>
    </div>
</div>

@php
    $user = $user ?? Auth::user();
    $userRole = $user?->role?->name ?? 'customer';
@endphp

<!-- CUSTOMER DASHBOARD -->
@if($userRole === 'customer')
    <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); padding: 40px; border-radius: 12px; border: 1px solid rgba(102, 126, 234, 0.2); text-align: center;">
        <h2 style="margin: 0 0 16px 0; color: #1f2d3d; font-size: 24px;">👋 Selamat Datang, Pelanggan!</h2>
        <p style="margin: 0; color: #6b7280; font-size: 16px;">Anda adalah pelanggan. Dashboard Anda sedang dalam pengembangan. Terima kasih telah menjadi bagian dari kami!</p>
    </div>

<!-- EDITOR DASHBOARD -->
@elseif($userRole === 'editor')
    <!-- Stats Cards Grid -->
    <div class="dashboard-grid">
        <!-- Total Post -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">📝</div>
                <div>
                    <div class="stat-label">Total Post</div>
                    <div class="stat-number">{{ \App\Models\Post::count() }}</div>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-active">{{ \App\Models\Post::where('status', 'published')->count() }} Published</span>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">📦</div>
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-number">{{ \App\Models\Product::count() }}</div>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-active">{{ \App\Models\Product::where('is_active', true)->count() }} Aktif</span>
            </div>
        </div>

        <!-- Total Kategori -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">🏷️</div>
                <div>
                    <div class="stat-label">Total Kategori</div>
                    <div class="stat-number">{{ \App\Models\Category::count() }}</div>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-info">{{ \App\Models\Category::where('type', 'product')->count() }} Produk</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions for Editor -->
    <div class="quick-actions" style="margin: 30px 0; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
        <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #252C45;">⚡ Aksi Cepat</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
            <a href="{{ route('post.create') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                ✍️ Buat Post
            </a>
            <a href="{{ route('post.index') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                📝 Kelola Post
            </a>
            <a href="{{ route('product.create') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                ➕ Tambah Produk
            </a>
            <a href="{{ route('product.index') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                📦 Kelola Produk
            </a>
            <a href="{{ route('categories.index') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                🏷️ Kelola Kategori
            </a>
        </div>
    </div>

    <!-- Recent Items for Editor -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-top: 30px;">
        <!-- Recent Posts -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #252C45;">📝 Post Terbaru</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                @php $recentPosts = \App\Models\Post::orderBy('created_at', 'desc')->limit(5)->get(); @endphp
                @if($recentPosts->count() > 0)
                    @foreach($recentPosts as $post)
                        <div style="padding: 12px 0; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <a href="{{ route('post.edit', $post->slug) }}" style="color: #252C45; font-weight: 600; text-decoration: none; display: block;">
                                    {{ Str::limit($post->title, 35) }}
                                </a>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #999;">
                                    {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; {{ $post->status === 'published' ? 'background: #dbeafe; color: #1e40af;' : 'background: #f3f4f6; color: #4b5563;' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; color: #999; padding: 20px 0;">Belum ada post</p>
                @endif
            </div>
        </div>

        <!-- Recent Products -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #252C45;">📦 Produk Terbaru</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                @php $recentProducts = \App\Models\Product::orderBy('created_at', 'desc')->limit(5)->get(); @endphp
                @if($recentProducts->count() > 0)
                    @foreach($recentProducts as $product)
                        <div style="padding: 12px 0; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <a href="{{ route('product.edit', $product->slug) }}" style="color: #252C45; font-weight: 600; text-decoration: none; display: block;">
                                    {{ Str::limit($product->name, 35) }}
                                </a>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #999;">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; {{ $product->is_active ? 'background: #d1fae5; color: #065f46;' : 'background: #f3f4f6; color: #4b5563;' }}">
                                {{ $product->is_active ? '✓ Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; color: #999; padding: 20px 0;">Belum ada produk</p>
                @endif
            </div>
        </div>
    </div>

<!-- ADMINISTRATOR DASHBOARD -->
@else
    <!-- Stats Cards Grid -->
    <div class="dashboard-grid">
        <!-- Total Produk -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">📦</div>
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-number">{{ \App\Models\Product::count() }}</div>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-active">{{ \App\Models\Product::where('is_active', true)->count() }} Aktif</span>
            </div>
        </div>

        <!-- Total Post -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">📝</div>
                <div>
                    <div class="stat-label">Total Post</div>
                    <div class="stat-number">{{ \App\Models\Post::count() }}</div>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-active">{{ \App\Models\Post::where('status', 'published')->count() }} Published</span>
            </div>
        </div>

        <!-- Total Kategori -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">🏷️</div>
                <div>
                    <div class="stat-label">Total Kategori</div>
                    <div class="stat-number">{{ \App\Models\Category::count() }}</div>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-info">{{ \App\Models\Category::where('type', 'product')->count() }} Produk</span>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">👥</div>
                <div>
                    <div class="stat-label">Total Pengguna</div>
                    <div class="stat-number">{{ \App\Models\User::count() }}</div>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-info">{{ \App\Models\User::whereNotNull('role_id')->count() }} Dengan Role</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions for Admin -->
    <div class="quick-actions" style="margin: 30px 0; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
        <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #252C45;">⚡ Aksi Cepat</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
            <a href="{{ route('product.create') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                ➕ Tambah Produk
            </a>
            <a href="{{ route('post.create') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                ✍️ Buat Post
            </a>
            <a href="{{ route('categories.index') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                🏷️ Kelola Kategori
            </a>
            <a href="{{ route('auth.show') }}" style="padding: 12px 16px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #fff; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 600; transition: all 0.18s;">
                👥 Kelola Pengguna
            </a>
        </div>
    </div>

    <!-- Recent Items for Admin -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-top: 30px;">
        <!-- Recent Products -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #252C45;">📦 Produk Terbaru</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                @php $recentProducts = \App\Models\Product::orderBy('created_at', 'desc')->limit(5)->get(); @endphp
                @if($recentProducts->count() > 0)
                    @foreach($recentProducts as $product)
                        <div style="padding: 12px 0; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <a href="{{ route('product.edit', $product->slug) }}" style="color: #252C45; font-weight: 600; text-decoration: none; display: block;">
                                    {{ Str::limit($product->name, 35) }}
                                </a>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #999;">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; {{ $product->is_active ? 'background: #d1fae5; color: #065f46;' : 'background: #f3f4f6; color: #4b5563;' }}">
                                {{ $product->is_active ? '✓ Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; color: #999; padding: 20px 0;">Belum ada produk</p>
                @endif
            </div>
            <a href="{{ route('product.index') }}" style="display: block; text-align: center; margin-top: 12px; color: #667eea; font-weight: 600; text-decoration: none;">
                Lihat Semua →
            </a>
        </div>

        <!-- Recent Posts -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #252C45;">📝 Post Terbaru</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                @php $recentPosts = \App\Models\Post::orderBy('created_at', 'desc')->limit(5)->get(); @endphp
                @if($recentPosts->count() > 0)
                    @foreach($recentPosts as $post)
                        <div style="padding: 12px 0; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <a href="{{ route('post.edit', $post->slug) }}" style="color: #252C45; font-weight: 600; text-decoration: none; display: block;">
                                    {{ Str::limit($post->title, 35) }}
                                </a>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #999;">
                                    {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; {{ $post->status === 'published' ? 'background: #dbeafe; color: #1e40af;' : 'background: #f3f4f6; color: #4b5563;' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; color: #999; padding: 20px 0;">Belum ada post</p>
                @endif
            </div>
            <a href="{{ route('post.index') }}" style="display: block; text-align: center; margin-top: 12px; color: #f5576c; font-weight: 600; text-decoration: none;">
                Lihat Semua →
            </a>
        </div>
    </div>
@endif

<!-- Account Info (Tampil untuk semua role kecuali customer) -->
@if($userRole !== 'customer')
    <div style="margin-top: 30px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); padding: 20px; border-radius: 12px; border: 1px solid rgba(102, 126, 234, 0.2);">
        <h4 style="margin: 0 0 12px 0; color: #252C45; font-size: 16px;">👤 Profil Anda</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <p style="margin: 0; font-size: 12px; color: #999;">Nama</p>
                <p style="margin: 4px 0 0 0; font-weight: 600; color: #252C45;">{{ $user?->name ?? '-' }}</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 12px; color: #999;">Email</p>
                <p style="margin: 4px 0 0 0; font-weight: 600; color: #252C45;">{{ $user?->email ?? '-' }}</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 12px; color: #999;">Role</p>
                <p style="margin: 4px 0 0 0; font-weight: 600; color: #252C45;">{{ $user?->role?->name ?? 'User' }}</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 12px; color: #999;">Member Sejak</p>
                <p style="margin: 4px 0 0 0; font-weight: 600; color: #252C45;">{{ $user?->created_at?->format('d M Y') ?? '-' }}</p>
            </div>
        </div>
    </div>
@endif

<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .welcome-msg {
        font-size: 14px;
        color: #6b7280;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(37, 44, 69, 0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(37, 44, 69, 0.12);
        border-color: #cbd5e1;
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #1f2d3d;
    }

    .stat-footer {
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
        font-size: 12px;
    }

    .stat-active {
        color: #059669;
        font-weight: 600;
    }

    .stat-info {
        color: #6b7280;
        font-weight: 600;
    }

    .quick-actions a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 12px;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .stat-card-header {
            gap: 12px;
        }

        .stat-number {
            font-size: 24px;
        }
    }
</style>
@endsection
