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
    <div class="qr-modal-wrapper">
        <div class="qr-modal-card" id="printableQrArea">
            <!-- Header với branding -->
            <div class="qr-modal-header">
                <div class="qr-brand-logo">
                    <img src="<?= BASE_URL ?>/public/src/logo/favicon.png" alt="Aurora Logo">
                </div>
                <div class="qr-brand-name">
                    <span class="qr-brand-title">AURORA HOTEL PLAZA</span>
                    <span class="qr-brand-subtitle">Restaurant & Bar</span>
                </div>
                <button type="button" class="qr-close-btn close-modal" title="Đóng">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Nội dung chính -->
            <div class="qr-modal-body">
                <!-- Tên bàn/phòng -->
                <div class="qr-table-name-container">
                    <div class="qr-table-label"><?= $type === 'room' ? 'PHÒNG' : 'BÀN' ?></div>
                    <div class="qr-table-name" id="qrTableDisplay">01</div>
                </div>

                <!-- QR Code -->
                <div class="qr-code-container">
                    <div class="qr-code-frame">
                        <div id="qrcode" class="qr-code-wrapper">
                            <div id="qrcode-canvas"></div>
                        </div>
                        <div class="qr-logo-overlay">
                            <img src="<?= BASE_URL ?>/public/src/logo/favicon.png" alt="Logo">
                        </div>
                    </div>
                </div>

                <!-- Hướng dẫn -->
                <div class="qr-instructions">
                    <div class="qr-instruction-main">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Quét mã để đặt món</span>
                    </div>
                    <div class="qr-instruction-sub">
                        Scan QR to order • Cảm ơn Quý khách
                    </div>
                </div>

                <!-- URL (hidden trong print) -->
                <div class="qr-url-container no-print">
                    <code id="qrUrl"></code>
                </div>
            </div>

            <!-- Footer với actions -->
            <div class="qr-modal-footer no-print">
                <button type="button" class="qr-action-btn qr-action-primary" onclick="openQrLink()">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Mở link</span>
                </button>
                <button type="button" class="qr-action-btn qr-action-secondary" onclick="printQR()">
                    <i class="fas fa-print"></i>
                    <span>In mã QR</span>
                </button>
                <button type="button" class="qr-action-btn qr-action-secondary" onclick="downloadQR()">
                    <i class="fas fa-download"></i>
                    <span>Tải ảnh</span>
                </button>
                <button type="button" class="qr-action-btn qr-action-outline close-modal">
                    <i class="fas fa-times"></i>
                    <span>Đóng</span>
                </button>
            </div>

            <!-- Print Header (hidden on screen) -->
            <div class="qr-print-header" style="display:none;">
                <!-- Hidden per user request for minimal style -->
            </div>

            <!-- Print Footer (hidden on screen) -->
            <div class="qr-print-footer" style="display:none;">
                <div id="qrPrintTableName" style="font-weight:700; font-size:14px; margin-top:10px; text-transform:uppercase;"></div>
            </div>
        </div>

        <!-- Modal Title (for admin reference) -->
        <div class="qr-modal-title-bar no-print">
            <span id="qrModalTitle">Mã QR <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></span>
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

    /* QR Modal Styles - Redesigned */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(12px);
    }

    /* QR Modal Wrapper */
    .qr-modal-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem;
        animation: qrModalFadeIn 0.35s ease-out;
    }

    @keyframes qrModalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* QR Modal Card */
    .qr-modal-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 24px;
        box-shadow: 
            0 30px 60px -15px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(212, 175, 55, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        max-width: 420px;
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    /* QR Modal Header */
    .qr-modal-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-bottom: 3px solid var(--gold);
        position: relative;
    }

    .qr-brand-logo {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.15);
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .qr-brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .qr-brand-name {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .qr-brand-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--gold);
        letter-spacing: 1px;
    }

    .qr-brand-subtitle {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        letter-spacing: 2px;
        font-weight: 300;
    }

    .qr-close-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.8);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .qr-close-btn:hover {
        background: rgba(212, 175, 55, 0.3);
        border-color: var(--gold);
        color: var(--gold);
        transform: scale(1.05);
    }

    /* QR Modal Body */
    .qr-modal-body {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
    }

    /* Table Name Container */
    .qr-table-name-container {
        text-align: center;
    }

    .qr-table-label {
        font-size: 0.85rem;
        color: #666;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .qr-table-name {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gold-dark);
        background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 2px;
    }

    /* QR Code Container */
    .qr-code-container {
        position: relative;
    }

    .qr-code-frame {
        position: relative;
        padding: 1.5rem;
        background: #fff;
        border-radius: 20px;
        box-shadow: 
            0 8px 24px rgba(0, 0, 0, 0.1),
            inset 0 2px 8px rgba(212, 175, 55, 0.05);
        border: 2px solid rgba(212, 175, 55, 0.2);
    }

    .qr-code-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 220px;
        min-height: 220px;
    }

    .qr-logo-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50px;
        height: 50px;
        background: white;
        padding: 6px;
        border-radius: 10px;
        border: 2px solid rgba(212, 175, 55, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10;
    }

    .qr-logo-overlay img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    #qrcode-canvas img {
        width: 200px !important;
        height: 200px !important;
        border-radius: 8px;
    }

    /* QR Instructions */
    .qr-instructions {
        text-align: center;
    }

    .qr-instruction-main {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .qr-instruction-main i {
        color: var(--gold);
        font-size: 1.1rem;
    }

    .qr-instruction-sub {
        font-size: 0.85rem;
        color: #888;
        font-weight: 400;
    }

    /* QR URL Container */
    .qr-url-container {
        padding: 0.75rem 1rem;
        background: #f5f5f5;
        border-radius: 8px;
        width: 100%;
        max-width: 300px;
    }

    .qr-url-container code {
        font-size: 0.75rem;
        color: #666;
        word-break: break-all;
        display: block;
        text-align: center;
    }

    /* QR Modal Footer */
    .qr-modal-footer {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        padding: 1.5rem 2rem 2rem;
        background: linear-gradient(to top, rgba(248, 249, 250, 0.8) 0%, transparent 100%);
        border-top: 1px solid rgba(212, 175, 55, 0.1);
    }

    .qr-action-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .qr-action-primary {
        background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .qr-action-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(212, 175, 55, 0.4);
    }

    .qr-action-secondary {
        background: #fff;
        color: var(--gold-dark);
        border: 2px solid rgba(212, 175, 55, 0.3);
    }

    .qr-action-secondary:hover {
        background: rgba(212, 175, 55, 0.1);
        border-color: var(--gold);
    }

    .qr-action-outline {
        background: transparent;
        color: #666;
        border: 2px solid #e5e7eb;
    }

    .qr-action-outline:hover {
        background: #f5f5f5;
        border-color: #d1d5db;
    }

    /* QR Modal Title Bar */
    .qr-modal-title-bar {
        margin-top: 1rem;
        text-align: center;
    }

    .qr-modal-title-bar span {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        letter-spacing: 1px;
    }

    /* Bulk Print modal - keep existing styles */
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        position: relative;
        animation: modalFadeIn 0.3s ease-out;
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
            display: flex !important;
            justify-content: center;
            align-items: center;
        }

        .qr-modal-wrapper {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-modal-card {
            box-shadow: none !important;
            margin: 0 !important;
            border: none !important;
            width: auto !important;
            max-width: none !important;
            padding: 0 !important;
            background: white !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .no-print,
        .qr-modal-header,
        .qr-instructions,
        .qr-url-container,
        .qr-modal-footer,
        .qr-modal-title-bar,
        .qr-table-name-container,
        #qrUrl {
            display: none !important;
        }

        .qr-modal-body {
            padding: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
        }

        .qr-code-container {
            margin: 0 !important;
        }

        .qr-code-frame {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .qr-code-wrapper {
            min-width: unset !important;
            min-height: unset !important;
        }

        #qrcode-canvas img {
            width: 120mm !important;
            height: 120mm !important;
            border-radius: 0 !important;
        }

        .qr-logo-overlay {
            width: 25mm !important;
            height: 25mm !important;
            border-radius: 4px !important;
            padding: 3mm !important;
            border: 1px solid #eee !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        }

        .qr-print-header {
            display: none !important;
        }

        .qr-print-footer {
            display: block !important;
            margin-top: 5mm !important;
            text-align: center !important;
        }

        #qrPrintTableName {
            font-size: 18pt !important;
            font-family: 'Outfit', sans-serif !important;
            color: #000 !important;
        }

        /* Force background graphics printing */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
    }
</style>

<script>
    let currentQrUrl = '';
    
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
                currentQrUrl = fullUrl;

                qrTitle.innerText = `QR Code: <?= $type === 'room' ? 'Room' : 'Table' ?> ${tableName}`;
                qrTableDisplay.innerText = `<?= $type === 'room' ? 'ROOM' : 'TABLE' ?> ${tableName.toUpperCase()}`;
                document.getElementById('qrPrintTableName').innerText = `<?= $type === 'room' ? 'ROOM' : 'TABLE' ?> ${tableName.toUpperCase()}`;
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

    function openQrLink() {
        if (currentQrUrl) {
            window.open(currentQrUrl, '_blank');
        }
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
