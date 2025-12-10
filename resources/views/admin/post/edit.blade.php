@extends('admin.layout.app')

@section('content')

<div style="margin-bottom: 24px;">
    <a href="{{ route('post.index') }}" style="display:inline-flex; align-items:center; gap:8px; color:#252C45; text-decoration:none; font-weight:500;">
        ← Kembali ke Artikel
    </a>
</div>

<form method="POST" action="{{ route('post.update', $post->slug) }}" enctype="multipart/form-data" style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:30px;">
    @csrf
    @method('PUT')

    <!-- LEFT COLUMN: FORM INPUT -->
    <div>
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h2 style="color:#252C45; font-size:20px; margin:0 0 20px 0; font-weight:700;">Edit Artikel</h2>

            @if ($errors->any())
                <div style="background:#fee; border:1px solid #fcc; color:#a00; padding:12px; border-radius:8px; margin-bottom:16px; font-size:14px;">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                <!-- Judul -->
                <div style="margin-bottom:18px;">
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Judul <span style="color:red;">*</span></label>
                    <input type="text" name="title" id="post-title" value="{{ old('title', $post->title) }}"
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; font-family:inherit;"
                        placeholder="Masukkan judul artikel"
                        required
                        onchange="updateSlug()">
                    @error('title') <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p> @enderror
                </div>

                <!-- Excerpt -->
                <div style="margin-bottom:18px;">
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Ringkasan</label>
                    <textarea name="excerpt" style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; font-family:inherit; resize:vertical; min-height:80px;"
                        placeholder="Masukkan ringkasan artikel">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt') <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p> @enderror
                </div>

                <!-- Content -->
                <div style="margin-bottom:18px;">
                    <label style="display:block; color:#252C45; font-weight:600; font-size:14px; margin-bottom:6px;">Isi Artikel</label>
                    <textarea name="content" style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; font-family:inherit; resize:vertical; min-height:150px;"
                        placeholder="Masukkan isi artikel">{{ old('content', $post->content) }}</textarea>
                    @error('content') <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div style="margin-bottom:20px;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; color:#252C45; font-weight:600; font-size:14px;">
                        <input type="checkbox" id="status_checkbox" onchange="setStatus(this)" {{ old('status', $post->status) === 'published' ? 'checked' : '' }}>
                        <span>Publikasikan Artikel</span>
                    </label>
                    <input type="hidden" name="status" id="status" value="{{ old('status', $post->status) }}">
                </div>

                <!-- Hidden Slug Input -->
                <input type="hidden" name="slug" id="slug-input" value="{{ $post->slug }}">
                <input type="hidden" name="type" value="post">

                <!-- Submit Button -->
                <button type="submit"
                    style="width:100%; padding:11px 16px; background:#252C45; color:white; border:none; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; transition:all 0.18s;">
                    Simpan Perubahan
                </button>
        </div>
    </div>

    <!-- RIGHT COLUMN: MEDIA + SLUG -->
    <div>
        <!-- Featured Image / Media -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04); margin-bottom:20px;">
            <h3 style="color:#252C45; font-size:16px; margin:0 0 16px 0; font-weight:700;">Foto Artikel</h3>

            <!-- Image Preview -->
            <div id="imagePreview" style="margin-bottom:12px; min-height:140px; background:#f5f7fa; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#999;">
                @if ($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="max-width:140px; max-height:140px; border-radius:8px;">
                @else
                    <span style="font-size:14px;">Tidak ada foto</span>
                @endif
            </div>

            <!-- Upload Button -->
            <div style="display:flex; gap:8px;">
                <button type="button" onclick="document.getElementById('directUploadInput').click()"
                    style="flex:1; padding:10px 12px; background:#4FD1C5; color:#052027; border:none; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; transition:all 0.18s;">
                    ⬆️ Upload
                </button>
                <button type="button" onclick="openMediaPicker('featured_image_id', 'imagePreview')"
                    style="flex:1; padding:10px 12px; background:#f0f4f8; color:#252C45; border:1px solid #ddd; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; transition:all 0.18s;">
                    📷 Gallery
                </button>
            </div>

            <input type="hidden" name="featured_image" id="featured_image_id" value="{{ $post->featured_image }}">
            <input type="file" id="directUploadInput" style="display:none;" accept="image/png,image/jpeg,image/jpg,image/webp,.png,.jpg,.jpeg,.webp" onchange="handleDirectUpload(event)">
        </div>

        <!-- Kategori -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04); margin-bottom:20px;">
            <h3 style="color:#252C45; font-size:16px; margin:0 0 16px 0; font-weight:700;">Kategori Artikel</h3>
            <div style="max-height:150px; overflow-y:auto; padding:8px; background:#f9f9f9; border:1px solid #ddd; border-radius:8px;">
                @foreach ($categories as $category)
                    <label style="display:flex; align-items:center; gap:8px; margin-bottom:8px; cursor:pointer; color:#666;">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategories ?? []) ? 'checked' : '' }}>
                        <span style="font-size:14px;">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('categories') <p style="color:red; font-size:12px; margin:4px 0 0 0;">{{ $message }}</p> @enderror
        </div>

        <!-- Slug -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow: 0 6px 20px rgba(37,44,69,0.04);">
            <h3 style="color:#252C45; font-size:16px; margin:0 0 12px 0; font-weight:700;">URL Slug</h3>
            <p style="color:#666; font-size:13px; margin:0 0 12px 0;">Jika kosong, akan otomatis dari judul</p>
            <input type="text" id="slug-display" value="{{ $post->slug }}"
                style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; font-family:'Courier New', monospace;"
                onchange="document.getElementById('slug-input').value = this.value;">
        </div>
    </div>


    </form>

<!-- Media Picker Modal -->
<div id="mediaPickerModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:1000; overflow:auto;">
    <div style="background:white; max-width:600px; margin:40px auto; border-radius:12px; overflow:hidden;">
        <div style="padding:20px; background:#252C45; color:white; display:flex; justify-content:space-between; align-items:center;">
            <h2 style="margin:0; font-size:18px;">📷 Pilih Foto</h2>
            <button type="button" onclick="closeMediaPicker()" style="background:none; border:none; color:white; font-size:24px; cursor:pointer;">✕</button>
        </div>
        <div id="mediaPickerContent" style="padding:20px; display:grid; grid-template-columns:repeat(auto-fill, minmax(100px, 1fr)); gap:12px; max-height:400px; overflow-y:auto;">
            <p style="grid-column:1/-1; color:#666;">Memuat...</p>
        </div>
    </div>
</div>

<script>
let selectedMediaField = null;

function setStatus(checkbox) {
    const statusHidden = document.getElementById('status');
    statusHidden.value = checkbox.checked ? 'published' : 'draft';
}

// Override form submission to include right column data
document.querySelector('form[method="POST"]').addEventListener('submit', function(e) {
    // Update hidden fields before submit
    document.getElementById('slug-input').value = document.getElementById('slug-display').value;

    // Update status from checkbox
    const statusCheckbox = document.getElementById('status_checkbox');
    if (statusCheckbox) {
        const statusHidden = document.getElementById('status');
        statusHidden.value = statusCheckbox.checked ? 'published' : 'draft';
    }
});

function updateSlug() {
    const name = document.getElementById('post-title').value;
    if (name) {
        const slug = name
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug-display').value = slug;
        document.getElementById('slug-input').value = slug;
    }
}

async function openMediaPicker(fieldId, displayId) {
    selectedMediaField = { fieldId, displayId };
    document.getElementById('mediaPickerModal').style.display = 'block';

    try {
        const response = await fetch('{{ route("media.all") }}');
        const medias = await response.json();

        const content = document.getElementById('mediaPickerContent');
        if (medias.length === 0) {
            content.innerHTML = '<p style="grid-column:1/-1; color:#999; text-align:center;">Tidak ada media</p>';
            return;
        }

        content.innerHTML = medias.map(media => {
            const imgUrl = '{{ asset("storage") }}/' + media.path;
            return `
                <div style="cursor:pointer; position:relative;" onclick="selectMedia('${media.id}', '${media.path}', '${media.filename}')">
                    <img src="${imgUrl}" alt="${media.filename}"
                        style="width:100%; height:100px; object-fit:cover; border-radius:8px; display:block;">
                    <p style="font-size:11px; color:#666; margin:4px 0 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${media.filename}</p>
                </div>
            `;
        }).join('');
    } catch(error) {
        console.error('Error loading media:', error);
    }
}

function selectMedia(id, path, filename) {
    if (!selectedMediaField) return;

    document.getElementById(selectedMediaField.fieldId).value = path;

    const preview = document.getElementById(selectedMediaField.displayId);
    const baseUrl = '{{ asset("storage") }}';
    const fullPath = baseUrl + '/' + path;

    preview.innerHTML = `
        <div style="position:relative; display:inline-block;">
            <img src="${fullPath}" alt="${filename}"
                style="max-width:140px; max-height:140px; border-radius:8px; display:block; object-fit:cover;">
            <button type="button" onclick="clearMedia()"
                style="position:absolute; top:-8px; right:-8px; background:#ff4444; color:white; border:none; border-radius:50%; width:28px; height:28px; cursor:pointer; font-weight:bold; font-size:16px;">✕</button>
        </div>
    `;

    console.log('Image selected:', fullPath);
    closeMediaPicker();
}

function clearMedia() {
    document.getElementById('featured_image_id').value = '';
    document.getElementById('imagePreview').innerHTML = '<span style="font-size:14px; color:#999;">Tidak ada foto</span>';
}

function closeMediaPicker() {
    document.getElementById('mediaPickerModal').style.display = 'none';
}

async function handleDirectUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Show loading state
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '<p style="color:#999; font-size:14px;">Uploading...</p>';

    const formData = new FormData();
    formData.append('media', file);

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                         document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Error: CSRF token tidak ditemukan');
            preview.innerHTML = '<span style="font-size:14px; color:#999;">Tidak ada foto</span>';
            return;
        }

        const response = await fetch('{{ route("media.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Upload response:', data);

        if (data.success && data.media) {
            // Set up selectedMediaField to point to the right elements
            selectedMediaField = { fieldId: 'featured_image_id', displayId: 'imagePreview' };
            selectMedia(data.media.id, data.media.path, data.media.filename);
            alert('✅ Foto berhasil diupload!');
        } else {
            alert('Upload gagal: ' + (data.message || 'Unknown error'));
            preview.innerHTML = '<span style="font-size:14px; color:#999;">Tidak ada foto</span>';
        }
    } catch(error) {
        console.error('Upload error:', error);
        alert('Gagal upload foto: ' + error.message);
        preview.innerHTML = '<span style="font-size:14px; color:#999;">Tidak ada foto</span>';
    }

    event.target.value = '';
}
</script>

@endsection


