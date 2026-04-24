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
                    <a href="<?= BASE_URL ?>/admin/pos" class="nav-item <?= activeClass('/admin/pos') ?>">
                        <i class="fas fa-desktop"></i>
                        <span>POS Dashboard</span>
                    </a>
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
                        <ol class="help-list help-steps">
                            <li><strong>Truy cập:</strong> Vào menu "Giám sát trực tiếp" ở sidebar trái</li>
                            <li><strong>Xem tổng quan:</strong> Nhìn lên thanh command bar trên cùng để xem:
                                <ul>
                                    <li>Số bàn đang phục vụ</li>
                                    <li>Số bàn trống</li>
                                    <li>Doanh thu tạm tính (tổng tiền các đơn đang mở)</li>
                                </ul>
                            </li>
                            <li><strong>Xem chi tiết bàn:</strong> Mỗi bàn được hiển thị trong một card riêng với:
                                <ul>
                                    <li>Tên bàn và số khách</li>
                                    <li>Danh sách món đã gọi (kèm ghi chú nếu có)</li>
                                    <li>Tổng tiền tạm tính</li>
                                    <li>Thời gian mở bàn</li>
                                    <li>Trạng thái: "Đang ăn" hoặc "Đang chờ gọi món"</li>
                                </ul>
                            </li>
                            <li><strong>Xử lý đơn hoàn tất:</strong> Khi khách ăn xong:
                                <ul>
                                    <li>Nhấn nút "HOÀN TẤT & LƯU TRỮ" màu vàng</li>
                                    <li>Đơn sẽ được lưu vào lịch sử</li>
                                    <li>Bàn chuyển sang trạng thái "Trống"</li>
                                </ul>
                            </li>
                            <li><strong>Làm mới dữ liệu:</strong> 
                                <ul>
                                    <li>Hệ thống tự động reload mỗi 8 giây</li>
                                    <li>Nhấn nút <i class="fas fa-sync-alt"></i> để refresh thủ công</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="help-tip">
                            <i class="fas fa-lightbulb"></i> <strong>Mẹo:</strong> Card bàn có border màu vàng khi có idle timer (khách đã vào nhưng chưa gọi món) - cần kiểm tra gấp!
                        </div>
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
                        <ol class="help-list help-steps">
                            <li><strong>Truy cập:</strong> Vào menu "Giám sát phiên QR" ở sidebar</li>
                            <li><strong>Xem danh sách phiên:</strong> Mỗi phiên QR hiển thị:
                                <ul>
                                    <li>Tên bàn/khách hàng</li>
                                    <li>Thời gian bắt đầu phiên</li>
                                    <li>Thời gian còn lại (countdown)</li>
                                    <li>Danh sách món đã đặt</li>
                                    <li>Tổng tiền tạm tính</li>
                                </ul>
                            </li>
                            <li><strong>Xử lý phiên:</strong>
                                <ul>
                                    <li>Phiên mới: Khách vừa quét QR và đang chọn món</li>
                                    <li>Phiên chờ: Khách đã chọn món xong, đang chờ chế biến</li>
                                    <li>Phiên hoàn tất: Nhấn "HOÀN TẤT" để lưu phiên</li>
                                </ul>
                            </li>
                            <li><strong>Lưu ý quan trọng:</strong>
                                <ul>
                                    <li>Thời gian phiên mặc định: 5 phút (300 giây)</li>
                                    <li>Khi hết giờ: Phiên tự động hủy nếu khách chưa đặt món</li>
                                    <li>Timer hiển thị màu cam: Còn 2-5 phút</li>
                                    <li>Timer hiển thị màu đỏ: Còn dưới 2 phút - cần xử lý gấp!</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="help-tip">
                            <i class="fas fa-lightbulb"></i> <strong>Mẹo:</strong> Nếu khách cần thêm thời gian, hãy hướng dẫn họ quét lại QR để tạo phiên mới.
                        </div>
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
                        <ol class="help-list help-steps">
                            <li><strong>Thêm nhân viên mới:</strong>
                                <ul>
                                    <li>Vào menu "Nhân sự & Ca trực"</li>
                                    <li>Nhấn "Thêm nhân viên"</li>
                                    <li>Nhập thông tin: Họ tên, email, số điện thoại</li>
                                    <li>Chọn vai trò: Server (phục vụ bàn) hoặc Waiter (order)</li>
                                    <li>Nhấn "Lưu" để hoàn tất</li>
                                </ul>
                            </li>
                            <li><strong>Phân ca trực:</strong>
                                <ul>
                                    <li>Chọn ngày cần xếp ca</li>
                                    <li>Kéo thả nhân viên vào ca sáng/chiều/tối</li>
                                    <li>Hệ thống tự động gửi thông báo cho nhân viên</li>
                                </ul>
                            </li>
                            <li><strong>Theo dõi hiệu suất:</strong>
                                <ul>
                                    <li>Xem số bàn mỗi nhân viên phụ trách</li>
                                    <li>Thống kê số đơn đã xử lý</li>
                                    <li>Đánh giá chất lượng phục vụ</li>
                                </ul>
                            </li>
                        </ol>
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
                        <ol class="help-list help-steps">
                            <li><strong>Thêm món mới:</strong>
                                <ul>
                                    <li>Vào menu "Danh sách món" → Nhấn "Thêm món"</li>
                                    <li>Nhập tên món tiếng Việt (bắt buộc)</li>
                                    <li>Nhập tên món tiếng Anh (nếu có)</li>
                                    <li>Nhập mô tả ngắn về món</li>
                                    <li><strong>Nhập giá:</strong> 
                                        <ul>
                                            <li>Chỉ nhập số nguyên (không nhập dấu chấm/phẩy thập phân)</li>
                                            <li>Tối đa 10 chữ số (VD: 99999, 198552, 1234567890)</li>
                                            <li>Đơn vị tính: VND</li>
                                            <li>Ví dụ: Nhập "99999" thay vì "99.999" hoặc "99,999"</li>
                                        </ul>
                                    </li>
                                    <li>Chọn danh mục (Khai vị, Món chính, Tráng miệng...)</li>
                                    <li>Chọn loại menu (Món Á, Món Âu, Alacarte...)</li>
                                    <li>Chọn phục vụ: Nhà hàng, Room Service, hoặc Cả hai</li>
                                    <li>Upload ảnh đại diện (khuyến khích)</li>
                                    <li>Thêm tags nếu cần (Bán chạy, Mới, Cay, Chay...)</li>
                                    <li>Nhập stock: -1 = không giới hạn, hoặc số lượng cụ thể</li>
                                    <li>Nhấn "Lưu" để hoàn tất</li>
                                </ul>
                            </li>
                            <li><strong>Sửa món:</strong>
                                <ul>
                                    <li>Tìm món cần sửa (dùng search hoặc filter)</li>
                                    <li>Nhấn nút <i class="fas fa-pen"></i> bên phải</li>
                                    <li>Thay đổi thông tin cần thiết</li>
                                    <li>Nhấn "Lưu thay đổi"</li>
                                </ul>
                            </li>
                            <li><strong>Xóa món:</strong>
                                <ul>
                                    <li>Nhấn nút <i class="fas fa-trash"></i> màu đỏ</li>
                                    <li>Xác nhận xóa trong popup</li>
                                    <li><strong>Cảnh báo:</strong> Món đã có trong đơn hàng không nên xóa!</li>
                                </ul>
                            </li>
                            <li><strong>Toggle nhanh:</strong>
                                <ul>
                                    <li>Nút "Hiển thị" (mắt): Bật/tắt hiển thị trong menu</li>
                                    <li>Nút "Còn hàng": Đánh thức món còn/hết hàng</li>
                                </ul>
                            </li>
                            <li><strong>Lọc & Tìm kiếm:</strong>
                                <ul>
                                    <li>Search: Tìm theo tên món (VI/EN)</li>
                                    <li>Danh mục: Lọc theo nhóm món</li>
                                    <li>Phục vụ: Nhà hàng / Room Service / Cả hai</li>
                                    <li>Loại menu: Món Á / Món Âu / Alacarte</li>
                                    <li>Tags: Bán chạy / Mới / Cay / Chay / Đề xuất</li>
                                    <li>Tồn kho: Còn hàng / Sắp hết / Hết hàng</li>
                                    <li>Khoảng giá: Dưới 50k / 50k-100k / 100k-200k / Trên 200k</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="help-tip">
                            <i class="fas fa-lightbulb"></i> <strong>Mẹo:</strong> Nên upload ảnh món để thực đơn đẹp hơn. Ảnh nên có kích thước tối thiểu 800x600px.
                        </div>
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
                        <ol class="help-list help-steps">
                            <li><strong>Tạo set mới:</strong>
                                <ul>
                                    <li>Vào menu "Set & Combo" → Nhấn "Thêm set"</li>
                                    <li>Nhập tên set (VD: "Set Gia Đình 4 Người")</li>
                                    <li>Nhập mô tả ngắn về set</li>
                                    <li>Nhập giá set (thường rẻ hơn mua lẻ)</li>
                                    <li>Upload ảnh đại diện cho set</li>
                                    <li>Chọn loại menu phục vụ</li>
                                    <li>Nhấn "Lưu" để tạo set</li>
                                </ul>
                            </li>
                            <li><strong>Thêm món vào set:</strong>
                                <ul>
                                    <li>Mở set vừa tạo</li>
                                    <li>Nhấn "Thêm món vào set"</li>
                                    <li>Chọn món từ danh sách có sẵn</li>
                                    <li>Nhập số lượng mỗi món trong set</li>
                                    <li>Sắp xếp thứ tự hiển thị của các món</li>
                                    <li>Nhấn "Lưu" để hoàn tất</li>
                                </ul>
                            </li>
                            <li><strong>Quản lý set:</strong>
                                <ul>
                                    <li>Sửa thông tin set: Nhấn nút <i class="fas fa-pen"></i></li>
                                    <li>Xóa set: Nhấn nút <i class="fas fa-trash"></i></li>
                                    <li>Toggle hiển thị: Bật/tắt set trong menu</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="help-tip">
                            <i class="fas fa-lightbulb"></i> <strong>Mẹo:</strong> Set combo nên có giá ưu đãi hơn 10-20% so với mua lẻ để khuyến khích khách gọi.
                        </div>
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
                        <ol class="help-list help-steps">
                            <li><strong>Thêm bàn/phòng mới:</strong>
                                <ul>
                                    <li>Vào menu "Sơ đồ bàn ăn"</li>
                                    <li>Chọn tab: "Bàn Nhà Hàng" hoặc "Khách Lưu Trú (Phòng)"</li>
                                    <li>Nhập tên bàn/phòng (VD: "Bàn 01" hoặc "Phòng 701")</li>
                                    <li>Nhập khu vực (VD: "Tầng 1", "Sân vườn", "Tầng 7"...)</li>
                                    <li>Nhập sức chứa (số người tối đa)</li>
                                    <li>Nhập thứ tự hiển thị (số nhỏ hiển thị trước)</li>
                                    <li>Nhấn "Thêm bàn/phòng"</li>
                                </ul>
                            </li>
                            <li><strong>Tạo mã QR cho bàn:</strong>
                                <ul>
                                    <li>Tìm bàn cần tạo QR trong danh sách</li>
                                    <li>Nhấn nút <i class="fas fa-qrcode"></i> màu xanh</li>
                                    <li>Hệ thống tự động tạo mã QR</li>
                                    <li>Popup hiện ra với mã QR và URL</li>
                                    <li>Nhấn "In QR" hoặc "Tải ảnh" để lưu</li>
                                </ul>
                            </li>
                            <li><strong>Reset mã QR:</strong>
                                <ul>
                                    <li>Nhấn nút <i class="fas fa-sync-alt"></i> màu vàng</li>
                                    <li><strong>Cảnh báo:</strong> Nếu QR đã in, mã cũ sẽ không dùng được!</li>
                                    <li>Xác nhận reset trong popup</li>
                                    <li>In lại QR mới cho bàn</li>
                                </ul>
                            </li>
                            <li><strong>In hàng loạt QR:</strong>
                                <ul>
                                    <li>Nhấn nút "In QR" ở góc phải trên</li>
                                    <li>Chọn các bàn cần in (dùng checkbox)</li>
                                    <li>Nhấn "In đã chọn"</li>
                                    <li>Hệ thống mở trang in với layout A4 (10 QR/trang)</li>
                                </ul>
                            </li>
                            <li><strong>In hướng dẫn sử dụng:</strong>
                                <ul>
                                    <li>Nhấn nút "In HDSD" ở góc phải trên</li>
                                    <li>Giấy hướng dẫn khách cách quét QR và đặt món</li>
                                    <li>Nên in và đặt tại mỗi bàn</li>
                                </ul>
                            </li>
                            <li><strong>Quản lý trạng thái:</strong>
                                <ul>
                                    <li>"Trống": Bàn không có khách</li>
                                    <li>"Có khách": Bàn đang có đơn hàng</li>
                                    <li>"Đang dùng": Bàn được hiển thị trong hệ thống</li>
                                    <li>"Tạm ẩn": Bàn bị ẩn (không cho khách đặt)</li>
                                </ul>
                            </li>
                            <li><strong>Sửa/Xóa bàn:</strong>
                                <ul>
                                    <li>Sửa: Nhấn nút <i class="fas fa-pen"></i></li>
                                    <li>Xóa: Nhấn nút <i class="fas fa-trash"></i> màu đỏ</li>
                                    <li><strong>Lưu ý:</strong> Bàn đang có khách không thể xóa!</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="help-tip">
                            <i class="fas fa-lightbulb"></i> <strong>Mẹo:</strong> Nên nhóm bàn theo khu vực để dễ quản lý. Đặt tên khu vực rõ ràng như "Tầng 1", "Sân vườn", "VIP"...
                        </div>
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
            margin: 1% auto;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 1200px;
            width: 95%;
            animation: modalFadeIn 0.3s ease-out;
            max-height: 98vh;
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
            padding: 2rem 2.5rem;
            border-bottom: 3px solid #e5e7eb;
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
            gap: 1rem;
            font-size: 1.6rem;
            font-weight: 800;
            color: #1e293b;
        }

        .help-modal-title i {
            color: #d4af37;
            font-size: 2.2rem;
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
            padding: 2.5rem;
            overflow-y: auto;
            flex: 1;
        }

        .help-section {
            display: flex;
            gap: 2rem;
            padding: 2rem;
            border-radius: 20px;
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
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.4);
        }

        .help-section-content h3 {
            margin: 0 0 0.75rem 0;
            font-size: 1.3rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .help-section-content h3 i {
            color: #d4af37;
        }

        .help-desc {
            margin: 0 0 1.25rem 0;
            color: #64748b;
            font-size: 1rem;
            font-style: italic;
            line-height: 1.6;
        }

        .help-list {
            margin: 0;
            padding-left: 1.5rem;
            color: #374151;
            font-size: 0.95rem;
            line-height: 2;
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
            padding-left: 2.5rem;
            margin-bottom: 1rem;
        }

        .help-steps li::before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 1.75rem;
            height: 1.75rem;
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
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
            padding: 2rem 2.5rem;
            border-top: 3px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);
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

        /* Help Tip Box */
        .help-tip {
            margin-top: 1.5rem;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 5px solid #d4af37;
            border-radius: 12px;
            font-size: 0.95rem;
            color: #92400e;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
        }

        .help-tip i {
            font-size: 1rem;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .help-tip strong {
            color: #78350f;
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
