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
  .form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
  }

  .form-group input:focus,
  .form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .form-group textarea {
    min-height: 120px;
    resize: vertical;
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

  .avatar-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-top: 10px;
    border: 2px solid #e5e7eb;
  }
</style>

<div class="container">
  <div class="header-section">
    <h1>{{ $testimonial->id ? '✏️ Edit' : '➕ Tambah' }} Testimonial</h1>
    <p style="margin: 8px 0 0 0; color: #64748b;">
      <a href="{{ route('page-builder.testimonial.index') }}" style="color: #64748b; text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Kembali ke daftar
      </a>
    </p>
  </div>

  <div class="form-card">
    <form method="POST" action="{{ route('page-builder.testimonial.store') }}" enctype="multipart/form-data">
      @csrf
      @if($testimonial->id)
        <input type="hidden" name="id" value="{{ $testimonial->id }}">
      @endif

      <div class="form-group">
        <label>Nama *</label>
        <input type="text" name="name" value="{{ $testimonial->name }}" required placeholder="Nama Pelanggan">
      </div>

      <div class="form-group">
        <label>Designation / Jabatan</label>
        <input type="text" name="designation" value="{{ $testimonial->designation }}" placeholder="Customer, CEO, etc.">
      </div>

      <div class="form-group">
        <label>Testimonial *</label>
        <textarea name="testimonial" required placeholder="Isi testimonial...">{{ $testimonial->testimonial }}</textarea>
      </div>

      <div class="form-group">
        <label>Avatar / Foto</label>
        <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(this)">
        @if($testimonial->avatar)
        <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="Avatar" class="avatar-preview" id="avatarPreview">
        @else
        <div id="avatarPreview" style="display: none;">
          <img src="" alt="Avatar Preview" class="avatar-preview">
        </div>
        @endif
      </div>

      <div class="form-group">
        <label>Order</label>
        <input type="number" name="order" value="{{ $testimonial->order ?? 0 }}" placeholder="0">
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="is_active" value="1" {{ $testimonial->is_active !== false ? 'checked' : '' }}>
          Active
        </label>
      </div>

      <button type="submit" class="btn-save">
        <i class="fas fa-save"></i> Simpan Testimonial
      </button>
    </form>
  </div>
</div>

<script>
function previewAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      let preview = document.getElementById('avatarPreview');
      if (preview.style.display === 'none' || !preview.querySelector('img')) {
        preview.innerHTML = '<img src="' + e.target.result + '" alt="Avatar Preview" class="avatar-preview">';
        preview.style.display = 'block';
      } else {
        preview.querySelector('img').src = e.target.result;
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection

