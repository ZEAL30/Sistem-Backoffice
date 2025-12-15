@extends('admin.layout.app')

@section('content')
<style>
  .container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .header-section {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .header-section h1 {
    margin: 0;
    font-size: 32px;
    color: #1a202c;
    font-weight: 700;
  }

  .form-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .btn-save {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 14px 28px;
    cursor: pointer;
    font-weight: 600;
    font-size: 16px;
    width: 100%;
    margin-top: 20px;
  }

  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
  }

  .help-text {
    color: #64748b;
    font-size: 12px;
    margin-top: 4px;
  }
</style>

<div class="container">
  <div class="header-section">
    <h1>{{ $item->id ? '✏️ Edit' : '➕ Tambah' }} Navigation Item</h1>
    <p style="margin: 8px 0 0 0; color: #64748b;">
      <a href="{{ route('page-builder.navigation.index') }}" style="color: #64748b; text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Kembali ke daftar
      </a>
    </p>
  </div>

  <div class="form-card">
    <form method="POST" action="{{ route('page-builder.navigation.store') }}">
      @csrf
      @if($item->id)
        <input type="hidden" name="id" value="{{ $item->id }}">
      @endif

      <div class="form-group">
        <label>Label / Nama Menu *</label>
        <input type="text" name="label" value="{{ $item->label }}" required placeholder="Home, About Us, etc.">
      </div>

      <div class="form-group">
        <label>URL *</label>
        <input type="text" name="url" value="{{ $item->url }}" required placeholder="/, /about, /blog, etc.">
        <div class="help-text">Gunakan format: / untuk home, /about untuk about page, dll.</div>
      </div>

      <div class="form-group">
        <label>Route Pattern (untuk active link)</label>
        <input type="text" name="route_pattern" value="{{ $item->route_pattern }}" placeholder="blog*, about, product*">
        <div class="help-text">Gunakan * untuk wildcard (contoh: blog* untuk semua halaman blog)</div>
      </div>

      <div class="form-group">
        <label>Target</label>
        <select name="target">
          <option value="_self" {{ $item->target === '_self' ? 'selected' : '' }}>Same Window</option>
          <option value="_blank" {{ $item->target === '_blank' ? 'selected' : '' }}>New Window</option>
        </select>
      </div>

      <div class="form-group">
        <label>Order</label>
        <input type="number" name="order" value="{{ $item->order ?? ($item->id ? $item->order : 0) }}" placeholder="0">
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="is_active" value="1" {{ $item->is_active !== false ? 'checked' : '' }}>
          Active
        </label>
      </div>

      <button type="submit" class="btn-save">
        <i class="fas fa-save"></i> Simpan Menu Item
      </button>
    </form>
  </div>
</div>
@endsection

