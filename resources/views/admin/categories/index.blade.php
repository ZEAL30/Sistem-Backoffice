@extends('admin.layout.app')

@section('content')
<style>
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .header-section {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .header-section h1 {
    margin: 0;
    font-size: 32px;
    color: #252C45;
    font-weight: 700;
  }

  .form-sidebar {
    width: 340px;
  }

  .form-box {
    background: rgba(255, 255, 255, 0.95);
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .form-box h3 {
    margin: 0 0 20px 0;
    font-size: 16px;
    color: #252C45;
    font-weight: 700;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .form-group label {
    display: block;
    font-size: 13px;
    color: #252C45;
    margin-bottom: 6px;
    font-weight: 600;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #e6eef6;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #667eea;
  }

  .btn-submit {
    width: 100%;
    padding: 10px 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
  }

  .alert {
    background: rgba(79, 209, 197, 0.2);
    border-left: 4px solid #4FD1C5;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 30px;
    color: #0d9488;
  }

  .main-content {
    flex: 1;
  }

  .wrapper {
    display: flex;
    gap: 20px;
  }

  .table-wrapper {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th {
    background: #252C45;
    color: white;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
  }

  td {
    padding: 16px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
  }

  tbody tr:hover {
    background: #f9f9f9;
  }

  .type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    background: #64748b;
    color: white;
  }

  .actions {
    display: flex;
    gap: 6px;
  }

  .btn-action {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
    font-weight: 600;
  }

  .btn-edit {
    background: #fff3e0;
    color: #f57c00;
  }

  .btn-edit:hover {
    background: #ffe0b2;
  }

  .btn-delete {
    background: #ffebee;
    color: #d32f2f;
  }

  .btn-delete:hover {
    background: #ffcdd2;
  }

  .empty-state {
    background: rgba(255, 255, 255, 0.95);
    padding: 60px;
    border-radius: 12px;
    text-align: center;
    color: #999;
  }

  .img-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
  }

  .category-name {
    font-weight: 600;
    color: #252C45;
  }

  .category-slug {
    font-size: 12px;
    color: #999;
  }
</style>

<div class="container">
  <div class="header-section">
    <h1>🏷️ Kategori @if($type) - {{ ucfirst($type) }} @endif</h1>
  </div>

  <div class="wrapper">
    <!-- Main Content -->
    <div class="main-content">
      @if(session('success'))
        <div class="alert">✅ {{ session('success') }}</div>
      @endif

      @if($categories->isEmpty())
        <div class="empty-state">
          Belum ada kategori
        </div>
      @else
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th style="width: 60px;">Foto</th>
                <th>Nama</th>
                <th>Slug</th>
                <th>Tipe</th>
                <th style="width: 140px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($categories as $cat)
                <tr>
                  <td>
                    @if(!empty($cat->image))
                      <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="img-thumb">
                    @else
                      <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 24px;">📁</div>
                    @endif
                  </td>
                  <td>
                    <div class="category-name">{{ $cat->name }}</div>
                    <div class="category-slug">{{ $cat->slug }}</div>
                  </td>
                  <td><code style="background: #f5f5f5; padding: 4px 8px; border-radius: 4px;">{{ $cat->slug }}</code></td>
                  <td>
                    <span class="type-badge">{{ ucfirst($cat->type) }}</span>
                  </td>
                  <td>
                    <div class="actions">
                      <a href="{{ url('/categories/' . $cat->id . '/edit') }}" class="btn-action btn-edit">Edit</a>
                      <form method="POST" action="{{ url('/categories/' . $cat->id) }}" style="display: inline;" onsubmit="return confirm('Hapus kategori ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">Hapus</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <!-- Sidebar Form -->
    <aside class="form-sidebar">
      <div class="form-box">
        <h3>Tambah Kategori</h3>
        <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="type" value="{{ $type ?? 'product' }}" />

          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" placeholder="Nama kategori" required>
          </div>

          <div class="form-group">
            <label>Slug (opsional)</label>
            <input type="text" name="slug" placeholder="Biarkan kosong untuk otomatis">
          </div>

          <div class="form-group">
            <label>Gambar (opsional)</label>
            <input type="file" name="image" accept="image/*">
          </div>

          <button type="submit" class="btn-submit">Tambah Kategori</button>
        </form>
      </div>
    </aside>
  </div>
</div>

@endsection
