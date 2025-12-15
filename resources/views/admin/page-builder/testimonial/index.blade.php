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

  .btn-primary {
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

  .testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .testimonial-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
  }

  .testimonial-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .testimonial-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 24px;
    color: #9ca3af;
  }

  .testimonial-name {
    font-size: 18px;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 5px;
    text-align: center;
  }

  .testimonial-designation {
    color: #64748b;
    font-size: 14px;
    text-align: center;
    margin-bottom: 15px;
  }

  .testimonial-text {
    color: #374151;
    font-size: 14px;
    line-height: 1.6;
    text-align: center;
  }

  .testimonial-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    justify-content: center;
  }

  .btn-edit, .btn-delete {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: none;
  }

  .btn-edit {
    background: #667eea;
    color: white;
  }

  .btn-delete {
    background: #ef4444;
    color: white;
  }

  .alert {
    background: rgba(74, 222, 128, 0.2);
    border-left: 4px solid #4ade80;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 30px;
    color: #166534;
  }
</style>

<div class="container">
  <div class="header-section">
    <div>
      <h1>💬 Testimonial Builder</h1>
      <p style="margin: 8px 0 0 0; color: #64748b;">Kelola testimonial pelanggan</p>
    </div>
    <a href="{{ route('page-builder.testimonial.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Testimonial
    </a>
  </div>

  @if (session('success'))
    <div class="alert">
      {{ session('success') }}
    </div>
  @endif

  <div class="testimonials-grid">
    @forelse($testimonials as $testimonial)
      <div class="testimonial-card">
        <div class="testimonial-avatar">
          @if($testimonial->avatar)
            <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
          @else
            <i class="bi bi-person-fill"></i>
          @endif
        </div>
        <div class="testimonial-name">{{ $testimonial->name }}</div>
        <div class="testimonial-designation">{{ $testimonial->designation ?? 'Customer' }}</div>
        <div class="testimonial-text">{{ Str::limit($testimonial->testimonial, 150) }}</div>
        <div class="testimonial-actions">
          <a href="{{ route('page-builder.testimonial.edit', $testimonial->id) }}" class="btn-edit">
            <i class="fas fa-edit"></i> Edit
          </a>
          <form action="{{ route('page-builder.testimonial.destroy', $testimonial->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus testimonial ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete">
              <i class="fas fa-trash"></i> Hapus
            </button>
          </form>
        </div>
        <div style="text-align: center; margin-top: 10px; font-size: 12px; color: #9ca3af;">
          Order: {{ $testimonial->order }} | 
          <span style="color: {{ $testimonial->is_active ? '#4ade80' : '#f59e0b' }};">
            {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
      </div>
    @empty
      <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 12px;">
        <p style="color: #64748b;">Belum ada testimonial. Klik "Tambah Testimonial" untuk membuat.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection

