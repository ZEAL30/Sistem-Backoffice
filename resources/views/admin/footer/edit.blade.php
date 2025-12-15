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

  .header-section h1 {
    margin: 0;
    font-size: 32px;
    color: #1a202c;
    font-weight: 700;
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

  .preview-area h2 {
    margin: 0 0 20px 0;
    font-size: 20px;
    color: #1a202c;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 10px;
  }

  .form-area {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-height: 80vh;
    overflow-y: auto;
  }

  .form-area h2 {
    margin: 0 0 20px 0;
    font-size: 20px;
    color: #1a202c;
  }

  .section-item {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    cursor: move;
    transition: all 0.2s;
  }

  .section-item:hover {
    border-color: #667eea;
    background: #f3f4f6;
  }

  .section-item.active {
    border-color: #667eea;
    background: #eef2ff;
  }

  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }

  .section-type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #e0e7ff;
    color: #4338ca;
  }

  .btn-remove {
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 4px 8px;
    cursor: pointer;
    font-size: 12px;
  }

  .btn-remove:hover {
    background: #dc2626;
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
  .form-group textarea,
  .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
  }

  .form-group input:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .form-group textarea {
    min-height: 100px;
    resize: vertical;
  }

  .menu-item {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
  }

  .menu-item input {
    flex: 1;
  }

  .btn-add-menu {
    background: #10b981;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    cursor: pointer;
    font-size: 14px;
    margin-top: 10px;
  }

  .btn-add-menu:hover {
    background: #059669;
  }

  .btn-add-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    cursor: pointer;
    font-weight: 600;
    width: 100%;
    margin-top: 20px;
  }

  .btn-add-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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

  .preview-footer {
    background: #f3f4f6;
    padding: 40px 20px;
    border-radius: 8px;
  }

  .preview-footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
  }

  .preview-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
  }

  .preview-section h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #1a202c;
  }

  .preview-section p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
  }

  .preview-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .preview-menu li {
    margin-bottom: 8px;
  }

  .preview-menu a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s;
  }

  .preview-menu a:hover {
    color: #006666;
  }

  .preview-contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
  }

  .preview-contact-item .icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }

  .preview-copyright {
    text-align: center;
    color: #64748b;
    font-size: 14px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
  }

  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
  }

  .alert-success {
    background: rgba(74, 222, 128, 0.2);
    border-left: 4px solid #4ade80;
    color: #166534;
  }

  .alert-error {
    background: rgba(239, 68, 68, 0.2);
    border-left: 4px solid #ef4444;
    color: #991b1b;
  }
</style>

<div class="container">
  <div class="header-section">
    <div>
      <h1>🎨 Visual Footer Builder</h1>
      <p style="margin: 8px 0 0 0; color: #64748b;">Edit dan atur konten footer secara visual</p>
    </div>
    <a href="{{ route('footer.index') }}" style="color: #64748b; text-decoration: none;">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-error">
      {{ session('error') }}
    </div>
  @endif

  <form id="footerForm" method="POST" action="{{ route('footer.update') }}">
    @csrf
    @method('PUT')

    <div class="builder-container">
      <!-- Preview Area -->
      <div class="preview-area">
        <h2>📱 Preview Footer</h2>
        <div class="preview-footer" id="previewFooter">
          <!-- Preview akan diupdate via JavaScript -->
        </div>
      </div>

      <!-- Form Area -->
      <div class="form-area">
        <h2>⚙️ Edit Sections</h2>

        <div id="sectionsContainer">
          @foreach($sections as $index => $section)
            @include('admin.footer.partials.section-form', ['section' => $section, 'index' => $index])
          @endforeach
        </div>

        <button type="button" class="btn-add-section" onclick="addNewSection()">
          <i class="fas fa-plus"></i> Tambah Section Baru
        </button>

        <button type="submit" class="btn-save">
          <i class="fas fa-save"></i> Simpan Perubahan
        </button>
      </div>
    </div>
  </form>
</div>

<script>
let sectionIndex = {{ $sections->count() }};
let selectedSectionIndex = null;

// Update preview saat form berubah
document.addEventListener('DOMContentLoaded', function() {
  updatePreview();

  // Event listeners untuk semua input
  document.querySelectorAll('#sectionsContainer input, #sectionsContainer textarea, #sectionsContainer select').forEach(input => {
    input.addEventListener('input', updatePreview);
    input.addEventListener('change', updatePreview);
  });

  // Click handler untuk section items
  document.querySelectorAll('.section-item').forEach(item => {
    item.addEventListener('click', function() {
      const index = this.dataset.index;
      selectSection(index);
    });
  });
});

function selectSection(index) {
  selectedSectionIndex = index;
  document.querySelectorAll('.section-item').forEach(item => {
    item.classList.remove('active');
  });
  document.querySelector(`[data-index="${index}"]`)?.classList.add('active');

  // Scroll to form
  const formElement = document.querySelector(`[data-form-index="${index}"]`);
  if (formElement) {
    formElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
}

function addNewSection() {
  const container = document.getElementById('sectionsContainer');
  const newSection = createSectionForm(sectionIndex, {
    type: 'column',
    title: '',
    content: '',
    data: null,
    order: sectionIndex + 1,
    is_active: true
  });

  container.insertAdjacentHTML('beforeend', newSection);
  sectionIndex++;
  updatePreview();

  // Scroll to new section
  const newElement = container.lastElementChild;
  newElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
  selectSection(sectionIndex - 1);
}

function removeSection(index) {
  if (confirm('Yakin ingin menghapus section ini?')) {
    const sectionElement = document.querySelector(`[data-index="${index}"]`);
    if (sectionElement) {
      sectionElement.remove();
    }
    updatePreview();
  }
}

function addMenuItem(index) {
  const container = document.getElementById(`menuItems_${index}`);
  const itemIndex = container.children.length;
  const menuItem = `
    <div class="menu-item">
      <input type="text" name="sections[${index}][data][${itemIndex}][label]" placeholder="Label" required>
      <input type="text" name="sections[${index}][data][${itemIndex}][url]" placeholder="URL" required>
      <button type="button" class="btn-remove" onclick="this.parentElement.remove(); updatePreview();">×</button>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', menuItem);
  updatePreview();
}

function updatePreview() {
  const form = document.getElementById('footerForm');
  const formData = new FormData(form);
  const sections = [];

  // Collect all sections
  const sectionElements = document.querySelectorAll('[data-index]');
  sectionElements.forEach((element, idx) => {
    const index = element.dataset.index;
    const type = formData.get(`sections[${index}][type]`) || element.querySelector('[name*="[type]"]')?.value;
    const title = formData.get(`sections[${index}][title]`) || element.querySelector('[name*="[title]"]')?.value || '';
    const content = formData.get(`sections[${index}][content]`) || element.querySelector('[name*="[content]"]')?.value || '';
    const isActive = element.querySelector('[name*="[is_active]"]')?.checked !== false;

    if (type && isActive) {
      let data = null;
      if (type === 'menu') {
        data = [];
        const menuItems = element.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
          const label = item.querySelector('input[placeholder="Label"]')?.value;
          const url = item.querySelector('input[placeholder="URL"]')?.value;
          if (label && url) {
            data.push({ label, url });
          }
        });
      } else if (type === 'contact') {
        data = {
          phone: element.querySelector('[name*="[data][phone]"]')?.value || '',
          email: element.querySelector('[name*="[data][email]"]')?.value || '',
          address: element.querySelector('[name*="[data][address]"]')?.value || ''
        };
      }

      sections.push({ type, title, content, data, order: idx + 1 });
    }
  });

  // Sort by order
  sections.sort((a, b) => a.order - b.order);

  // Render preview
  renderPreview(sections);
}

function renderPreview(sections) {
  const preview = document.getElementById('previewFooter');
  let html = '<div class="preview-footer-content">';

  sections.forEach(section => {
    if (section.type === 'column') {
      html += `
        <div class="preview-section">
          <h3>${section.title || 'Title'}</h3>
          <p>${section.content || 'Content...'}</p>
        </div>
      `;
    } else if (section.type === 'menu') {
      html += `
        <div class="preview-section preview-menu">
          <h3>${section.title || 'Menu'}</h3>
          <ul>
            ${section.data ? section.data.map(item => `<li><a href="${item.url}">${item.label}</a></li>`).join('') : ''}
          </ul>
        </div>
      `;
    } else if (section.type === 'contact') {
      html += `
        <div class="preview-section">
          <h3>${section.title || 'Contact Info'}</h3>
          ${section.data?.phone ? `<div class="preview-contact-item"><div class="icon">📞</div><div><strong>Phone:</strong><br>${section.data.phone}</div></div>` : ''}
          ${section.data?.email ? `<div class="preview-contact-item"><div class="icon">✉️</div><div><strong>Email:</strong><br>${section.data.email}</div></div>` : ''}
          ${section.data?.address ? `<div class="preview-contact-item"><div class="icon">📍</div><div><strong>Alamat</strong><br>${section.data.address.replace(/\n/g, '<br>')}</div></div>` : ''}
        </div>
      `;
    }
  });

  html += '</div>';

  // Add copyright if exists
  const copyrightSection = sections.find(s => s.type === 'copyright');
  if (copyrightSection) {
    html += `<div class="preview-copyright">${copyrightSection.content || ''}</div>`;
  }

  preview.innerHTML = html;
}

function createSectionForm(index, section = {}) {
  const type = section.type || 'column';
  let formHtml = `
    <div class="section-item" data-index="${index}" data-form-index="${index}">
      <div class="section-header">
        <span class="section-type-badge">${type}</span>
        <button type="button" class="btn-remove" onclick="removeSection(${index})">Hapus</button>
      </div>

      <input type="hidden" name="sections[${index}][id]" value="${section.id || ''}">
      <input type="hidden" name="sections[${index}][order]" value="${section.order || index + 1}">

      <div class="form-group">
        <label>Type</label>
        <select name="sections[${index}][type]" onchange="changeSectionType(${index}, this.value)">
          <option value="column" ${type === 'column' ? 'selected' : ''}>Column (Text)</option>
          <option value="menu" ${type === 'menu' ? 'selected' : ''}>Menu</option>
          <option value="contact" ${type === 'contact' ? 'selected' : ''}>Contact Info</option>
          <option value="copyright" ${type === 'copyright' ? 'selected' : ''}>Copyright</option>
        </select>
      </div>

      <div class="form-group">
        <label>Title</label>
        <input type="text" name="sections[${index}][title]" value="${section.title || ''}" placeholder="Section Title">
      </div>
  `;

  if (type === 'column' || type === 'copyright') {
    formHtml += `
      <div class="form-group">
        <label>Content</label>
        <textarea name="sections[${index}][content]" placeholder="Content...">${section.content || ''}</textarea>
      </div>
    `;
  }

  if (type === 'menu') {
    formHtml += `
      <div class="form-group">
        <label>Menu Items</label>
        <div id="menuItems_${index}">
          ${section.data ? section.data.map((item, idx) => `
            <div class="menu-item">
              <input type="text" name="sections[${index}][data][${idx}][label]" value="${item.label || ''}" placeholder="Label" required>
              <input type="text" name="sections[${index}][data][${idx}][url]" value="${item.url || ''}" placeholder="URL" required>
              <button type="button" class="btn-remove" onclick="this.parentElement.remove(); updatePreview();">×</button>
            </div>
          `).join('') : ''}
        </div>
        <button type="button" class="btn-add-menu" onclick="addMenuItem(${index})">+ Tambah Menu Item</button>
      </div>
    `;
  }

  if (type === 'contact') {
    formHtml += `
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="sections[${index}][data][phone]" value="${section.data?.phone || ''}" placeholder="+6285161728383">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="sections[${index}][data][email]" value="${section.data?.email || ''}" placeholder="gecgroup@gmail.com">
      </div>
      <div class="form-group">
        <label>Address</label>
        <textarea name="sections[${index}][data][address]" placeholder="Address...">${section.data?.address || ''}</textarea>
      </div>
    `;
  }

  formHtml += `
      <div class="form-group">
        <label>
          <input type="checkbox" name="sections[${index}][is_active]" value="1" ${section.is_active !== false ? 'checked' : ''}>
          Active
        </label>
      </div>
    </div>
  `;

  return formHtml;
}

function changeSectionType(index, newType) {
  const sectionElement = document.querySelector(`[data-index="${index}"]`);
  const section = {
    type: newType,
    title: sectionElement.querySelector('[name*="[title]"]')?.value || '',
    content: sectionElement.querySelector('[name*="[content]"]')?.value || '',
    data: null,
    order: sectionElement.querySelector('[name*="[order]"]')?.value || index + 1,
    is_active: sectionElement.querySelector('[name*="[is_active]"]')?.checked
  };

  sectionElement.outerHTML = createSectionForm(index, section);

  // Re-attach event listeners
  document.querySelectorAll(`[data-index="${index}"] input, [data-index="${index}"] textarea, [data-index="${index}"] select`).forEach(input => {
    input.addEventListener('input', updatePreview);
    input.addEventListener('change', updatePreview);
  });

  updatePreview();
}
</script>
@endsection

