@extends('admin.layout.app')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('auth.show') }}" style="display:inline-flex; align-items:center; gap:8px; color:#252C45; text-decoration:none; font-weight:500;">
        ← Kembali ke User
    </a>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
    <!-- LEFT COLUMN -->
    <div>
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h2 style="color:#252C45; font-size:20px; margin:0 0 20px 0; font-weight:700;">➕ Tambah Pengguna</h2>

            @if ($errors->any())
                <div style="background:#fee; border:1px solid #fcc; color:#a00; padding:12px; border-radius:8px; margin-bottom:16px; font-size:14px;">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('auth.store') }}" style="display:flex; flex-direction:column; gap:16px;">
                @csrf

                <div>
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Nama Lengkap <span style="color:red;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                    @error('name')
                        <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Email <span style="color:red;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                    @error('email')
                        <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Nomor Telepon</label>
                    <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="0812345678"
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                    @error('phone_number')
                        <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Password <span style="color:red;">*</span></label>
                    <input type="password" name="password" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                    @error('password')
                        <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Konfirmasi Password <span style="color:red;">*</span></label>
                    <input type="password" name="password_confirmation" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                </div>

                <div>
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Role <span style="color:red;">*</span></label>
                    <select name="role_id" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                        <option value="">-- Pilih Role --</option>
                        @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Status <span style="color:red;">*</span></label>
                    <select name="status" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                        <option value="">-- Pilih Status --</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" style="width:100%; padding:11px 16px; background:#252C45; color:white; border:none; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; transition:all 0.18s;">
                    💾 Simpan Pengguna
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div>
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="color:#252C45; font-size:16px; margin:0 0 16px 0; font-weight:700;">👤 Informasi Role</h3>

            <div style="display:grid; gap:12px;">
                <div style="padding:16px; background:#f9f9f9; border-radius:8px; border-left:4px solid #4FD1C5;">
                    <h4 style="color:#252C45; font-size:13px; margin:0 0 8px 0; font-weight:600;">🔐 Admin</h4>
                    <p style="color:#666; font-size:13px; margin:0;">Akses penuh ke semua fitur</p>
                </div>
                <div style="padding:16px; background:#f9f9f9; border-radius:8px; border-left:4px solid #4FD1C5;">
                    <h4 style="color:#252C45; font-size:13px; margin:0 0 8px 0; font-weight:600;">✍️ Editor</h4>
                    <p style="color:#666; font-size:13px; margin:0;">Dapat mengelola konten</p>
                </div>
                <div style="padding:16px; background:#f9f9f9; border-radius:8px; border-left:4px solid #4FD1C5;">
                    <h4 style="color:#252C45; font-size:13px; margin:0 0 8px 0; font-weight:600;">👥 User</h4>
                    <p style="color:#666; font-size:13px; margin:0;">Akses terbatas (view only)</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
