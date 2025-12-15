@extends('admin.layout.app')

@section('content')
<style>
  .container {
    max-width: 1400px;
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

  .builder-container {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
  }

  .preview-area {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-height: 600px;
  }

  .form-area {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-height: 80vh;
    overflow-y: auto;
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
    min-height: 100px;
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

  .preview-hero {
    background: linear-gradient(45deg, #378981, #5BAF9F);
    border-radius: 12px;
    padding: 40px;
    color: white;
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .image-preview {
    max-width: 100%;
    border-radius: 8px;
    margin-top: 20px;
  }
</style>

<div class="container">
  <div class="header-section">
    <div>
      <h1>🚀 Edit Hero Section</h1>
      <p style="margin: 8px 0 0 0; color: #64748b;">Edit konten hero section</p>
    </div>
    <a href="{{ route('page-builder.hero.index') }}" style="color: #64748b; text-decoration: none;">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <form method="POST" action="{{ route('page-builder.hero.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $hero->id }}">

    <div class="builder-container">
      <!-- Preview Area -->
      <div class="preview-area">
        <h2 style="margin: 0 0 20px 0; font-size: 20px; color: #1a202c; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
          📱 Preview Hero
        </h2>
        <div class="preview-hero" id="previewHero" style="background: linear-gradient({{ $hero->background_gradient ?? '45deg, #378981, #5BAF9F' }});">
          <div id="previewBadge" style="background: #006666; padding: 8px 16px; border-radius: 9999px; display: inline-block; font-size: 14px; font-weight: 600; margin-bottom: 20px; width: fit-content;">
            {{ $hero->badge_text ?? 'Badge Text' }}
          </div>
          <h2 id="previewTitle" style="font-size: 32px; font-weight: bold; margin-bottom: 15px;">
            {{ $hero->title ?? 'Hero Title' }}
          </h2>
          <p id="previewDesc" style="font-size: 18px; opacity: 0.9; margin-bottom: 20px;">
            {{ $hero->description ?? 'Hero description...' }}
          </p>
          <a href="#" id="previewButton" style="background: #006666; padding: 12px 24px; border-radius: 9999px; color: white; text-decoration: none; font-weight: 600; display: inline-block; width: fit-content;">
            {{ $hero->button_text ?? 'Button Text' }}
          </a>
          @if($hero->image)
          <img src="{{ asset('storage/' . $hero->image) }}" alt="Hero" class="image-preview" id="previewImage">
          @endif
        </div>
      </div>

      <!-- Form Area -->
      <div class="form-area">
        <h2 style="margin: 0 0 20px 0; font-size: 20px; color: #1a202c;">⚙️ Edit Form</h2>

        <div class="form-group">
          <label>Badge Text</label>
          <input type="text" name="badge_text" value="{{ $hero->badge_text }}" placeholder="WAKTU YANG PAS BUAT UPGRADE PC!" oninput="updatePreview()">
        </div>

        <div class="form-group">
          <label>Title *</label>
          <input type="text" name="title" value="{{ $hero->title }}" required placeholder="DESAIN PC SESUAI KEBUTUHANMU" oninput="updatePreview()">
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea name="description" placeholder="Description..." oninput="updatePreview()">{{ $hero->description }}</textarea>
        </div>

        <div class="form-group">
          <label>Button Text</label>
          <input type="text" name="button_text" value="{{ $hero->button_text }}" placeholder="CONTACT US +" oninput="updatePreview()">
        </div>

        <div class="form-group">
          <label>Button URL</label>
          <input type="text" name="button_url" value="{{ $hero->button_url }}" placeholder="/contact" oninput="updatePreview()">
        </div>

        <div class="form-group">
          <label>Background Gradient</label>
          <input type="text" name="background_gradient" value="{{ $hero->background_gradient }}" placeholder="45deg, #378981, #5BAF9F" oninput="updatePreview()">
          <small style="color: #64748b; font-size: 12px;">Format: "45deg, #378981, #5BAF9F"</small>
        </div>

        <div class="form-group">
          <label>Hero Image</label>
          <input type="file" name="image" accept="image/*" onchange="previewImage(this)">
          @if($hero->image)
          <p style="margin-top: 8px; color: #64748b; font-size: 12px;">Current: {{ $hero->image }}</p>
          @endif
        </div>

        <div class="form-group">
          <label>
            <input type="checkbox" name="is_active" value="1" {{ $hero->is_active ? 'checked' : '' }}>
            Active
          </label>
        </div>

        <button type="submit" class="btn-save">
          <i class="fas fa-save"></i> Simpan Perubahan
        </button>
      </div>
    </div>
  </form>
</div>

<script>
function updatePreview() {
  const form = document.querySelector('form');
  const formData = new FormData(form);
  
  document.getElementById('previewBadge').textContent = formData.get('badge_text') || 'Badge Text';
  document.getElementById('previewTitle').textContent = formData.get('title') || 'Hero Title';
  document.getElementById('previewDesc').textContent = formData.get('description') || 'Hero description...';
  document.getElementById('previewButton').textContent = formData.get('button_text') || 'Button Text';
  
  const gradient = formData.get('background_gradient') || '45deg, #378981, #5BAF9F';
  document.getElementById('previewHero').style.background = `linear-gradient(${gradient})`;
}

function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      let img = document.getElementById('previewImage');
      if (!img) {
        img = document.createElement('img');
        img.id = 'previewImage';
        img.className = 'image-preview';
        document.getElementById('previewHero').appendChild(img);
      }
      img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection

