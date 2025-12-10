@extends('admin.layout.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h1 style="margin-bottom: 30px; color: #333;">👤 Profil Saya</h1>

        <div style="display: grid; gap: 20px;">
            <div style="padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Nama Lengkap</label>
                <p style="margin: 0; font-size: 16px; color: #333; font-weight: 500;">{{ Auth::user()->name }}</p>
            </div>

            <div style="padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Email</label>
                <p style="margin: 0; font-size: 16px; color: #333; font-weight: 500;">{{ Auth::user()->email }}</p>
            </div>

            <div style="padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Nomor Telepon</label>
                <p style="margin: 0; font-size: 16px; color: #333; font-weight: 500;">{{ Auth::user()->phone_number ?? '-' }}</p>
            </div>

            <div style="padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Role</label>
                <p style="margin: 0; font-size: 16px;">
                    <span style="display: inline-block; padding: 6px 12px; background: {{ Auth::user()->role->slug == 'admin' ? '#667eea' : (Auth::user()->role->slug == 'editor' ? '#ff9800' : '#4caf50') }}; color: white; border-radius: 20px; font-size: 14px; font-weight: 600;">
                        {{ Auth::user()->role->name }}
                    </span>
                </p>
            </div>

            <div style="padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Status</label>
                <p style="margin: 0; font-size: 16px;">
                    <span style="display: inline-block; padding: 6px 12px; background: {{ Auth::user()->status == 'active' ? '#4caf50' : '#f44336' }}; color: white; border-radius: 20px; font-size: 14px; font-weight: 600; text-transform: capitalize;">
                        {{ Auth::user()->status }}
                    </span>
                </p>
            </div>

            <div style="padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Tergabung Sejak</label>
                <p style="margin: 0; font-size: 16px; color: #333; font-weight: 500;">{{ Auth::user()->created_at->format('d F Y - H:i') }}</p>
            </div>

            <div style="padding: 15px; background: #f5f5f5; border-radius: 5px;">
                <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Terakhir Diperbarui</label>
                <p style="margin: 0; font-size: 16px; color: #333; font-weight: 500;">{{ Auth::user()->updated_at->format('d F Y - H:i') }}</p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <a href="{{ route('dashboard') }}" style="flex: 1; padding: 12px; background: #ddd; color: #333; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-align: center; display: flex; align-items: center; justify-content: center;">
                ← Kembali
            </a>
        </div>
    </div>
</div>
@endsection
