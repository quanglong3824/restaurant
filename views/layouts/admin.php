<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#d4af37">
    <title><?= e($pageTitle ?? 'Admin') ?> — Aurora Restaurant</title>

    <!-- App Icons & iOS Web App Meta -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/src/logo/favicon.png">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/public/src/logo/favicon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= e(APP_NAME) ?>">

    <!-- Google Fonts: Outfit & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- QRCode JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= asset('public/css/admin.css') ?>">
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="<?= asset('public/css/' . e($pageCSS) . '.css') ?>">
    <?php endif; ?>
</head>

<body class="admin-layout">

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-crown"></i>
                <div>
                    <h2>AURORA</h2>
                    <p>Management System</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Nav -->
        <nav class="sidebar-nav">

            <?php if (Auth::isAdmin()): ?>
                <div class="nav-section">
                    <div class="nav-section-title">VẬN HÀNH</div>
                    <a href="<?= BASE_URL ?>/admin/realtime" class="nav-item <?= activeClass('/admin/realtime') ?>">
                        <i class="fas fa-satellite-dish"></i>
                        <span>Giám sát trực tiếp</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/realtime/qr-sessions" class="nav-item <?= activeClass('/admin/realtime/qr-sessions') ?>">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Giám sát phiên QR</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/shifts" class="nav-item <?= activeClass('/admin/shifts') ?>">
                        <i class="fas fa-user-clock"></i>
                        <span>Nhân sự & Ca trực</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">THỰC ĐƠN & BÀN</div>
                    <a href="<?= BASE_URL ?>/admin/menu" class="nav-item <?= activeClass('/admin/menu') ?>">
                        <i class="fas fa-utensils"></i>
                        <span>Danh sách món</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/menu/sets" class="nav-item <?= activeClass('/admin/menu/sets') ?>">
                        <i class="fas fa-layer-group"></i>
                        <span>Set & Combo</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/menu-types" class="nav-item <?= activeClass('/admin/menu-types') ?>">
                        <i class="fas fa-layer-group"></i>
                        <span>Phân loại menu</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/categories" class="nav-item <?= activeClass('/admin/categories') ?>">
                        <i class="fas fa-tags"></i>
                        <span>Danh mục món</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/tables" class="nav-item <?= activeClass('/admin/tables') ?>">
                        <i class="fas fa-table-cells-large"></i>
                        <span>Sơ đồ bàn ăn</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">CÔNG CỤ</div>
                    <a href="javascript:void(0)" class="nav-item" onclick="testNotifSound()">
                        <i class="fas fa-volume-up"></i>
                        <span>Kiểm tra âm thanh</span>
                    </a>
                    <a href="<?= BASE_URL ?>/it/database" class="nav-item <?= activeClass('/it/database') ?>">
                        <i class="fas fa-database"></i>
                        <span>Sao lưu dữ liệu</span>
                    </a>
                </div>

                <script>
                function testNotifSound() {
                    const audio = new Audio('<?= BASE_URL ?>/public/audio/nofi.mp3');
                    audio.play().catch(e => {
                        alert('Không thể phát âm thanh. Vui lòng kiểm tra quyền truy cập âm thanh trên trình duyệt.');
                        console.error(e);
                    });
                }
                </script>

                <div class="nav-section">
                    <div class="nav-section-title">DOANH THU</div>
                    <a href="<?= BASE_URL ?>/admin/reports" class="nav-item <?= activeClass('/admin/reports') ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Báo cáo thống kê</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">HỆ THỐNG</div>
                    <a href="<?= BASE_URL ?>/admin/activity" class="nav-item <?= activeClass('/admin/activity') ?>">
                        <i class="fas fa-history"></i>
                        <span>Nhật ký hoạt động</span>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (Auth::isIT()): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Quản trị IT</div>
                    <a href="<?= BASE_URL ?>/it/users" class="nav-item <?= activeClass('/it/users') ?>">
                        <i class="fas fa-users"></i>
                        <span>Quản lý User</span>
                    </a>
                    <a href="<?= BASE_URL ?>/it/settings" class="nav-item <?= activeClass('/it/settings') ?>">
                        <i class="fas fa-cog"></i>
                        <span>Cài đặt hệ thống</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/menu/clear" class="nav-item <?= activeClass('/admin/menu/clear') ?>">
                        <i class="fas fa-trash-alt"></i>
                        <span>Xóa dữ liệu thực đơn</span>
                    </a>
                </div>
            <?php endif; ?>

        </nav>

        <!-- Sidebar Footer - User Info & Logout -->
        <div class="sidebar-footer">
            <div class="sidebar-user-card">
                <div class="sidebar-user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?= e(Auth::user()['name'] ?? 'User') ?></div>
                    <div class="sidebar-user-role"><?= e(roleLabel(Auth::user()['role'] ?? '')) ?></div>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/auth/logout" class="sidebar-logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
            <div class="sidebar-version">
                AURORA v<?= APP_VERSION ?>
            </div>
        </div>

    </aside>


    <!-- Main Content -->
    <div class="admin-body">

        <!-- Page Header -->
        <div class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="admin-topbar-title">
                <h1><?= e($pageTitle ?? '') ?></h1>
                <?php if (isset($pageSubtitle)): ?>
                    <p><?= e($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>

            <!-- Notification Area -->
            <div class="topbar-right">
                <!-- Help Button -->
                <button class="help-btn" id="helpBtn" title="Hướng dẫn sử dụng">
                    <i class="fas fa-circle-question"></i>
                    <span>Hướng dẫn</span>
                </button>

                <div class="notification-area" id="notificationArea">
                    <button class="notification-bell" id="notificationBell">
                        <i class="fas fa-bell"></i>
                        <span class="notification-count" id="notificationCount"></span>
                    </button>
                    <div class="notification-panel" id="notificationPanel">
                        <div class="notification-panel-header">
                            <span>Thông báo</span>
                            <button class="btn-ghost small" id="markAllAsReadBtn">Đánh dấu đã đọc</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <!-- Notifications will be injected here by JavaScript -->
                            <div class="notification-item empty">Chưa có thông báo mới.</div>
                        </div>
                    </div>
                </div>

                <div class="topbar-divider"></div>

                <div class="topbar-user">
                    <div class="user-info">
                        <strong><?= e(Auth::user()['name'] ?? '') ?></strong>
                        <span><?= e(roleLabel(Auth::user()['role'] ?? '')) ?></span>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Content -->
        <main class="admin-main">
            <?php
            // Flash message
            if (!empty($_SESSION['flash'])):
                $fType = $_SESSION['flash']['type'];
                $fIcon = match ($fType) {
                    'success' => 'check-circle',
                    'warning' => 'exclamation-triangle',
                    'danger' => 'times-circle',
                    default => 'info-circle',
                };
                ?>
                <div class="alert alert-<?= e($fType) ?>" data-autohide="4000" role="alert">
                    <i class="fas fa-<?= $fIcon ?>" aria-hidden="true"></i>
                    <?= e($_SESSION['flash']['message']) ?>
                </div>
                <?php unset($_SESSION['flash']); endif; ?>

            <?php require BASE_PATH . "/views/{$view}.php"; ?>
        </main>

    </div>

    <!-- Help Modal -->
    <div id="helpModal" class="help-modal">
        <div class="help-modal-content">
            <div class="help-modal-header">
                <div class="help-modal-title">
                    <i class="fas fa-circle-question"></i>
                    <span>Hướng Dẫn Thao Tác và Sử Dụng</span>
                </div>
                <button class="help-modal-close" id="helpModalClose">&times;</button>
            </div>
            <div class="help-modal-body">
                
                <!-- Section 1: Giám sát trực tiếp -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-satellite-dish"></i> Giám Sát Trực Tiếp</h3>
                        <p class="help-desc">Theo dõi toàn bộ hoạt động của nhà hàng theo thời gian thực</p>
                        <ul class="help-list">
                            <li><strong>Xem trạng thái bàn:</strong> Bàn đang phục vụ, bàn trống, thời gian mở bàn</li>
                            <li><strong>Theo dõi đơn hàng:</strong> Xem chi tiết món đã gọi, tổng tiền từng bàn</li>
                            <li><strong>Doanh thu tạm tính:</strong> Thống kê doanh thu theo thời gian thực</li>
                            <li><strong>Hoàn tất đơn:</strong> Nút "HOÀN TẤT & LƯU TRỮ" để kết thúc phiên bàn</li>
                            <li><strong>Tự động reload:</strong> Dữ liệu tự động cập nhật mỗi 8 giây</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 2: QR Sessions -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-mobile-alt"></i> Giám Sát Phiên QR</h3>
                        <p class="help-desc">Theo dõi các phiên khách quét mã QR đặt món</p>
                        <ul class="help-list">
                            <li><strong>Phiên đang hoạt động:</strong> Xem khách nào đang đặt món qua QR</li>
                            <li><strong>Thời gian phiên:</strong> Theo dõi thời gian còn lại của phiên</li>
                            <li><strong>Đơn hàng từ QR:</strong> Xem món khách tự đặt qua quét mã</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 3: Nhân sự & Ca trực -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-user-clock"></i> Nhân Sự & Ca Trực</h3>
                        <p class="help-desc">Quản lý nhân viên và phân công ca làm việc</p>
                        <ul class="help-list">
                            <li><strong>Danh sách nhân viên:</strong> Xem danh sách server/waiter</li>
                            <li><strong>Phân ca:</strong> Gán nhân viên vào ca trực</li>
                            <li><strong>Theo dõi hiệu suất:</strong> Xem số bàn/nhân viên phụ trách</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 4: Quản lý Món ăn -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-utensils"></i> Quản Lý Món Ăn</h3>
                        <p class="help-desc">Thêm, sửa, xóa và quản lý thực đơn</p>
                        <ul class="help-list">
                            <li><strong>Thêm món mới:</strong> Nhập tên (VI/EN), mô tả, giá (VND), chọn danh mục, loại menu</li>
                            <li><strong>Nhập giá:</strong> Giá trị nguyên tối đa 10 chữ số (VD: 99999, 198552, 1234567890)</li>
                            <li><strong>Ảnh món:</strong> Upload ảnh đại diện và bộ sưu tập ảnh</li>
                            <li><strong>Tags:</strong> Đánh dấu "Bán chạy", "Mới", "Cay", "Chay", "Đề xuất"</li>
                            <li><strong>Tùy chọn ghi chú:</strong> Thêm các option như "Ít cay", "Không hành",...</li>
                            <li><strong>Stock:</strong> Quản lý tồn kho, nhập -1 nếu không giới hạn</li>
                            <li><strong>Lọc & Tìm kiếm:</strong> Lọc theo danh mục, trạng thái, loại menu, tag, khoảng giá</li>
                            <li><strong>Toggle hiển thị:</strong> Bật/tắt hiển thị món trong menu</li>
                            <li><strong>Toggle còn hàng:</strong> Đánh dấu món còn/hết hàng</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 5: Set & Combo -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-layer-group"></i> Set & Combo</h3>
                        <p class="help-desc">Tạo các set món combo theo nhóm</p>
                        <ul class="help-list">
                            <li><strong>Tạo set mới:</strong> Đặt tên, mô tả, giá set</li>
                            <li><strong>Thêm món vào set:</strong> Chọn các món lẻ để đưa vào set</li>
                            <li><strong>Sắp xếp:</strong> Tùy chỉnh thứ tự hiển thị</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 6: Phân loại Menu -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-layer-group"></i> Phân Loại Menu</h3>
                        <p class="help-desc">Định nghĩa các loại menu (Món Á, Món Âu, Alacarte...)</p>
                        <ul class="help-list">
                            <li><strong>Thêm loại:</strong> Tạo loại menu mới với tên VI/EN</li>
                            <li><strong>Quản lý:</strong> Sửa, xóa, ẩn/hiện loại menu</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 7: Danh Mục Món -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-tags"></i> Danh Mục Món</h3>
                        <p class="help-desc">Phân nhóm món ăn (Khai vị, Món chính, Tráng miệng...)</p>
                        <ul class="help-list">
                            <li><strong>Thêm danh mục:</strong> Nhập tên, mô tả, icon</li>
                            <li><strong>Sắp xếp:</strong> Kéo thả hoặc nhập số thứ tự</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 8: Sơ Đồ Bàn Ăn -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-table-cells-large"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-table-cells-large"></i> Sơ Đồ Bàn Ăn</h3>
                        <p class="help-desc">Quản lý bàn ăn và phòng lưu trú</p>
                        <ul class="help-list">
                            <li><strong>Thêm bàn/phòng:</strong> Nhập tên, khu vực, sức chứa</li>
                            <li><strong>Chuyển tab:</strong> Chọn "Bàn Nhà Hàng" hoặc "Khách Lưu Trú"</li>
                            <li><strong>Tạo QR:</strong> Click nút <i class="fas fa-qrcode"></i> để tạo mã QR cho bàn</li>
                            <li><strong>Reset QR:</strong> Tạo lại mã QR mới (cảnh báo nếu đã in)</li>
                            <li><strong>In QR:</strong> In mã QR đơn lẻ hoặc in hàng loạt</li>
                            <li><strong>In hướng dẫn:</strong> In giấy hướng dẫn sử dụng QR cho khách</li>
                            <li><strong>Trạng thái:</strong> Xem bàn trống/có khách, đang dùng/tạm ẩn</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 9: Mã QR & Cách Sử Dụng -->
                <div class="help-section help-section-highlight">
                    <div class="help-section-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-qrcode"></i> Hướng Dẫn Sử Dụng QR Cho Khách</h3>
                        <p class="help-desc">Quy trình quét mã QR và đặt món</p>
                        <ol class="help-list help-steps">
                            <li><strong>Bước 1:</strong> Khách quét mã QR tại bàn bằng điện thoại</li>
                            <li><strong>Bước 2:</strong> Hệ thống tự động nhận diện bàn và tạo phiên</li>
                            <li><strong>Bước 3:</strong> Khách xem menu và chọn món</li>
                            <li><strong>Bước 4:</strong> Khách nhập ghi chú (nếu có) và xác nhận đơn</li>
                            <li><strong>Bước 5:</strong> Bếp/Nhân viên nhận thông báo đơn mới</li>
                            <li><strong>Lưu ý:</strong> Mỗi bàn chỉ có 1 phiên hoạt động tại thời điểm</li>
                        </ol>
                    </div>
                </div>

                <!-- Section 10: Báo Cáo Thống Kê -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-chart-pie"></i> Báo Cáo Thống Kê</h3>
                        <p class="help-desc">Xem báo cáo doanh thu, đơn hàng</p>
                        <ul class="help-list">
                            <li><strong>Chọn khoảng thời gian:</strong> Hôm nay, Tuần này, Tháng này, Tùy chỉnh</li>
                            <li><strong>Doanh thu:</strong> Tổng doanh thu, phương thức thanh toán</li>
                            <li><strong>Đơn hàng:</strong> Số lượng đơn, giá trị trung bình</li>
                            <li><strong>Món bán chạy:</strong> Top món được gọi nhiều nhất</li>
                            <li><strong>Xuất báo cáo:</strong> Export PDF/Excel (nếu có)</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 11: Nhật Ký Hoạt Động -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-history"></i> Nhật Ký Hoạt Động</h3>
                        <p class="help-desc">Theo dõi lịch sử thao tác trong hệ thống</p>
                        <ul class="help-list">
                            <li><strong>Lịch sử tạo/sửa/xóa:</strong> Món, danh mục, bàn, user...</li>
                            <li><strong>Phân quyền:</strong> Biết ai làm gì, vào lúc nào</li>
                            <li><strong>Lọc theo:</strong> Người dùng, hành động, mức độ</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 12: Cài Đặt & IT -->
                <div class="help-section">
                    <div class="help-section-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="help-section-content">
                        <h3><i class="fas fa-cog"></i> Cài Đặt Hệ Thống (IT Only)</h3>
                        <p class="help-desc">Các tùy chỉnh hệ thống</p>
                        <ul class="help-list">
                            <li><strong>Quản lý User:</strong> Thêm, sửa, phân quyền nhân viên</li>
                            <li><strong>Sao lưu dữ liệu:</strong> Backup database SQL</li>
                            <li><strong>Xóa dữ liệu:</strong> Clear menu, orders (cẩn trọng!)</li>
                            <li><strong>Cài đặt chung:</strong> Cấu hình app, theme, notifications</li>
                        </ul>
                    </div>
                </div>

                <!-- Shortcuts -->
                <div class="help-shortcuts">
                    <h4><i class="fas fa-keyboard"></i> Phím Tắt Hữu Ích</h4>
                    <div class="shortcuts-grid">
                        <div class="shortcut-item">
                            <kbd>Ctrl</kbd> + <kbd>K</kbd>
                            <span>Tìm kiếm nhanh</span>
                        </div>
                        <div class="shortcut-item">
                            <kbd>F5</kbd>
                            <span>Làm mới trang</span>
                        </div>
                        <div class="shortcut-item">
                            <kbd>Esc</kbd>
                            <span>Đóng modal</span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="help-modal-footer">
                <p class="help-footer-text">
                    <i class="fas fa-info-circle"></i> Cần hỗ trợ thêm? Liên hệ quản trị viên hoặc IT.
                </p>
                <button class="btn btn-gold" id="helpGotIt">
                    <i class="fas fa-check"></i> Đã hiểu
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Help Button Styles */
        .help-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            color: #fff;
            border: none;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
            animation: helpGlow 2s ease-in-out infinite;
        }

        .help-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(212, 175, 55, 0.5);
        }

        .help-btn i {
            font-size: 1rem;
        }

        @keyframes helpGlow {
            0%, 100% {
                box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
            }
            50% {
                box-shadow: 0 2px 20px rgba(212, 175, 55, 0.6), 0 0 30px rgba(212, 175, 55, 0.3);
            }
        }

        /* Help Modal Styles */
        .help-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            overflow-y: auto;
        }

        .help-modal-content {
            background: #fff;
            margin: 3% auto;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 900px;
            width: 90%;
            animation: modalFadeIn 0.3s ease-out;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .help-modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);
            border-radius: 24px 24px 0 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .help-modal-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e293b;
        }

        .help-modal-title i {
            color: #d4af37;
            font-size: 1.8rem;
        }

        .help-modal-close {
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 1.5rem;
            color: #64748b;
        }

        .help-modal-close:hover {
            background: #e5e7eb;
            color: #1e293b;
            transform: rotate(90deg);
        }

        .help-modal-body {
            padding: 2rem;
            overflow-y: auto;
            flex: 1;
        }

        .help-section {
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .help-section:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: #d4af37;
        }

        .help-section-highlight {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-color: #d4af37;
        }

        .help-section-icon {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .help-section-content h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .help-section-content h3 i {
            color: #d4af37;
        }

        .help-desc {
            margin: 0 0 1rem 0;
            color: #64748b;
            font-size: 0.9rem;
            font-style: italic;
        }

        .help-list {
            margin: 0;
            padding-left: 1.25rem;
            color: #374151;
            font-size: 0.875rem;
            line-height: 1.8;
        }

        .help-list li {
            margin-bottom: 0.5rem;
        }

        .help-list strong {
            color: #1e293b;
        }

        .help-steps {
            counter-reset: step-counter;
            list-style: none;
            padding: 0;
        }

        .help-steps li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 0.75rem;
        }

        .help-steps li::before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            background: #d4af37;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .help-shortcuts {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: #fff;
            padding: 1.5rem;
            border-radius: 16px;
            margin-top: 1.5rem;
        }

        .help-shortcuts h4 {
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .shortcuts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .shortcut-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            text-align: center;
        }

        .shortcut-item kbd {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .help-modal-footer {
            padding: 1.5rem 2rem;
            border-top: 2px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-radius: 0 0 24px 24px;
        }

        .help-footer-text {
            margin: 0;
            color: #64748b;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .help-footer-text i {
            color: #d4af37;
        }

        @media (max-width: 768px) {
            .help-section {
                flex-direction: column;
                gap: 1rem;
            }

            .help-section-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .help-modal-content {
                width: 95%;
                margin: 5% auto;
            }

            .help-modal-header {
                padding: 1rem 1.5rem;
            }

            .help-modal-body {
                padding: 1rem;
            }

            .help-modal-footer {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem 1.5rem;
            }
        }
    </style>

    <script>
        // Help Modal Logic
        document.addEventListener('DOMContentLoaded', () => {
            const helpBtn = document.getElementById('helpBtn');
            const helpModal = document.getElementById('helpModal');
            const helpModalClose = document.getElementById('helpModalClose');
            const helpGotIt = document.getElementById('helpGotIt');

            // Open modal
            if (helpBtn) {
                helpBtn.addEventListener('click', () => {
                    helpModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            }

            // Close modal
            if (helpModalClose) {
                helpModalClose.addEventListener('click', () => {
                    helpModal.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }

            // Got it button
            if (helpGotIt) {
                helpGotIt.addEventListener('click', () => {
                    helpModal.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }

            // Close on outside click
            window.addEventListener('click', (e) => {
                if (e.target === helpModal) {
                    helpModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });

            // Close on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && helpModal.style.display === 'block') {
                    helpModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        });
    </script>

</body>

</html>
