@extends('admin.layout.app')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; color:#252C45; text-decoration:none; font-weight:500;">
        ← Kembali ke Dashboard
    </a>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px;">
    <!-- LEFT COLUMN -->
    <div>
        <div style="background:#fff; border-radius:12px; padding:30px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h1 style="color:#252C45; font-size:28px; margin:0 0 24px 0; font-weight:700;">👤 Profil Saya</h1>

            <div style="display:grid; gap:16px;">
                <div>
                    <label style="display:block; color:#999; font-size:12px; font-weight:600; margin-bottom:4px; text-transform:uppercase;">Nama Lengkap</label>
                    <p style="margin:0; font-size:16px; color:#252C45; font-weight:600;">{{ Auth::user()->name }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; font-weight:600; margin-bottom:4px; text-transform:uppercase;">Email</label>
                    <p style="margin:0; font-size:16px; color:#252C45; font-weight:600;">{{ Auth::user()->email }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; font-weight:600; margin-bottom:4px; text-transform:uppercase;">Nomor Telepon</label>
                    <p style="margin:0; font-size:16px; color:#252C45; font-weight:600;">{{ Auth::user()->phone_number ?? '- (belum diisi)' }}</p>
                </div>

                <div style="padding-top:16px; border-top:1px solid #f0f0f0;">
                    <label style="display:block; color:#999; font-size:12px; font-weight:600; margin-bottom:4px; text-transform:uppercase;">Role</label>
                    <p style="margin:0; font-size:16px;">
                        <span style="display:inline-block; padding:6px 14px; background:#252C45; color:white; border-radius:20px; font-size:14px; font-weight:600;">
                            {{ Auth::user()->role->name }}
                        </span>
                    </p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; font-weight:600; margin-bottom:4px; text-transform:uppercase;">Status</label>
                    <p style="margin:0; font-size:16px;">
                        <span style="display:inline-block; padding:6px 14px; background:#{{ Auth::user()->status === 'active' ? '4FD1C5' : 'ff4444' }}; color:#{{ Auth::user()->status === 'active' ? '052027' : 'fff' }}; border-radius:20px; font-size:14px; font-weight:600; text-transform:capitalize;">
                            {{ Auth::user()->status === 'active' ? '✅ Aktif' : '❌ Tidak Aktif' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div>
        <!-- Info Card -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04); margin-bottom:20px;">
            <h3 style="color:#252C45; font-size:14px; font-weight:700; margin:0 0 16px 0;">📋 Detail Akun</h3>

            <div style="display:flex; flex-direction:column; gap:12px;">
                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px; font-weight:600;">ID User</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:14px; font-family:'Courier New', monospace;">{{ Auth::user()->id }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px; font-weight:600;">Tergabung Sejak</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:14px;">{{ Auth::user()->created_at->format('d M Y - H:i') }}</p>
                </div>

                <div>
                    <label style="display:block; color:#999; font-size:12px; margin-bottom:4px; font-weight:600;">Terakhir Update</label>
                    <p style="margin:0; color:#252C45; font-weight:600; font-size:14px;">{{ Auth::user()->updated_at->format('d M Y - H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Permissions Card -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="color:#252C45; font-size:14px; font-weight:700; margin:0 0 16px 0;">🔐 Permissions</h3>

            <div style="display:flex; flex-direction:column; gap:8px;">
                @if(Auth::user()->role->slug === 'admin')
                    <label style="display:flex; align-items:center; gap:8px; color:#666; font-size:13px;">
                        <input type="checkbox" checked disabled>
                        <span>✅ Akses admin penuh</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; color:#666; font-size:13px;">
                        <input type="checkbox" checked disabled>
                        <span>✅ Kelola user</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; color:#666; font-size:13px;">
                        <input type="checkbox" checked disabled>
                        <span>✅ Kelola produk & artikel</span>
                    </label>
                @elseif(Auth::user()->role->slug === 'editor')
                    <label style="display:flex; align-items:center; gap:8px; color:#666; font-size:13px;">
                        <input type="checkbox" checked disabled>
                        <span>✅ Kelola konten</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; color:#666; font-size:13px;">
                        <input type="checkbox" disabled>
                        <span>❌ Kelola user</span>
                    </label>
                @else
                    <label style="display:flex; align-items:center; gap:8px; color:#666; font-size:13px;">
                        <input type="checkbox" checked disabled>
                        <span>✅ View konten</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; color:#666; font-size:13px;">
                        <input type="checkbox" disabled>
                        <span>❌ Edit konten</span>
                    </label>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
