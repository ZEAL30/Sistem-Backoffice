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

  .alert {
    background: rgba(79, 209, 197, 0.2);
    border-left: 4px solid #4FD1C5;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 30px;
    color: #0d9488;
  }

  .alert-danger {
    background: rgba(239, 68, 68, 0.2);
    border-left-color: #ef4444;
    color: #dc2626;
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

  .status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-approved {
    background: #d1fae5;
    color: #065f46;
  }

  .status-pending {
    background: #fef3c7;
    color: #92400e;
  }

  .actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
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

  .btn-approve {
    background: #d1fae5;
    color: #065f46;
  }

  .btn-approve:hover {
    background: #a7f3d0;
  }

  .btn-reject {
    background: #fecaca;
    color: #7f1d1d;
  }

  .btn-reject:hover {
    background: #fca5a5;
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

  .comment-content {
    max-width: 400px;
    word-wrap: break-word;
  }

  .comment-author {
    font-weight: 600;
    color: #252C45;
  }

  .filter-section {
    background: rgba(255, 255, 255, 0.95);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
  }

  .filter-section form {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .filter-section select {
    padding: 8px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
  }

  .filter-section button {
    padding: 8px 16px;
    background: #252C45;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }

  .filter-section button:hover {
    background: #1a1f2e;
  }
</style>

<div class="container">
  <div class="header-section">
    <h1>💬 Kelola Komentar</h1>
  </div>

  @if(session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- Filter Section -->
  <div class="filter-section">
    <form method="GET" action="{{ route('comments.index') }}" class="flex gap-2 items-center">
      <label class="font-medium text-slate-700">Filter:</label>
      <select name="status" onchange="this.form.submit()">
        <option value="">Semua Komentar</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
      </select>
    </form>
  </div>

  @if($comments->isEmpty())
    <div class="empty-state">
      💭 Belum ada komentar
    </div>
  @else
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Penulis</th>
            <th>Komentar</th>
            <th>Artikel</th>
            <th>Status</th>
            <th>Spam Score</th>
            <th>Tanggal</th>
            <th style="width: 250px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($comments as $comment)
            <tr>
              <td>
                <div class="comment-author">{{ $comment->author_name }}</div>
                <div class="text-sm text-slate-500">{{ $comment->author_email }}</div>
                <div class="text-xs text-slate-400 mt-1">IP: {{ substr($comment->ip_address, 0, 10) }}...</div>
              </td>
              <td>
                <div class="comment-content">{{ Str::limit($comment->content, 100) }}</div>
                <button
                  type="button"
                  class="text-teal-600 text-sm font-semibold mt-2 hover:text-teal-700"
                  onclick="showCommentDetail('{{ htmlspecialchars($comment->content, ENT_QUOTES) }}', '{{ $comment->author_name }}')"
                >
                  Lihat Selengkapnya
                </button>
              </td>
              <td>
                <a href="{{ route('blog.show', $comment->post->slug) }}" class="text-teal-600 hover:text-teal-700 font-semibold">
                  {{ Str::limit($comment->post->title, 30) }}
                </a>
              </td>
              <td>
                <span class="status-badge {{ $comment->is_approved ? 'status-approved' : 'status-pending' }}">
                  {{ $comment->is_approved ? '✅ Disetujui' : '⏳ Menunggu' }}
                </span>
              </td>
              <td>
                <div class="flex items-center gap-2">
                  <div class="w-12 bg-gray-200 rounded-full h-2">
                    <div class="bg-{{ $comment->spam_score > 70 ? 'red-500' : ($comment->spam_score > 40 ? 'yellow-500' : 'green-500') }} h-2 rounded-full" style="width: {{ $comment->spam_score }}%"></div>
                  </div>
                  <span class="text-sm font-bold">{{ $comment->spam_score }}</span>
                  <span class="text-xs px-2 py-1 rounded {{
                    $comment->spam_status === 'spam' ? 'bg-red-100 text-red-700' :
                    ($comment->spam_status === 'suspicious' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')
                  }}">
                    {{ ucfirst($comment->spam_status) }}
                  </span>
                </div>
              </td>
              <td class="text-slate-500">{{ $comment->created_at->format('d M Y H:i') }}</td>
              <td>
                <div class="actions">
                  @if(!$comment->is_approved)
                    <form method="POST" action="{{ route('comments.approve', $comment->id) }}" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn-action btn-approve" title="Setujui">✓ Setujui</button>
                    </form>
                  @else
                    <form method="POST" action="{{ route('comments.reject', $comment->id) }}" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn-action btn-reject" title="Tolak">✗ Tolak</button>
                    </form>
                  @endif
                  @if(!$comment->is_flagged)
                    <button
                      type="button"
                      class="btn-action btn-delete"
                      onclick="showFlagModal({{ $comment->id }}, '{{ $comment->author_name }}')"
                      title="Tandai Spam"
                    >
                      🚩 Flag
                    </button>
                  @endif
                  <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" style="display: inline;" onsubmit="return confirm('Hapus komentar ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" title="Hapus">🗑️ Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($comments->hasPages())
      <div style="display: flex; justify-content: center; margin-top: 30px;">
        {{ $comments->links() }}
      </div>
    @endif
  @endif
</div>

<!-- Modal for comment detail -->
<div id="commentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
  <div style="background: white; padding: 30px; border-radius: 12px; max-width: 600px; width: 90%;">
    <h2 id="modalAuthor" style="margin: 0 0 15px 0; color: #252C45;"></h2>
    <div id="modalContent" style="color: #555; line-height: 1.6; margin-bottom: 20px;"></div>
    <button onclick="closeCommentModal()" style="padding: 10px 20px; background: #252C45; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Tutup</button>
  </div>
</div>

<!-- Modal for flagging comment -->
<div id="flagModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
  <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
    <h2 id="flagModalTitle" style="margin: 0 0 15px 0; color: #252C45;"></h2>
    <form method="POST" id="flagForm">
      @csrf
      <div style="margin-bottom: 15px;">
        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #252C45;">Alasan Flagging:</label>
        <textarea name="flag_reason" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-family: Arial; min-height: 80px;" required placeholder="Jelaskan mengapa komentar ini ditandai sebagai spam..."></textarea>
      </div>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button type="button" onclick="closeFlagModal()" style="padding: 10px 20px; background: #f0f4f8; color: #252C45; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
        <button type="submit" style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">🚩 Tandai Spam</button>
      </div>
    </form>
  </div>
</div>

<script>
  function showCommentDetail(content, author) {
    document.getElementById('modalAuthor').textContent = 'Komentar dari: ' + author;
    document.getElementById('modalContent').textContent = content;
    document.getElementById('commentModal').style.display = 'flex';
  }

  function closeCommentModal() {
    document.getElementById('commentModal').style.display = 'none';
  }

  function showFlagModal(commentId, author) {
    document.getElementById('flagModalTitle').textContent = '🚩 Tandai Spam - ' + author;
    document.getElementById('flagForm').action = '/admin/comments/' + commentId + '/flag';
    document.getElementById('flagModal').style.display = 'flex';
  }

  function closeFlagModal() {
    document.getElementById('flagModal').style.display = 'none';
  }


  // Close modal when clicking outside
  document.getElementById('commentModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeCommentModal();
    }
  });
</script>

@endsection
