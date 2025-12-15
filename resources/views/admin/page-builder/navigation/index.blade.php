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

  .nav-items-list {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  /* scoped to builder list to avoid clashing with sidebar */
  .nav-items-list .builder-nav-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 10px;
    background: #f9fafb;
    cursor: move;
  }

  .nav-items-list .builder-nav-item:hover {
    background: #f3f4f6;
    border-color: #667eea;
  }

  .nav-items-list .builder-nav-item-info {
    flex: 1;
  }

  .nav-items-list .builder-nav-item-label {
    font-weight: 600;
    color: #1a202c;
    font-size: 16px;
  }

  .nav-items-list .builder-nav-item-url {
    color: #64748b;
    font-size: 14px;
    margin-top: 4px;
  }

  .nav-items-list .builder-nav-item-actions {
    display: flex;
    gap: 10px;
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

  .empty-state {
    text-align: center;
    padding: 40px;
    color: #64748b;
  }
</style>

<div class="container">
  <div class="header-section">
    <div>
      <h1>🧭 Navigation Builder</h1>
      <p style="margin: 8px 0 0 0; color: #64748b;">Kelola menu navigasi website</p>
    </div>
    <a href="{{ route('page-builder.navigation.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Menu Item
    </a>
  </div>

  @if (session('success'))
    <div class="alert">
      {{ session('success') }}
    </div>
  @endif

  <div class="nav-items-list">
    @if($items->count() > 0)
      <div id="navItemsList">
        @foreach($items as $item)
          <div class="builder-nav-item" data-id="{{ $item->id }}" data-order="{{ $item->order }}">
            <div class="builder-nav-item-info">
              <div class="builder-nav-item-label">{{ $item->label }}</div>
              <div class="builder-nav-item-url">{{ $item->url }} 
                @if($item->route_pattern)
                  <span style="color: #9ca3af;">({{ $item->route_pattern }})</span>
                @endif
              </div>
            </div>
            <div class="builder-nav-item-actions">
              <a href="{{ route('page-builder.navigation.edit', $item->id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Edit
              </a>
              <form action="{{ route('page-builder.navigation.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">
                  <i class="fas fa-trash"></i> Hapus
                </button>
              </form>
            </div>
            <div style="margin-left: 15px; font-size: 12px; color: #9ca3af;">
              Order: {{ $item->order }} | 
              <span style="color: {{ $item->is_active ? '#4ade80' : '#f59e0b' }};">
                {{ $item->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <p>Belum ada menu item. Klik "Tambah Menu Item" untuk membuat.</p>
      </div>
    @endif
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
      const list = document.getElementById('navItemsList');
      if (list) {
        new Sortable(list, {
          animation: 150,
          onEnd: function(evt) {
            const items = Array.from(list.children).map((item, index) => ({
              id: item.dataset.id,
              order: index + 1
            }));
            
            fetch('{{ route("page-builder.navigation.reorder") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({ items })
            }).then(() => {
              location.reload();
            });
          }
        });
      }
});
</script>
@endsection

