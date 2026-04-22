<?php
// views/admin/menu/form.php — Add / Edit menu item
$isEdit = !empty($item);
?>
<div class="card" style="max-width:700px;">
    <div class="card-header">
        <h2>
            <i class="fas fa-<?= $isEdit ? 'pen' : 'plus' ?>"></i>
            <?= $isEdit ? 'Sửa món: ' . e($item['name']) : 'Thêm Món mới' ?>
        </h2>
        <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/admin/menu/<?= $isEdit ? 'update' : 'store' ?>"
        enctype="multipart/form-data">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <!-- Giữ lại các tham số filter và pagination khi update -->
            <input type="hidden" name="page" value="<?= $_GET['page'] ?? 1 ?>">
            <input type="hidden" name="service" value="<?= $_GET['service'] ?? '' ?>">
            <input type="hidden" name="category" value="<?= $_GET['category'] ?? '' ?>">
            <input type="hidden" name="status" value="<?= $_GET['status'] ?? '' ?>">
            <input type="hidden" name="search" value="<?= $_GET['search'] ?? '' ?>">
            <!-- Không truyền menu_type từ form vì đây là loại của món, không phải filter -->
            <input type="hidden" name="tag" value="<?= $_GET['tag'] ?? '' ?>">
            <input type="hidden" name="stock_status" value="<?= $_GET['stock_status'] ?? '' ?>">
            <input type="hidden" name="price_range" value="<?= $_GET['price_range'] ?? '' ?>">
        <?php endif; ?>

        <!-- 2-column responsive grid -->
        <div class="form-grid-2">

            <div class="form-group">
                <label class="form-label">Danh mục <span style="color:var(--danger)">*</span></label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($isEdit && $item['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Phân loại Menu <span style="color:var(--danger)">*</span></label>
                <select name="menu_type" class="form-control" required>
                    <?php 
                    $currentType = $isEdit && isset($item['menu_type']) ? $item['menu_type'] : 'asia';
                    foreach ($menuTypes as $type): 
                    ?>
                        <option value="<?= e($type['type_key']) ?>" <?= $currentType === $type['type_key'] ? 'selected' : '' ?>>
                            <?= e($type['name']) ?> <?= $type['name_en'] ? '(' . e($type['name_en']) . ')' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-hint"><a href="<?= BASE_URL ?>/admin/menu-types" target="_blank">Quản lý các loại menu</a></p>
            </div>

            <div class="form-group">
                <label class="form-label">Phục vụ cho</label>
                <select name="service_type" class="form-control">
                    <option value="both" <?= ($isEdit && isset($item['service_type']) && $item['service_type'] === 'both') ? 'selected' : '' ?>>Cả hai (Nhà hàng & Lưu trú)</option>
                    <option value="restaurant" <?= ($isEdit && isset($item['service_type']) && $item['service_type'] === 'restaurant') ? 'selected' : '' ?>>Chỉ Nhà hàng</option>
                    <option value="room_service" <?= ($isEdit && isset($item['service_type']) && $item['service_type'] === 'room_service') ? 'selected' : '' ?>>Chỉ Phòng lưu trú (Room Service)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tên món (VI) <span style="color:var(--danger)">*</span></label>
                <input type="text" name="name" class="form-control" required
                    value="<?= $isEdit ? e($item['name']) : '' ?>" placeholder="VD: Bò lúc lắc">
            </div>

            <div class="form-group">
                <label class="form-label">Tên món (EN)</label>
                <input type="text" name="name_en" class="form-control"
                    value="<?= $isEdit ? e($item['name_en'] ?? '') : '' ?>" placeholder="VD: Shaken Beef">
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="2"
                    placeholder="Mô tả ngắn về món..."><?= $isEdit ? e($item['description'] ?? '') : '' ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Giá (VND) <span style="color:var(--danger)">*</span></label>
                <div style="position: relative;">
                    <input type="text" name="price" id="price-input" class="form-control" required 
                        maxlength="10" pattern="^[0-9]{1,10}$" inputmode="numeric" autocomplete="off"
                        value="<?= $isEdit ? $item['price'] : '' ?>" placeholder="VD: 198552">
                    <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--gold); font-weight: 600; pointer-events: none;">VND</span>
                </div>
                <p class="form-hint">Nhập số nguyên tối đa 10 ký tự (VD: 99999, 198552, 1234567890).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control" min="0"
                    value="<?= $isEdit ? $item['sort_order'] : '0' ?>" <?= !$isEdit ? 'readonly style="background:#f1f5f9;cursor:not-allowed;"' : '' ?>>
                <?php if (!$isEdit): ?>
                <p class="form-hint">Thứ tự sẽ tự động gán khi lưu (số lớn nhất + 1).</p>
                <?php endif; ?>
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Tags hiển thị</label>
                <div style="display:flex;gap:.85rem;flex-wrap:wrap;padding:.5rem 0;">
                    <?php
                    $allTags = ['bestseller', 'new', 'spicy', 'vegetarian', 'recommended'];
                    $rawTags = $isEdit ? array_map('trim', explode(',', $item['tags'] ?? '')) : [];
                    $activeTags = array_filter($rawTags, fn($t) => $t && strpos($t, 'opt:') !== 0);
                    foreach ($allTags as $tag):
                        ?>
                        <label style="display:flex;align-items:center;gap:.4rem;font-size:.875rem;cursor:pointer;">
                            <input type="checkbox" name="tags[]" value="<?= $tag ?>" <?= in_array($tag, $activeTags) ? 'checked' : '' ?>>
                            <?= ucfirst($tag) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════
                 CHIP BUILDER — Tùy chọn ghi chú nhanh (VI)
            ═══════════════════════════════════════════════════ -->
            <div class="form-group col-span-2">
                <label class="form-label">
                    <i class="fas fa-tags me-1" style="color:var(--gold);"></i>
                    Tùy chọn ghi chú nhanh (Tiếng Việt)
                </label>
                <p class="form-hint" style="margin-bottom:.6rem;">
                    Nhập từng tùy chọn, nhấn <kbd>Enter</kbd> hoặc <kbd>,</kbd> để thêm. Click <strong>×</strong> để xóa chip.
                </p>
                <!-- Hidden input lưu giá trị CSV -->
                <input type="hidden" name="note_options" id="noteOptsCsvVI" 
                       value="<?= $isEdit ? e($item['note_options'] ?? '') : '' ?>">
                <!-- Chip display area + input -->
                <div id="optsBuilderVI" class="chip-builder">
                    <div id="optsChipsVI" class="chips-row"></div>
                    <input type="text" id="optsInputVI" class="chip-input" placeholder="Nhập gợi ý... (VD: Ít cay, Không hành...)">
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════
                 CHIP BUILDER — Tùy chọn ghi chú nhanh (EN)
            ═══════════════════════════════════════════════════ -->
            <div class="form-group col-span-2">
                <label class="form-label">
                    <i class="fas fa-tags me-1" style="color:var(--gold);"></i>
                    Tùy chọn ghi chú nhanh (Tiếng Anh)
                </label>
                <p class="form-hint" style="margin-bottom:.6rem;">
                    Tương ứng theo thứ tự với Tiếng Việt. Dùng khi khách xem menu ở chế độ EN.
                </p>
                <input type="hidden" name="note_options_en" id="noteOptsCsvEN"
                       value="<?= $isEdit ? e($item['note_options_en'] ?? '') : '' ?>">
                <div id="optsBuilderEN" class="chip-builder">
                    <div id="optsChipsEN" class="chips-row"></div>
                    <input type="text" id="optsInputEN" class="chip-input" placeholder="Enter option... (e.g. Less spicy, No onion...)">
                </div>
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Kho (Stock) <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control" value="<?= e($item['stock'] ?? -1) ?>" required>
                <p class="form-hint">Nhập -1 nếu món này không giới hạn số lượng bán.</p>
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Ảnh chính (Ảnh đại diện món)</label>
                <?php if ($isEdit && $item['image']): ?>
                    <div style="margin-bottom:.5rem;">
                        <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>"
                            style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--border-gold);">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*">
                <p class="form-hint">JPG, PNG, WebP. Tối đa 5MB. Đây là ảnh hiển thị ngoài danh sách.</p>
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Bộ sưu tập ảnh (Slide Thumbnail Chi Tiết Món)</label>
                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                <p class="form-hint">Chọn <strong>nhiều ảnh</strong> bằng cách giữ Ctrl/Cmd khi chọn file.</p>
            </div>

            <?php if ($isEdit): ?>
                <div class="form-group">
                    <label class="form-label">Trạng thái hiển thị</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?= $item['is_active'] ? 'selected' : '' ?>>Hiển thị</option>
                        <option value="0" <?= !$item['is_active'] ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
            <?php endif; ?>

        </div><!-- /form-grid-2 -->

        <div style="display:flex;gap:.75rem;margin-top:1rem;">
            <button type="submit" class="btn btn-gold btn-lg">
                <i class="fas fa-save"></i>
                <?= $isEdit ? 'Lưu thay đổi' : 'Thêm món' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline btn-lg">Huỷ</a>
        </div>

    </form>
</div>

<style>
/* ── Chip Builder ─────────────────────────────────────────────────── */
.chip-builder {
    border: 1.5px solid var(--border-color, #e5e7eb);
    border-radius: 10px;
    padding: .5rem .65rem;
    min-height: 48px;
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    align-items: center;
    cursor: text;
    background: var(--card-bg, #fff);
    transition: border-color .2s;
}
.chip-builder:focus-within {
    border-color: var(--gold, #d4af37);
    box-shadow: 0 0 0 3px rgba(212,175,55,.12);
}
.chips-row {
    display: contents; /* chips sit inline with input */
}
.note-chip {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: rgba(212,175,55,.15);
    border: 1.5px solid var(--gold, #d4af37);
    color: var(--gold-dark, #785e0a);
    border-radius: 20px;
    padding: .22rem .65rem;
    font-size: .8rem;
    font-weight: 700;
    white-space: nowrap;
    animation: chipIn .15s ease;
}
@keyframes chipIn {
    from { transform: scale(.8); opacity: 0; }
    to   { transform: scale(1);  opacity: 1; }
}
.note-chip .chip-del {
    background: none;
    border: none;
    color: var(--gold-dark, #785e0a);
    cursor: pointer;
    padding: 0;
    font-size: .75rem;
    line-height: 1;
    opacity: .7;
    transition: opacity .15s;
}
.note-chip .chip-del:hover { opacity: 1; }
.chip-input {
    border: none !important;
    outline: none !important;
    flex: 1;
    min-width: 160px;
    font-size: .875rem;
    background: transparent;
    padding: .2rem 0;
    color: var(--text-primary, #1e293b);
}
kbd {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: .1rem .35rem;
    font-size: .75rem;
    font-family: monospace;
}
</style>

<script>
/* ── Chip Builder Logic ──────────────────────────────────────────── */
function initChipBuilder(inputId, chipsId, csvId) {
    const input    = document.getElementById(inputId);
    const chipsRow = document.getElementById(chipsId);
    const csvHidden= document.getElementById(csvId);

    // Parse existing CSV and render chips
    const initVal = csvHidden.value.trim();
    if (initVal) {
        initVal.split(',').map(s => s.trim()).filter(Boolean).forEach(val => addChip(val));
    }

    function syncCsv() {
        const chips = chipsRow.querySelectorAll('.note-chip[data-val]');
        csvHidden.value = Array.from(chips).map(c => c.dataset.val).join(', ');
    }

    function addChip(val) {
        val = val.trim();
        if (!val) return;
        // Prevent duplicates (case-insensitive)
        const existing = chipsRow.querySelectorAll('.note-chip[data-val]');
        for (const c of existing) {
            if (c.dataset.val.toLowerCase() === val.toLowerCase()) return;
        }
        const chip = document.createElement('span');
        chip.className = 'note-chip';
        chip.dataset.val = val;
        chip.innerHTML = `${val} <button type="button" class="chip-del" title="Xóa">×</button>`;
        chip.querySelector('.chip-del').addEventListener('click', () => {
            chip.remove();
            syncCsv();
        });
        chipsRow.appendChild(chip);
        syncCsv();
    }

    function processInput() {
        const parts = input.value.split(',');
        parts.forEach((p, i) => {
            // Add all except the last fragment (typing in progress)
            if (i < parts.length - 1) {
                addChip(p);
            }
        });
        // Keep only the last fragment in the input
        input.value = parts[parts.length - 1];
    }

    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const val = input.value.replace(/,$/, '').trim();
            if (val) { addChip(val); input.value = ''; }
        }
        // Backspace on empty input removes last chip
        if (e.key === 'Backspace' && !input.value) {
            const chips = chipsRow.querySelectorAll('.note-chip');
            if (chips.length > 0) {
                chips[chips.length - 1].remove();
                syncCsv();
            }
        }
    });

    input.addEventListener('input', processInput);

    // Click on builder area focuses input
    document.getElementById(inputId.replace('Input', 'Builder')).addEventListener('click', () => input.focus());
}

document.addEventListener('DOMContentLoaded', () => {
    initChipBuilder('optsInputVI', 'optsChipsVI', 'noteOptsCsvVI');
    initChipBuilder('optsInputEN', 'optsChipsEN', 'noteOptsCsvEN');

    // Price input validation - only allow digits
    const priceInput = document.getElementById('price-input');
    if (priceInput) {
        // Format display with dots as thousand separators on blur
        priceInput.addEventListener('blur', () => {
            const rawValue = priceInput.value.replace(/\./g, '');
            if (rawValue && /^\d+$/.test(rawValue)) {
                // Store raw value in data attribute for form submission
                priceInput.dataset.rawValue = rawValue;
            }
        });

        // Only allow digits input
        priceInput.addEventListener('input', (e) => {
            let value = e.target.value;
            // Remove any non-digit characters
            value = value.replace(/[^0-9]/g, '');
            // Limit to 10 characters
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            e.target.value = value;
        });

        // Prevent non-numeric keys
        priceInput.addEventListener('keydown', (e) => {
            // Allow: backspace, delete, tab, escape, enter, arrow keys
            if ([8, 9, 13, 27, 37, 38, 39, 40, 46].indexOf(e.keyCode) !== -1) {
                return;
            }
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            if ((e.keyCode === 65 || e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 88) && e.ctrlKey) {
                return;
            }
            // Block anything else
            if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }


    // Fix builder IDs (replace 'Input' → 'Builder' in click handler)
    document.getElementById('optsBuilderVI').addEventListener('click', () => document.getElementById('optsInputVI').focus());
    document.getElementById('optsBuilderEN').addEventListener('click', () => document.getElementById('optsInputEN').focus());

    // Fix: Allow Enter to submit form from select elements
    document.querySelectorAll('select.form-control').forEach(select => {
        select.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Blur the select to close dropdown
                select.blur();
                // Submit the form
                select.closest('form').submit();
            }
        });
    });

    // Fix: Scroll to top when page loads (fix "cuộn khá sâu" issue)
    window.scrollTo(0, 0);

    // Fix: Focus first input for better UX
    const firstInput = document.querySelector('input[name="name"]');
    if (firstInput) firstInput.focus();
});
</script>
