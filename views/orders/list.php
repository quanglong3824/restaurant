<?php // views/orders/list.php — Danh sách tất cả các bàn đang order (đang bận) ?>
<div class="page-content">

    <div class="order-list-header">
        <div>
            <h1>Quản lý Bàn Đang Bận</h1>
            <p>Tổng số <strong><?= count($orders) ?></strong> bàn đang được phục vụ</p>
        </div>

        <!-- Filter Tabs -->
        <div class="order-filters" id="areaFilterContainer">
            <button class="filter-btn is-active" data-area="all">Tất cả</button>
            <?php foreach ($areas as $area): ?>
                <button class="filter-btn" data-area="<?= e($area) ?>">Khu <?= e($area) ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="order-list-body" id="orderListBody">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class="fas fa-coffee"></i>
                <h3>Thảnh thơi tay chân!</h3>
                <p>Hiện không có bàn nào đang phục vụ.</p>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $o): ?>
                <?php
                // Calculate wait time
                $openedTime = strtotime($o['opened_at']);
                $diffMins = floor((time() - $openedTime) / 60);
                $timeDisplay = $diffMins > 60
                    ? floor($diffMins / 60) . 'h ' . ($diffMins % 60) . 'm'
                    : $diffMins . ' phút';
                $timeClass = $diffMins > 30 ? '' : 'is-normal';
                ?>
                <div class="order-row-card" 
                    data-area="<?= e($o['table_area']) ?>"
                    onclick="window.location.href='<?= BASE_URL ?>/orders?table_id=<?= $o['table_id'] ?>&order_id=<?= $o['id'] ?>'">
                    
                    <div class="order-row-main">
                        <div class="order-table-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="order-table-info">
                            <h3>
                                <?= e($o['table_name']) ?>
                                <span class="badge-order-id">#<?= $o['id'] ?></span>
                            </h3>
                            <?php 
                            // Hiển thị notification badges nếu có
                            $tableId = $o['table_id'];
                            $hasNotifications = !empty($notificationsByTable[$tableId]);
                            if ($hasNotifications): 
                            ?>
                                <div class="notification-badges">
                                    <?php foreach ($notificationsByTable[$tableId] as $notif): 
                                        $type = $notif['type'];
                                        $count = $notif['count'];
                                        $badgeConfig = [
                                            'call_staff' => ['icon' => 'fa-bell', 'label' => 'Gọi NV', 'class' => 'badge-call'],
                                            'request_payment' => ['icon' => 'fa-credit-card', 'label' => 'Thanh toán', 'class' => 'badge-payment'],
                                            'new_qr_order' => ['icon' => 'fa-qrcode', 'label' => 'Đơn QR', 'class' => 'badge-qr'],
                                        ];
                                        $config = $badgeConfig[$type] ?? ['icon' => 'fa-bell', 'label' => $type, 'class' => 'badge-default'];
                                    ?>
                                        <span class="notif-badge <?= $config['class'] ?>">
                                            <i class="fas <?= $config['icon'] ?>"></i>
                                            <?= $config['label'] ?>
                                            <?php if ($count > 1): ?>
                                                <span class="badge-count"><?= $count ?></span>
                                            <?php endif; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($o['note'])): ?>
                                <div style="font-size: 0.7rem; color: var(--gold-dark); margin-top: <?= $hasNotifications ? '2px' : '-2px' ?>; margin-bottom: 4px;">
                                    <i class="fas fa-info-circle"></i> <?= e($o['note']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="order-table-meta">
                                <span>
                                    <i class="fas fa-user-friends"></i>
                                    <?= $o['guest_count'] ?> khách
                                </span>
                                <span>
                                    <i class="fas fa-map-marker-alt"></i>
                                    Khu <?= e($o['table_area']) ?>
                                </span>
                                <span>
                                    <i class="fas fa-user-tie"></i>
                                    <?= e($o['waiter_name']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-row-stats">
                        <div class="stat-box">
                            <div class="label">Thời gian</div>
                            <div class="value time-display <?= $timeClass ?>">
                                <i class="far fa-clock"></i>
                                <?= $timeDisplay ?>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="label">Đã gọi</div>
                            <div class="value">
                                <?= $o['item_count'] ?> món
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="label">Tổng tiền</div>
                            <div class="value price">
                                <?= formatPrice($o['total']) ?>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right chevron-icon"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Empty state for filters -->
        <div id="emptyFilterState" class="empty-state" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>Không có bàn nào</h3>
            <p>Khu vực này hiện không có bàn đang phục vụ.</p>
        </div>
    </div>

</div>

<!-- External CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/list.css">

<!-- External JavaScript -->
<script src="<?= BASE_URL ?>/public/js/orders/list.js"></script>
