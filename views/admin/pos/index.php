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
.pos-dashboard { display: flex; height: calc(100vh - 120px); overflow: hidden; }
.pos-sidebar { width: 220px; background: #1e293b; border-right: 1px solid #334155; display: flex; flex-direction: column; }
.pos-tabs { flex: 1; display: flex; flex-direction: column; }
.pos-tab { padding: 14px 16px; color: #94a3b8; cursor: pointer; border-left: 3px solid transparent; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s; }
.pos-tab:hover { background: rgba(212,175,55,0.1); color: #d4af37; }
.pos-tab.active { background: rgba(212,175,55,0.15); border-left-color: #d4af37; color: #d4af37; }
.pos-tab i { width: 20px; text-align: center; }
.pos-content { flex: 1; overflow-y: auto; background: #f8fafc; padding: 16px; }
.pos-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 12px 16px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; }
.pos-header h2 { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0; }
.pos-header-actions { display: flex; gap: 8px; }

.floor-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; }
.floor-card { background: white; border-radius: 12px; padding: 12px; cursor: pointer; border: 2px solid #e2e8f0; transition: all 0.2s; min-height: 80px; display: flex; flex-direction: column; justify-content: center; align-items: center; }
.floor-card:hover { border-color: #d4af37; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(212,175,55,0.2); }
.floor-card.occupied { border-color: #ef4444; background: linear-gradient(135deg, #fef2f2 0%, white 100%); }
.floor-card.occupied:hover { border-color: #dc2626; }
.floor-card-name { font-size: 0.95rem; font-weight: 800; color: #1e293b; }
.floor-card-status { font-size: 0.65rem; color: #64748b; margin-top: 4px; }
.floor-card.occupied .floor-card-status { color: #ef4444; }
.floor-card-actions { display: flex; gap: 4px; margin-top: 8px; }
.floor-btn { padding: 4px 8px; border-radius: 6px; font-size: 0.6rem; font-weight: 700; cursor: pointer; border: none; }
.floor-btn-gold { background: #d4af37; color: white; }
.floor-btn-red { background: #ef4444; color: white; }
.floor-btn-ghost { background: #f1f5f9; color: #64748b; }

.menu-tabs { display: flex; gap: 6px; margin-bottom: 12px; }
.menu-tab { padding: 8px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; cursor: pointer; background: white; border: 1px solid #e2e8f0; color: #64748b; transition: all 0.2s; }
.menu-tab:hover { border-color: #d4af37; }
.menu-tab.active { background: #d4af37; color: white; border-color: #d4af37; }
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }
.menu-item-card { background: white; border-radius: 10px; padding: 10px; cursor: pointer; border: 1px solid #e2e8f0; transition: all 0.15s; }
.menu-item-card:hover { border-color: #d4af37; transform: scale(1.02); }
.menu-item-name { font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
.menu-item-price { font-size: 0.75rem; color: #d4af37; font-weight: 800; }
.menu-item-add { position: absolute; top: 6px; right: 6px; width: 28px; height: 28px; background: #d4af37; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; cursor: pointer; }

.cart-panel { position: fixed; right: 16px; bottom: 16px; width: 320px; background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; flex-direction: column; max-height: 400px; z-index: 100; }
.cart-header { padding: 12px 14px; background: #1e293b; border-radius: 16px 16px 0 0; color: white; display: flex; align-items: center; justify-content: space-between; }
.cart-header-name { font-weight: 800; font-size: 0.9rem; }
.cart-body { flex: 1; overflow-y: auto; padding: 10px; }
.cart-item { display: flex; align-items: center; padding: 8px; background: #f8fafc; border-radius: 8px; margin-bottom: 6px; }
.cart-item-name { flex: 1; font-size: 0.8rem; font-weight: 600; }
.cart-item-qty { display: flex; align-items: center; gap: 4px; }
.cart-qty-btn { width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; }
.cart-item-price { font-size: 0.8rem; font-weight: 700; color: #d4af37; margin-left: 8px; }
.cart-footer { padding: 12px; border-top: 1px solid #e2e8f0; }
.cart-total { display: flex; justify-content: space-between; font-size: 0.95rem; font-weight: 800; margin-bottom: 8px; }
.cart-actions { display: flex; gap: 8px; }
.cart-btn { padding: 10px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; cursor: pointer; border: none; flex: 1; text-align: center; }
.cart-btn-gold { background: #d4af37; color: white; }
.cart-btn-green { background: #10b981; color: white; }
.cart-btn-red { background: #ef4444; color: white; }

.order-detail { background: white; border-radius: 12px; padding: 16px; }
.order-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; }
.order-table-name { font-size: 1.2rem; font-weight: 800; color: #d4af37; }
.order-info { font-size: 0.75rem; color: #64748b; }
.order-items { max-height: 300px; overflow-y: auto; }
.order-item-row { display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #f1f5f9; }
.order-item-row:last-child { border-bottom: none; }
.order-item-name { flex: 1; font-size: 0.85rem; }
.order-item-qty { font-size: 0.8rem; color: #64748b; }
.order-item-price { font-size: 0.85rem; font-weight: 700; color: #1e293b; }
.order-item-status { font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; margin-left: 8px; }
.order-item-status.draft { background: #fef3c7; color: #92400e; }
.order-item-status.confirmed { background: #d1fae5; color: #065f46; }
.order-footer { padding-top: 12px; border-top: 2px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
.order-total { font-size: 1.1rem; font-weight: 800; color: #d4af37; }
.order-actions { display: flex; gap: 8px; }

.notif-list { max-height: 400px; overflow-y: auto; }
.notif-item { background: white; border-radius: 10px; padding: 12px; margin-bottom: 8px; border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.15s; }
.notif-item:hover { border-color: #d4af37; }
.notif-item.unread { border-left: 3px solid #d4af37; }
.notif-type { font-size: 0.7rem; color: #64748b; margin-bottom: 4px; }
.notif-message { font-size: 0.85rem; color: #1e293b; }
.notif-time { font-size: 0.7rem; color: #94a3b8; margin-top: 4px; }

.realtime-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
.realtime-card { background: white; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }
.realtime-card-header { padding: 12px 14px; background: #f8fafc; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }
.realtime-table-name { font-size: 1rem; font-weight: 800; color: #1e293b; }
.realtime-status { font-size: 0.7rem; padding: 3px 8px; border-radius: 6px; }
.realtime-status.open { background: #dcfce7; color: #166534; }
.realtime-status.closed { background: #f3f4f6; color: #6b7280; }
.realtime-card-body { padding: 10px 14px; max-height: 200px; overflow-y: auto; }
.realtime-item { display: flex; align-items: center; padding: 6px 0; border-bottom: 1px solid #f1f5f9; }
.realtime-item-name { flex: 1; font-size: 0.8rem; }
.realtime-item-qty { font-size: 0.75rem; color: #64748b; }
.realtime-card-footer { padding: 10px 14px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; }
.realtime-total { font-size: 0.9rem; font-weight: 800; color: #d4af37; }
.realtime-actions { display: flex; gap: 6px; }

.area-section { margin-bottom: 24px; }
.area-header { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; }
.area-header h3 { font-size: 0.95rem; font-weight: 800; color: #1e293b; margin: 0; }

.modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; }
.modal-backdrop.show { display: flex; }
.modal { background: white; border-radius: 16px; width: 90%; max-width: 400px; max-height: 80vh; overflow-y: auto; }
.modal-header { padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
.modal-title { font-size: 1rem; font-weight: 800; }
.modal-close { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; }
.modal-body { padding: 16px; }
.modal-footer { padding: 12px 16px; border-top: 1px solid #e2e8f0; display: flex; gap: 8px; }

.btn { padding: 10px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; border: none; }
.btn-gold { background: #d4af37; color: white; }
.btn-green { background: #10b981; color: white; }
.btn-red { background: #ef4444; color: white; }
.btn-ghost { background: #f1f5f9; color: #64748b; }

.guest-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; }
.guest-btn { padding: 10px; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; background: #f8fafc; border: 2px solid #e2e8f0; color: #1e293b; }
.guest-btn:hover { border-color: #d4af37; }
.guest-btn.selected { background: #d4af37; color: white; border-color: #d4af37; }

.form-control { padding: 10px; border-radius: 8px; border: 2px solid #e2e8f0; font-size: 0.85rem; width: 100%; }

.stat-box { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: white; border-radius: 8px; border: 1px solid #e2e8f0; margin-right: 8px; }
.stat-box i { color: #d4af37; }
.stat-box span { font-weight: 800; }

@media (max-width: 768px) {
    .pos-dashboard { flex-direction: column; }
    .pos-sidebar { width: 100%; height: auto; flex-direction: row; }
    .pos-tabs { flex-direction: row; overflow-x: auto; }
    .pos-tab { padding: 10px 12px; border-left: none; border-bottom: 3px solid transparent; }
    .pos-tab.active { border-bottom-color: #d4af37; }
    .cart-panel { width: 100%; right: 0; bottom: 0; border-radius: 16px 16px 0 0; max-height: 300px; }
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
                <h2><i class="fas fa-chair"></i> Sơ đồ bàn - <?= $type === 'room' ? 'Khách lưu trú' : 'Nhà hàng' ?></h2>
                <div class="pos-header-actions">
                    <div class="stat-box"><i class="fas fa-circle text-success"></i> <?= $counts['available'] ?> Trống</div>
                    <div class="stat-box"><i class="fas fa-circle text-danger"></i> <?= $counts['occupied'] ?> Bận</div>
                    <a href="<?= BASE_URL ?>/admin/pos?tab=floor&type=<?= $type === 'table' ? 'room' : 'table' ?>" class="btn btn-ghost" style="padding:6px 10px;font-size:0.75rem">
                        <i class="fas fa-exchange-alt"></i> <?= $type === 'table' ? 'Phòng' : 'Bàn' ?>
                    </a>
                </div>
            </div>

            <?php $areasToRender = ($type === 'room') ? $room_areas : $other_areas; ?>
            <?php foreach ($areasToRender as $area => $tables): ?>
                <div class="area-section">
                    <div class="area-header">
                        <i class="fas fa-map-marker-alt" style="color:#d4af37"></i>
                        <h3><?= $type === 'room' ? 'Tầng' : 'Khu' ?>: <?= e($area) ?></h3>
                    </div>
                    <div class="floor-grid">
                        <?php foreach ($tables as $t): ?>
                            <?php $isOccupied = $t['status'] === 'occupied'; ?>
                            <div class="floor-card <?= $isOccupied ? 'occupied' : '' ?>" data-table="<?= e(json_encode($t)) ?>">
                                <div class="floor-card-name"><?= e($t['name']) ?></div>
                                <div class="floor-card-status"><?= $isOccupied ? 'Có khách' : 'Trống' ?></div>
                                <?php if ($isOccupied): ?>
                                    <div class="floor-card-actions">
                                        <button class="floor-btn floor-btn-gold" onclick="viewOrder(<?= $t['id'] ?>)">Chi tiết</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!empty($vip1) || !empty($vip2)): ?>
                <div class="area-section">
                    <div class="area-header"><i class="fas fa-crown" style="color:#d4af37"></i><h3>VIP 1 & 2</h3></div>
                    <div class="floor-grid">
                        <?php foreach (array_merge($vip1, $vip2) as $t): ?>
                            <div class="floor-card <?= $t['status'] === 'occupied' ? 'occupied' : '' ?>" data-table="<?= e(json_encode($t)) ?>">
                                <div class="floor-card-name"><?= e($t['name']) ?></div>
                                <div class="floor-card-status"><?= $t['status'] === 'occupied' ? 'Có khách' : 'Trống' ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($vip3) || !empty($vip4)): ?>
                <div class="area-section">
                    <div class="area-header"><i class="fas fa-crown" style="color:#d4af37"></i><h3>VIP 3 & 4</h3></div>
                    <div class="floor-grid">
                        <?php foreach (array_merge($vip3, $vip4) as $t): ?>
                            <div class="floor-card <?= $t['status'] === 'occupied' ? 'occupied' : '' ?>" data-table="<?= e(json_encode($t)) ?>">
                                <div class="floor-card-name"><?= e($t['name']) ?></div>
                                <div class="floor-card-status"><?= $t['status'] === 'occupied' ? 'Có khách' : 'Trống' ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- MENU TAB -->
        <div id="tabMenu" class="tab-content" style="<?= $tab !== 'menu' ? 'display:none' : '' ?>">
            <div class="pos-header">
                <h2><i class="fas fa-utensils"></i> Menu - <?= ucfirst($menuType) ?></h2>
                <div class="pos-header-actions">
                    <select class="form-control" style="width:auto;padding:6px 10px" onchange="changeMenuType(this.value)">
                        <option value="asia" <?= $menuType === 'asia' ? 'selected' : '' ?>>Món Á</option>
                        <option value="europe" <?= $menuType === 'europe' ? 'selected' : '' ?>>Món Âu</option>
                        <option value="alacarte" <?= $menuType === 'alacarte' ? 'selected' : '' ?>>Ala Carte</option>
                        <option value="sets" <?= $menuType === 'sets' ? 'selected' : '' ?>>Sets</option>
                        <option value="other" <?= $menuType === 'other' ? 'selected' : '' ?>>Đồ uống</option>
                    </select>
                    <?php if ($orderId > 0): ?>
                        <span style="background:#10b981;color:white;padding:4px 10px;border-radius:8px;font-size:0.75rem">
                            Order #<?= $orderId ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="menu-tabs">
                <?php foreach ($categories as $cat): ?>
                    <div class="menu-tab" data-cat="<?= e($cat['name']) ?>"><?= e($cat['name']) ?></div>
                <?php endforeach; ?>
            </div>

            <?php if ($menuType === 'sets' && !empty($sets)): ?>
                <div class="menu-grid">
                    <?php foreach ($sets as $set): ?>
                        <div class="menu-item-card" onclick="showSetDetail(<?= e(json_encode($set)) ?>)">
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
                <?php foreach ($groupedMenu as $catName => $items): ?>
                    <div class="area-section" data-cat="<?= e($catName) ?>">
                        <div class="area-header"><h3><?= e($catName) ?></h3></div>
                        <div class="menu-grid">
                            <?php foreach ($items as $item): ?>
                                <div class="menu-item-card" data-item="<?= e(json_encode($item)) ?>">
                                    <div class="menu-item-name"><?= e($item['name']) ?></div>
                                    <div class="menu-item-price"><?= formatPrice($item['price']) ?></div>
                                    <?php if ($orderId > 0): ?>
                                        <div class="menu-item-add" onclick="addItemToOrder(<?= $item['id'] ?>)">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
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
                            <div class="order-item-row">
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
                    <button class="btn btn-ghost" onclick="refreshRealtime()" style="padding:6px 10px;font-size:0.75rem">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="realtime-grid" id="realtimeGrid">
                <?php foreach ($realtimeOrders as $order): ?>
                    <div class="realtime-card" id="realtime-<?= $order['id'] ?>">
                        <div class="realtime-card-header">
                            <div class="realtime-table-name"><?= e($order['full_name']) ?></div>
                            <span class="realtime-status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                        </div>
                        <div class="realtime-card-body">
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="realtime-item">
                                    <div class="realtime-item-name"><?= e($item['item_name'] ?? $item['name']) ?></div>
                                    <div class="realtime-item-qty">x<?= $item['quantity'] ?></div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($order['items'])): ?>
                                <div style="color:#64748b;font-size:0.8rem;text-align:center">Chưa có món</div>
                            <?php endif; ?>
                        </div>
                        <div class="realtime-card-footer">
                            <div class="realtime-total"><?= e($order['total_fmt']) ?></div>
                            <div class="realtime-actions">
                                <button class="floor-btn floor-btn-gold" onclick="viewOrder(<?= $order['table_id'] ?>)">Chi tiết</button>
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
                    <?php if ($t['status'] === 'available' && $t['id'] !== $tableId): ?>
                        <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($t['area']) ?>)</option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn btn-gold" onclick="submitTransfer()"><i class="fas fa-exchange-alt"></i> Chuyển</button>
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

function switchTab(tab) {
    document.querySelectorAll('.pos-tab').forEach(t => t.classList.remove('active'));
    document.querySelector('.pos-tab[data-tab="' + tab + '"]').classList.add('active');
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).style.display = 'block';
}

document.querySelectorAll('.pos-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        switchTab(this.dataset.tab);
    });
});

function viewOrder(tableId) {
    window.location.href = POS.baseUrl + '/admin/pos?tab=order&table_id=' + tableId;
}

function changeMenuType(type) {
    window.location.href = POS.baseUrl + '/admin/pos?tab=menu&menu_type=' + type + '&order_id=' + POS.orderId;
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

function addItemToOrder(itemId) {
    fetch(POS.baseUrl + '/admin/pos/add-item', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: POS.orderId, table_id: POS.tableId, menu_item_id: itemId, qty: 1 })
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
        <div class="cart-item">
            <div class="cart-item-name">${it.item_name}</div>
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
            <div class="order-item-row">
                <div class="order-item-name">${it.item_name}</div>
                <div class="order-item-qty">
                    <button class="cart-qty-btn" onclick="updateItemQty(${it.id}, -1)"><i class="fas fa-minus"></i></button>
                    <span style="font-weight:700">${it.quantity}</span>
                    <button class="cart-qty-btn" onclick="updateItemQty(${it.id}, 1)"><i class="fas fa-plus"></i></button>
                </div>
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

setInterval(refreshRealtime, 10000);
</script>