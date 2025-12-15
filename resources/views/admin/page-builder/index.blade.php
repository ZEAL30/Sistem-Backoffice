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
  }

  .header-section h1 {
    margin: 0;
    font-size: 32px;
    color: #1a202c;
    font-weight: 700;
  }

  .builder-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .builder-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    text-align: center;
  }

  .builder-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .builder-card-icon {
    font-size: 48px;
    margin-bottom: 15px;
  }

  .builder-card h2 {
    font-size: 24px;
    color: #1a202c;
    margin-bottom: 10px;
  }

  .builder-card p {
    color: #64748b;
    margin-bottom: 20px;
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

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
  }

  .stats {
    display: flex;
    gap: 15px;
    margin-top: 15px;
    justify-content: center;
  }

  .stat-item {
    padding: 8px 16px;
    background: #f3f4f6;
    border-radius: 6px;
    font-size: 14px;
    color: #64748b;
  }
</style>

<div class="container">
  <div class="header-section">
    <h1>🎨 Visual Page Builder</h1>
    <p style="margin: 8px 0 0 0; color: #64748b;">Kelola konten halaman website secara visual</p>
  </div>

  <div class="builder-grid">
    <!-- Hero Builder -->
    <div class="builder-card">
      <div class="builder-card-icon">🚀</div>
      <h2>Hero Section</h2>
      <p>Edit hero section di halaman home</p>
      <div class="stats">
        <span class="stat-item">{{ $hero ? '✓ Active' : '✗ No Data' }}</span>
      </div>
      <a href="{{ route('page-builder.hero.index') }}" class="btn-primary">
        <i class="fas fa-edit"></i> Kelola Hero
      </a>
    </div>

    <!-- Testimonial Builder -->
    <div class="builder-card">
      <div class="builder-card-icon">💬</div>
      <h2>Testimonial</h2>
      <p>Kelola testimonial pelanggan</p>
      <div class="stats">
        <span class="stat-item">{{ $testimonials->count() }} Items</span>
      </div>
      <a href="{{ route('page-builder.testimonial.index') }}" class="btn-primary">
        <i class="fas fa-edit"></i> Kelola Testimonial
      </a>
    </div>

    <!-- Navigation Builder -->
    <div class="builder-card">
      <div class="builder-card-icon">🧭</div>
      <h2>Navigation Menu</h2>
      <p>Edit menu navigasi website</p>
      <div class="stats">
        <span class="stat-item">{{ $navItems->count() }} Items</span>
      </div>
      <a href="{{ route('page-builder.navigation.index') }}" class="btn-primary">
        <i class="fas fa-edit"></i> Kelola Navigation
      </a>
    </div>

    <!-- Footer Builder -->
    <div class="builder-card">
      <div class="builder-card-icon">🦶</div>
      <h2>Footer</h2>
      <p>Kelola footer website</p>
      <a href="{{ route('footer.edit') }}" class="btn-primary">
        <i class="fas fa-edit"></i> Kelola Footer
      </a>
    </div>
  </div>
</div>
@endsection

