<div class="section-item" data-index="{{ $index }}" data-form-index="{{ $index }}">
    <div class="section-header">
        <span class="section-type-badge">{{ ucfirst($section->type) }}</span>
        <button type="button" class="btn-remove" onclick="removeSection({{ $index }})">Hapus</button>
    </div>

    <input type="hidden" name="sections[{{ $index }}][id]" value="{{ $section->id ?? '' }}">
    <input type="hidden" name="sections[{{ $index }}][order]" value="{{ $section->order ?? $index + 1 }}">

    <div class="form-group">
        <label>Type</label>
        <select name="sections[{{ $index }}][type]" onchange="changeSectionType({{ $index }}, this.value)">
            <option value="column" {{ $section->type === 'column' ? 'selected' : '' }}>Column (Text)</option>
            <option value="menu" {{ $section->type === 'menu' ? 'selected' : '' }}>Menu</option>
            <option value="contact" {{ $section->type === 'contact' ? 'selected' : '' }}>Contact Info</option>
            <option value="copyright" {{ $section->type === 'copyright' ? 'selected' : '' }}>Copyright</option>
        </select>
    </div>

    <div class="form-group">
        <label>Title</label>
        <input type="text" name="sections[{{ $index }}][title]" value="{{ $section->title ?? '' }}" placeholder="Section Title">
    </div>

    @if($section->type === 'column' || $section->type === 'copyright')
    <div class="form-group">
        <label>Content</label>
        <textarea name="sections[{{ $index }}][content]" placeholder="Content...">{{ $section->content ?? '' }}</textarea>
    </div>
    @endif

    @if($section->type === 'menu')
    <div class="form-group">
        <label>Menu Items</label>
        <div id="menuItems_{{ $index }}">
            @if($section->data && is_array($section->data))
                @foreach($section->data as $idx => $item)
                <div class="menu-item">
                    <input type="text" name="sections[{{ $index }}][data][{{ $idx }}][label]" value="{{ $item['label'] ?? '' }}" placeholder="Label" required>
                    <input type="text" name="sections[{{ $index }}][data][{{ $idx }}][url]" value="{{ $item['url'] ?? '' }}" placeholder="URL" required>
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove(); updatePreview();">×</button>
                </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn-add-menu" onclick="addMenuItem({{ $index }})">+ Tambah Menu Item</button>
    </div>
    @endif

    @if($section->type === 'contact')
    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="sections[{{ $index }}][data][phone]" value="{{ $section->data['phone'] ?? '' }}" placeholder="+6285161728383">
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="sections[{{ $index }}][data][email]" value="{{ $section->data['email'] ?? '' }}" placeholder="gecgroup@gmail.com">
    </div>
    <div class="form-group">
        <label>Address</label>
        <textarea name="sections[{{ $index }}][data][address]" placeholder="Address...">{{ $section->data['address'] ?? '' }}</textarea>
    </div>
    @endif

    <div class="form-group">
        <label>
            <input type="checkbox" name="sections[{{ $index }}][is_active]" value="1" {{ $section->is_active !== false ? 'checked' : '' }}>
            Active
        </label>
    </div>
</div>

