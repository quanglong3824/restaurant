<?php // views/admin/tables/index.php ?>
<div class="content-with-aside content-with-aside--sm">

    <!-- Tabs -->
    <div class="tabs-container" style="grid-column: 1 / -1; margin-bottom: 1rem;">
        <div class="tabs" style="display: flex; gap: 1rem; border-bottom: 2px solid #eee; padding-bottom: 0.5rem;">
            <a href="<?= BASE_URL ?>/admin/tables?type=table" class="tab-item <?= $type === 'table' ? 'active' : '' ?>"
                style="text-decoration: none; color: <?= $type === 'table' ? 'var(--gold)' : '#666' ?>; font-weight: bold; padding: 0.5rem 1rem; border-bottom: 3px solid <?= $type === 'table' ? 'var(--gold)' : 'transparent' ?>;">
                <i class="fas fa-chair"></i> Bàn Nhà Hàng
            </a>
            <a href="<?= BASE_URL ?>/admin/tables?type=room" class="tab-item <?= $type === 'room' ? 'active' : '' ?>"
                style="text-decoration: none; color: <?= $type === 'room' ? 'var(--gold)' : '#666' ?>; font-weight: bold; padding: 0.5rem 1rem; border-bottom: 3px solid <?= $type === 'room' ? 'var(--gold)' : 'transparent' ?>;">
                <i class="fas fa-bed"></i> Khách Lưu Trú (Phòng)
            </a>
        </div>
    </div>

    <!-- Area Filter Buttons -->
    <?php if (!empty($groupedTables)): ?>
    <div class="area-filter-container" style="grid-column: 1 / -1; margin-bottom: 1rem;">
        <div class="area-filter-label" style="font-weight: 600; margin-bottom: 0.5rem; color: #666;">
            <i class="fas fa-filter"></i> Lọc <?= $type === 'room' ? 'tầng' : 'khu vực' ?>:
        </div>
        <div class="area-filter-buttons" style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            <button type="button" class="area-filter-btn active" data-area="all">
                <i class="fas fa-th-large"></i> Tất cả
            </button>
            <?php foreach (array_keys($groupedTables) as $area): ?>
                <button type="button" class="area-filter-btn" data-area="<?= e($area) ?>">
                    <?= e($area) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Table list -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas <?= $type === 'room' ? 'fa-bed' : 'fa-chair' ?>"></i> Danh sách
                <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h2>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <span class="badge badge-gold"><?= count($tables) ?> <?= $type === 'room' ? 'phòng' : 'bàn' ?></span>
                <a href="<?= BASE_URL ?>/admin/tables/qr-instructions" class="btn btn-outline btn-sm"
                    title="In hướng dẫn sử dụng QR cho khách">
                    <i class="fas fa-file-instruction"></i> In HDSD
                </a>
                <button type="button" class="btn btn-outline btn-sm" onclick="openBulkPrintModal()"
                    title="In hàng loạt mã QR">
                    <i class="fas fa-print"></i> In QR
                </button>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th><?= $type === 'room' ? 'Số phòng' : 'Tên bàn' ?></th>
                        <th class="table-hide-sm">Khu vực</th>
                        <th class="table-hide-sm">Sức chứa</th>
                        <th>Trạng thái</th>
                        <th>Kích hoạt</th>
                        <th style="width:160px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($groupedTables)): ?>
                        <tr>
                            <td colspan="6" style="text-align:center;padding:2rem;color:#9ca3af;">
                                Chưa có <?= $type === 'room' ? 'phòng' : 'bàn' ?> nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($groupedTables as $area => $areaTables): ?>
                            <!-- Group Header Row -->
                            <tr style="background-color: #f8fafc;">
                                <td colspan="6" style="padding: 1rem; border-left: 4px solid var(--gold);">
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <h3
                                            style="margin: 0; font-size: 1.1rem; color: var(--gold-dark); display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-layer-group"></i>
                                            Khu vực: <?= e($area) ?>
                                        </h3>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
                                                <input type="checkbox" class="area-checkbox" data-area="<?= e($area) ?>" style="width: 16px; height: 16px; cursor: pointer;">
                                                <span>Chọn tất cả</span>
                                            </label>
                                            <span class="badge badge-outline" style="font-size: 0.75rem;">
                                                <?= count($areaTables) ?> <?= $type === 'room' ? 'phòng' : 'bàn' ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Items in Group -->
                            <?php foreach ($areaTables as $t): ?>
                                <tr class="table-row" data-area="<?= e($area) ?>" data-table-id="<?= $t['id'] ?>">
                                    <td>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                            <input type="checkbox" class="table-checkbox" 
                                                value="<?= $t['id'] ?>" 
                                                data-name="<?= e($t['name']) ?>"
                                                data-token="<?= e($t['qr_token'] ?? '') ?>"
                                                style="width: 16px; height: 16px; cursor: pointer;">
                                            <strong><?= e($t['name']) ?></strong>
                                        </label>
                                    </td>
                                    <td class="table-hide-sm"><?= e($t['area'] ?? '—') ?></td>
                                    <td class="table-hide-sm"><?= $t['capacity'] ?>             <?= $type === 'room' ? 'người' : 'người' ?></td>
                                    <td>
                                        <?php if ($t['status'] === 'occupied'): ?>
                                            <span class="badge badge-danger">
                                                <i class="fas fa-circle" style="font-size:.5rem"></i> Có khách
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-circle" style="font-size:.5rem"></i>
                                                <?= $type === 'room' ? 'Sẵn sàng' : 'Trống' ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $t['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $t['is_active'] ? 'Đang dùng' : 'Tạm ẩn' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:.4rem;">
                                            <!-- QR Button -->
                                            <button type="button" class="btn btn-outline btn-sm btn-qr" data-id="<?= $t['id'] ?>"
                                                data-name="<?= e($t['name']) ?>" data-token="<?= e($t['qr_token'] ?? '') ?>"
                                                title="Tạo QR">
                                                <i class="fas fa-qrcode"></i>
                                            </button>

                                            <!-- Reset QR Button -->
                                            <button type="button" class="btn btn-outline btn-sm" style="color:var(--warning);"
                                                title="Tạo/Reset mã QR"
                                                onclick="confirmResetQR(<?= $t['id'] ?>, '<?= e($t['name']) ?>', <?= (int) $t['is_printed'] ?>, <?= (int) $t['scan_count'] ?>, <?= (int) $t['items_count'] ?>)">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>

                                            <a href="<?= BASE_URL ?>/admin/tables/edit?id=<?= $t['id'] ?>"
                                                class="btn btn-outline btn-sm" title="Sửa">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <?php if ($t['status'] !== 'occupied'): ?>
                                                <form method="POST" action="<?= BASE_URL ?>/admin/tables/delete"
                                                    style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                                    <button type="submit" class="btn btn-danger-outline btn-sm"
                                                        data-confirm="Xóa <?= $type === 'room' ? 'phòng' : 'bàn' ?> '<?= e($t['name']) ?>'?"
                                                        title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add / Edit form -->
    <div class="card sticky-aside">
        <?php if ($editItem): ?>
            <!-- Edit mode -->
            <div class="card-header">
                <h2><i class="fas fa-pen"></i> Sửa <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h2>
                <a href="<?= BASE_URL ?>/admin/tables?type=<?= $type ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/tables/update">
                <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
                <input type="hidden" name="type" value="<?= $type ?>">

                <div class="form-group">
                    <label class="form-label"><?= $type === 'room' ? 'Số phòng' : 'Tên bàn' ?> *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($editItem['name']) ?>"
                        placeholder="VD: <?= $type === 'room' ? '701' : 'Bàn 01' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Khu vực</label>
                    <input type="text" name="area" class="form-control" value="<?= e($editItem['area'] ?? '') ?>"
                        placeholder="VD: <?= $type === 'room' ? 'Tầng 7' : 'Tầng 1, Sân vườn...' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Sức chứa (người)</label>
                    <input type="number" name="capacity" class="form-control" min="1"
                        max="<?= $type === 'room' ? '3' : '20' ?>" value="<?= (int) $editItem['capacity'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" min="0"
                        value="<?= (int) $editItem['sort_order'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?= $editItem['is_active'] ? 'selected' : '' ?>>Đang dùng</option>
                        <option value="0" <?= !$editItem['is_active'] ? 'selected' : '' ?>>Tạm ẩn</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-gold btn-block">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </form>

        <?php else: ?>
            <!-- Add mode -->
            <div class="card-header">
                <h2><i class="fas fa-plus"></i> Thêm <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h2>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/tables/store">
                <input type="hidden" name="type" value="<?= $type ?>">
                <div class="form-group">
                    <label class="form-label"><?= $type === 'room' ? 'Số phòng' : 'Tên bàn' ?> *</label>
                    <input type="text" name="name" class="form-control" required
                        placeholder="VD: <?= $type === 'room' ? '701' : 'Bàn 01' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Khu vực</label>
                    <input type="text" name="area" class="form-control"
                        placeholder="VD: <?= $type === 'room' ? 'Tầng 7' : 'Tầng 1, Sân vườn...' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Sức chứa (người)</label>
                    <input type="number" name="capacity" class="form-control" min="1"
                        max="<?= $type === 'room' ? '3' : '20' ?>" value="<?= $type === 'room' ? '3' : '4' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                </div>
                <button type="submit" class="btn btn-gold btn-block">
                    <i class="fas fa-save"></i> Thêm <?= $type === 'room' ? 'phòng' : 'bàn' ?>
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header">
            <h3 id="qrModalTitle">Mã QR <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h3>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <div class="modal-body" id="printableQrArea">
            <div class="qr-print-header" style="display:none; text-align:center; margin-bottom:20px;">
                <h1 style="font-family:'Playfair Display', serif; color:#D4AF37; margin:0; font-size:28px;">AURORA HOTEL
                    PLAZA</h1>
                <p style="margin:5px 0 15px; font-size:14px; letter-spacing:2px; color:#666;">RESTAURANT & BAR</p>
                <div
                    style="border-top:1px solid #D4AF37; border-bottom:1px solid #D4AF37; padding:10px 0; margin:10px 0;">
                    <h2 id="qrTableDisplay" style="margin:0; font-size:24px; color:#1a1a1a;">BÀN 01</h2>
                </div>
            </div>

            <div id="qrcode"
                style="display: flex; justify-content: center; margin-bottom: 1.5rem; padding:15px; background:#fff; border-radius:12px; position: relative;">
                <div id="qrcode-canvas"></div>
                <img src="<?= BASE_URL ?>/public/src/logo/favicon.png" class="qr-logo-modal" alt="Logo">
            </div>

            <div class="qr-print-footer" style="display:none; text-align:center; margin-top:15px;">
                <p style="font-weight:600; margin-bottom:5px;">QUÉT MÃ ĐỂ ĐẶT MÓN</p>
                <p style="font-size:12px; color:#888;">Cảm ơn Quý khách / Thank you!</p>
            </div>

            <p id="qrUrl"
                style="font-size: 0.75rem; color: #999; word-break: break-all; margin-bottom: 1.5rem; font-family:monospace;">
            </p>

            <div style="display: flex; gap: 0.75rem; justify-content: center;" class="no-print">
                <button type="button" class="btn btn-gold" onclick="printQR()">
                    <i class="fas fa-print"></i> In QR
                </button>
                <button type="button" class="btn btn-outline" onclick="downloadQR()">
                    <i class="fas fa-download"></i> Tải ảnh
                </button>
                <button type="button" class="btn btn-outline close-modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Area Filter Button Styles */
    .area-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 20px;
        background: #fff;
        color: #666;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .area-filter-btn:hover {
        border-color: var(--gold);
        color: var(--gold-dark);
        background: rgba(212, 175, 55, 0.05);
    }

    .area-filter-btn.active {
        border-color: var(--gold);
        background: var(--gold);
        color: #fff;
    }

    .area-filter-btn i {
        font-size: 0.85rem;
    }

    .area-group-row {
        transition: display 0.2s ease;
    }

    /* QR Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        position: relative;
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .close-modal {
        background: #f3f4f6;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .close-modal:hover {
        background: #e5e7eb;
    }

    .qr-logo-modal {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: white;
        padding: 5px;
        border-radius: 12px;
        border: 1px solid #eee;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    #qrcode-canvas img {
        border: 1px solid #f0f0f0;
        padding: 10px;
        border-radius: 8px;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #qrModal,
        #qrModal * {
            visibility: visible;
        }

        .modal {
            position: absolute;
            left: 0;
            top: 0;
            background: #fff;
            padding: 0;
        }

        .modal-content {
            box-shadow: none;
            margin: 0;
            border: none;
            width: 100%;
            max-width: none;
        }

        .no-print,
        .modal-header,
        #qrUrl {
            display: none !important;
        }

        .qr-print-header,
        .qr-print-footer {
            display: block !important;
        }

        #printableQrArea {
            padding: 40px !important;
        }

        #qrcode {
            margin: 0 auto !important;
            padding: 0 !important;
            border: none !important;
        }

        .qr-logo-modal {
            width: 80px;
            height: 80px;
        }

        /* Larger logo for print */
        #qrcode-canvas img {
            width: 450px !important;
            height: 450px !important;
            border: none !important;
            padding: 0 !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('qrModal');
        const qrContainer = document.getElementById('qrcode-canvas');
        const qrUrlText = document.getElementById('qrUrl');
        const qrTitle = document.getElementById('qrModalTitle');
        const qrTableDisplay = document.getElementById('qrTableDisplay');
        const closeBtns = document.querySelectorAll('.close-modal');

        document.querySelectorAll('.btn-qr').forEach(btn => {
            btn.addEventListener('click', () => {
                const tableId = btn.dataset.id;
                const tableName = btn.dataset.name;
                const token = btn.dataset.token;

                if (!token) {
                    if (confirm('Bàn/Phòng này chưa có mã QR định danh. Bạn có muốn hệ thống tự động tạo mã ngay bây giờ?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '<?= BASE_URL ?>/admin/qr-codes/generate';
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'table_id';
                        input.value = tableId;
                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                    return;
                }

                const fullUrl = `<?= BASE_URL ?>/q?t=${token}`;

                qrTitle.innerText = `Mã QR: <?= $type === 'room' ? 'Phòng' : 'Bàn' ?> ${tableName}`;
                qrTableDisplay.innerText = `<?= $type === 'room' ? 'PHÒNG' : 'BÀN' ?> ${tableName.toUpperCase()}`;
                qrUrlText.innerText = fullUrl;
                qrContainer.innerHTML = '';

                new QRCode(qrContainer, {
                    text: fullUrl,
                    width: 300,
                    height: 300,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H,
                    margin: 2
                });

                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            });
        });

        window.onclick = (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        };
    });

    function printQR() {
        window.print();
    }

    function downloadQR() {
        const img = document.querySelector('#qrcode-canvas img');
        if (!img) return;
        const link = document.createElement('a');
        link.download = `QR-${document.getElementById('qrTableDisplay').innerText}.png`;
        link.href = img.src;
        link.click();
    }

    function confirmResetQR(tableId, tableName, isPrinted, scanCount, itemsCount) {
        if (itemsCount > 0) {
            alert(`CẢNH BÁO: <?= $type === 'room' ? 'Phòng' : 'Bàn' ?> ${tableName} đang có khách đã đặt món (${itemsCount} món).\n\nVui lòng hoàn tất đơn hàng và thanh toán trước khi reset QR.`);
            return;
        }

        if (isPrinted) {
            if (!confirm(`Mã QR của ${tableName} ĐÃ ĐƯỢC IN ra giấy.\n\nNếu bạn reset, mã QR cũ trên giấy sẽ không còn tác dụng và khách không thể quét được nữa.\n\nBạn có CHẮC CHẮN vẫn muốn tạo mã mới?`)) {
                return;
            }
        } else if (scanCount > 0) {
            if (!confirm(`Mã QR này đã được quét ${scanCount} lần.\n\nBạn có chắc chắn muốn reset không?`)) {
                return;
            }
        } else {
            if (!confirm(`Xác nhận tạo mã QR mới cho ${tableName}?`)) {
                return;
            }
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/qr-codes/generate';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'table_id';
        input.value = tableId;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    // Bulk Print Modal Functions
    function openBulkPrintModal() {
        document.getElementById('bulkPrintModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        updateSelectedCount();
    }

    function closeBulkPrintModal() {
        document.getElementById('bulkPrintModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function updateSelectedCount() {
        const selectedTables = document.querySelectorAll('.table-checkbox:checked');
        document.getElementById('selectedCount').textContent = selectedTables.length;
    }

    function selectAllTables() {
        const checkboxes = document.querySelectorAll('.table-checkbox');
        checkboxes.forEach(cb => cb.checked = true);
        updateSelectedCount();
    }

    function deselectAllTables() {
        const checkboxes = document.querySelectorAll('.table-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        updateSelectedCount();
    }

    function printSelected() {
        const selectedTables = [];
        document.querySelectorAll('.table-checkbox:checked').forEach(cb => {
            if (cb.dataset.token) {
                selectedTables.push({
                    id: cb.value,
                    name: cb.dataset.name,
                    token: cb.dataset.token
                });
            }
        });

        if (selectedTables.length === 0) {
            alert('Vui lòng chọn ít nhất 1 bàn/phòng có mã QR để in.');
            return;
        }

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/qr-codes/print-bulk';
        form.target = '_blank';

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = '<?= $type ?>';

        const tablesInput = document.createElement('input');
        tablesInput.type = 'hidden';
        tablesInput.name = 'tables';
        tablesInput.value = JSON.stringify(selectedTables);

        form.appendChild(typeInput);
        form.appendChild(tablesInput);
        document.body.appendChild(form);
        form.submit();
        closeBulkPrintModal();
    }

    // Area Filter Button handling
    document.addEventListener('DOMContentLoaded', () => {
        // Area filter buttons
        const filterButtons = document.querySelectorAll('.area-filter-btn');
        const tableRows = document.querySelectorAll('.table-row');
        const groupHeaderRows = document.querySelectorAll('tr[style*="background-color: #f8fafc"]');
        
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const selectedArea = this.dataset.area;
                
                // Update active button state
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Filter table rows and group headers
                if (selectedArea === 'all') {
                    // Show all
                    tableRows.forEach(row => row.style.display = '');
                    groupHeaderRows.forEach(row => row.style.display = '');
                } else {
                    // Filter by area
                    tableRows.forEach(row => {
                        if (row.dataset.area === selectedArea) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    
                    // Filter group headers (they contain the area name in their content)
                    groupHeaderRows.forEach(row => {
                        const areaText = row.querySelector('h3');
                        if (areaText && areaText.textContent.includes(selectedArea)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
                
                // Update visible count in badge
                const visibleRows = document.querySelectorAll('.table-row:not([style*="display: none"])');
                const countBadge = document.querySelector('.badge-gold');
                if (countBadge) {
                    const typeText = '<?= $type === 'room' ? 'phòng' : 'bàn' ?>';
                    countBadge.textContent = `${visibleRows.length} ${typeText}`;
                }
            });
        });
        
        // Area checkboxes
        document.querySelectorAll('.area-checkbox').forEach(areaCheckbox => {
            areaCheckbox.addEventListener('change', function() {
                const area = this.dataset.area;
                const tableCheckboxes = document.querySelectorAll(`.table-checkbox`);
                
                tableCheckboxes.forEach(cb => {
                    const row = cb.closest('tr');
                    if (row && row.dataset.area === area) {
                        cb.checked = this.checked;
                    }
                });
                updateSelectedCount();
            });
        });

        // Individual table checkboxes
        document.querySelectorAll('.table-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });
    });
</script>

<!-- Bulk Print Modal -->
<div id="bulkPrintModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3><i class="fas fa-print"></i> In hàng loạt mã QR</h3>
            <button type="button" class="close-modal" onclick="closeBulkPrintModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                <div>
                    <p style="margin: 0; font-weight: 600;">Đã chọn: <span id="selectedCount" style="color: var(--gold);">0</span> bàn/phòng</p>
                    <p style="margin: 5px 0 0; font-size: 0.85rem; color: #666;">Mỗi trang A4 in được 10 mã (2 cột x 5 hàng)</p>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="selectAllTables()">
                        <i class="fas fa-check-square"></i> Chọn tất cả
                    </button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="deselectAllTables()">
                        <i class="fas fa-square"></i> Bỏ chọn tất cả
                    </button>
                </div>
            </div>

            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px; padding: 0.5rem;">
                <p style="text-align: center; padding: 1rem; color: #999;">
                    <i class="fas fa-info-circle"></i> Sử dụng checkbox ở danh sách bên trên để chọn bàn/phòng cần in
                </p>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: center; margin-top: 1.5rem;">
                <button type="button" class="btn btn-gold" onclick="printSelected()">
                    <i class="fas fa-print"></i> In đã chọn
                </button>
                <button type="button" class="btn btn-outline" onclick="closeBulkPrintModal()">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Bulk Print Modal Styles */
    #bulkPrintModal .modal-content {
        max-width: 600px;
    }

    #bulkPrintModal .modal-body {
        padding: 1.5rem;
    }
</style>
