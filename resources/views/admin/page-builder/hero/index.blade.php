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

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
  }

  .hero-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .hero-preview {
    background: linear-gradient(45deg, #378981, #5BAF9F);
    border-radius: 12px;
    padding: 40px;
    color: white;
    margin-top: 20px;
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
      <h1>🚀 Hero Section Builder</h1>
      <p style="margin: 8px 0 0 0; color: #64748b;">Kelola hero section di halaman home</p>
    </div>
    <a href="{{ route('page-builder.hero.edit') }}" class="btn-primary">
      <i class="fas fa-edit"></i> Edit Hero
    </a>
  </div>

  @if (session('success'))
    <div class="alert">
      {{ session('success') }}
    </div>
  @endif

  <div class="hero-card">
    @if($heroes->count() > 0)
      @foreach($heroes as $hero)
        <div class="hero-preview">
          @if($hero->badge_text)
          <div style="background: #006666; padding: 8px 16px; border-radius: 9999px; display: inline-block; font-size: 14px; font-weight: 600; margin-bottom: 20px;">
            {{ $hero->badge_text }}
          </div>
          @endif
          <h2 style="font-size: 32px; font-weight: bold; margin-bottom: 15px;">{{ $hero->title }}</h2>
          <p style="font-size: 18px; opacity: 0.9; margin-bottom: 20px;">{{ $hero->description }}</p>
          @if($hero->button_text)
          <a href="#" style="background: #006666; padding: 12px 24px; border-radius: 9999px; color: white; text-decoration: none; font-weight: 600; display: inline-block;">
            {{ $hero->button_text }}
          </a>
          @endif
          <div style="margin-top: 20px; color: {{ $hero->is_active ? '#4ade80' : '#f59e0b' }};">
            Status: {{ $hero->is_active ? 'Active' : 'Inactive' }}
          </div>
        </div>
      @endforeach
    @else
      <p style="text-align: center; color: #64748b; padding: 40px;">
        Belum ada hero section. Klik "Edit Hero" untuk membuat.
      </p>
    @endif
  </div>
</div>
@endsection

