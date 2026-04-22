<?php
// ============================================================
// AuthController — PIN Login & Redirect
// ============================================================

require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class AuthController extends Controller
{
    /**
     * GET /home — Landing page for iOS Home Screen
     */
    public function landing(): void
    {
        $this->view('layouts/public', [
            'view' => 'home',
            'pageTitle' => 'Aurora Restaurant',
        ]);
    }

    /**
     * GET / — Redirect theo role sau khi đăng nhập
     */
    public function home(): void
    {
        if (!Auth::check()) {
            $this->landing();
            return;
        }

        // Redirect theo role
        match (Auth::role()) {
            ROLE_WAITER => $this->redirect('/tables'),
            ROLE_ADMIN => $this->redirect('/admin/menu'),
            ROLE_IT => $this->redirect('/it/users'),
            default => $this->redirect('/auth/login'),
        };
    }

    /**
     * GET /auth/login — Hiển thị màn hình PIN login
     */
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->home();
        }

        // Lấy danh sách nhân viên
        $userModel = new User();
        $staff = $userModel->getActiveStaff();

        $this->view('auth/login', [
            'pageTitle' => 'Đăng nhập',
            'staff' => $staff,
        ]);
    }

    /**
     * POST /auth/login — Xử lý PIN login
     */
    public function handleLogin(): void
    {
        $username = trim($this->input('username', ''));
        $pin = trim($this->input('pin', ''));
        $activityLog = new ActivityLog();

        if (empty($username) || empty($pin)) {
            $activityLog->logLogin(0, false, 'Missing credentials');
            $_SESSION['login_error'] = 'Vui lòng nhập tên đăng nhập và mã PIN.';
            $this->redirect('/auth/login');
        }

        $userModel = new User();
        $user = $userModel->findByCredentials($username, $pin);

        if (!$user) {
            $activityLog->logLogin(0, false, 'Invalid PIN for user: ' . $username);
            $_SESSION['login_error'] = 'PIN không đúng. Vui lòng thử lại.';
            $this->redirect('/auth/login');
        }

        // Lưu thông tin user vào session
        Auth::login($user);

        // Log successful login
        $activityLog->logLogin($user['id'], true);

        $this->home();
    }

    /**
     * GET /auth/logout
     */
    public function logout(): void
    {
        // Log logout before destroying session
        if (Auth::isLoggedIn()) {
            $activityLog = new ActivityLog();
            $activityLog->logLogout(Auth::user()['id']);
        }
        
        Auth::logout();
        $this->redirect('/auth/login');
    }
}
