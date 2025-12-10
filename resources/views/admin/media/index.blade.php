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

  .btn-upload {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: #252C45;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 15px;
  }

  .btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(37, 44, 69, 0.4);
  }

  .alert {
    background: rgba(79, 209, 197, 0.2);
    border-left: 4px solid #4FD1C5;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 30px;
    color: #0d9488;
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

  .btn-copy {
    background: #e3f2fd;
    color: #1976d2;
  }

  .btn-copy:hover {
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

  .filename {
    font-weight: 600;
    color: #252C45;
  }

  .file-meta {
    font-size: 12px;
    color: #999;
  }

  .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
  }

  .modal.active {
    display: flex;
  }

  .modal-content {
    background: white;
    border-radius: 12px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  }

  .modal-header {
    padding: 20px;
    background: #252C45;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 12px 12px 0 0;
  }

  .modal-header h2 {
    margin: 0;
    font-size: 18px;
  }

  .modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
  }

  .modal-body {
    padding: 20px;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .form-group label {
    display: block;
    color: #252C45;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 6px;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
  }

  .modal-actions {
    display: flex;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid #f0f0f0;
  }

  .btn-save {
    flex: 1;
    padding: 10px 16px;
    background: #252C45;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-save:hover {
    background: #1a1f2e;
  }

  .btn-cancel {
    flex: 1;
    padding: 10px 16px;
    background: #f0f4f8;
    color: #252C45;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-cancel:hover {
    background: #e0e8f0;
  }
</style>

<div class="container">
  <div class="header-section">
    <h1>📁 Media Library</h1>
    <button onclick="document.getElementById('fileInput').click()" class="btn-upload">
      ⬆️ Upload File
    </button>
  </div>

  <!-- Upload Form (Hidden) -->
  <form id="uploadForm" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="file" id="fileInput" name="media" accept="image/*" onchange="showUploadModal(event)">
  </form>

  @if(session('success'))
    <div class="alert">✅ {{ session('success') }}</div>
  @endif

  @if(isset($medias) && $medias->isNotEmpty())
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th style="width: 60px;">Foto</th>
            <th>Filename</th>
            <th>Ukuran</th>
            <th>Tanggal</th>
            <th style="width: 160px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($medias as $media)
            <tr>
              <td>
                @if($media->path)
                  <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->filename }}" class="img-thumb">
                @else
                  <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 24px;">🖼️</div>
                @endif
              </td>
              <td>
                <div class="filename">{{ $media->filename }}</div>
              </td>
              <td class="file-meta">{{ round($media->size / 1024, 1) }} KB</td>
              <td class="file-meta">{{ $media->created_at->format('d M Y') }}</td>
              <td>
                <div class="actions">
                  <button type="button" class="btn-action btn-copy" onclick="copyToClipboard('{{ asset('storage/' . $media->path) }}')">Copy</button>
                  <button type="button" class="btn-action btn-edit" onclick="openEditModal({{ $media->id }})">Edit</button>
                  <form method="POST" action="{{ route('media.destroy', $media->id) }}" style="display: inline;" onsubmit="return confirm('Hapus media ini?');">
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

    @if($medias->hasPages())
      <div style="display: flex; justify-content: center; margin-top: 30px;">
        {{ $medias->links() }}
      </div>
    @endif
  @else
    <div class="empty-state">
      📭 Belum ada media
    </div>
  @endif
</div>

<!-- Upload Metadata Modal -->
<div id="uploadModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>📤 Upload Media</h2>
      <button class="modal-close" onclick="closeUploadModal()">✕</button>
    </div>
    <div class="modal-body">
      <div id="filePreviewContainer" style="margin-bottom: 20px; text-align: center;">
        <img id="filePreview" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; display: none;">
        <p id="fileName" style="margin-top: 10px; color: #666;"></p>
      </div>
      <div class="form-group">
        <label>Alt Text (untuk aksesibilitas)</label>
        <input type="text" id="uploadAltText" placeholder="Misal: Foto produk kemeja biru">
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea id="uploadDescription" placeholder="Misal: Kemeja biru premium, ukuran M-XXL" style="resize: vertical; min-height: 80px;"></textarea>
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn-save" id="uploadButton" onclick="handleFileUpload()">🚀 Upload</button>
      <button class="btn-cancel" onclick="closeUploadModal()">Batal</button>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 id="modalTitle">✏️ Edit Media</h2>
      <button class="modal-close" onclick="closeEditModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Filename</label>
        <input type="text" id="editFilename" readonly style="background: #f5f5f5; cursor: not-allowed;">
      </div>
      <div class="form-group">
        <label>Alt Text</label>
        <input type="text" id="editAltText" placeholder="Masukkan alt text untuk aksesibilitas">
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea id="editDescription" placeholder="Masukkan deskripsi media" style="resize: vertical; min-height: 80px;"></textarea>
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn-save" onclick="saveEditMedia()">💾 Simpan</button>
      <button class="btn-cancel" onclick="closeEditModal()">Batal</button>
    </div>
  </div>
</div>

<script>
let selectedFile = null;
let currentEditMediaId = null;

// Upload modal functions
function showUploadModal(event) {
  const fileInput = document.getElementById('fileInput');
  const file = fileInput.files[0];

  if (!file) {
    alert('Pilih file terlebih dahulu');
    return;
  }

  selectedFile = file;

  // Tampilkan preview untuk image
  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('filePreview').src = e.target.result;
      document.getElementById('filePreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  } else {
    document.getElementById('filePreview').style.display = 'none';
  }

  document.getElementById('fileName').textContent = file.name + ' (' + formatFileSize(file.size) + ')';

  // Buka modal
  document.getElementById('uploadModal').style.display = 'flex';
}

function closeUploadModal() {
  document.getElementById('uploadModal').style.display = 'none';
  selectedFile = null;
  document.getElementById('fileInput').value = '';
  document.getElementById('uploadAltText').value = '';
  document.getElementById('uploadDescription').value = '';
}

// Edit modal functions
function openEditModal(mediaId) {
  currentEditMediaId = mediaId;

  // Fetch data dari server
  fetch(`/admin/media/${mediaId}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('editFilename').value = data.filename;
      document.getElementById('editAltText').value = data.alt_text || '';
      document.getElementById('editDescription').value = data.description || '';
      document.getElementById('editModal').style.display = 'flex';
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal memuat data media');
    });
}

function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
  currentEditMediaId = null;
}

function saveEditMedia() {
  if (!currentEditMediaId) {
    alert('Media ID tidak ditemukan');
    return;
  }

  const altText = document.getElementById('editAltText').value;
  const description = document.getElementById('editDescription').value;

  const formData = new FormData();
  formData.append('alt_text', altText);
  formData.append('description', description);
  formData.append('_method', 'PUT');

  fetch(`/admin/media/${currentEditMediaId}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Accept': 'application/json',
    },
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        alert('✅ Media berhasil diperbarui');
        closeEditModal();
        location.reload();
      } else {
        alert('❌ Gagal: ' + (data.message || 'Tidak diketahui'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('❌ Terjadi kesalahan saat menyimpan');
    });
}

// Upload file handler
function handleFileUpload() {
  if (!selectedFile) {
    alert('Pilih file terlebih dahulu');
    return;
  }

  const formData = new FormData();
  formData.append('media', selectedFile);
  formData.append('alt_text', document.getElementById('uploadAltText').value);
  formData.append('description', document.getElementById('uploadDescription').value);

  const uploadBtn = document.getElementById('uploadButton');
  uploadBtn.disabled = true;
  uploadBtn.textContent = '⏳ Uploading...';

  fetch('{{ route("media.store") }}', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('✅ Media berhasil diupload');
        closeUploadModal();
        location.reload();
      } else {
        alert('❌ Gagal: ' + (data.message || 'Tidak diketahui'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('❌ Terjadi kesalahan saat upload');
    })
    .finally(() => {
      uploadBtn.disabled = false;
      uploadBtn.textContent = '🚀 Upload';
    });
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function copyToClipboard(url) {
  navigator.clipboard.writeText(url);
  alert('✅ URL disalin ke clipboard');
}

// Close modals when clicking outside
window.onclick = function(event) {
  const uploadModal = document.getElementById('uploadModal');
  const editModal = document.getElementById('editModal');

  if (event.target === uploadModal) {
    closeUploadModal();
  }
  if (event.target === editModal) {
    closeEditModal();
  }
}
</script>

@endsection
