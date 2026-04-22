<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="Đăng nhập hệ thống Aurora Restaurant">
    <title>Đăng nhập — Aurora Restaurant</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;0,800;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/auth.css">
</head>

<body>

    <div class="auth-bg" aria-hidden="true"></div>

    <div class="auth-card" role="main">

        <!-- Logo -->
        <div class="auth-logo">
            <div class="auth-logo__icon" aria-hidden="true">
                <i class="fas fa-utensils"></i>
            </div>
            <h1 class="auth-logo__title">Aurora Restaurant</h1>
            <p class="auth-logo__sub">Đăng nhập để tiếp tục</p>
        </div>

        <!-- Error alert -->
        <?php if (!empty($_SESSION['login_error'])): ?>
            <div class="auth-alert auth-alert--error" role="alert">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                <?= e($_SESSION['login_error']) ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/auth/login" id="loginForm" novalidate>

            <!-- Hidden fields -->
            <input type="hidden" name="username" id="usernameField">
            <input type="hidden" name="pin" id="pinField">

            <!-- ── Step 1: Chọn nhân viên ── -->
            <div id="waiterSection">
                <p class="field-label">
                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                    1. Chọn nhân viên
                </p>
                <div class="user-grid" role="listbox">
                    <?php foreach ($staff as $w): ?>
                        <button type="button" class="user-chip" data-username="<?= e($w['username']) ?>"
                            data-role="<?= e($w['role']) ?>">
                            <?php if ($w['role'] === 'admin'): ?>
                                <i class="fas fa-user-shield" style="margin-right:5px; color:var(--gold);"></i>
                            <?php elseif ($w['role'] === 'it'): ?>
                                <i class="fas fa-user-gear" style="margin-right:5px;"></i>
                            <?php endif; ?>
                            <?= e($w['name']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ── Step 2: Nhập PIN ── -->
            <div id="pinSection" class="u-hidden" style="margin-top: 1.5rem;">
                <p class="field-label">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    2. Nhập PIN (4 số)
                </p>
                <!-- PIN dots indicator -->
                <div class="pin-dots">
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                </div>
                <!-- PIN numpad -->
                <div class="pin-pad">
                    <?php foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $n): ?>
                        <button type="button" class="pin-key" data-key="<?= $n ?>"><?= $n ?></button>
                    <?php endforeach; ?>
                    <button type="button" class="pin-key pin-key--del" data-key="del"><i
                            class="fas fa-delete-left"></i></button>
                    <button type="button" class="pin-key pin-key--zero" data-key="0">0</button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" id="submitBtn" class="btn-login" disabled aria-label="Đăng nhập">
                <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                Đăng nhập
            </button>

        </form>
    </div>

    <script src="<?= BASE_URL ?>/public/js/auth.js" defer></script>
</body>

</html>