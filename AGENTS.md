# AGENTS.md — Aurora Restaurant Project Guide

## Project Overview

**Aurora Restaurant** — Digital Menu & Order System (Version 2.2.4)
- **Type**: PHP MVC Web Application
- **Purpose**: QR code-based ordering system for restaurants
- **Location**: `/restaurant` directory under XAMPP htdocs
- **Database**: MySQL (auroraho_restaurant)

---

## Architecture

### MVC Structure
```
restaurant/
├── core/              # Core framework classes
│   ├── Router.php     # Simple routing system
│   ├── Auth.php       # Session-based authentication
│   ├── Controller.php # Base controller
│   └── Model.php      # Base model
├── controllers/       # 22 controllers
├── models/           # 14 models
├── views/            # Templates organized by feature
├── public/           # Static assets (JS, CSS, audio, uploads)
├── config/           # Configuration files
├── helpers/          # Utility functions + phpqrcode library
├── database/         # SQL scripts
└── backups/          # Database backups
```

### Key Configuration Files
- `config/constants.php` — App constants, roles, paths, session settings
- `config/database.php` — Database connection with .env support
- `index.php` — Main entry point, route definitions

---

## User Roles

| Role    | Description           | Access Level                    |
|---------|----------------------|---------------------------------|
| waiter  | Wait staff           | Tables, orders, menu, notifications |
| admin   | Restaurant manager   | Full access + reports, settings   |
| it      | Technical support    | System settings, DB backup, users |

---

## Main Features & Routes

### Authentication
- `/auth/login` — Login page (PIN-based)
- `/auth/logout` — Logout
- `/home`, `/` — Landing page

### Waiter Features
- `/tables` — Table management (open, close, merge, transfer)
- `/menu` — View menu
- `/orders` — Order management
- `/orders/history` — Order history
- `/notifications` — Real-time notifications

### Admin Features
- `/admin/realtime` — Real-time monitoring dashboard
- `/admin/shifts` — Staff shift management
- `/admin/menu` — Menu item management
- `/admin/menu-types` — Menu type classification
- `/admin/menu/sets` — Set menu management
- `/admin/categories` — Category management
- `/admin/tables` — Table configuration
- `/admin/qr-codes` — QR code generation
- `/admin/reports` — Sales reports
- `/admin/activity` — Activity logs

### Customer QR Ordering
- `/q` — Short link for QR
- `/qr/landing` — Landing page
- `/qr/menu` — Customer menu view
- `/qr/order/status` — Order status
- `/qr/order/history` — Customer order history

### IT Features
- `/it/users` — User management
- `/it/database` — Database backup/cleanup
- `/it/settings` — System settings

---

## Database Models

| Model                | Table/Purpose                    |
|---------------------|----------------------------------|
| `User.php`          | User accounts & authentication   |
| `Table.php`         | Table management, status, merging |
| `TableStatusHistory.php` | Table status change tracking |
| `MenuItem.php`      | Menu items (products)            |
| `MenuCategory.php`  | Menu categories                  |
| `MenuType.php`      | Menu classification (breakfast, lunch, etc.) |
| `MenuSet.php`       | Set menus (combo)                |
| `Order.php`         | Orders & order items             |
| `OrderNotification.php` | Real-time order notifications |
| `Support.php`       | Customer support requests        |
| `ActivityLog.php`   | System activity logging          |
| `Setting.php`       | System settings (key-value)      |
| `QrTable.php`       | QR code table mapping            |
| `CustomerSession.php` | Customer QR session tracking   |

---

## Controllers Map

| Controller                | Purpose                          |
|--------------------------|----------------------------------|
| `AuthController`         | Login/logout                     |
| `TableController`        | Table operations (waiter)        |
| `MenuController`         | Menu display (waiter)            |
| `OrderController`        | Order management (waiter)        |
| `NotificationController` | Real-time notifications          |
| `SupportController`      | Support requests (waiter)        |
| `AdminRealtimeController`| Real-time dashboard              |
| `AdminShiftController`   | Shift management                 |
| `AdminMenuController`    | Menu CRUD                        |
| `AdminMenuTypeController`| Menu type management             |
| `AdminMenuSetController` | Set menu management              |
| `AdminCategoryController`| Category management              |
| `AdminTableController`   | Table configuration              |
| `AdminQrController`      | QR code generation               |
| `AdminActivityController`| Activity logs                    |
| `ReportController`       | Sales reports                    |
| `SettingController`      | IT settings & DB management      |
| `QrMenuController`       | Customer QR menu                 |
| `QrOrderController`      | Customer order submission        |
| `QrSupportController`    | Customer support (QR)            |

---

## Key Conventions

### Naming Conventions
- **Controllers**: PascalCase + "Controller" suffix (e.g., `OrderController`)
- **Models**: PascalCase, singular (e.g., `Order`, `MenuItem`)
- **Views**: Organized by feature in subdirectories
- **Routes**: kebab-case in URLs (e.g., `/admin/menu-types`)

### Authentication
- Session-based with `Auth` class
- Use `Auth::require()` to enforce login
- Use `Auth::requireRole()` for role-based access
- PIN-based login for staff

### Database
- PDO with prepared statements
- Connection via `getDB()` function
- UTF8MB4 charset
- Config via `.env` file (located outside public_html)

### Response Format
- **HTML views**: Direct rendering via `require_once`
- **AJAX responses**: JSON format with `['ok' => bool, 'message' => string, 'data' => mixed]`

---

## Important Helpers & Libraries

- `helpers/functions.php` — Global helper functions
- `helpers/phpqrcode/` — QR code generation library
- Custom JS modules in `public/js/` organized by feature

---

## Development Notes

### Location-Based Access
- Customer QR menu has geofencing (200m radius)
- Coordinates: `RESTAURANT_LAT`, `RESTAURANT_LNG` in constants
- Can be disabled via `DEV_MODE` or IT settings

### Session Configuration
- Session name: `aurora_restaurant_session`
- Lifetime: 8 hours
- Stored server-side

### Caching
- Aggressive no-cache headers for Safari iPad compatibility
- Important for production QR ordering

### Security
- CSRF protection needed for state-changing operations
- Input validation in controllers
- Role-based access control throughout

---

## Common Tasks

### Add New Feature
1. Create model in `models/` extending base `Model`
2. Create controller in `controllers/` extending base `Controller`
3. Add routes in `index.php`
4. Create views in appropriate `views/` subdirectory
5. Add JS/CSS in `public/` if needed

### Database Changes
1. Update model methods
2. Create migration SQL in `database/`
3. Update IT backup/cleanup scripts if needed

### Debugging
- Check `ActivityLog` model for audit trails
- Use `/admin/activity` for logs
- Database backups available in `backups/`

---

## Testing Guidelines

- Test location-based features with DEV_MODE enabled
- Verify role-based access for all routes
- Test QR flow on mobile devices
- Check notification polling under load
- Validate merge/transfer table operations

---

## Contact & Support

- **Developer**: LongDev
- **Version**: 2.2.4
- **Environment**: XAMPP (PHP + MySQL)
- **Production**: Aurora Hotel Plaza

---

## Important Workflow Notes

### Deployment Environment
- **Production First**: This project runs LIVE on production server (cPanel)
- **GitHub + cPanel**: Code is synced via GitHub to cPanel for deployment
- **No local testing required**: Changes go directly to production

### Git Commit Guidelines
- **Commit language**: Vietnamese (tiếng Việt)
- **Commit message format**: Clear, descriptive messages in Vietnamese
- **Example**: 
  - `Sửa lỗi thanh toán QR không cập nhật trạng thái đơn hàng`
  - `Thêm tính năng gộp bàn khu vực A và B`
  - `Cập nhật menu breakfast cho tháng 4`

### Database Access
- **Priority 1**: Read sample database from `database/` directory
- **Priority 2**: Read backup files from `backups/` directory
- **If unavailable**: Ask user to provide database dump or access
- **NEVER** modify production database without user confirmation

### Production Safety
- Test critical features with `DEV_MODE = true` first (in `config/constants.php`)
- Always backup before running cleanup/migration scripts
- Use `/it/database/backup` route to create backups before major changes
- Check activity logs at `/admin/activity` after deployments
