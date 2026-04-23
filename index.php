<?php
// ============================================================
// Entry Point — Aurora Restaurant
// ============================================================

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

// Chống cache toàn cục (đặc biệt quan trọng cho Safari iPad trên Prod)
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/helpers/functions.php';

Auth::start();

$router = new Router();

// ── Auth ──────────────────────────────────────────────────
$router->get('/auth/login', 'AuthController', 'showLogin');
$router->post('/auth/login', 'AuthController', 'handleLogin');
$router->get('/auth/logout', 'AuthController', 'logout');

// ── Home: landing page for iOS Shortcut ──────────────────
$router->get('/home', 'AuthController', 'landing');
$router->get('/', 'AuthController', 'home');

// ── Waiter: Tables ────────────────────────────────────────
$router->get('/tables', 'TableController', 'index');
$router->get('/tables/getMergedChildren', 'TableController', 'getMergedChildren');
$router->post('/tables/open', 'TableController', 'open');
$router->post('/tables/close', 'TableController', 'close');
$router->post('/tables/merge', 'TableController', 'merge');
$router->post('/tables/unmerge', 'TableController', 'unmerge');
$router->post('/tables/transfer', 'TableController', 'transfer');
// Split/Merge advanced
$router->post('/tables/split', 'TableController', 'split');
$router->post('/tables/transfer-item', 'TableController', 'transfer_item');
$router->get('/tables/get-items-by-table', 'TableController', 'get_items_by_table');
$router->post('/tables/merge_areas', 'TableController', 'merge_areas');

$router->post('/tables/unmerge_areas', 'TableController', 'unmerge_areas');

// ── Waiter: Menu ─────────────────────────────────────────
$router->get('/menu', 'MenuController', 'index');

// ── Waiter: Orders ───────────────────────────────────────
$router->get('/orders', 'OrderController', 'index');
$router->post('/orders/add', 'OrderController', 'addItem');
$router->post('/orders/add-set', 'OrderController', 'addSet');
$router->post('/orders/update', 'OrderController', 'updateItem');
$router->post('/orders/update-guest-count', 'OrderController', 'updateGuestCount');
$router->post('/orders/remove', 'OrderController', 'removeItem');
$router->post('/orders/update-note', 'OrderController', 'updateItemNote');
$router->post('/orders/confirm', 'OrderController', 'confirmOrder');
$router->get('/orders/history', 'OrderController', 'history');
$router->get('/orders/get-detail', 'OrderController', 'getOrderDetail');
$router->get('/orders/print', 'OrderController', 'print');

// ── Customer & Waiter: Support & Payment Requests ───────
$router->post('/support/request', 'SupportController', 'makeRequest');
$router->get('/support/pending', 'SupportController', 'getPending');
$router->post('/support/resolve', 'SupportController', 'resolve');

// ── Admin: Real-time Monitoring ──────────────────────────
$router->get('/admin/realtime', 'AdminRealtimeController', 'index');
$router->get('/admin/realtime/data', 'AdminRealtimeController', 'data');
$router->post('/admin/realtime/dismiss', 'AdminRealtimeController', 'dismiss');
$router->get('/admin/realtime/qr-sessions', 'AdminRealtimeController', 'qrSessions');

// ── Admin: Shift Management ───────────────────────────────
$router->get('/admin/shifts', 'AdminShiftController', 'index');
$router->post('/admin/shifts/store', 'AdminShiftController', 'store');
$router->post('/admin/shifts/delete', 'AdminShiftController', 'delete');
$router->post('/admin/shifts/assign', 'AdminShiftController', 'assign');
$router->post('/admin/shifts/remove_assign', 'AdminShiftController', 'removeAssign');
$router->get('/admin/menu', 'AdminMenuController', 'index');
$router->get('/admin/menu/create', 'AdminMenuController', 'create');
$router->post('/admin/menu/store', 'AdminMenuController', 'store');
$router->get('/admin/menu/edit', 'AdminMenuController', 'edit');
$router->post('/admin/menu/update', 'AdminMenuController', 'update');
$router->post('/admin/menu/delete', 'AdminMenuController', 'delete');
$router->post('/admin/menu/toggle', 'AdminMenuController', 'toggle');
// Clear menu data (IT only)
$router->get('/admin/menu/clear', 'AdminMenuController', 'clearPage');
$router->post('/admin/menu/clear', 'AdminMenuController', 'clear');

// ── Admin: Menu Types (Phân loại menu) ────────────────────
$router->get('/admin/menu-types', 'AdminMenuTypeController', 'index');
$router->post('/admin/menu-types/store', 'AdminMenuTypeController', 'store');
$router->get('/admin/menu-types/edit', 'AdminMenuTypeController', 'edit');
$router->post('/admin/menu-types/update', 'AdminMenuTypeController', 'update');
$router->post('/admin/menu-types/delete', 'AdminMenuTypeController', 'delete');
$router->post('/admin/menu-types/toggle', 'AdminMenuTypeController', 'toggle');

// ── Admin: Menu Sets (À la carte) ─────────────────────────
$router->get('/admin/menu/sets', 'AdminMenuSetController', 'index');
$router->post('/admin/menu/sets/store', 'AdminMenuSetController', 'store');
$router->post('/admin/menu/sets/update', 'AdminMenuSetController', 'update');
$router->post('/admin/menu/sets/delete', 'AdminMenuSetController', 'delete');
$router->post('/admin/menu/sets/toggle', 'AdminMenuSetController', 'toggle');

// ── Admin: Categories ─────────────────────────────────────
$router->get('/admin/categories', 'AdminCategoryController', 'index');
$router->get('/admin/categories/edit', 'AdminCategoryController', 'edit');
$router->post('/admin/categories/store', 'AdminCategoryController', 'store');
$router->post('/admin/categories/update', 'AdminCategoryController', 'update');
$router->post('/admin/categories/delete', 'AdminCategoryController', 'delete');

// ── Admin: Tables Management ──────────────────────────────
$router->get('/admin/tables', 'AdminTableController', 'index');
$router->get('/admin/tables/edit', 'AdminTableController', 'edit');
$router->post('/admin/tables/store', 'AdminTableController', 'store');
$router->post('/admin/tables/update', 'AdminTableController', 'update');
$router->post('/admin/tables/delete', 'AdminTableController', 'delete');

// ── Admin: Reports ────────────────────────────────────────
$router->get('/admin/reports', 'ReportController', 'index');

// ── IT: User Management ───────────────────────────────────
$router->get('/it/users', 'SettingController', 'users');
$router->get('/it/users/edit', 'SettingController', 'editUser');
$router->post('/it/users/store', 'SettingController', 'storeUser');
$router->post('/it/users/update', 'SettingController', 'updateUser');
$router->post('/it/users/delete', 'SettingController', 'deleteUser');

// ── IT: Database Backup ───────────────────────────────────
$router->get('/it/database', 'SettingController', 'database');
$router->get('/it/database/backup', 'SettingController', 'backup');
$router->get('/it/database/download', 'SettingController', 'downloadBackup');
$router->post('/it/database/delete', 'SettingController', 'deleteBackup');

// ── IT: Database Cleanup ──────────────────────────────────
$router->post('/it/database/cleanup/all', 'SettingController', 'cleanupAll');
$router->post('/it/database/cleanup/orders', 'SettingController', 'cleanupOrders');
$router->post('/it/database/cleanup/table', 'SettingController', 'cleanupTable');

// ── IT: Settings Management ───────────────────────────────
$router->get('/it/settings', 'SettingController', 'settings');
$router->post('/it/settings/update', 'SettingController', 'updateSetting');
$router->post('/it/settings/reset', 'SettingController', 'resetSetting');

// ── Admin: Activity Logs ─────────────────────────────────
$router->get('/admin/activity', 'AdminActivityController', 'index');
$router->get('/admin/activity/data', 'AdminActivityController', 'data');
$router->get('/admin/activity/entityLogs', 'AdminActivityController', 'entityLogs');
$router->post('/admin/activity/cleanup', 'AdminActivityController', 'cleanup');
$router->get('/admin/activity/export', 'AdminActivityController', 'export');

// ── QR Ordering: Customer ──────────────────────────────────
$router->get('/q', 'QrMenuController', 'shortLink');
$router->get('/qr/landing', 'QrMenuController', 'landing');
$router->get('/qr/landing/history-ajax', 'QrMenuController', 'historyAjax');
$router->get('/qr/menu', 'QrMenuController', 'index');
$router->post('/qr/menu/location', 'QrMenuController', 'saveLocation');
$router->post('/qr/cart/add', 'QrMenuController', 'addToCart');
$router->post('/qr/cart/update', 'QrMenuController', 'updateCart');
$router->post('/qr/cart/remove', 'QrMenuController', 'removeFromCart');
$router->post('/qr/order/submit', 'QrOrderController', 'submit');
$router->post('/qr/session/clear', 'QrOrderController', 'clearSession');
$router->get('/qr/order/status', 'QrOrderController', 'status');
$router->get('/qr/order/poll-status', 'QrOrderController', 'pollStatus');
$router->get('/qr/order/history', 'QrOrderController', 'history');
$router->get('/qr/order/customer-history', 'QrOrderController', 'customerHistory');
$router->get('/qr/sessions', 'QrMenuController', 'sessions');
$router->get('/qr/thank-you', 'QrOrderController', 'thankYou');
$router->post('/qr/support/call-waiter', 'QrSupportController', 'callWaiter');
$router->post('/qr/support/request-bill', 'QrSupportController', 'requestBill');

// ── QR Ordering: Admin ─────────────────────────────────────
$router->get('/admin/qr-codes', 'AdminQrController', 'index');
$router->post('/admin/qr-codes/generate', 'AdminQrController', 'generate');
$router->get('/admin/qr-codes/download', 'AdminQrController', 'download');
$router->post('/admin/qr-codes/delete', 'AdminQrController', 'delete');
$router->get('/admin/qr-codes/print-bulk', 'AdminQrController', 'printBulk');
$router->post('/admin/qr-codes/print-bulk', 'AdminQrController', 'printBulkPost');

// ── Admin: Tables QR Instructions ───────────────────────────
$router->get('/admin/tables/qr-instructions', 'AdminTableController', 'qrInstructions');

// ── Notifications: Real-time Polling ───────────────────────
$router->get('/notifications', 'NotificationController', 'waiterIndex');
$router->get('/api/notifications/poll', 'NotificationController', 'poll');
$router->post('/api/notifications/mark-read', 'NotificationController', 'markRead');
$router->post('/api/notifications/resolve-support', 'NotificationController', 'resolveSupport');

$router->dispatch();
