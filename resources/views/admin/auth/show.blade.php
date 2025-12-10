@extends('admin.layout.app')

@section('content')
<div>
    <h1 style="margin-bottom: 30px; color: #333; font-size: 28px;">👥 Kelola Pengguna</h1>

    @if(session('success'))
        <div style="background: #e8f5e9; border: 1px solid #4caf50; padding: 15px; border-radius: 5px; margin-bottom: 20px; color: #2e7d32;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ route('auth.create') }}" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 5px; font-weight: 600; transition: all 0.3s ease;">
            ➕ Tambah Pengguna Baru
        </a>
    </div>

    @if($users->isEmpty())
        <div style="background: white; padding: 40px; border-radius: 10px; text-align: center; color: #666;">
            <p>Belum ada pengguna terdaftar.</p>
        </div>
    @else
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <th style="padding: 15px; text-align: left;">ID</th>
                        <th style="padding: 15px; text-align: left;">Nama</th>
                        <th style="padding: 15px; text-align: left;">Email</th>
                        <th style="padding: 15px; text-align: left;">Telepon</th>
                        <th style="padding: 15px; text-align: left;">Role</th>
                        <th style="padding: 15px; text-align: left;">Status</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid #f0f0f0; hover:background-color: #f9f9f9;">
                            <td style="padding: 15px;">{{ $user->id }}</td>
                            <td style="padding: 15px; font-weight: 500;">{{ $user->name }}</td>
                            <td style="padding: 15px;">{{ $user->email }}</td>
                            <td style="padding: 15px; font-size: 12px;">{{ $user->phone_number ?? '-' }}</td>
                            <td style="padding: 15px;">
                                <span style="display: inline-block; padding: 4px 12px; background: {{ $user->role->slug == 'admin' ? '#667eea' : ($user->role->slug == 'editor' ? '#ff9800' : '#4caf50') }}; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    {{ $user->role?->name ?? '-' }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <span style="display: inline-block; padding: 4px 12px; background: {{ $user->status == 'active' ? '#4caf50' : '#f44336' }}; color: white; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize;">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('auth.edit', $user->id) }}" style="display: inline-block; padding: 6px 12px; background: #2196F3; color: white; text-decoration: none; border-radius: 3px; font-size: 12px; margin-right: 5px; transition: all 0.3s ease;">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('auth.destroy', $user->id) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background: #f44336; color: white; border: none; border-radius: 3px; font-size: 12px; cursor: pointer; transition: all 0.3s ease;">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 20px; text-align: center; color: #999;">Tidak ada pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>

<style>
    table tbody tr:hover {
        background-color: #f9f9f9;
    }

    a, button {
        transition: all 0.3s ease;
    }

    a:hover, button:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
</style>
@endsection
