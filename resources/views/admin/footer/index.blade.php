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

  .alert {
    background: rgba(74, 222, 128, 0.2);
    border-left: 4px solid #4ade80;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 30px;
    color: #166534;
  }

  .sections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .section-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
  }

  .section-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .section-type {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 10px;
    background: #e0e7ff;
    color: #4338ca;
  }

  .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 8px;
  }

  .section-content {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 12px;
  }

  .section-order {
    color: #94a3b8;
    font-size: 12px;
  }
</style>

<div class="container">
  <div class="header-section">
    <div>
      <h1>🎨 Footer Builder</h1>
      <p style="margin: 8px 0 0 0; color: #64748b;">Kelola konten footer website</p>
    </div>
    <a href="{{ route('footer.edit') }}" class="btn-primary">
      <i class="fas fa-edit"></i> Edit Footer
    </a>
  </div>

  @if (session('success'))
    <div class="alert">
      {{ session('success') }}
    </div>
  @endif

  <div class="sections-grid">
    @forelse($sections as $section)
      <div class="section-card">
        <span class="section-type">{{ ucfirst($section->type) }}</span>
        <div class="section-title">{{ $section->title ?? 'No Title' }}</div>
        <div class="section-content">
          @if($section->type === 'menu' && $section->data)
            <ul style="margin: 0; padding-left: 20px;">
              @foreach($section->data as $item)
                <li>{{ $item['label'] ?? '' }}</li>
              @endforeach
            </ul>
          @elseif($section->type === 'contact' && $section->data)
            <div>
              @if(isset($section->data['phone']))
                <div>📞 {{ $section->data['phone'] }}</div>
              @endif
              @if(isset($section->data['email']))
                <div>✉️ {{ $section->data['email'] }}</div>
              @endif
            </div>
          @else
            {{ Str::limit($section->content ?? '', 100) }}
          @endif
        </div>
        <div class="section-order">Order: {{ $section->order }} | 
          <span style="color: {{ $section->is_active ? '#4ade80' : '#f59e0b' }};">
            {{ $section->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
      </div>
    @empty
      <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 12px;">
        <p style="color: #64748b;">Belum ada section footer. Klik "Edit Footer" untuk membuat.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection

