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
    color: #1a202c;
    font-weight: 700;
  }

  .btn-new {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 15px;
  }

  .btn-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
  }

  .alert {
    background: rgba(74, 222, 128, 0.2);
    border-left: 4px solid #4ade80;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 30px;
    color: #166534;
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
    background: #1a202c;
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

  .status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-active {
    background: rgba(74, 222, 128, 0.2);
    color: #166534;
  }

  .status-inactive {
    background: rgba(251, 191, 36, 0.2);
    color: #92400e;
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

  .btn-view {
    background: #e3f2fd;
    color: #1976d2;
  }

  .btn-view:hover {
    background: #bbdefb;
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

  .price {
    font-weight: 700;
    color: #667eea;
  }
</style>

<div class="container">
  <div class="header-section">
    <h1>📦 Product Store</h1>
    <a href="{{ route('product.create') }}" class="btn-new">➕ Tambah Produk</a>
  </div>

  @if(session('success'))
    <div class="alert">✅ {{ session('success') }}</div>
  @endif

  @if(isset($products) && $products->isNotEmpty())
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th style="width: 60px;">Foto</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Status</th>
            <th style="width: 150px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
            <tr>
              <td>
                @if($product->featured_image)
                  <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="img-thumb">
                @else
                  <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 24px;">📦</div>
                @endif
              </td>
              <td>
                <strong>{{ $product->name ?? $product->title ?? '-' }}</strong>
                <div style="font-size: 12px; color: #999; margin-top: 4px;">{{ $product->slug }}</div>
              </td>
              <td class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
              <td>
                <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                  {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td>
                <div class="actions">
                  <a href="{{ route('product.show', $product->slug) }}" class="btn-action btn-view">Lihat</a>
                  <a href="{{ route('product.edit', $product->slug) }}" class="btn-action btn-edit">Edit</a>
                  <form method="POST" action="{{ route('product.destroy', $product->slug) }}" style="display: inline;" onsubmit="return confirm('Hapus produk ini?');">
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
  @else
    <div class="empty-state">
      📭 Belum ada produk
    </div>
  @endif
</div>

@endsection
