<?php
$tab = $tab ?? 'floor';
$type = $type ?? 'table';
$menuType = $menuType ?? 'asia';
$tableId = $tableId ?? 0;
$orderId = $orderId ?? 0;

$vip_areas = ['VIP 1', 'VIP 2', 'VIP 3', 'VIP 4', 'VIP 1 2', 'VIP 3 4'];
$vip1 = $grouped['VIP 1'] ?? [];
$vip2 = $grouped['VIP 2'] ?? [];
$vip3 = $grouped['VIP 3'] ?? [];
$vip4 = $grouped['VIP 4'] ?? [];

$other_areas = [];
$room_areas = [];
foreach ($grouped as $area => $tables) {
    if (in_array($area, $vip_areas)) continue;
    $isRoomFloor = is_numeric($area) || preg_match('/^(Tầng\s*)?\d+$/', $area);
    if ($type === 'room' && $isRoomFloor) {
        $room_areas[$area] = $tables;
    } else {
        $other_areas[$area] = $tables;
    }
}

$uniqueAreas = [];
foreach (array_keys($grouped) as $a) {
    if (!in_array($a, ['VIP 1 2', 'VIP 3 4'])) $uniqueAreas[] = $a;
}
?>
<style>
.pos-dashboard { display: flex; flex-direction: column; height: auto; min-height: calc(100vh - 120px); overflow: visible; }
.pos-sidebar { width: 100%; background: #1e293b; border-bottom: 1px solid #334155; display: flex; flex-direction: row; padding: 8px; overflow-x: auto; }
.pos-tabs { display: flex; flex-direction: row; gap: 4px; }
.pos-tab { padding: 10px 14px; color: #94a3b8; cursor: pointer; border-bottom: 2px solid transparent; border-left: none; display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.8rem; transition: all 0.2s; white-space: nowrap; }
.pos-tab:hover { background: rgba(212,175,55,0.1); color: #d4af37; }
.pos-tab.active { background: rgba(212,175,55,0.15); border-bottom-color: #d4af37; color: #d4af37; }
.pos-tab i { width: 18px; text-align: center; }
.pos-content { flex: 1; overflow-y: auto; background: #f8fafc; padding: 16px; }
.pos-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 12px 16px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; }
.pos-header h2 { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0; }
.pos-header-actions { display: flex; gap: 8px; }

.floor-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px; }
.floor-card { background: white; border-radius: 14px; padding: 14px; cursor: pointer; border: 2px solid #e2e8f0; transition: all 0.2s; min-height: 90px; display: flex; flex-direction: column; justify-content: center; align-items: center; position: relative; }
.floor-card:hover { border-color: #d4af37; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(212,175,55,0.25); }
.floor-card.occupied { border-color: #ef4444; background: linear-gradient(135deg, #fef2f2 0%, white 100%); }
.floor-card.occupied:hover { border-color: #dc2626; box-shadow: 0 8px 20px rgba(239,68,68,0.25); }
.floor-card.merged-child { border-color: #8b5cf6; background: linear-gradient(135deg, #f3e8ff 0%, white 100%); }
.floor-card.merged-child:hover { border-color: #7c3aed; }
.floor-card.available { border-color: #10b981; background: linear-gradient(135deg, #d1fae5 0%, white 100%); }
.floor-card.available:hover { border-color: #059669; }
.floor-card-name { font-size: 1rem; font-weight: 800; color: #1e293b; }
.floor-card-status { font-size: 0.7rem; color: #64748b; margin-top: 4px; font-weight: 600; }
.floor-card.occupied .floor-card-status { color: #ef4444; }
.floor-card.available .floor-card-status { color: #10b981; }
.floor-card-icon { position: absolute; top: 8px; right: 8px; font-size: 0.8rem; opacity: 0.3; }
.floor-card.occupied .floor-card-icon { opacity: 0.5; color: #ef4444; }
.floor-card-actions { display: flex; gap: 6px; margin-top: 10px; flex-wrap: wrap; justify-content: center; }
.floor-btn { padding: 5px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 700; cursor: pointer; border: none; }
.floor-btn-gold { background: #d4af37; color: white; }
.floor-btn-red { background: #ef4444; color: white; }
.floor-btn-ghost { background: #f1f5f9; color: #64748b; }
.floor-btn-blue { background: #3b82f6; color: white; }
.floor-btn-purple { background: #8b5cf6; color: white; }
.floor-btn:hover { transform: scale(1.05); }

.type-tabs { display: flex; gap: 8px; margin-bottom: 12px; }
.type-tab { padding: 10px 20px; border-radius: 12px; font-size: 0.85rem; font-weight: 700; cursor: pointer; background: white; border: 2px solid #e2e8f0; color: #64748b; transition: all 0.2s; }
.type-tab:hover { border-color: #d4af37; }
.type-tab.active { background: #d4af37; color: white; border-color: #d4af37; }

.area-section { margin-bottom: 20px; }
.area-header { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; padding: 10px 14px; background: white; border-radius: 10px; border: 1px solid #e2e8f0; }
.area-header h3 { font-size: 0.9rem; font-weight: 800; color: #1e293b; margin: 0; }
.area-header i { color: #d4af37; }

.menu-tabs { display: flex; gap: 6px; margin-bottom: 12px; flex-wrap: wrap; }
.menu-tab { padding: 10px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; cursor: pointer; background: white; border: 2px solid #e2e8f0; color: #64748b; transition: all 0.2s; }
.menu-tab:hover { border-color: #d4af37; color: #d4af37; }
.menu-tab.active { background: #d4af37; color: white; border-color: #d4af37; }
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 14px; }
.menu-item-card { background: white; border-radius: 12px; padding: 12px; cursor: pointer; border: 2px solid #e2e8f0; transition: all 0.15s; position: relative; display: flex; flex-direction: column; min-height: 100px; }
.menu-item-card:hover { border-color: #d4af37; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(212,175,55,0.2); }
.menu-item-img { width: 100%; height: 80px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.menu-item-img img { width: 100%; height: 100%; object-fit: cover; }
.menu-item-img i { color: #d4af37; opacity: 0.3; font-size: 2rem; }
.menu-item-name { font-size: 0.9rem; font-weight: 700; color: #1e293b; margin-bottom: 4px; line-height: 1.2; }
.menu-item-price { font-size: 0.85rem; color: #d4af37; font-weight: 800; }
.menu-item-add { position: absolute; bottom: 8px; right: 8px; width: 36px; height: 36px; background: #d4af37; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 10px rgba(212,175,55,0.3); }
.menu-item-add:hover { transform: scale(1.1); }
.menu-search { margin-bottom: 12px; }
.menu-search input { width: 100%; padding: 10px 14px; border-radius: 10px; border: 2px solid #e2e8f0; font-size: 0.85rem; }
.menu-search input:focus { border-color: #d4af37; outline: none; }

.order-detail { background: white; border-radius: 14px; padding: 16px; border: 1px solid #e2e8f0; }
.order-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 12px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; }
.order-table-name { font-size: 1.3rem; font-weight: 800; color: #d4af37; }
.order-info { font-size: 0.8rem; color: #64748b; margin-top: 4px; }
.order-items { max-height: 400px; overflow-y: auto; }
.order-item-row { display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f1f5f9; gap: 8px; }
.order-item-row:last-child { border-bottom: none; }
.order-item-name { flex: 1; font-size: 0.9rem; font-weight: 600; color: #1e293b; }
.order-item-qty { display: flex; align-items: center; gap: 6px; }
.cart-qty-btn { width: 28px; height: 28px; border-radius: 50%; background: #f1f5f9; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; border: 1px solid #e2e8f0; }
.cart-qty-btn:hover { background: #e2e8f0; }
.order-item-price { font-size: 0.9rem; font-weight: 700; color: #d4af37; min-width: 80px; text-align: right; }
.order-item-status { font-size: 0.7rem; padding: 3px 8px; border-radius: 6px; font-weight: 700; }
.order-item-status.draft { background: #fef3c7; color: #92400e; }
.order-item-status.confirmed { background: #d1fae5; color: #065f46; }
.order-item-status.cooking { background: #fee2e2; color: #991b1b; }
.order-item-status.served { background: #dbeafe; color: #1e40af; }
.order-footer { padding: 16px; margin-top: 16px; background: #1e293b; border-radius: 12px; display: flex; flex-direction: column; gap: 12px; }
.order-total { font-size: 1.5rem; font-weight: 800; color: #d4af37; text-align: center; }
.order-actions { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; }
.btn { padding: 10px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; border: 2px solid #e2e8f0; background: white; color: #1e293b; transition: all 0.2s; }
.btn:hover { transform: translateY(-1px); }
.btn-gold { background: #d4af37; color: white; border-color: #d4af37; }
.btn-green { background: #10b981; color: white; border-color: #10b981; }
.btn-red { background: #ef4444; color: white; border-color: #ef4444; }
.btn-ghost { background: #f8fafc; color: #64748b; }

.notif-list { display: flex; flex-direction: column; gap: 10px; }
.notif-item { background: white; border-radius: 12px; padding: 14px; border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.2s; }
.notif-item:hover { border-color: #d4af37; transform: translateX(4px); }
.notif-item.unread { border-left: 4px solid #d4af37; background: linear-gradient(135deg, #fffbeb 0%, white 100%); }
.notif-type { font-size: 0.75rem; color: #64748b; font-weight: 700; margin-bottom: 6px; }
.notif-type i { margin-right: 6px; }
.notif-message { font-size: 0.9rem; color: #1e293b; font-weight: 600; }
.notif-time { font-size: 0.7rem; color: #94a3b8; margin-top: 6px; }

.realtime-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
.realtime-card { background: white; border-radius: 14px; border: 2px solid #e2e8f0; overflow: hidden; transition: all 0.2s; }
.realtime-card:hover { border-color: #d4af37; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(212,175,55,0.2); }
.realtime-card-header { padding: 14px; background: #f8fafc; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }
.realtime-table-name { font-size: 1rem; font-weight: 800; color: #1e293b; }
.realtime-status { font-size: 0.75rem; padding: 4px 10px; border-radius: 8px; font-weight: 700; }
.realtime-status.open { background: #dcfce7; color: #166534; }
.realtime-status.closed { background: #f3f4f6; color: #6b7280; }
.realtime-card-body { padding: 12px; max-height: 200px; overflow-y: auto; }
.realtime-item { display: flex; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
.realtime-item:last-child { border-bottom: none; }
.realtime-item-name { flex: 1; font-size: 0.85rem; color: #1e293b; }
.realtime-item-qty { font-size: 0.8rem; color: #64748b; font-weight: 600; }
.realtime-card-footer { padding: 12px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; }
.realtime-total { font-size: 1rem; font-weight: 800; color: #d4af37; }
.realtime-actions { display: flex; gap: 8px; }
.idle-badge-realtime { background: linear-gradient(135deg,#f59e0b,#fbbf24); color: white; padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; margin-top: 6px; display: inline-block; }
.idle-badge-realtime.critical { background: linear-gradient(135deg,#ef4444,#f87171); }

.cart-panel { position: fixed; right: 16px; bottom: 16px; width: 360px; max-width: calc(100vw - 32px); background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; flex-direction: column; max-height: 400px; z-index: 100; }
@media (max-width: 768px) {
    .cart-panel { width: 100%; right: 0; bottom: 0; border-radius: 16px 16px 0 0; max-height: 300px; }
    .floor-grid { grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); }
    .menu-grid { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); }
}
</style>

<div class="pos-dashboard">
    <div class="pos-sidebar">
        <div class="pos-tabs">
            <div class="pos-tab <?= $tab === 'floor' ? 'active' : '' ?>" data-tab="floor">
                <i class="fas fa-chair"></i> Sơ đồ bàn
            </div>
            <div class="pos-tab <?= $tab === 'menu' ? 'active' : '' ?>" data-tab="menu">
                <i class="fas fa-utensils"></i> Menu
            </div>
            <div class="pos-tab <?= $tab === 'order' ? 'active' : '' ?>" data-tab="order">
                <i class="fas fa-receipt"></i> Order
            </div>
            <div class="pos-tab <?= $tab === 'notif' ? 'active' : '' ?>" data-tab="notif">
                <i class="fas fa-bell"></i> Thông báo
                <?php if ($notifStats['unread'] > 0): ?>
                    <span style="background:#ef4444;color:white;padding:2px 6px;border-radius:10px;font-size:0.65rem"><?= $notifStats['unread'] ?></span>
                <?php endif; ?>
            </div>
            <div class="pos-tab <?= $tab === 'realtime' ? 'active' : '' ?>" data-tab="realtime">
                <i class="fas fa-satellite-dish"></i> Realtime
            </div>
        </div>
    </div>

    <div class="pos-content">
        <!-- FLOOR TAB -->
        <div id="tabFloor" class="tab-content" style="<?= $tab !== 'floor' ? 'display:none' : '' ?>">
            <div class="pos-header">
                <h2><i class="fas fa-chair"></i> Sơ đồ bàn</h2>
                <div class="pos-header-actions">
                    <div class="stat-box"><i class="fas fa-circle text-success"></i> <?= $counts['available'] ?> Trống</div>
                    <div class="stat-box"><i class="fas fa-circle text-danger"></i> <?= $counts['occupied'] ?> Bận</div>
                </div>
            </div>

            <div class="type-tabs">
                <a href="<?= BASE_URL ?>/admin/pos?tab=floor&type=table" class="type-tab <?= $type === 'table' ? 'active' : '' ?>">
                    <i class="fas fa-utensils"></i> Bàn nhà hàng
                </a>
                <a href="<?= BASE_URL ?>/admin/pos?tab=floor&type=room" class="type-tab <?= $type === 'room' ? 'active' : '' ?>">
                    <i class="fas fa-bed"></i> Khách lưu trú
                </a>
            </div>

            <?php $areasToRender = ($type === 'room') ? $room_areas : $other_areas; ?>
            <?php foreach ($areasToRender as $area => $tables): ?>
                <div class="area-section">
                    <div class="area-header">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3><?= $type === 'room' ? 'Tầng' : 'Khu' ?>: <?= e($area) ?></h3>
                    </div>
                    <div class="floor-grid">
                        <?php foreach ($tables as $t): ?>
                            <?php $isOccupied = $t['status'] === 'occupied'; ?>
                            <?php $isChild = !empty($t['parent_id']); ?>
                            <div class="floor-card <?= $isOccupied ? 'occupied' : 'available' ?> <?= $isChild ? 'merged-child' : '' ?>" data-table="<?= e(json_encode($t)) ?>">
                                <div class="floor-card-icon">
                                    <i class="fas <?= $isOccupied ? 'fa-user' : 'fa-chair' ?>"></i>
                                </div>
                                <div class="floor-card-name"><?= e($t['name']) ?></div>
                                <div class="floor-card-status">
                                    <?= $isOccupied ? 'Có khách' : 'Trống' ?>
                                    <?php if ($isChild): ?>
                                        <span style="color:#8b5cf6">(Ghép)</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($isOccupied): ?>
                                    <div class="floor-card-actions">
                                        <button class="floor-btn floor-btn-gold" onclick="event.stopPropagation();viewOrder(<?= $t['id'] ?>)">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </button>
                                        <button class="floor-btn floor-btn-blue" onclick="event.stopPropagation();openTransferModal(<?= $t['id'] ?>)">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <?php if (!$isChild): ?>
                                            <button class="floor-btn floor-btn-purple" onclick="event.stopPropagation();openMergeModal(<?= $t['id'] ?>)">
                                                <i class="fas fa-link"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="floor-btn floor-btn-ghost" onclick="event.stopPropagation();unmergeTable(<?= $t['id'] ?>)">
                                                <i class="fas fa-unlink"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!empty($vip1) || !empty($vip2)): ?>
                <div class="area-section">
                    <div class="area-header"><i class="fas fa-crown"></i><h3>VIP 1 & 2</h3></div>
                    <div class="floor-grid">
                        <?php foreach (array_merge($vip1, $vip2) as $t): ?>
                            <?php $isOccupiedVIP = $t['status'] === 'occupied'; ?>
                            <?php $isChildVIP = !empty($t['parent_id']); ?>
                            <div class="floor-card <?= $isOccupiedVIP ? 'occupied' : 'available' ?> <?= $isChildVIP ? 'merged-child' : '' ?>" data-table="<?= e(json_encode($t)) ?>">
                                <div class="floor-card-icon"><i class="fas <?= $isOccupiedVIP ? 'fa-crown' : 'fa-chair' ?>"></i></div>
                                <div class="floor-card-name"><?= e($t['name']) ?></div>
                                <div class="floor-card-status"><?= $isOccupiedVIP ? 'VIP - Có khách' : 'VIP - Trống' ?><?php if ($isChildVIP): ?><span style="color:#8b5cf6">(Ghép)</span><?php endif; ?></div>
                                <?php if ($isOccupiedVIP): ?>
                                    <div class="floor-card-actions">
                                        <button class="floor-btn floor-btn-gold" onclick="event.stopPropagation();viewOrder(<?= $t['id'] ?>)"><i class="fas fa-eye"></i></button>
                                        <button class="floor-btn floor-btn-blue" onclick="event.stopPropagation();openTransferModal(<?= $t['id'] ?>)"><i class="fas fa-exchange-alt"></i></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($vip3) || !empty($vip4)): ?>
                <div class="area-section">
                    <div class="area-header"><i class="fas fa-crown"></i><h3>VIP 3 & 4</h3></div>
                    <div class="floor-grid">
                        <?php foreach (array_merge($vip3, $vip4) as $t): ?>
                            <?php $isOccupiedVIP34 = $t['status'] === 'occupied'; ?>
                            <?php $isChildVIP34 = !empty($t['parent_id']); ?>
                            <div class="floor-card <?= $isOccupiedVIP34 ? 'occupied' : 'available' ?> <?= $isChildVIP34 ? 'merged-child' : '' ?>" data-table="<?= e(json_encode($t)) ?>">
                                <div class="floor-card-icon"><i class="fas <?= $isOccupiedVIP34 ? 'fa-crown' : 'fa-chair' ?>"></i></div>
                                <div class="floor-card-name"><?= e($t['name']) ?></div>
                                <div class="floor-card-status"><?= $isOccupiedVIP34 ? 'VIP - Có khách' : 'VIP - Trống' ?><?php if ($isChildVIP34): ?><span style="color:#8b5cf6">(Ghép)</span><?php endif; ?></div>
                                <?php if ($isOccupiedVIP34): ?>
                                    <div class="floor-card-actions">
                                        <button class="floor-btn floor-btn-gold" onclick="event.stopPropagation();viewOrder(<?= $t['id'] ?>)"><i class="fas fa-eye"></i></button>
                                        <button class="floor-btn floor-btn-blue" onclick="event.stopPropagation();openTransferModal(<?= $t['id'] ?>)"><i class="fas fa-exchange-alt"></i></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- MENU TAB -->
        <div id="tabMenu" class="tab-content" style="<?= $tab !== 'menu' ? 'display:none' : '' ?>">
            <div class="pos-header">
                <h2><i class="fas fa-utensils"></i> Menu</h2>
                <div class="pos-header-actions">
                    <select class="form-control" style="width:auto;padding:8px 12px;border-radius:10px" onchange="changeMenuType(this.value)">
                        <option value="asia" <?= $menuType === 'asia' ? 'selected' : '' ?>>🍜 Món Á</option>
                        <option value="europe" <?= $menuType === 'europe' ? 'selected' : '' ?>>🥩 Món Âu</option>
                        <option value="alacarte" <?= $menuType === 'alacarte' ? 'selected' : '' ?>>🍽 Ala Carte</option>
                        <option value="sets" <?= $menuType === 'sets' ? 'selected' : '' ?>>📦 Sets</option>
                        <option value="other" <?= $menuType === 'other' ? 'selected' : '' ?>>🥤 Đồ uống</option>
                    </select>
                    <?php if ($orderId > 0): ?>
                        <span style="background:#10b981;color:white;padding:6px 12px;border-radius:10px;font-size:0.8rem;font-weight:700">
                            <i class="fas fa-receipt"></i> Order #<?= $orderId ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="menu-search">
                <input type="text" placeholder="🔍 Tìm món..." onkeyup="searchMenu(this.value)">
            </div>

            <div class="menu-tabs">
                <?php foreach ($categories as $cat): ?>
                    <div class="menu-tab <?= ($cat['name'] === $categories[0]['name']) ? 'active' : '' ?>" data-cat="<?= e($cat['name']) ?>" onclick="filterMenuCategory(this)">
                        <?= e($cat['name']) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($menuType === 'sets' && !empty($sets)): ?>
                <div class="menu-grid" id="menuGrid">
                    <?php foreach ($sets as $set): ?>
                        <div class="menu-item-card" onclick="showSetDetail(<?= e(json_encode($set)) ?>)">
                            <div class="menu-item-img">
                                <?php if ($set['image']): ?>
                                    <img src="<?= BASE_URL ?>/public/uploads/<?= e($set['image']) ?>" alt="<?= e($set['name']) ?>">
                                <?php else: ?>
                                    <i class="fas fa-box-open"></i>
                                <?php endif; ?>
                            </div>
                            <div class="menu-item-name"><?= e($set['name']) ?></div>
                            <div class="menu-item-price"><?= formatPrice($set['price']) ?></div>
                            <?php if ($orderId > 0): ?>
                                <div class="menu-item-add" onclick="event.stopPropagation();addSetToOrder(<?= $set['id'] ?>)">
                                    <i class="fas fa-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="menu-grid" id="menuGrid">
                    <?php foreach ($groupedMenu as $catName => $items): ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $optsStr = $item['note_options'] ?? '';
                            $optsEnStr = $item['note_options_en'] ?? '';
                            $optsArr = array_filter(array_map('trim', explode(',', $optsStr)));
                            $optsEnArr = array_filter(array_map('trim', explode(',', $optsEnStr)));
                            $combinedOpts = [];
                            foreach ($optsArr as $idx => $optVal) {
                                $enVal = $optsEnArr[$idx] ?? '';
                                $combinedOpts[] = $enVal ? $optVal . ' / ' . $enVal : $optVal;
                            }
                            $itemOptsJson = json_encode($combinedOpts, JSON_UNESCAPED_UNICODE);
                            ?>
                            <div class="menu-item-card" data-cat="<?= e($catName) ?>" data-name="<?= strtolower(e($item['name'])) ?>" data-item="<?= e(json_encode($item)) ?>">
                                <div class="menu-item-img">
                                    <?php if ($item['image']): ?>
                                        <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>">
                                    <?php else: ?>
                                        <i class="fas fa-utensils"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="menu-item-name"><?= e($item['name']) ?></div>
                                <div class="menu-item-price"><?= formatPrice($item['price']) ?></div>
                                <?php if ($orderId > 0): ?>
                                    <div class="menu-item-add" onclick="event.stopPropagation();openAddItemModal(<?= $item['id'] ?>, '<?= addslashes(e($item['name'])) ?>', <?= htmlspecialchars($itemOptsJson) ?>)">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ORDER TAB -->
        <div id="tabOrder" class="tab-content" style="<?= $tab !== 'order' ? 'display:none' : '' ?>">
            <?php if ($orderId > 0 && $order): ?>
                <div class="order-detail">
                    <div class="order-header">
                        <div>
                            <div class="order-table-name"><?= e($tableModel->getFullDisplayName($tableId)) ?></div>
                            <div class="order-info">
                                <?= $order['guest_count'] ?> khách | <?= date('H:i', strtotime($order['opened_at'])) ?> | <?= e($order['waiter_name'] ?? 'Admin') ?>
                            </div>
                        </div>
                        <button class="btn btn-ghost" onclick="openGuestModal()">
                            <i class="fas fa-user-edit"></i>
                        </button>
                    </div>

                    <div class="order-items">
                        <?php foreach ($orderItems as $item): ?>
                            <?php
                            $optsStr = $item['note_options'] ?? '';
                            $optsEnStr = $item['note_options_en'] ?? '';
                            $optsArr = array_filter(array_map('trim', explode(',', $optsStr)));
                            $optsEnArr = array_filter(array_map('trim', explode(',', $optsEnStr)));
                            $combinedOpts = [];
                            foreach ($optsArr as $idx => $optVal) {
                                $enVal = $optsEnArr[$idx] ?? '';
                                $combinedOpts[] = $enVal ? $optVal . ' / ' . $enVal : $optVal;
                            }
                            $itemOptsJson = json_encode($combinedOpts, JSON_UNESCAPED_UNICODE);
                            ?>
                            <div class="order-item-row" data-item-id="<?= $item['id'] ?>" data-options="<?= htmlspecialchars($itemOptsJson) ?>">
                                <div class="order-item-name">
                                    <?= e($item['item_name']) ?>
                                    <?php if ($item['note']): ?>
                                        <span style="font-size:0.7rem;color:#d4af37">(<?= e($item['note']) ?>)</span>
                                    <?php endif; ?>
                                </div>
                                <div class="order-item-qty">
                                    <button class="cart-qty-btn" onclick="updateItemQty(<?= $item['id'] ?>, -1)"><i class="fas fa-minus"></i></button>
                                    <span style="font-weight:700"><?= $item['quantity'] ?></span>
                                    <button class="cart-qty-btn" onclick="updateItemQty(<?= $item['id'] ?>, 1)"><i class="fas fa-plus"></i></button>
                                </div>
                                <button class="floor-btn floor-btn-ghost" onclick="openItemNoteModal(<?= $item['id'] ?>, '<?= addslashes(e($item['item_name'])) ?>', <?= htmlspecialchars($itemOptsJson) ?>, '<?= addslashes(e($item['note'] ?? '')) ?>')"><i class="fas fa-pen"></i></button>
                                <div class="order-item-price"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                                <span class="order-item-status <?= $item['status'] ?? 'draft' ?>"><?= ucfirst($item['status'] ?? 'draft') ?></span>
                                <button class="floor-btn floor-btn-red" onclick="removeItem(<?= $item['id'] ?>)" style="margin-left:4px"><i class="fas fa-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-footer">
                        <div class="order-total"><?= formatPrice($orderTotal) ?></div>
                        <div class="order-actions">
                            <?php $draftCount = count(array_filter($orderItems, fn($i) => ($i['status'] ?? 'draft') === 'draft')); ?>
                            <?php $confirmedCount = count(array_filter($orderItems, fn($i) => in_array(($i['status'] ?? 'draft'), ['confirmed', 'cooking', 'served']))); ?>
                            <?php if ($draftCount > 0): ?>
                                <button class="btn btn-gold" onclick="confirmOrder()">
                                    <i class="fas fa-check"></i> Xác nhận (<?= $draftCount ?>)
                                </button>
                            <?php elseif ($orderTotal > 0): ?>
                                <button class="btn btn-green" onclick="openPaymentModal()">
                                    <i class="fas fa-credit-card"></i> Thanh toán
                                </button>
                            <?php else: ?>
                                <button class="btn btn-red" onclick="cancelOrder()">
                                    <i class="fas fa-times"></i> Hủy bàn
                                </button>
                            <?php endif; ?>
                            <?php if ($confirmedCount > 0): ?>
                                <button class="btn btn-ghost" onclick="openSplitModal()">
                                    <i class="fas fa-cut"></i> Tách món
                                </button>
                            <?php endif; ?>
                            <?php if ($orderTotal > 0): ?>
                                <button class="btn btn-ghost" onclick="printBill()">
                                    <i class="fas fa-print"></i> In
                                </button>
                                <button class="btn btn-ghost" onclick="openTransferModal(<?= $tableId ?>)">
                                    <i class="fas fa-exchange-alt"></i> Chuyển
                                </button>
                                <?php if (empty($table['parent_id'])): ?>
                                    <button class="btn btn-ghost" onclick="openMergeModal(<?= $tableId ?>)">
                                        <i class="fas fa-link"></i> Ghép
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-ghost" onclick="unmergeTable(<?= $tableId ?>)">
                                        <i class="fas fa-unlink"></i> Tách bàn
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div style="text-align:center;padding:40px">
                    <i class="fas fa-receipt fa-3x" style="color:#d4af37;opacity:0.3"></i>
                    <h3 style="margin-top:16px;color:#64748b">Chưa chọn order</h3>
                    <p style="color:#94a3b8;font-size:0.85rem">Click vào bàn ở tab "Sơ đồ bàn" để xem chi tiết</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- NOTIF TAB -->
        <div id="tabNotif" class="tab-content" style="<?= $tab !== 'notif' ? 'display:none' : '' ?>">
            <div class="pos-header">
                <h2><i class="fas fa-bell"></i> Thông báo</h2>
                <div class="pos-header-actions">
                    <button class="btn btn-ghost" onclick="markAllRead()" style="padding:6px 10px;font-size:0.75rem">
                        <i class="fas fa-check-double"></i> Đánh dấu đã đọc
                    </button>
                </div>
            </div>

            <div class="notif-list">
                <?php if (empty($notifications)): ?>
                    <div style="text-align:center;padding:30px;color:#64748b">Không có thông báo mới</div>
                <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                        <div class="notif-item <?= $notif['is_read'] ? '' : 'unread' ?>" onclick="handleNotif(<?= e(json_encode($notif)) ?>)">
                            <div class="notif-type">
                                <i class="fas <?= match($notif['type']) {
                                    'payment_request' => 'fa-credit-card',
                                    'new_order' => 'fa-utensils',
                                    'support_request' => 'fa-hand-paper',
                                    default => 'fa-bell'
                                } ?>"></i>
                                <?= ucfirst(str_replace('_', ' ', $notif['type'])) ?>
                            </div>
                            <div class="notif-message"><?= e($notif['message']) ?></div>
                            <div class="notif-time"><?= date('H:i', strtotime($notif['created_at'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- REALTIME TAB -->
        <div id="tabRealtime" class="tab-content" style="<?= $tab !== 'realtime' ? 'display:none' : '' ?>">
            <div class="pos-header">
                <h2><i class="fas fa-satellite-dish"></i> Giám sát trực tiếp</h2>
                <div class="pos-header-actions">
                    <span style="font-size:0.75rem;color:#64748b">
                        <i class="fas fa-clock"></i> Auto-refresh: 10s
                    </span>
                    <button class="btn btn-ghost" onclick="refreshRealtime()" style="padding:6px 10px;font-size:0.75rem">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="realtime-grid" id="realtimeGrid">
                <?php foreach ($realtimeOrders as $order): ?>
                    <?php $isIdle = empty($order['items']); ?>
                    <?php $idleSeconds = $isIdle ? (time() - strtotime($order['opened_at'])) : 0; ?>
                    <div class="realtime-card" id="realtime-<?= $order['id'] ?>">
                        <div class="realtime-card-header">
                            <div>
                                <div class="realtime-table-name"><?= e($order['full_name']) ?></div>
                                <?php if ($isIdle && $idleSeconds > 0): ?>
                                    <?php $remaining = max(0, 300 - $idleSeconds); ?>
                                    <div class="idle-badge-realtime <?= $remaining < 60 ? 'critical' : '' ?>">
                                        <i class="fas fa-clock"></i> Chưa gọi món: <?= floor($remaining / 60) ?>:<?= ($remaining % 60 < 10 ? '0' : '') . ($remaining % 60) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <span class="realtime-status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                        </div>
                        <div class="realtime-card-body">
                            <?php if (!empty($order['items'])): ?>
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="realtime-item">
                                        <div class="realtime-item-name"><?= e($item['item_name'] ?? $item['name']) ?></div>
                                        <div class="realtime-item-qty">x<?= $item['quantity'] ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="text-align:center;padding:20px;color:#f59e0b;font-weight:700">
                                    <i class="fas fa-hourglass-half"></i> Khách mới vào - chưa gọi món
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="realtime-card-footer">
                            <div class="realtime-total"><?= e($order['total_fmt']) ?></div>
                            <div class="realtime-actions">
                                <button class="floor-btn floor-btn-gold" onclick="viewOrder(<?= $order['table_id'] ?>)">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </button>
                                <button class="floor-btn floor-btn-blue" onclick="openTransferModal(<?= $order['table_id'] ?>)">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- CART PANEL (Floating) -->
<?php if ($orderId > 0): ?>
<div class="cart-panel" id="cartPanel">
    <div class="cart-header">
        <div class="cart-header-name"><?= e($tableModel->getFullDisplayName($tableId)) ?></div>
        <button onclick="hideCart()" style="background:none;border:none;color:white;cursor:pointer"><i class="fas fa-chevron-down"></i></button>
    </div>
    <div class="cart-body" id="cartBody">
        <?php foreach ($orderItems as $item): ?>
            <div class="cart-item" data-item-id="<?= $item['id'] ?>">
                <div class="cart-item-name"><?= e($item['item_name']) ?></div>
                <div class="cart-item-qty">
                    <button class="cart-qty-btn" onclick="updateItemQty(<?= $item['id'] ?>, -1)"><i class="fas fa-minus"></i></button>
                    <span><?= $item['quantity'] ?></span>
                    <button class="cart-qty-btn" onclick="updateItemQty(<?= $item['id'] ?>, 1)"><i class="fas fa-plus"></i></button>
                </div>
                <div class="cart-item-price"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            <span>Tổng</span>
            <span id="cartTotal"><?= formatPrice($orderTotal) ?></span>
        </div>
        <div class="cart-actions">
            <button class="cart-btn cart-btn-gold" onclick="switchTab('menu')"><i class="fas fa-plus"></i> Thêm món</button>
            <button class="cart-btn cart-btn-green" onclick="confirmOrder()"><i class="fas fa-check"></i> Xác nhận</button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- MODALS -->
<div class="modal-backdrop" id="modalOpenTable">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Mở bàn <span id="openTableName" style="color:#d4af37"></span></div>
            <button class="modal-close" onclick="closeModal('modalOpenTable')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size:0.85rem;color:#64748b;margin-bottom:12px">Số lượng khách</p>
            <div class="guest-grid" id="guestGrid">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <button class="guest-btn <?= $i === 2 ? 'selected' : '' ?>" onclick="selectGuest(<?= $i ?>)"><?= $i ?></button>
                <?php endfor; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modalOpenTable')">Hủy</button>
            <button class="btn btn-gold" onclick="submitOpenTable()"><i class="fas fa-check"></i> Mở bàn</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalPayment">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Thanh toán</div>
            <button class="modal-close" onclick="closeModal('modalPayment')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="text-align:center;margin-bottom:16px">
                <div style="font-size:0.75rem;color:#64748b">Số tiền</div>
                <div style="font-size:1.8rem;font-weight:800;color:#d4af37" id="paymentAmount"><?= formatPrice($orderTotal) ?></div>
            </div>
            <div style="display:flex;gap:10px;margin-bottom:16px">
                <label id="payCash" style="flex:1;padding:14px;background:#f8fafc;border:2px solid #d4af37;border-radius:10px;text-align:center;cursor:pointer" onclick="selectPayment('cash')">
                    <input type="radio" name="pay_method" value="cash" checked style="display:none">
                    <i class="fas fa-money-bill" style="color:#d4af37"></i>
                    <div style="font-size:0.8rem;font-weight:700;margin-top:4px">Tiền mặt</div>
                </label>
                <label id="payTransfer" style="flex:1;padding:14px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:10px;text-align:center;cursor:pointer" onclick="selectPayment('transfer')">
                    <input type="radio" name="pay_method" value="transfer" style="display:none">
                    <i class="fas fa-university" style="color:#64748b"></i>
                    <div style="font-size:0.8rem;font-weight:700;margin-top:4px">Chuyển khoản</div>
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modalPayment')">Hủy</button>
            <button class="btn btn-gold" onclick="submitPayment()"><i class="fas fa-credit-card"></i> Thanh toán</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalGuest">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Cập nhật số khách</div>
            <button class="modal-close" onclick="closeModal('modalGuest')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="guest-grid" id="guestUpdateGrid">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <button class="guest-btn <?= $i === ($order['guest_count'] ?? 2) ? 'selected' : '' ?>" onclick="selectGuestUpdate(<?= $i ?>)"><?= $i ?></button>
                <?php endfor; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-gold" onclick="submitGuestUpdate()"><i class="fas fa-check"></i> Lưu</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalTransfer">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Chuyển bàn</div>
            <button class="modal-close" onclick="closeModal('modalTransfer')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <select class="form-control" id="transferTarget">
                <option value="">-- Chọn bàn trống --</option>
                <?php foreach ($allTables as $t): ?>
                    <?php if ($t['status'] === 'available' && $t['id'] !== $tableId && empty($t['parent_id'])): ?>
                        <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($t['area']) ?>)</option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modalTransfer')">Hủy</button>
            <button class="btn btn-gold" onclick="submitTransfer()"><i class="fas fa-exchange-alt"></i> Chuyển</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalMerge">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Ghép bàn</div>
            <button class="modal-close" onclick="closeModal('modalMerge')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size:0.85rem;color:#64748b;margin-bottom:12px">Chọn bàn trống để ghép vào bàn hiện tại</p>
            <select class="form-control" id="mergeTarget">
                <option value="">-- Chọn bàn --</option>
                <?php foreach ($allTables as $t): ?>
                    <?php if ($t['status'] === 'available' && $t['id'] !== $tableId && empty($t['parent_id'])): ?>
                        <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($t['area']) ?>)</option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modalMerge')">Hủy</button>
            <button class="btn btn-gold" onclick="submitMerge()"><i class="fas fa-link"></i> Ghép</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalSplit">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tách món sang bàn mới</div>
            <button class="modal-close" onclick="closeModal('modalSplit')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom:12px">
                <label style="font-size:0.75rem;color:#64748b;font-weight:700">Bàn đích</label>
                <select class="form-control" id="splitTargetTable">
                    <option value="">-- Chọn bàn trống --</option>
                    <?php foreach ($allTables as $t): ?>
                        <?php if ($t['status'] === 'available' && $t['id'] !== $tableId && empty($t['parent_id'])): ?>
                            <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($t['area']) ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom:12px">
                <label style="font-size:0.75rem;color:#64748b;font-weight:700">Số khách (bàn mới)</label>
                <div style="display:flex;gap:6px">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <button class="guest-btn <?= $i === 2 ? 'selected' : '' ?>" onclick="selectSplitGuest(<?= $i ?>)" style="padding:6px 10px"><?= $i ?></button>
                    <?php endfor; ?>
                </div>
            </div>
            <div style="margin-bottom:12px">
                <label style="font-size:0.75rem;color:#64748b;font-weight:700">Món cần tách (<span id="splitSelectedCount">0</span>)</label>
                <div id="splitItemsList" style="max-height:200px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:8px;padding:8px">
                    <?php foreach ($orderItems as $item): ?>
                        <?php if (in_array(($item['status'] ?? 'draft'), ['confirmed', 'cooking', 'served'])): ?>
                            <div style="display:flex;align-items:center;padding:6px;border-bottom:1px solid #f1f5f9">
                                <input type="checkbox" class="split-item-cb" data-item-id="<?= $item['id'] ?>" onchange="updateSplitCount()">
                                <span style="flex:1;font-size:0.85rem"><?= e($item['item_name']) ?></span>
                                <span style="font-size:0.8rem;color:#64748b">x<?= $item['quantity'] ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modalSplit')">Hủy</button>
            <button class="btn btn-gold" onclick="submitSplit()"><i class="fas fa-cut"></i> Tách</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalItemNote">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Ghi chú món</div>
            <button class="modal-close" onclick="closeModal('modalItemNote')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="itemNoteName" style="font-weight:800;font-size:1rem;margin-bottom:12px"></div>
            <div id="itemNoteOptions" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px"></div>
            <input type="text" class="form-control" id="itemNoteText" placeholder="Ghi chú thêm (VD: Không hành, ít cay...)">
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modalItemNote')">Hủy</button>
            <button class="btn btn-gold" onclick="submitItemNote()"><i class="fas fa-check"></i> Lưu</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalAddItem">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="addItemName">Thêm món</div>
            <button class="modal-close" onclick="closeModal('modalAddItem')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="addItemOptions" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px"></div>
            <input type="text" class="form-control" id="addItemNote" placeholder="Ghi chú (VD: Không hành...)" style="margin-bottom:12px">
            <div style="display:flex;align-items:center;justify-content:center;gap:12px">
                <button class="guest-btn" onclick="changeAddQty(-1)"><i class="fas fa-minus"></i></button>
                <span id="addItemQty" style="font-size:1.5rem;font-weight:800;width:50px;text-align:center">1</span>
                <button class="guest-btn" onclick="changeAddQty(1)"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modalAddItem')">Hủy</button>
            <button class="btn btn-gold" onclick="submitAddItem()"><i class="fas fa-plus"></i> Thêm</button>
        </div>
    </div>
</div>

<script>
const POS = {
    baseUrl: '<?= BASE_URL ?>',
    orderId: <?= $orderId ?: 0 ?>,
    tableId: <?= $tableId ?: 0 ?>,
    menuType: '<?= $menuType ?>',
    selectedGuest: 2,
    selectedGuestUpdate: <?= $order['guest_count'] ?? 2 ?>,
    selectedPayment: 'cash',
    currentTableId: null
};

// ── Tab Switching ───────────────────────────────────────────
function switchTab(tab) {
    document.querySelectorAll('.pos-tab').forEach(function(t) {
        t.classList.remove('active');
    });
    
    const activeTab = document.querySelector('.pos-tab[data-tab="' + tab + '"]');
    if (activeTab) {
        activeTab.classList.add('active');
    }
    
    document.querySelectorAll('.tab-content').forEach(function(c) {
        c.style.display = 'none';
    });
    
    const tabId = 'tab' + tab.charAt(0).toUpperCase() + tab.slice(1);
    const tabContent = document.getElementById(tabId);
    if (tabContent) {
        tabContent.style.display = 'block';
    }
}

document.querySelectorAll('.pos-tab').forEach(function(tabEl) {
    tabEl.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        switchTab(this.dataset.tab);
    });
});

function viewOrder(tableId) {
    window.location.href = POS.baseUrl + '/admin/pos?tab=order&table_id=' + tableId;
}

function changeMenuType(type) {
    window.location.href = POS.baseUrl + '/admin/pos?tab=menu&menu_type=' + type + '&order_id=' + POS.orderId;
}

function searchMenu(keyword) {
    const kw = keyword.toLowerCase().trim();
    document.querySelectorAll('.menu-item-card').forEach(function(card) {
        const name = card.dataset.name || '';
        if (kw === '' || name.includes(kw)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function filterMenuCategory(tabEl) {
    document.querySelectorAll('.menu-tab').forEach(function(t) {
        t.classList.remove('active');
    });
    tabEl.classList.add('active');
    
    const cat = tabEl.dataset.cat;
    document.querySelectorAll('.menu-item-card').forEach(function(card) {
        if (cat === 'all' || card.dataset.cat === cat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

document.querySelectorAll('.floor-card').forEach(card => {
    card.addEventListener('click', function() {
        const table = JSON.parse(this.dataset.table);
        POS.currentTableId = table.id;
        
        if (table.status === 'occupied') {
            viewOrder(table.id);
        } else {
            document.getElementById('openTableName').textContent = table.name;
            openModal('modalOpenTable');
        }
    });
});

function openModal(id) {
    document.getElementById(id).classList.add('show');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}

function selectGuest(n) {
    POS.selectedGuest = n;
    document.querySelectorAll('#guestGrid .guest-btn').forEach(b => b.classList.remove('selected'));
    document.querySelector('#guestGrid .guest-btn:nth-child(' + n + ')').classList.add('selected');
}

function selectGuestUpdate(n) {
    POS.selectedGuestUpdate = n;
    document.querySelectorAll('#guestUpdateGrid .guest-btn').forEach(b => b.classList.remove('selected'));
    document.querySelector('#guestUpdateGrid .guest-btn:nth-child(' + n + ')').classList.add('selected');
}

function selectPayment(method) {
    POS.selectedPayment = method;
    const cash = document.getElementById('payCash');
    const transfer = document.getElementById('payTransfer');
    if (method === 'cash') {
        cash.style.borderColor = '#d4af37';
        cash.querySelector('i').style.color = '#d4af37';
        transfer.style.borderColor = '#e2e8f0';
        transfer.querySelector('i').style.color = '#64748b';
    } else {
        transfer.style.borderColor = '#d4af37';
        transfer.querySelector('i').style.color = '#d4af37';
        cash.style.borderColor = '#e2e8f0';
        cash.querySelector('i').style.color = '#64748b';
    }
}

function submitOpenTable() {
    fetch(POS.baseUrl + '/admin/pos/open-table', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ table_id: POS.currentTableId, guest_count: POS.selectedGuest })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalOpenTable');
            window.location.href = POS.baseUrl + '/admin/pos?tab=order&table_id=' + d.table_id + '&order_id=' + d.order_id;
        } else {
            alert(d.message || 'Lỗi mở bàn');
        }
    });
}

// ── Add Item with Note ────────────────────────────────────
let addItemItemId = 0;
let addItemQtyCount = 1;
let addItemSelectedOpts = [];

function openAddItemModal(itemId, itemName, options) {
    addItemItemId = itemId;
    addItemQtyCount = 1;
    addItemSelectedOpts = [];
    
    document.getElementById('addItemName').textContent = itemName;
    document.getElementById('addItemQty').textContent = '1';
    document.getElementById('addItemNote').value = '';
    
    const optsContainer = document.getElementById('addItemOptions');
    optsContainer.innerHTML = '';
    
    const opts = options ? JSON.parse(options) : [];
    opts.forEach(opt => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = opt;
        btn.className = 'guest-btn';
        btn.style.cssText = 'padding:6px 12px;font-size:0.8rem;';
        btn.onclick = function() {
            const idx = addItemSelectedOpts.indexOf(opt);
            if (idx >= 0) {
                addItemSelectedOpts.splice(idx, 1);
                this.classList.remove('selected');
            } else {
                addItemSelectedOpts.push(opt);
                this.classList.add('selected');
            }
        };
        optsContainer.appendChild(btn);
    });
    
    openModal('modalAddItem');
}

function changeAddQty(delta) {
    addItemQtyCount = Math.max(1, addItemQtyCount + delta);
    document.getElementById('addItemQty').textContent = addItemQtyCount;
}

function submitAddItem() {
    const freeNote = document.getElementById('addItemNote').value.trim();
    const parts = [...addItemSelectedOpts];
    if (freeNote) parts.push(freeNote);
    const note = parts.join(', ');
    
    fetch(POS.baseUrl + '/admin/pos/add-item', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: POS.orderId, table_id: POS.tableId, menu_item_id: addItemItemId, qty: addItemQtyCount, note: note })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalAddItem');
            POS.orderId = d.order_id;
            updateCartUI(d);
            if (POS.orderId > 0) showCart();
        } else {
            alert(d.message || 'Lỗi thêm món');
        }
    });
}

// ── Quick Add (no modal) ───────────────────────────────────
function addItemToOrder(itemId) {
    fetch(POS.baseUrl + '/admin/pos/add-item', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: POS.orderId, table_id: POS.tableId, menu_item_id: itemId, qty: 1, note: '' })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            POS.orderId = d.order_id;
            updateCartUI(d);
            if (POS.orderId > 0 && document.getElementById('cartPanel').style.display === 'none') {
                showCart();
            }
        } else {
            alert(d.message || 'Lỗi thêm món');
        }
    });
}

function updateItemQty(itemId, delta) {
    fetch(POS.baseUrl + '/admin/pos/update-item-qty', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId, order_id: POS.orderId, delta: delta })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) updateCartUI(d);
    });
}

function removeItem(itemId) {
    if (!confirm('Xóa món này?')) return;
    fetch(POS.baseUrl + '/admin/pos/remove-item', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId, order_id: POS.orderId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) updateCartUI(d);
    });
}

function updateCartUI(d) {
    document.getElementById('cartTotal').textContent = d.total_fmt;
    const body = document.getElementById('cartBody');
    body.innerHTML = d.items.map(it => `
        <div class="cart-item" data-item-id="${it.id}">
            <div class="cart-item-name">${it.item_name}${it.note ? '<span style="font-size:0.65rem;color:#d4af37">(' + it.note + ')</span>' : ''}</div>
            <div class="cart-item-qty">
                <button class="cart-qty-btn" onclick="updateItemQty(${it.id}, -1)"><i class="fas fa-minus"></i></button>
                <span>${it.quantity}</span>
                <button class="cart-qty-btn" onclick="updateItemQty(${it.id}, 1)"><i class="fas fa-plus"></i></button>
            </div>
            <div class="cart-item-price">${it.subtotal_fmt}</div>
        </div>
    `).join('');
    
    const orderBody = document.querySelector('.order-items');
    if (orderBody) {
        orderBody.innerHTML = d.items.map(it => `
            <div class="order-item-row" data-item-id="${it.id}" data-options="${it.note_options || '[]'}">
                <div class="order-item-name">${it.item_name}${it.note ? '<span style="font-size:0.7rem;color:#d4af37">(' + it.note + ')</span>' : ''}</div>
                <div class="order-item-qty">
                    <button class="cart-qty-btn" onclick="updateItemQty(${it.id}, -1)"><i class="fas fa-minus"></i></button>
                    <span style="font-weight:700">${it.quantity}</span>
                    <button class="cart-qty-btn" onclick="updateItemQty(${it.id}, 1)"><i class="fas fa-plus"></i></button>
                </div>
                <button class="floor-btn floor-btn-ghost" onclick="openItemNoteModal(${it.id}, '${it.item_name.replace(/'/g, "\\'")}', ${it.note_options || '[]'}, '${it.note || ''}')"><i class="fas fa-pen"></i></button>
                <div class="order-item-price">${it.subtotal_fmt}</div>
                <span class="order-item-status ${it.status || 'draft'}">${it.status || 'draft'}</span>
                <button class="floor-btn floor-btn-red" onclick="removeItem(${it.id})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('');
    }
    
    document.querySelector('.order-total')?.textContent = d.total_fmt;
}

function confirmOrder() {
    fetch(POS.baseUrl + '/admin/pos/confirm-order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: POS.orderId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            updateCartUI(d);
            location.reload();
        } else {
            alert(d.message || 'Lỗi xác nhận');
        }
    });
}

function openPaymentModal() {
    openModal('modalPayment');
}

function submitPayment() {
    fetch(POS.baseUrl + '/admin/pos/payment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ table_id: POS.tableId, order_id: POS.orderId, payment_method: POS.selectedPayment })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalPayment');
            alert('Thanh toán thành công!');
            window.location.href = POS.baseUrl + '/admin/pos?tab=floor';
        } else {
            alert(d.message || 'Lỗi thanh toán');
        }
    });
}

function cancelOrder() {
    if (!confirm('Hủy bàn này?')) return;
    fetch(POS.baseUrl + '/admin/pos/payment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ table_id: POS.tableId, order_id: POS.orderId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            window.location.href = POS.baseUrl + '/admin/pos?tab=floor';
        }
    });
}

function openGuestModal() {
    openModal('modalGuest');
}

function submitGuestUpdate() {
    fetch(POS.baseUrl + '/admin/pos/update-guest', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: POS.orderId, guest_count: POS.selectedGuestUpdate })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalGuest');
            location.reload();
        }
    });
}

function refreshRealtime() {
    fetch(POS.baseUrl + '/admin/pos/realtime-data')
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            const grid = document.getElementById('realtimeGrid');
            grid.innerHTML = d.data.map(o => `
                <div class="realtime-card">
                    <div class="realtime-card-header">
                        <div class="realtime-table-name">${o.full_name}</div>
                        <span class="realtime-status ${o.status}">${o.status}</span>
                    </div>
                    <div class="realtime-card-body">
                        ${o.items.map(it => `
                            <div class="realtime-item">
                                <div class="realtime-item-name">${it.item_name}</div>
                                <div class="realtime-item-qty">x${it.quantity}</div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="realtime-card-footer">
                        <div class="realtime-total">${o.total_fmt}</div>
                        <button class="floor-btn floor-btn-gold" onclick="viewOrder(${o.table_id})">Chi tiết</button>
                    </div>
                </div>
            `).join('');
        }
    });
}

function markAllRead() {
    fetch(POS.baseUrl + '/admin/pos/mark-notif-read', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: 0 })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) location.reload();
    });
}

function handleNotif(notif) {
    if (!notif.is_read) {
        fetch(POS.baseUrl + '/admin/pos/mark-notif-read', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: notif.id })
        });
    }
    if (notif.table_id) viewOrder(notif.table_id);
}

function showCart() {
    document.getElementById('cartPanel').style.display = 'flex';
}

function hideCart() {
    document.getElementById('cartPanel').style.display = 'none';
}

// ── Transfer ──────────────────────────────────────────────
function openTransferModal(tableId) {
    POS.transferTableId = tableId;
    openModal('modalTransfer');
}

function submitTransfer() {
    const targetId = parseInt(document.getElementById('transferTarget').value);
    if (!targetId) { alert('Chọn bàn đích'); return; }
    
    fetch(POS.baseUrl + '/admin/pos/transfer-table', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ from_table_id: POS.transferTableId, to_table_id: targetId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalTransfer');
            alert('Chuyển bàn thành công!');
            window.location.href = POS.baseUrl + '/admin/pos?tab=floor';
        } else {
            alert(d.message || 'Lỗi chuyển bàn');
        }
    });
}

// ── Merge/Unmerge ──────────────────────────────────────────
function openMergeModal(tableId) {
    POS.mergeParentId = tableId;
    openModal('modalMerge');
}

function submitMerge() {
    const childId = parseInt(document.getElementById('mergeTarget').value);
    if (!childId) { alert('Chọn bàn để ghép'); return; }
    
    fetch(POS.baseUrl + '/admin/pos/merge-table', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ parent_id: POS.mergeParentId, child_id: childId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalMerge');
            alert('Ghép bàn thành công!');
            location.reload();
        } else {
            alert(d.message || 'Lỗi ghép bàn');
        }
    });
}

function unmergeTable(tableId) {
    if (!confirm('Tách bàn này khỏi nhóm ghép?')) return;
    
    fetch(POS.baseUrl + '/admin/pos/unmerge-table', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ table_id: tableId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            alert('Tách bàn thành công!');
            location.reload();
        } else {
            alert(d.message || 'Lỗi tách bàn');
        }
    });
}

// ── Split Order ────────────────────────────────────────────
let splitGuestCount = 2;

function openSplitModal() {
    openModal('modalSplit');
}

function selectSplitGuest(n) {
    splitGuestCount = n;
    document.querySelectorAll('#modalSplit .guest-btn').forEach(b => b.classList.remove('selected'));
    event.target.classList.add('selected');
}

function updateSplitCount() {
    const count = document.querySelectorAll('.split-item-cb:checked').length;
    document.getElementById('splitSelectedCount').textContent = count;
}

function submitSplit() {
    const targetTableId = parseInt(document.getElementById('splitTargetTable').value);
    if (!targetTableId) { alert('Chọn bàn đích'); return; }
    
    const itemIds = [];
    document.querySelectorAll('.split-item-cb:checked').forEach(cb => {
        itemIds.push(parseInt(cb.dataset.itemId));
    });
    
    if (itemIds.length === 0) { alert('Chọn món cần tách'); return; }
    
    fetch(POS.baseUrl + '/admin/pos/split-order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: POS.orderId, target_table_id: targetTableId, guest_count: splitGuestCount, item_ids: itemIds })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalSplit');
            alert('Tách món thành công!');
            location.reload();
        } else {
            alert(d.message || 'Lỗi tách món');
        }
    });
}

// ── Print Bill ─────────────────────────────────────────────
function printBill() {
    window.open(POS.baseUrl + '/orders/print?order_id=' + POS.orderId, '_blank');
}

// ── Item Note ──────────────────────────────────────────────
let currentNoteItemId = 0;
let selectedNoteOptions = [];

function openItemNoteModal(itemId, itemName, options, currentNote) {
    currentNoteItemId = itemId;
    selectedNoteOptions = [];
    
    document.getElementById('itemNoteName').textContent = itemName;
    
    const optsContainer = document.getElementById('itemNoteOptions');
    optsContainer.innerHTML = '';
    
    const opts = options ? JSON.parse(options) : [];
    const currentParts = currentNote ? currentNote.split(',').map(s => s.trim()).filter(Boolean) : [];
    
    opts.forEach(opt => {
        const isActive = currentParts.includes(opt);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = opt;
        btn.style.cssText = `padding:6px 12px;border-radius:20px;font-size:0.8rem;cursor:pointer;border:2px solid ${isActive ? '#d4af37' : '#e2e8f0'};background:${isActive ? 'rgba(212,175,55,0.15)' : '#f8fafc'};color:${isActive ? '#d4af37' : '#64748b'};font-weight:700;`;
        btn.onclick = function() {
            const idx = selectedNoteOptions.indexOf(opt);
            if (idx >= 0) {
                selectedNoteOptions.splice(idx, 1);
                this.style.borderColor = '#e2e8f0';
                this.style.background = '#f8fafc';
                this.style.color = '#64748b';
            } else {
                selectedNoteOptions.push(opt);
                this.style.borderColor = '#d4af37';
                this.style.background = 'rgba(212,175,55,0.15)';
                this.style.color = '#d4af37';
            }
        };
        if (isActive) selectedNoteOptions.push(opt);
        optsContainer.appendChild(btn);
    });
    
    const freeText = currentParts.filter(p => !opts.includes(p)).join(', ');
    document.getElementById('itemNoteText').value = freeText;
    
    openModal('modalItemNote');
}

function submitItemNote() {
    const freeText = document.getElementById('itemNoteText').value.trim();
    const parts = [...selectedNoteOptions];
    if (freeText) parts.push(freeText);
    const note = parts.join(', ');
    
    fetch(POS.baseUrl + '/admin/pos/update-item-note', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: currentNoteItemId, order_id: POS.orderId, note: note })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            closeModal('modalItemNote');
            updateCartUI(d);
        } else {
            alert('Lỗi lưu ghi chú');
        }
    });
}

// ── Add note button to order items ──────────────────────────
document.querySelectorAll('.order-item-row').forEach(row => {
    const nameDiv = row.querySelector('.order-item-name');
    if (nameDiv) {
        const noteBtn = document.createElement('button');
        noteBtn.className = 'floor-btn floor-btn-ghost';
        noteBtn.innerHTML = '<i class="fas fa-pen"></i>';
        noteBtn.style.marginLeft = '4px';
        noteBtn.onclick = function(e) {
            e.stopPropagation();
            const itemData = row.dataset;
            openItemNoteModal(
                parseInt(itemData?.itemId || row.querySelector('.cart-qty-btn')?.onclick?.toString().match(/\d+/)?.[0] || 0),
                nameDiv.textContent.split('(')[0].trim(),
                itemData?.options || '[]',
                nameDiv.querySelector('span')?.textContent?.replace(/[()]/g, '') || ''
            );
        };
        row.querySelector('.order-item-qty')?.after(noteBtn);
    }
});

// ── Realtime with idle timer ───────────────────────────────
function refreshRealtime() {
    fetch(POS.baseUrl + '/admin/pos/realtime-data')
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            const grid = document.getElementById('realtimeGrid');
            grid.innerHTML = d.data.map(o => {
                let idleBadge = '';
                if (o.is_idle && o.idle_seconds > 0) {
                    const remaining = Math.max(0, 300 - o.idle_seconds);
                    const min = Math.floor(remaining / 60);
                    const sec = remaining % 60;
                    const critical = remaining < 60;
                    idleBadge = `<div class="idle-badge ${critical ? 'critical' : ''}"><i class="fas fa-clock"></i> ${min}:${sec < 10 ? '0' + sec : sec}</div>`;
                }
                return `
                <div class="realtime-card" id="realtime-${o.id}">
                    <div class="realtime-card-header">
                        <div>
                            <div class="realtime-table-name">${o.full_name}</div>
                            ${idleBadge}
                        </div>
                        <span class="realtime-status ${o.status}">${o.status}</span>
                    </div>
                    <div class="realtime-card-body">
                        ${o.items.length > 0 ? o.items.map(it => `
                            <div class="realtime-item">
                                <div class="realtime-item-name">${it.item_name}</div>
                                <div class="realtime-item-qty">x${it.quantity}</div>
                            </div>
                        `).join('') : '<div style="color:#64748b;font-size:0.8rem;text-align:center">Chưa có món</div>'}
                    </div>
                    <div class="realtime-card-footer">
                        <div class="realtime-total">${o.total_fmt}</div>
                        <div class="realtime-actions">
                            <button class="floor-btn floor-btn-gold" onclick="viewOrder(${o.table_id})">Chi tiết</button>
                            <button class="floor-btn floor-btn-blue" onclick="openTransferModal(${o.table_id})">Chuyển</button>
                        </div>
                    </div>
                </div>
                `;
            }).join('');
        }
    });
}

setInterval(refreshRealtime, 10000);
</script>