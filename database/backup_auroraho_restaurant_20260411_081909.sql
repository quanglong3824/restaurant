-- Aurora Restaurant Database Backup
-- Generated: 2026-04-11 08:19:09

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL COMMENT 'Hành động thực hiện (login, create, update, delete...)',
  `entity` varchar(50) NOT NULL COMMENT 'Thực thể bị tác động (user, table, order, menu_item...)',
  `entity_id` int(10) unsigned DEFAULT NULL COMMENT 'ID của thực thể',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'ID người thực hiện (NULL = system)',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP address',
  `user_agent` text DEFAULT NULL COMMENT 'User agent string',
  `request_uri` varchar(500) DEFAULT NULL COMMENT 'URI yêu cầu',
  `request_method` varchar(10) DEFAULT 'GET' COMMENT 'HTTP method',
  `metadata` text DEFAULT NULL COMMENT 'Dữ liệu metadata (JSON)',
  `level` enum('info','notice','warning','error','critical') NOT NULL DEFAULT 'info' COMMENT 'Mức độ quan trọng',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm ghi log',
  PRIMARY KEY (`id`),
  KEY `idx_action` (`action`),
  KEY `idx_entity` (`entity`,`entity_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_level` (`level`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_ip` (`ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhật ký hoạt động hệ thống';

INSERT INTO `activity_logs` VALUES ('1', 'logout', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 18:29:25');
INSERT INTO `activity_logs` VALUES ('2', 'error', 'user', '0', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: admin\"}', 'warning', '2026-04-07 18:29:31');
INSERT INTO `activity_logs` VALUES ('3', 'login', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:29:33');
INSERT INTO `activity_logs` VALUES ('4', 'logout', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 18:39:52');
INSERT INTO `activity_logs` VALUES ('5', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:39:56');
INSERT INTO `activity_logs` VALUES ('6', 'logout', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 18:46:27');
INSERT INTO `activity_logs` VALUES ('7', 'error', 'user', '0', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: waiter01\"}', 'warning', '2026-04-07 18:46:31');
INSERT INTO `activity_logs` VALUES ('8', 'login', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:46:34');
INSERT INTO `activity_logs` VALUES ('9', 'login', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:47:15');
INSERT INTO `activity_logs` VALUES ('10', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":189,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"Ít  cay / mildly spicy\"}', 'info', '2026-04-07 18:48:18');
INSERT INTO `activity_logs` VALUES ('11', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-07 19:21:27');
INSERT INTO `activity_logs` VALUES ('12', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"Ít  cay / mildly spicy\"}', 'info', '2026-04-07 19:21:42');
INSERT INTO `activity_logs` VALUES ('13', 'create', 'order', '191', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/tables/open', 'POST', '{\"table_id\":36,\"waiter_id\":\"3\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-07 19:25:01');
INSERT INTO `activity_logs` VALUES ('14', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":191,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-07 19:25:05');
INSERT INTO `activity_logs` VALUES ('15', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":191,\"menu_item_id\":197,\"item_name\":\"Tôm Khô Cải Chua\",\"quantity\":1,\"note\":\"Ít ngọt / Less sweet\"}', 'info', '2026-04-07 19:25:08');
INSERT INTO `activity_logs` VALUES ('16', 'create', 'order', '192', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/tables/open', 'POST', '{\"table_id\":3,\"waiter_id\":\"3\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-07 19:29:17');
INSERT INTO `activity_logs` VALUES ('17', 'error', 'user', '0', NULL, '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: waiter01\"}', 'warning', '2026-04-07 19:31:57');
INSERT INTO `activity_logs` VALUES ('18', 'login', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:32:00');
INSERT INTO `activity_logs` VALUES ('19', 'login', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:33:48');
INSERT INTO `activity_logs` VALUES ('20', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":195,\"item_name\":\"Súp Vi Cá\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-07 19:34:06');
INSERT INTO `activity_logs` VALUES ('21', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":196,\"item_name\":\"Bạch Tuộc Nướng Muối Ớt\",\"quantity\":1,\"note\":\"Không cay / Not spicy\"}', 'info', '2026-04-07 19:34:09');
INSERT INTO `activity_logs` VALUES ('22', 'logout', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 19:35:51');
INSERT INTO `activity_logs` VALUES ('23', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:35:54');
INSERT INTO `activity_logs` VALUES ('24', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (iPad; CPU OS 16_7_11 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.107 Mobile/15E148 Safari/604.1', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:49:39');
INSERT INTO `activity_logs` VALUES ('25', 'logout', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 19:50:56');
INSERT INTO `activity_logs` VALUES ('26', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:50:58');
INSERT INTO `activity_logs` VALUES ('27', 'login', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 13:22:34');
INSERT INTO `activity_logs` VALUES ('28', 'logout', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-08 13:23:06');
INSERT INTO `activity_logs` VALUES ('29', 'error', 'user', '0', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: it\"}', 'warning', '2026-04-08 13:23:09');
INSERT INTO `activity_logs` VALUES ('30', 'login', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 13:23:11');
INSERT INTO `activity_logs` VALUES ('31', 'login', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 13:47:08');
INSERT INTO `activity_logs` VALUES ('32', 'delete', 'menu_clear', '0', '2', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/menu/clear', 'POST', '{\"type\":\"all\",\"deleted\":{\"items\":50,\"categories\":11,\"sets\":2,\"setItems\":0},\"user_id\":\"2\"}', 'warning', '2026-04-08 13:58:58');
INSERT INTO `activity_logs` VALUES ('33', 'create', 'menu_category', '36', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Khai Vị\",\"name_en\":\"Appertizer\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":1}', 'info', '2026-04-08 14:03:44');
INSERT INTO `activity_logs` VALUES ('34', 'create', 'menu_category', '37', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Món Chính\",\"name_en\":\"Main Course\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":2}', 'info', '2026-04-08 14:05:46');
INSERT INTO `activity_logs` VALUES ('35', 'create', 'menu_category', '38', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Xà Lách\",\"name_en\":\"Salad\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":3}', 'info', '2026-04-08 14:08:11');
INSERT INTO `activity_logs` VALUES ('36', 'create', 'menu_category', '39', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Súp\",\"name_en\":\"Soup\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":0}', 'info', '2026-04-08 14:08:49');
INSERT INTO `activity_logs` VALUES ('37', 'create', 'menu_category', '40', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Mì Ý\",\"name_en\":\"Spaghetti\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":4}', 'info', '2026-04-08 14:09:31');
INSERT INTO `activity_logs` VALUES ('38', 'create', 'menu_category', '41', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Bánh Mì\",\"name_en\":\"Sandwich\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":0}', 'info', '2026-04-08 14:09:48');
INSERT INTO `activity_logs` VALUES ('39', 'delete', 'menu_clear', '0', '2', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/menu/clear', 'POST', '{\"type\":\"all\",\"deleted\":{\"items\":0,\"categories\":6,\"sets\":0,\"setItems\":0},\"user_id\":\"2\"}', 'warning', '2026-04-08 14:11:21');
INSERT INTO `activity_logs` VALUES ('40', 'update', 'menu_type', '1', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu-types/update', 'POST', '{\"name\":\"Món Á\",\"name_en\":\"Asian Cuisine\",\"type_key\":\"asia\",\"description\":\"Các món ăn truyền thống châu Á\",\"color\":\"#0ea5e9\",\"icon\":\"fa-bowl-rice\",\"sort_order\":1,\"is_active\":1}', 'info', '2026-04-08 14:19:00');
INSERT INTO `activity_logs` VALUES ('41', 'create', 'menu_category', '42', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Cơm\",\"name_en\":\"Rice\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":0}', 'info', '2026-04-08 14:26:57');
INSERT INTO `activity_logs` VALUES ('42', 'create', 'menu_category', '43', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Mì & Bún\",\"name_en\":\"Noodle\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":1}', 'info', '2026-04-08 14:27:14');
INSERT INTO `activity_logs` VALUES ('43', 'create', 'menu_category', '44', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Cháo\",\"name_en\":\"Porridge\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":2}', 'info', '2026-04-08 14:27:42');
INSERT INTO `activity_logs` VALUES ('44', 'create', 'menu_category', '45', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Heo\",\"name_en\":\"Pork\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":3}', 'info', '2026-04-08 14:28:37');
INSERT INTO `activity_logs` VALUES ('45', 'create', 'menu_category', '46', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Bò\",\"name_en\":\"BEEF\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":4}', 'info', '2026-04-08 14:28:48');
INSERT INTO `activity_logs` VALUES ('46', 'create', 'menu_category', '47', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Tôm\",\"name_en\":\"Shrimp\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":5}', 'info', '2026-04-08 14:29:09');
INSERT INTO `activity_logs` VALUES ('47', 'create', 'menu_category', '48', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Súp\",\"name_en\":\"Soup\",\"menu_type\":\"europe\",\"icon\":\"fa-utensils\",\"sort_order\":6}', 'info', '2026-04-08 14:29:44');
INSERT INTO `activity_logs` VALUES ('48', 'delete', 'menu_category', '48', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/delete', 'POST', '{\"name\":\"Súp\"}', 'info', '2026-04-08 14:30:10');
INSERT INTO `activity_logs` VALUES ('49', 'delete', 'user', '3', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/it/users/delete', 'POST', '{\"username\":\"waiter01\",\"name\":\"Nhân Viên Nhà Hàng\"}', 'info', '2026-04-08 14:41:31');
INSERT INTO `activity_logs` VALUES ('50', 'delete', 'user', '4', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/it/users/delete', 'POST', '{\"username\":\"waiter02\",\"name\":\"Nhân Viên Nhà Hàng\"}', 'info', '2026-04-08 14:41:32');
INSERT INTO `activity_logs` VALUES ('51', 'delete', 'user', '1', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/it/users/delete', 'POST', '{\"username\":\"admin\",\"name\":\"Admin Nhà Hàng\"}', 'info', '2026-04-08 14:41:38');
INSERT INTO `activity_logs` VALUES ('52', 'create', 'user', '5', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/it/users/store', 'POST', '{\"username\":\"waiter01\",\"name\":\"Nhân Viên 01\",\"role\":\"waiter\"}', 'info', '2026-04-08 14:41:58');
INSERT INTO `activity_logs` VALUES ('53', 'create', 'user', '6', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/it/users/store', 'POST', '{\"username\":\"waiter02\",\"name\":\"Nhân Viên 02\",\"role\":\"waiter\"}', 'info', '2026-04-08 14:42:11');
INSERT INTO `activity_logs` VALUES ('54', 'create', 'user', '7', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/it/users/store', 'POST', '{\"username\":\"admin01\",\"name\":\"Admin\",\"role\":\"admin\"}', 'info', '2026-04-08 14:42:29');
INSERT INTO `activity_logs` VALUES ('55', 'delete', 'menu_type', '4', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu-types/delete', 'POST', '{\"name\":\"Khác\"}', 'info', '2026-04-08 14:43:32');
INSERT INTO `activity_logs` VALUES ('56', 'create', 'menu_type', '9', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu-types/store', 'POST', '{\"name\":\"Khác\",\"name_en\":\"Orthers\",\"type_key\":\"orthers\",\"description\":\"Các món khác\",\"color\":\"#e70d0d\",\"icon\":\"fa-utensils\",\"sort_order\":4}', 'info', '2026-04-08 14:44:10');
INSERT INTO `activity_logs` VALUES ('57', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 14:46:26');
INSERT INTO `activity_logs` VALUES ('58', 'login', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 17:22:56');
INSERT INTO `activity_logs` VALUES ('59', 'create', 'menu_item', '198', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/store', 'POST', '{\"category_id\":42,\"name\":\"Cơm Trắng / Chén\",\"name_en\":\"Steamed Rice\",\"description\":null,\"price\":25000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":1,\"is_active\":1,\"service_type\":\"room_service\",\"menu_type\":\"asia\"}', 'info', '2026-04-08 17:23:51');
INSERT INTO `activity_logs` VALUES ('60', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 17:24:17');
INSERT INTO `activity_logs` VALUES ('61', 'create', 'order', '1', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/tables/open', 'POST', '{\"table_id\":34,\"waiter_id\":\"5\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-08 17:24:22');
INSERT INTO `activity_logs` VALUES ('62', 'create', 'order_item', NULL, '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":1,\"menu_item_id\":198,\"item_name\":\"Cơm Trắng / Chén\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-08 17:24:27');
INSERT INTO `activity_logs` VALUES ('63', 'create', 'order', '2', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/tables/open', 'POST', '{\"table_id\":1,\"waiter_id\":\"5\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-08 17:25:01');
INSERT INTO `activity_logs` VALUES ('64', 'create', 'order', '3', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/tables/open', 'POST', '{\"table_id\":35,\"waiter_id\":\"5\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-08 17:25:09');
INSERT INTO `activity_logs` VALUES ('65', 'update', 'menu_item', '288', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":81,\"name\":\"Bạc Sỉu\",\"name_en\":\"Fresh Milk with Coffee\",\"description\":\"Bạc sỉu nóng hoặc đá\",\"price\":40000,\"stock\":-1,\"tags\":null,\"note_options\":\"nóng, lạnh\",\"note_options_en\":\"hot, ice\",\"sort_order\":0,\"is_active\":1,\"service_type\":\"both\",\"menu_type\":\"other\"}', 'info', '2026-04-08 17:53:26');
INSERT INTO `activity_logs` VALUES ('66', 'update', 'menu_item', '286', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":81,\"name\":\"Cà Phê\",\"name_en\":\"Coffee\",\"description\":\"Cà phê nóng hoặc đá\",\"price\":35000,\"stock\":-1,\"tags\":null,\"note_options\":\"Nóng, Đá\",\"note_options_en\":\"Hot, Iced\",\"sort_order\":0,\"is_active\":1,\"service_type\":\"both\",\"menu_type\":\"other\"}', 'info', '2026-04-08 17:53:57');
INSERT INTO `activity_logs` VALUES ('67', 'update', 'menu_item', '288', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":81,\"name\":\"Bạc Sỉu\",\"name_en\":\"Fresh Milk with Coffee\",\"description\":\"Bạc sỉu nóng hoặc đá\",\"price\":40000,\"stock\":-1,\"tags\":null,\"note_options\":\"Nóng, Đá\",\"note_options_en\":\"Hot, Iced\",\"sort_order\":0,\"is_active\":1,\"service_type\":\"both\",\"menu_type\":\"other\"}', 'info', '2026-04-08 17:54:17');
INSERT INTO `activity_logs` VALUES ('68', 'logout', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-08 21:44:55');
INSERT INTO `activity_logs` VALUES ('69', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 21:44:58');
INSERT INTO `activity_logs` VALUES ('70', 'create', 'order_item', NULL, '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/orders/add', 'POST', '{\"order_id\":5,\"menu_item_id\":200,\"item_name\":\"Bò Cuốn Lá Xanh\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-08 21:45:21');
INSERT INTO `activity_logs` VALUES ('71', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (iPad; CPU OS 16_7_11 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.107 Mobile/15E148 Safari/604.1', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 21:45:50');
INSERT INTO `activity_logs` VALUES ('72', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (iPad; CPU OS 16_7_11 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.107 Mobile/15E148 Safari/604.1', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:25:00');
INSERT INTO `activity_logs` VALUES ('73', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:25:40');
INSERT INTO `activity_logs` VALUES ('74', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:27:17');
INSERT INTO `activity_logs` VALUES ('75', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (iPad; CPU OS 16_7_11 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.107 Mobile/15E148 Safari/604.1', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:28:37');
INSERT INTO `activity_logs` VALUES ('76', 'logout', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (iPad; CPU OS 16_7_11 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.107 Mobile/15E148 Safari/604.1', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 14:28:51');
INSERT INTO `activity_logs` VALUES ('77', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:29:15');
INSERT INTO `activity_logs` VALUES ('78', 'logout', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 14:30:42');
INSERT INTO `activity_logs` VALUES ('79', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:30:45');
INSERT INTO `activity_logs` VALUES ('80', 'update', 'menu_item', '200', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":68,\"name\":\"Bò Cuốn Lá Xanh\",\"name_en\":\"Mustard Leaf Rolls with Beef\",\"description\":\"Bò cuốn lá xanh mù tạt\",\"price\":179000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"asia\"}', 'info', '2026-04-09 14:33:04');
INSERT INTO `activity_logs` VALUES ('81', 'logout', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 14:33:24');
INSERT INTO `activity_logs` VALUES ('82', 'login', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:33:27');
INSERT INTO `activity_logs` VALUES ('83', 'update', 'menu_item', '205', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":69,\"name\":\"Súp Bào Ngư Hải Sản\",\"name_en\":\"Abalone Seafood Soup\",\"description\":\"Súp bào ngư hải sản\",\"price\":130000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"asia\"}', 'info', '2026-04-09 14:41:57');
INSERT INTO `activity_logs` VALUES ('84', 'update', 'menu_item', '283', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":80,\"name\":\"Cơm Trắng Chén\",\"name_en\":\"Steamed Rice / Small Bowl\",\"description\":\"Cơm trắng chén\",\"price\":20000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"room_service\",\"menu_type\":\"alacarte\"}', 'info', '2026-04-09 14:42:43');
INSERT INTO `activity_logs` VALUES ('85', 'logout', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 14:43:44');
INSERT INTO `activity_logs` VALUES ('86', 'login', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:43:46');
INSERT INTO `activity_logs` VALUES ('87', 'logout', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 14:52:24');
INSERT INTO `activity_logs` VALUES ('88', 'login', 'user', '2', '2', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 14:52:26');
INSERT INTO `activity_logs` VALUES ('89', 'logout', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 15:18:32');
INSERT INTO `activity_logs` VALUES ('90', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 15:18:39');
INSERT INTO `activity_logs` VALUES ('91', 'update', 'menu_item', '287', '2', '14.241.187.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":81,\"name\":\"Cà Phê Sữa\",\"name_en\":\"Coffee with Condensed Milk\",\"description\":\"Cà phê sữa nóng hoặc đá\",\"price\":40000,\"stock\":-1,\"tags\":null,\"note_options\":\"Nóng, Đá\",\"note_options_en\":\"Hot, Iced\",\"sort_order\":0,\"is_active\":1,\"service_type\":\"both\",\"menu_type\":\"other\"}', 'info', '2026-04-09 15:39:46');
INSERT INTO `activity_logs` VALUES ('92', 'logout', 'user', '2', '2', '14.241.187.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 15:48:58');
INSERT INTO `activity_logs` VALUES ('93', 'login', 'user', '5', '5', '14.241.187.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 15:49:01');
INSERT INTO `activity_logs` VALUES ('94', 'login', 'user', '7', '7', '14.241.187.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 15:49:59');
INSERT INTO `activity_logs` VALUES ('95', 'logout', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 15:53:29');
INSERT INTO `activity_logs` VALUES ('96', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 15:53:33');
INSERT INTO `activity_logs` VALUES ('97', 'logout', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 15:58:21');
INSERT INTO `activity_logs` VALUES ('98', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 15:58:25');
INSERT INTO `activity_logs` VALUES ('99', 'logout', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 16:31:20');
INSERT INTO `activity_logs` VALUES ('100', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 16:31:24');
INSERT INTO `activity_logs` VALUES ('101', 'logout', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 16:47:29');
INSERT INTO `activity_logs` VALUES ('102', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 16:47:32');
INSERT INTO `activity_logs` VALUES ('103', 'logout', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 17:46:49');
INSERT INTO `activity_logs` VALUES ('104', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 17:46:59');
INSERT INTO `activity_logs` VALUES ('105', 'logout', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 17:49:11');
INSERT INTO `activity_logs` VALUES ('106', 'login', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 17:49:15');
INSERT INTO `activity_logs` VALUES ('107', 'update', 'menu_category', '74', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/categories/update', 'POST', '{\"name\":\"Súp\",\"name_en\":\"Soup\",\"menu_type\":\"asia\",\"icon\":\"fa-bowl-food\",\"sort_order\":1,\"is_active\":1}', 'info', '2026-04-09 18:00:28');
INSERT INTO `activity_logs` VALUES ('108', 'update', 'menu_category', '69', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/categories/update', 'POST', '{\"name\":\"Súp\",\"name_en\":\"Soup\",\"menu_type\":\"europe\",\"icon\":\"fa-bowl-food\",\"sort_order\":2,\"is_active\":1}', 'info', '2026-04-09 18:00:41');
INSERT INTO `activity_logs` VALUES ('109', 'update', 'menu_item', '212', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":70,\"name\":\"Gỏi Xà Lách Trộn Kiểu Pháp\",\"name_en\":\"Caesar Salad\",\"description\":\"Xà lách Romaine, gà, bacon, phô mai Parmesan\",\"price\":135000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"europe\"}', 'info', '2026-04-09 18:01:38');
INSERT INTO `activity_logs` VALUES ('110', 'update', 'menu_item', '237', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":72,\"name\":\"Bún Xào Singapore (Room Service)\",\"name_en\":\"Stir Fried Rice Noodle Singapore Style\",\"description\":\"Bún xào Singapore\",\"price\":185000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"room_service\",\"menu_type\":\"europe\"}', 'info', '2026-04-09 18:02:29');
INSERT INTO `activity_logs` VALUES ('111', 'logout', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 18:04:24');
INSERT INTO `activity_logs` VALUES ('112', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 18:04:27');
INSERT INTO `activity_logs` VALUES ('113', 'update', 'menu_item', '248', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":74,\"name\":\"Súp Kem Bí Đỏ\",\"name_en\":\"Pumpkin Soup\",\"description\":\"Súp kem bí đỏ với kem, phô mai, bánh mì\",\"price\":90000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"europe\"}', 'info', '2026-04-09 18:05:06');
INSERT INTO `activity_logs` VALUES ('114', 'update', 'menu_item', '250', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":74,\"name\":\"Súp Khoai Tây Thịt Nguội\",\"name_en\":\"Ham & Potato Soup\",\"description\":\"Súp khoai tây thịt nguội\",\"price\":90000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"europe\"}', 'info', '2026-04-09 18:05:49');
INSERT INTO `activity_logs` VALUES ('115', 'delete', 'menu_item', '209', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/delete', 'POST', '{\"reason\":\"not_in_use\"}', 'info', '2026-04-09 18:07:16');
INSERT INTO `activity_logs` VALUES ('116', 'update', 'menu_item', '251', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":75,\"name\":\"Xà Lách Trộn Kiểu Pháp\",\"name_en\":\"Caesar Salad\",\"description\":\"Xà lách Romaine, gà, bacon, phô mai Parmesan\",\"price\":135000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"europe\"}', 'info', '2026-04-09 18:07:54');
INSERT INTO `activity_logs` VALUES ('117', 'update', 'menu_item', '252', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":75,\"name\":\"Xà Lách Cá Ngừ Kiểu Pháp\",\"name_en\":\"Nicoise Salad\",\"description\":\"Xà lách cá ngừ kiểu Pháp\",\"price\":135000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"europe\"}', 'info', '2026-04-09 18:08:25');
INSERT INTO `activity_logs` VALUES ('118', 'update', 'menu_item', '253', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":75,\"name\":\"Xà Lách Gà Nướng\",\"name_en\":\"Grilled Chicken Salad\",\"description\":\"Xà lách gà nướng\",\"price\":110000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"restaurant\",\"menu_type\":\"europe\"}', 'info', '2026-04-09 18:09:01');
INSERT INTO `activity_logs` VALUES ('119', 'update', 'menu_item', '283', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu/update', 'POST', '{\"category_id\":80,\"name\":\"Cơm Trắng Chén\",\"name_en\":\"Steamed Rice / Small Bowl\",\"description\":\"Cơm trắng chén\",\"price\":20000,\"stock\":-1,\"tags\":null,\"note_options\":null,\"note_options_en\":null,\"sort_order\":0,\"is_active\":1,\"service_type\":\"both\",\"menu_type\":\"alacarte\"}', 'info', '2026-04-09 18:13:16');
INSERT INTO `activity_logs` VALUES ('120', 'logout', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 18:19:37');
INSERT INTO `activity_logs` VALUES ('121', 'login', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 18:19:41');
INSERT INTO `activity_logs` VALUES ('122', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 18:40:22');
INSERT INTO `activity_logs` VALUES ('123', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:04:28');
INSERT INTO `activity_logs` VALUES ('124', 'login', 'user', '5', '5', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:04:31');
INSERT INTO `activity_logs` VALUES ('125', 'logout', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:05:00');
INSERT INTO `activity_logs` VALUES ('126', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:05:03');
INSERT INTO `activity_logs` VALUES ('127', 'logout', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:12:13');
INSERT INTO `activity_logs` VALUES ('128', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:12:21');
INSERT INTO `activity_logs` VALUES ('129', 'logout', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:14:29');
INSERT INTO `activity_logs` VALUES ('130', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:14:38');
INSERT INTO `activity_logs` VALUES ('131', 'logout', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:14:44');
INSERT INTO `activity_logs` VALUES ('132', 'login', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:14:51');
INSERT INTO `activity_logs` VALUES ('133', 'logout', 'user', '7', '7', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:15:29');
INSERT INTO `activity_logs` VALUES ('134', 'logout', 'user', '5', '5', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:33:11');
INSERT INTO `activity_logs` VALUES ('135', 'login', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:33:15');
INSERT INTO `activity_logs` VALUES ('136', 'logout', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:35:02');
INSERT INTO `activity_logs` VALUES ('137', 'login', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:35:05');
INSERT INTO `activity_logs` VALUES ('138', 'logout', 'user', '7', '7', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-09 19:44:31');
INSERT INTO `activity_logs` VALUES ('139', 'login', 'user', '2', '2', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-09 19:44:34');
INSERT INTO `activity_logs` VALUES ('140', 'login', 'user', '7', '7', '104.28.156.108', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-10 08:30:01');
INSERT INTO `activity_logs` VALUES ('141', 'login', 'user', '5', '5', '104.28.156.108', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-10 08:40:57');
INSERT INTO `activity_logs` VALUES ('142', 'create', 'order', '12', '5', '104.28.156.108', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '/restaurant/tables/open', 'POST', '{\"table_id\":1,\"waiter_id\":\"5\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-10 08:41:04');
INSERT INTO `activity_logs` VALUES ('143', 'login', 'user', '5', '5', '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-10 08:43:54');
INSERT INTO `activity_logs` VALUES ('144', 'create', 'order', '15', '5', '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '/restaurant/tables/open', 'POST', '{\"table_id\":2,\"waiter_id\":\"5\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-10 08:44:00');
INSERT INTO `activity_logs` VALUES ('145', 'login', 'user', '2', '2', '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-10 09:07:24');
INSERT INTO `activity_logs` VALUES ('146', 'logout', 'user', '2', '2', '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-10 09:12:22');
INSERT INTO `activity_logs` VALUES ('147', 'login', 'user', '2', '2', '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-10 09:12:27');
INSERT INTO `activity_logs` VALUES ('148', 'update', 'setting', '0', '2', '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/it/settings/update', 'POST', '{\"key\":\"dev_mode\",\"value\":\"1\"}', 'info', '2026-04-10 09:32:22');
INSERT INTO `activity_logs` VALUES ('149', 'update', 'setting', '0', '2', '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/it/settings/update', 'POST', '{\"key\":\"dev_mode\",\"value\":\"0\"}', 'info', '2026-04-10 09:46:58');
INSERT INTO `activity_logs` VALUES ('150', 'update', 'setting', '0', '2', '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/it/settings/update', 'POST', '{\"key\":\"dev_mode\",\"value\":\"0\"}', 'info', '2026-04-10 09:49:26');
INSERT INTO `activity_logs` VALUES ('151', 'update', 'setting', '0', '2', '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/it/settings/update', 'POST', '{\"key\":\"dev_mode\",\"value\":\"1\"}', 'info', '2026-04-10 09:49:29');
INSERT INTO `activity_logs` VALUES ('152', 'login', 'user', '5', '5', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-11 08:08:52');
INSERT INTO `activity_logs` VALUES ('153', 'logout', 'user', '5', '5', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-11 08:09:31');
INSERT INTO `activity_logs` VALUES ('154', 'login', 'user', '7', '7', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-11 08:09:35');
INSERT INTO `activity_logs` VALUES ('155', 'logout', 'user', '7', '7', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-11 08:10:07');
INSERT INTO `activity_logs` VALUES ('156', 'login', 'user', '5', '5', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-11 08:10:11');
INSERT INTO `activity_logs` VALUES ('157', 'logout', 'user', '5', '5', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-11 08:19:02');
INSERT INTO `activity_logs` VALUES ('158', 'login', 'user', '7', '7', '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-11 08:19:05');

DROP TABLE IF EXISTS `customer_sessions`;
CREATE TABLE `customer_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `table_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `location_data` text DEFAULT NULL COMMENT 'JSON location data for customer tracking',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_session_id` (`session_id`),
  KEY `idx_table_active` (`table_id`,`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customer_sessions` VALUES ('1', '8nqjohv2kjgupk6b3jnnusnhqn', '2', NULL, '118.69.64.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-08 17:59:58', '2026-04-09 17:59:58', '2026-04-08 17:58:59');
INSERT INTO `customer_sessions` VALUES ('3', 'dj0liqblpnju396un6ea0cg0pa', '1', NULL, '14.241.187.72', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-09 15:50:27', '2026-04-10 15:50:27', '2026-04-09 15:50:27');
INSERT INTO `customer_sessions` VALUES ('4', 'g9s81a7dvja3l872635ke2oej6', '1', NULL, '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-09 15:58:01', '2026-04-10 15:58:01', '2026-04-09 15:50:55');
INSERT INTO `customer_sessions` VALUES ('7', 'ltb5p74q7jruck5ua25q7kuso9', '1', NULL, '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-09 16:31:11', '2026-04-10 16:31:11', '2026-04-09 16:25:26');
INSERT INTO `customer_sessions` VALUES ('12', '6q3jg5395q0d7mv97b70cq7bsv', '34', NULL, '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-09 16:58:02', '2026-04-10 16:58:02', '2026-04-09 16:47:44');
INSERT INTO `customer_sessions` VALUES ('16', 'skfic9eng0dvjadvr8u01at6fh', '34', NULL, '118.69.64.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-09 17:25:00', '2026-04-10 17:25:00', '2026-04-09 17:19:46');
INSERT INTO `customer_sessions` VALUES ('23', 'u9l9rgflt0ulrpgkeqkvjm9jn4', '34', NULL, '118.69.64.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-09 17:25:18', '2026-04-10 17:25:18', '2026-04-09 17:25:18');
INSERT INTO `customer_sessions` VALUES ('24', 'oe2tuq3m62pnqnu2prgpgncolu', '1', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '1', '2026-04-09 18:27:57', '2026-04-10 18:27:57', '2026-04-09 18:15:47');
INSERT INTO `customer_sessions` VALUES ('31', 'fribfi3h09dbibta0hecvjlv32', '1', NULL, '123.31.134.55', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-09 19:06:12', '2026-04-10 19:06:12', '2026-04-09 19:05:36');
INSERT INTO `customer_sessions` VALUES ('33', '2de659imbdreti7ps3tv3v00fd', '1', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '1', '2026-04-09 19:10:45', '2026-04-10 19:10:45', '2026-04-09 19:10:45');
INSERT INTO `customer_sessions` VALUES ('34', 'qm0iagqgunsen9k8pd74sj3ku2', '19', NULL, '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', NULL, '1', '2026-04-10 08:43:47', '2026-04-11 08:43:47', '2026-04-10 08:42:38');
INSERT INTO `customer_sessions` VALUES ('37', '58bkl08dmr3u2l5j639dj7k655', '2', NULL, '118.69.240.61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', NULL, '1', '2026-04-10 09:50:01', '2026-04-11 09:50:01', '2026-04-10 09:25:24');
INSERT INTO `customer_sessions` VALUES ('47', 'f8c7uba3e0r6ivvbm82l0gbu4c', '2', NULL, '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', NULL, '1', '2026-04-11 08:09:52', '2026-04-12 08:09:52', '2026-04-11 08:09:52');
INSERT INTO `customer_sessions` VALUES ('48', '0qrfo04dp843kltd1pfu16qvd0', '2', NULL, '123.31.134.55', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', NULL, '1', '2026-04-11 08:18:20', '2026-04-12 08:18:20', '2026-04-11 08:10:33');

DROP TABLE IF EXISTS `location_limits`;
CREATE TABLE `location_limits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT 'Giới hạn QR Restaurant',
  `center_lat` decimal(10,8) NOT NULL COMMENT 'Vĩ độ trung tâm (Aurora Hotel)',
  `center_lng` decimal(11,8) NOT NULL COMMENT 'Kinh độ trung tâm',
  `radius_meters` int(10) unsigned NOT NULL DEFAULT 500 COMMENT 'Bán kính giới hạn (m)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Bật/tắt giới hạn vị trí',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `location_limits` VALUES ('1', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:36:35', '2026-03-08 16:36:35');
INSERT INTO `location_limits` VALUES ('2', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:46:06', '2026-03-08 16:46:06');

DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE `menu_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên danh mục: Khai vị, Chính, Tráng miệng...',
  `name_en` varchar(100) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `menu_type` varchar(50) DEFAULT 'asia' COMMENT 'Tham chiếu đến type_key trong menu_types',
  `icon` varchar(50) DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` smallint(5) unsigned DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_categories` VALUES ('68', 'Khai Vị', 'Appetizer', 'asia', 'fa-utensils', '1', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('69', 'Súp', 'Soup', 'europe', 'fa-bowl-food', '2', '1', '2026-04-08 17:48:52', '2026-04-09 18:00:41');
INSERT INTO `menu_categories` VALUES ('70', 'Gỏi', 'Vietnamese Salad', 'asia', 'fa-leaf', '3', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('71', 'Cơm & Xôi', 'Rice, Sticky Rice', 'asia', 'fa-bowl-rice', '4', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('72', 'Mì & Bún', 'Noodle', 'asia', 'fa-utensils', '5', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('73', 'Cháo', 'Rice Porridge', 'asia', 'fa-spoon', '6', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('74', 'Súp', 'Soup', 'asia', 'fa-bowl-food', '1', '1', '2026-04-08 17:48:52', '2026-04-09 18:00:28');
INSERT INTO `menu_categories` VALUES ('75', 'Xà Lách', 'Salad', 'europe', 'fa-leaf', '2', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('76', 'Mì Ý', 'Spaghetti', 'europe', 'fa-utensils', '3', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('77', 'Sandwich', 'Sandwich', 'europe', 'fa-bread-slice', '4', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('78', 'Món Chính', 'Main Course', 'europe', 'fa-drumstick-bite', '5', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('79', 'Tráng Miệng', 'Dessert', 'europe', 'fa-ice-cream', '6', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('80', 'Món Phụ', 'Sidedish', 'alacarte', 'fa-utensils', '1', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('81', 'Cà Phê', 'Coffee', 'other', 'fa-mug-hot', '1', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('82', 'Nước Ép', 'Fruit Juice', 'other', 'fa-glass-whiskey', '2', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('83', 'Bia', 'Beer', 'other', 'fa-beer', '3', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('84', 'Nước Ngọt', 'Soft Drink', 'other', 'fa-bottle-water', '4', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('85', 'Nước Suối', 'Mineral Water', 'other', 'fa-wine-bottle', '5', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_categories` VALUES ('86', 'Trà', 'Tea Collection', 'other', 'fa-flask', '6', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');

DROP TABLE IF EXISTS `menu_categories_backup`;
CREATE TABLE `menu_categories_backup` (
  `id` int(10) unsigned NOT NULL DEFAULT 0,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên danh mục: Khai vị, Chính, Tráng miệng...',
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `menu_type` enum('asia','europe','alacarte','set','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'asia',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` smallint(5) unsigned DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `menu_type` varchar(50) DEFAULT 'asia' COMMENT 'Tham chiếu đến type_key trong menu_types',
  `name` varchar(150) NOT NULL COMMENT 'Tên món',
  `name_en` varchar(150) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `description` text DEFAULT NULL COMMENT 'Mô tả món',
  `price` decimal(10,0) NOT NULL DEFAULT 0 COMMENT 'Giá (VND)',
  `image` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn ảnh món',
  `is_available` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=còn hàng, 0=hết hàng',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hiển thị, 0=ẩn',
  `service_type` enum('restaurant','room_service','both') NOT NULL DEFAULT 'both',
  `stock` int(11) NOT NULL DEFAULT -1,
  `tags` set('bestseller','new','spicy','vegetarian','recommended') DEFAULT NULL,
  `note_options` text DEFAULT NULL,
  `note_options_en` text DEFAULT NULL,
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_items_category` (`category_id`),
  KEY `idx_items_available` (`is_available`,`is_active`),
  CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_items` VALUES ('199', '68', 'asia', 'Phở Cuốn', 'Rice Pancake Rolls with Beef', 'Phở cuốn với bò', '179000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('200', '68', 'asia', 'Bò Cuốn Lá Xanh', 'Mustard Leaf Rolls with Beef', 'Bò cuốn lá xanh mù tạt', '179000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 14:33:04');
INSERT INTO `menu_items` VALUES ('201', '68', 'asia', 'Gỏi Ngó Sen Đưa Tôm Thịt', 'Lotus Stems Salad with Shrimp & Pork', 'Gỏi ngó sen tôm thịt', '180000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('202', '68', 'asia', 'Gỏi Củ Hủ Dừa Tôm Thịt', 'Coconut Palm Salad with Shrimp & Pork', 'Gỏi củ hủ dừa tôm thịt', '180000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('203', '68', 'asia', 'Gỏi Cuốn Tôm Thịt', 'Fresh Spring Rolls with Shrimp & Pork', 'Gỏi cuốn tôm thịt tươi', '135000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('204', '68', 'asia', 'Chả Giò Hải Sản Aurora', 'Aurora Deep Fried Seafood Spring Rolls', 'Chả giò hải sản Aurora', '135000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('205', '69', 'asia', 'Súp Bào Ngư Hải Sản', 'Abalone Seafood Soup', 'Súp bào ngư hải sản', '130000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 14:41:57');
INSERT INTO `menu_items` VALUES ('206', '69', 'asia', 'Súp Cua Hải Sản Tam Tố', 'Tam To Crab Meat & Seafood Soup', 'Súp cua hải sản tam tố', '130000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('207', '69', 'asia', 'Súp Kem Bí Đỏ', 'Pumpkin Soup', 'Súp kem bí đỏ', '90000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('208', '69', 'asia', 'Súp Kem Nấm', 'Creamy Mushroom Soup', 'Súp kem nấm tươi', '95000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('210', '69', 'asia', 'Súp Kem Nấm (Room Service)', 'Creamy Mushroom Soup', 'Súp kem nấm với sữa tươi, whipping cream', '115000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('211', '69', 'asia', 'Súp Khoai Tây Thịt Nguội (Room Service)', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', '115000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('212', '70', 'europe', 'Gỏi Xà Lách Trộn Kiểu Pháp', 'Caesar Salad', 'Xà lách Romaine, gà, bacon, phô mai Parmesan', '135000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:01:38');
INSERT INTO `menu_items` VALUES ('213', '70', 'asia', 'Xà Lách Cá Ngừ Kiểu Pháp', 'Nicoise Salad', 'Xà lách cá ngừ kiểu Pháp', '135000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('214', '70', 'asia', 'Xà Lách Gà Nướng', 'Grilled Chicken Salad', 'Xà lách gà nướng', '110000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('215', '70', 'asia', 'Gỏi Xà Lách Trộn', 'Mixed Salad', 'Xà lách trộn dầu giấm', '40000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('216', '70', 'asia', 'Gỏi Xà Lách Trứng Luộc', 'Mixed Salad & Boiled Egg', 'Xà lách trộn dầu giấm trứng luộc', '55000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('217', '70', 'asia', 'Xà Lách Trộn (Room Service)', 'Caesar Salad', 'Xà lách trộn kiểu Pháp', '145000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('218', '70', 'asia', 'Xà Lách Gà Nướng (Room Service)', 'Grilled Chicken Salad', 'Xà lách gà nướng', '135000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('219', '71', 'asia', 'Xôi Xéo', 'Steamed Sticky Rice with Green Beans', 'Xôi xéo đậu xanh', '45000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('220', '71', 'asia', 'Cơm Trắng', 'Steamed Rice', 'Cơm trắng', '20000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('221', '71', 'asia', 'Cơm Trắng / Thố', 'Steamed Rice / Big Bowl', 'Cơm trắng thố lớn', '60000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('222', '71', 'asia', 'Cơm Chiên Cá Mặn Gà Xé', 'Fried Rice with Salted Fish & Shredded Chicken', 'Cơm chiên cá mặn gà xé', '105000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('223', '71', 'asia', 'Cơm Chiên Hải Sản Kim Sa', 'Seafood Fried Rice with Salted Egg', 'Cơm chiên hải sản kim sa', '105000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('224', '71', 'asia', 'Cơm Chiên Thịt Xá Xíu Xốt XO', 'Fried Rice with Char Siu & XO Sauce', 'Cơm chiên thịt xá xíu xốt XO', '105000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('225', '71', 'asia', 'Cơm Trắng (Room Service - Chén)', 'Steamed Rice', 'Cơm trắng chén', '25000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('226', '71', 'asia', 'Cơm Chiên Cá Mặn Gà Xé (Room Service)', 'Fried Rice with Salted Fish & Shredded Chicken', 'Cơm chiên cá mặn gà xé', '110000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('227', '71', 'asia', 'Cơm Chiên Hải Sản Kim Sa (Room Service)', 'Seafood Fried Rice with Salted Egg', 'Cơm chiên hải sản kim sa', '135000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('228', '71', 'asia', 'Cơm Chiên Thịt Xá Xíu Xốt XO (Room Service)', 'Fried Rice with Char Siu & XO Sauce', 'Cơm chiên thịt xá xíu xốt XO', '135000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('229', '72', 'asia', 'Bún Mọc Măng Dọc Mùng', 'Vietnamese Colocasia Gigantea Noodle Soup', 'Bún mọc măng dọc mùng', '75000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('230', '72', 'asia', 'Bún Ốc Hà Nội', 'Vietnamese Noodle Soup with Snail', 'Bún ốc Hà Nội', '70000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('231', '72', 'asia', 'Bún Cá Hà Nội', 'Vietnamese Fish Noodle Soup', 'Bún cá Hà Nội', '75000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('232', '72', 'asia', 'Bún Xào Singapore', 'Stir Fried Rice Noodle Singapore Style', 'Bún xào Singapore', '185000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('233', '72', 'asia', 'Miến Xào Hàn Quốc', 'Stir Fried Vermicelli with Beef & Vegetables', 'Miến xào Hàn Quốc', '230000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('234', '72', 'asia', 'Mì Xào Tôm', 'Stir Fried Yellow Noodle with Shrimp', 'Mì xào tôm', '140000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('235', '72', 'asia', 'Mì Xào Thịt Bò', 'Stir Fried Yellow Noodle with Beef', 'Mì xào thịt bò', '190000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('236', '72', 'asia', 'Mì Xào Hải Sản', 'Stir Fried Yellow Noodle with Seafood', 'Mì xào hải sản', '165000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('237', '72', 'europe', 'Bún Xào Singapore (Room Service)', 'Stir Fried Rice Noodle Singapore Style', 'Bún xào Singapore', '185000', NULL, '1', '1', 'room_service', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:02:29');
INSERT INTO `menu_items` VALUES ('238', '72', 'asia', 'Mì Xào Thịt Bò (Room Service)', 'Stir Fried Yellow Noodle with Beef', 'Mì xào thịt bò', '190000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('239', '72', 'asia', 'Mì Xào Tôm (Room Service)', 'Stir Fried Yellow Noodle with Shrimp', 'Mì xào tôm', '160000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('240', '72', 'asia', 'Mì Xào Hải Sản (Room Service)', 'Stir Fried Yellow Noodle with Seafood', 'Mì xào hải sản', '190000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('241', '73', 'asia', 'Cháo Bò Bằm', 'Rice Porridge with Minced Beef', 'Cháo bò bằm', '115000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('242', '73', 'asia', 'Cháo Thịt Bằm', 'Rice Porridge with Minced Pork', 'Cháo thịt bằm', '65000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('243', '73', 'asia', 'Cháo Hải Sản', 'Rice Porridge with Seafood', 'Cháo hải sản', '115000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('244', '73', 'asia', 'Cháo Bò Bằm (Room Service)', 'Rice Porridge with Minced Beef', 'Cháo bò bằm', '115000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('245', '73', 'asia', 'Cháo Thịt Bằm (Room Service)', 'Rice Porridge with Minced Pork', 'Cháo thịt bằm', '90000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('246', '73', 'asia', 'Cháo Hải Sản (Room Service)', 'Rice Porridge with Seafood', 'Cháo hải sản', '155000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('247', '73', 'asia', 'Cháo Lá Dứa Hột Vịt Muối', 'Rice Porridge with Salted Egg', 'Cháo lá dứa hột vịt muối', '45000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('248', '74', 'europe', 'Súp Kem Bí Đỏ', 'Pumpkin Soup', 'Súp kem bí đỏ với kem, phô mai, bánh mì', '90000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:05:06');
INSERT INTO `menu_items` VALUES ('249', '74', 'asia', 'Súp Kem Nấm Tươi', 'Creamy Mushroom Soup', 'Súp kem nấm tươi', '95000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('250', '74', 'europe', 'Súp Khoai Tây Thịt Nguội', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', '90000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:05:49');
INSERT INTO `menu_items` VALUES ('251', '75', 'europe', 'Xà Lách Trộn Kiểu Pháp', 'Caesar Salad', 'Xà lách Romaine, gà, bacon, phô mai Parmesan', '135000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:07:54');
INSERT INTO `menu_items` VALUES ('252', '75', 'europe', 'Xà Lách Cá Ngừ Kiểu Pháp', 'Nicoise Salad', 'Xà lách cá ngừ kiểu Pháp', '135000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:08:25');
INSERT INTO `menu_items` VALUES ('253', '75', 'europe', 'Xà Lách Gà Nướng', 'Grilled Chicken Salad', 'Xà lách gà nướng', '110000', NULL, '1', '1', 'restaurant', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:09:01');
INSERT INTO `menu_items` VALUES ('254', '76', 'asia', 'Spaghetti Bolognese', 'Spaghetti Bolognese', 'Mì Ý sốt bò bằm', '195000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('255', '76', 'asia', 'Spaghetti Carbonara', 'Spaghetti Carbonara', 'Mì Ý sốt kem', '175000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('256', '76', 'asia', 'Spaghetti Marinara', 'Spaghetti Marinara', 'Mì Ý sốt hải sản', '215000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('257', '77', 'asia', 'Bánh Mì Sandwich Kẹp Phô Mai Thịt Nguội', 'Ham Cheese Sandwich', 'Bánh mì sandwich kẹp phô mai thịt nguội', '165000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('258', '77', 'asia', 'Bánh Mì Sandwich Thập Cẩm', 'Club Sandwich', 'Bánh mì sandwich thập cẩm', '210000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('259', '77', 'asia', 'Burger Bò', 'Beef Burger', 'Burger bò', '170000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('260', '77', 'asia', 'Club Sandwich (Room Service)', 'Club Sandwich', 'Bánh mì sandwich thập cẩm', '210000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('261', '77', 'asia', 'Beef Burger (Room Service)', 'Beef Burger', 'Burger bò', '215000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('262', '78', 'asia', 'Cá Hồi Nướng Xốt Nấm Hoặc Xốt Tiêu', 'Grilled Salmon with Mushroom Sauce or Pepper Sauce', 'Cá hồi nướng xốt nấm hoặc xốt tiêu', '345000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('263', '78', 'asia', 'Ức Vịt Xông Khói Xốt Samba', 'Smoked Duck Breast with Samba Sauce', 'Ức vịt xông khói xốt samba', '185000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('264', '78', 'asia', 'Ức Gà Nướng Xốt Nấm', 'Grilled Chicken Breast with Mushroom Sauce', 'Ức gà nướng xốt nấm', '130000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('265', '78', 'asia', 'Thăn Bò Áp Chảo Xốt Nấm Hoặc Xốt Tiêu', 'Roasted Beef Tenderloin with Mushroom Sauce or Pepper Sauce', 'Thăn bò áp chảo xốt nấm hoặc xốt tiêu', '330000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('266', '78', 'asia', 'Cá Hồi Nướng Xốt Nấm Hoặc Xốt Tiêu (Room Service)', 'Grilled Salmon with Mushroom Sauce or Pepper Sauce', 'Cá hồi nướng xốt nấm hoặc xốt tiêu', '475000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('267', '78', 'asia', 'Ức Vịt Xông Khói Xốt Samba (Room Service)', 'Smoked Duck Breast with Samba Sauce', 'Ức vịt xông khói xốt samba', '205000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('268', '78', 'asia', 'Thăn Bò Áp Chảo Xốt Nấm Hoặc Xốt Tiêu (Room Service)', 'Roasted Beef Tenderloin with Mushroom Sauce or Pepper Sauce', 'Thăn bò áp chảo xốt nấm hoặc xốt tiêu', '355000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('269', '79', 'asia', 'Trái Cây Đốt Rượu', 'Fresh Fruit Flambee', 'Trái cây đốt rượu với bơ, nước cam, đường, rhum đen', '85000', NULL, '1', '1', 'restaurant', '-1', '', 'Chọn loại trái: Xoài, Chuối, Thơm', 'Ask the waiter for your choice: Mango, Banana, Pineapple', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('270', '79', 'asia', 'Xoài Đốt Rượu', 'Mango Flambe', 'Xoài đốt rượu', '85000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('271', '79', 'asia', 'Thơm Đốt Rượu', 'Pineapple Flambe', 'Thơm đốt rượu', '70000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('272', '79', 'asia', 'Chuối Đốt Rượu', 'Banana Flambe', 'Chuối đốt rượu', '65000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('273', '79', 'asia', 'Trái Cây Theo Mùa', 'Seasonal Fresh Fruit', 'Trái cây theo mùa', '135000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('274', '79', 'asia', 'Trái Cây 3 Loại', '03 Kind of Seasonal Fresh Fruit', 'Trái cây 3 loại: Xoài, Thơm, Ổi', '135000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('275', '79', 'asia', 'Trái Cây 4 Loại', '04 Kind of Seasonal Fresh Fruit', 'Trái cây 4 loại: Xoài, Thơm, Ổi, Dưa Hấu', '175000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('276', '79', 'asia', 'Xoài Đốt Rượu (Room Service)', 'Mango Flambe', 'Xoài đốt rượu', '95000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('277', '79', 'asia', 'Trái Cây 3 Loại (Room Service)', '03 Kind of Seasonal Fresh Fruit', 'Trái cây 3 loại', '150000', NULL, '1', '1', 'room_service', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('278', '80', 'asia', 'Xà Lách Trộn Dầu Giấm', 'Mixed Salad with Vinegar Dressing', 'Xà lách, cà chua, hành tây, dưa leo', '40000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('279', '80', 'asia', 'Rau Thập Cẩm Xào Tỏi', 'Stir Fried Vegetables with Garlic', 'Rau thập cẩm xào tỏi', '65000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('280', '80', 'asia', 'Bánh Mì Bơ Tỏi', 'Garlic Bread', 'Bánh mì bơ lát tỏi băm', '70000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('281', '80', 'asia', 'Khoai Tây Chiên', 'French Fries', 'Khoai tây chiên', '50000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('282', '80', 'asia', 'Cơm Trắng Thố', 'Steamed Rice / Big Bowl', 'Cơm trắng thố', '60000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('283', '80', 'alacarte', 'Cơm Trắng Chén', 'Steamed Rice / Small Bowl', 'Cơm trắng chén', '20000', NULL, '1', '1', 'both', '-1', NULL, NULL, NULL, '0', '2026-04-08 17:48:52', '2026-04-09 18:13:16');
INSERT INTO `menu_items` VALUES ('284', '80', 'asia', 'Cơm Chiên Tỏi Trứng', 'Fried Rice with Garlic & Egg', 'Cơm chiên tỏi trứng', '55000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('285', '80', 'asia', 'Cơm Chiên Tỏi', 'Fried Rice with Garlic', 'Cơm chiên tỏi', '40000', NULL, '1', '1', 'restaurant', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-09 14:50:16');
INSERT INTO `menu_items` VALUES ('286', '81', 'other', 'Cà Phê', 'Coffee', 'Cà phê nóng hoặc đá', '35000', NULL, '1', '1', 'both', '-1', NULL, 'Nóng, Đá', 'Hot, Iced', '0', '2026-04-08 17:48:52', '2026-04-08 17:53:57');
INSERT INTO `menu_items` VALUES ('287', '81', 'other', 'Cà Phê Sữa', 'Coffee with Condensed Milk', 'Cà phê sữa nóng hoặc đá', '40000', NULL, '1', '1', 'both', '-1', NULL, 'Nóng, Đá', 'Hot, Iced', '0', '2026-04-08 17:48:52', '2026-04-09 15:39:46');
INSERT INTO `menu_items` VALUES ('288', '81', 'other', 'Bạc Sỉu', 'Fresh Milk with Coffee', 'Bạc sỉu nóng hoặc đá', '40000', NULL, '1', '1', 'both', '-1', NULL, 'Nóng, Đá', 'Hot, Iced', '0', '2026-04-08 17:48:52', '2026-04-08 17:54:17');
INSERT INTO `menu_items` VALUES ('289', '82', 'asia', 'Nước Cam Tươi', 'Orange Juice', 'Nước cam tươi', '60000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('290', '82', 'asia', 'Nước Xoài', 'Mango Juice', 'Nước xoài', '65000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('291', '82', 'asia', 'Nước Ép Thơm', 'Pineapple Juice', 'Nước ép thơm', '60000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('292', '82', 'asia', 'Nước Ép Dưa Hấu', 'Water Melon Juice', 'Nước ép dưa hấu', '60000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('293', '82', 'asia', 'Nước Chanh Dây', 'Passion Fruit Juice', 'Nước chanh dây', '60000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('294', '83', 'asia', 'Tiger Can Lon', 'Tiger', 'Bia Tiger can lon', '30000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('295', '83', 'asia', 'Tiger Silver Can Lon', 'Tiger Silver', 'Bia Tiger Silver can lon', '32000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('296', '83', 'asia', 'Heineken Can Lon', 'Heineken', 'Bia Heineken can lon', '34000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('297', '83', 'asia', 'Heineken Silver Can Lon', 'Heineken Silver', 'Bia Heineken Silver can lon', '36000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('298', '84', 'asia', 'Pepsi', 'Pepsi', 'Nước ngọt Pepsi', '25000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('299', '84', 'asia', '7 Up', '7 Up', 'Nước ngọt 7 Up', '25000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('300', '84', 'asia', 'Soda', 'Soda', 'Nước soda', '25000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('301', '85', 'asia', 'Aquafina 500ml', 'Aquafina 500ml', 'Nước suối Aquafina 500ml', '20000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('302', '85', 'asia', 'Aquafina 1.5L', 'Aquafina 1.5L', 'Nước suối Aquafina 1.5L', '45000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('303', '85', 'asia', 'Perrier', 'Perrier', 'Nước suối Perrier', '85000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('304', '86', 'asia', 'Trà Đen', 'Black Tea', 'Trà đen', '50000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('305', '86', 'asia', 'Trà Lipton', 'Lipton Tea', 'Trà Lipton', '50000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('306', '86', 'asia', 'Trà Sen', 'Lotus Tea', 'Trà sen', '50000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');
INSERT INTO `menu_items` VALUES ('307', '86', 'asia', 'Trà Lài', 'Jasmine Tea', 'Trà lài', '50000', NULL, '1', '1', 'both', '-1', '', '', '', '0', '2026-04-08 17:48:52', '2026-04-08 17:55:38');

DROP TABLE IF EXISTS `menu_set_items`;
CREATE TABLE `menu_set_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(10) unsigned NOT NULL,
  `menu_item_id` int(10) unsigned NOT NULL,
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `is_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=bắt buộc, 0=tuỳ chọn',
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_set_items_set` (`set_id`),
  KEY `fk_set_items_menu` (`menu_item_id`),
  CONSTRAINT `fk_set_items_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_set_items_set` FOREIGN KEY (`set_id`) REFERENCES `menu_sets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `menu_sets`;
CREATE TABLE `menu_sets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT 'Tên set',
  `name_en` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `menu_types`;
CREATE TABLE `menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên tiếng Việt',
  `name_en` varchar(100) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `type_key` varchar(50) NOT NULL COMMENT 'Mã định danh (vd: asia, europe, alacarte)',
  `description` text DEFAULT NULL COMMENT 'Mô tả ngắn',
  `color` varchar(20) DEFAULT '#0ea5e9' COMMENT 'Màu sắc đại diện (hex)',
  `icon` varchar(50) DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự sắp xếp',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Hiện, 0: Ẩn',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_type_key` (`type_key`),
  KEY `idx_types_active` (`is_active`),
  KEY `idx_types_sort` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân loại menu (Á, Âu, Alacarte, Khác)';

INSERT INTO `menu_types` VALUES ('18', 'Món Á', 'Asian Cuisine', 'asia', 'Các món ăn truyền thống châu Á', '#0ea5e9', 'fa-bowl-rice', '1', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_types` VALUES ('19', 'Món Âu', 'European Cuisine', 'europe', 'Các món ăn phong cách châu Âu', '#8b5cf6', 'fa-wine-glass', '2', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_types` VALUES ('20', 'Alacarte', 'Alacarte', 'alacarte', 'Các món gọi riêng', '#f59e0b', 'fa-utensils', '3', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');
INSERT INTO `menu_types` VALUES ('21', 'Đồ Uống', 'Beverages', 'other', 'Đồ uống các loại', '#10b981', 'fa-cocktail', '4', '1', '2026-04-08 17:48:52', '2026-04-08 17:48:52');

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `table_id` int(11) unsigned DEFAULT NULL COMMENT 'Bàn vật lý mà món này thuộc về (cho merged tables)',
  `menu_item_id` int(10) unsigned NOT NULL,
  `item_name` varchar(150) NOT NULL COMMENT 'Snapshot tên món tại thời điểm ghi',
  `item_price` decimal(10,0) NOT NULL COMMENT 'Snapshot giá tại thời điểm ghi',
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `note` varchar(255) DEFAULT NULL COMMENT 'Ghi chú: không hành, ít cay...',
  `split_from_item_id` int(11) unsigned DEFAULT NULL COMMENT 'ID của món gốc mà món này được tách từ đó',
  `is_split_item` tinyint(1) unsigned DEFAULT 0 COMMENT '1 = món này đã được tách từ bàn khác',
  `status` enum('draft','confirmed','cancelled') DEFAULT 'draft',
  `customer_id` varchar(64) DEFAULT NULL COMMENT 'Session ID của khách hàng (cho customer ordering)',
  `submitted_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian khách gửi món (chuyển từ draft sang pending)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order` (`order_id`),
  KEY `fk_order_items_menu` (`menu_item_id`),
  KEY `idx_order_items_table` (`table_id`),
  KEY `idx_split_tracking` (`is_split_item`,`split_from_item_id`),
  KEY `idx_table_status` (`table_id`,`status`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_submitted_at` (`submitted_at`),
  CONSTRAINT `fk_order_items_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` VALUES ('2', '5', '27', '200', 'Bò Cuốn Lá Xanh', '179000', '1', '', NULL, '0', 'confirmed', NULL, NULL, '2026-04-08 21:45:21', '2026-04-09 14:29:29');
INSERT INTO `order_items` VALUES ('3', '6', '1', '288', 'Bạc Sỉu', '40000', '1', 'Nóng / Hot', NULL, '0', '', 'ba33bd9f9138c7045dce23f24cba724e', '2026-04-09 15:51:43', '2026-04-09 15:51:43', '2026-04-09 15:51:43');
INSERT INTO `order_items` VALUES ('4', '7', '1', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '475c455fb9c07ba1a5b826e35d3b0e7e', '2026-04-09 16:25:37', '2026-04-09 16:25:37', '2026-04-09 16:25:37');
INSERT INTO `order_items` VALUES ('5', '7', '1', '288', 'Bạc Sỉu', '40000', '1', 'Đá / Iced', NULL, '0', '', '475c455fb9c07ba1a5b826e35d3b0e7e', '2026-04-09 16:25:37', '2026-04-09 16:25:37', '2026-04-09 16:25:37');
INSERT INTO `order_items` VALUES ('6', '7', '1', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '475c455fb9c07ba1a5b826e35d3b0e7e', '2026-04-09 16:30:40', '2026-04-09 16:30:40', '2026-04-09 16:30:40');
INSERT INTO `order_items` VALUES ('7', '8', '34', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', 'e45017afca1b93523a5408eb9ae0ad6c', '2026-04-09 16:57:58', '2026-04-09 16:57:58', '2026-04-09 16:57:58');
INSERT INTO `order_items` VALUES ('8', '9', '1', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:20:40', '2026-04-09 18:20:40', '2026-04-09 18:20:40');
INSERT INTO `order_items` VALUES ('9', '9', '1', '287', 'Cà Phê Sữa', '40000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:20:40', '2026-04-09 18:20:40', '2026-04-09 18:20:40');
INSERT INTO `order_items` VALUES ('10', '9', '1', '287', 'Cà Phê Sữa', '40000', '1', 'Nóng / Hot', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:20:40', '2026-04-09 18:20:40', '2026-04-09 18:20:40');
INSERT INTO `order_items` VALUES ('11', '9', '1', '284', 'Cơm Chiên Tỏi Trứng', '55000', '2', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:21:48', '2026-04-09 18:21:48', '2026-04-09 18:21:48');
INSERT INTO `order_items` VALUES ('12', '9', '1', '283', 'Cơm Trắng Chén', '20000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:21:48', '2026-04-09 18:21:48', '2026-04-09 18:21:48');
INSERT INTO `order_items` VALUES ('13', '9', '1', '248', 'Súp Kem Bí Đỏ', '90000', '2', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:21:48', '2026-04-09 18:21:48', '2026-04-09 18:21:48');
INSERT INTO `order_items` VALUES ('14', '9', '1', '250', 'Súp Khoai Tây Thịt Nguội', '90000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:21:48', '2026-04-09 18:21:48', '2026-04-09 18:21:48');
INSERT INTO `order_items` VALUES ('15', '9', '1', '289', 'Nước Cam Tươi', '60000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:21:48', '2026-04-09 18:21:48', '2026-04-09 18:21:48');
INSERT INTO `order_items` VALUES ('16', '9', '1', '292', 'Nước Ép Dưa Hấu', '60000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:21:48', '2026-04-09 18:21:48', '2026-04-09 18:21:48');
INSERT INTO `order_items` VALUES ('17', '9', '1', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:23:18', '2026-04-09 18:23:18', '2026-04-09 18:23:18');
INSERT INTO `order_items` VALUES ('18', '9', '1', '287', 'Cà Phê Sữa', '40000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:23:18', '2026-04-09 18:23:18', '2026-04-09 18:23:18');
INSERT INTO `order_items` VALUES ('19', '9', '1', '202', 'Gỏi Củ Hủ Dừa Tôm Thịt', '180000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:23:18', '2026-04-09 18:23:18', '2026-04-09 18:23:18');
INSERT INTO `order_items` VALUES ('20', '9', '1', '201', 'Gỏi Ngó Sen Đưa Tôm Thịt', '180000', '1', '', NULL, '0', '', '4bcebd09ef806a891c92289a3561f8ef', '2026-04-09 18:23:18', '2026-04-09 18:23:18', '2026-04-09 18:23:18');
INSERT INTO `order_items` VALUES ('21', '10', '1', '248', 'Súp Kem Bí Đỏ', '90000', '1', '', NULL, '0', '', '3cdf7668bc450e0eb4973ed6881e0c19', '2026-04-09 19:05:57', '2026-04-09 19:05:57', '2026-04-09 19:05:57');
INSERT INTO `order_items` VALUES ('22', '11', '1', '288', 'Bạc Sỉu', '40000', '1', 'zxcscs', NULL, '0', '', '6d14031a6d76828f12f28d393f55e06a', '2026-04-09 19:10:58', '2026-04-09 19:10:58', '2026-04-09 19:10:58');
INSERT INTO `order_items` VALUES ('23', '16', '2', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '081827ec611553d35e78c6d126bdb37f', '2026-04-10 09:32:45', '2026-04-10 09:32:45', '2026-04-10 09:32:45');
INSERT INTO `order_items` VALUES ('24', '16', '2', '287', 'Cà Phê Sữa', '40000', '1', '', NULL, '0', '', '081827ec611553d35e78c6d126bdb37f', '2026-04-10 09:32:45', '2026-04-10 09:32:45', '2026-04-10 09:32:45');
INSERT INTO `order_items` VALUES ('25', '16', '2', '200', 'Bò Cuốn Lá Xanh', '179000', '1', '', NULL, '0', '', '081827ec611553d35e78c6d126bdb37f', '2026-04-10 09:32:45', '2026-04-10 09:32:45', '2026-04-10 09:32:45');
INSERT INTO `order_items` VALUES ('26', '16', '2', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '081827ec611553d35e78c6d126bdb37f', '2026-04-10 09:38:26', '2026-04-10 09:38:26', '2026-04-10 09:38:26');
INSERT INTO `order_items` VALUES ('27', '16', '2', '200', 'Bò Cuốn Lá Xanh', '179000', '1', '', NULL, '0', '', '081827ec611553d35e78c6d126bdb37f', '2026-04-10 09:38:26', '2026-04-10 09:38:26', '2026-04-10 09:38:26');
INSERT INTO `order_items` VALUES ('28', '16', '2', '287', 'Cà Phê Sữa', '40000', '1', '', NULL, '0', '', '081827ec611553d35e78c6d126bdb37f', '2026-04-10 09:43:44', '2026-04-10 09:43:44', '2026-04-10 09:43:44');
INSERT INTO `order_items` VALUES ('29', '16', '2', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '081827ec611553d35e78c6d126bdb37f', '2026-04-10 09:50:08', '2026-04-10 09:50:08', '2026-04-10 09:50:08');
INSERT INTO `order_items` VALUES ('30', '17', '2', '288', 'Bạc Sỉu', '40000', '1', '', NULL, '0', '', '35e421a81e6f86bc30c264da6fae7379', '2026-04-11 08:10:39', '2026-04-11 08:10:39', '2026-04-11 08:10:39');
INSERT INTO `order_items` VALUES ('31', '17', '2', '286', 'Cà Phê', '35000', '2', '', NULL, '0', '', '35e421a81e6f86bc30c264da6fae7379', '2026-04-11 08:10:39', '2026-04-11 08:10:39', '2026-04-11 08:10:39');
INSERT INTO `order_items` VALUES ('32', '17', '2', '201', 'Gỏi Ngó Sen Đưa Tôm Thịt', '180000', '1', '', NULL, '0', '', '35e421a81e6f86bc30c264da6fae7379', '2026-04-11 08:12:15', '2026-04-11 08:12:15', '2026-04-11 08:12:15');
INSERT INTO `order_items` VALUES ('33', '17', '2', '200', 'Bò Cuốn Lá Xanh', '179000', '1', '', NULL, '0', '', '35e421a81e6f86bc30c264da6fae7379', '2026-04-11 08:18:11', '2026-04-11 08:18:11', '2026-04-11 08:18:11');
INSERT INTO `order_items` VALUES ('34', '17', '2', '288', 'Bạc Sỉu', '40000', '2', '', NULL, '0', '', '35e421a81e6f86bc30c264da6fae7379', '2026-04-11 08:18:11', '2026-04-11 08:18:11', '2026-04-11 08:18:11');

DROP TABLE IF EXISTS `order_notifications`;
CREATE TABLE `order_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned DEFAULT NULL,
  `table_id` int(10) unsigned NOT NULL,
  `notification_type` enum('new_order','order_item','support_request','payment_request','scan_qr') NOT NULL DEFAULT 'new_order',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `read_by` int(10) unsigned DEFAULT NULL COMMENT 'Nhân viên đã đọc',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notification_order` (`order_id`),
  KEY `idx_notification_table` (`table_id`),
  KEY `idx_notification_unread` (`is_read`),
  KEY `idx_notification_type` (`notification_type`),
  KEY `idx_notification_created` (`created_at`),
  CONSTRAINT `fk_notification_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notification_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu trữ thông báo order cho waiter';

INSERT INTO `order_notifications` VALUES ('1', '4', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-09 14:26:23', '5', '2026-04-08 17:58:59');
INSERT INTO `order_notifications` VALUES ('2', '4', '2', 'support_request', 'Bàn 2: Cần hỗ trợ', 'Khách tại bàn 2 đang gọi nhân viên.', '1', '2026-04-09 14:26:22', '5', '2026-04-08 17:59:54');
INSERT INTO `order_notifications` VALUES ('3', '4', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-09 14:26:22', '5', '2026-04-08 17:59:58');
INSERT INTO `order_notifications` VALUES ('4', '6', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 15:59:48', '5', '2026-04-09 15:50:27');
INSERT INTO `order_notifications` VALUES ('5', '6', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 15:59:48', '5', '2026-04-09 15:50:55');
INSERT INTO `order_notifications` VALUES ('6', '6', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 15:59:48', '5', '2026-04-09 15:51:43');
INSERT INTO `order_notifications` VALUES ('7', '6', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 15:59:48', '5', '2026-04-09 15:57:53');
INSERT INTO `order_notifications` VALUES ('8', '6', '1', 'payment_request', 'Bàn 1: Yêu cầu thanh toán', 'Khách tại bàn 1 yêu cầu tính tiền.', '1', '2026-04-09 15:58:32', '5', '2026-04-09 15:57:57');
INSERT INTO `order_notifications` VALUES ('9', '6', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 15:59:47', '5', '2026-04-09 15:58:01');
INSERT INTO `order_notifications` VALUES ('10', '7', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 16:46:45', '5', '2026-04-09 16:25:26');
INSERT INTO `order_notifications` VALUES ('11', '7', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 16:46:44', '5', '2026-04-09 16:25:37');
INSERT INTO `order_notifications` VALUES ('12', '7', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 16:46:44', '5', '2026-04-09 16:26:20');
INSERT INTO `order_notifications` VALUES ('13', '7', '1', 'support_request', 'Bàn 1: Cần hỗ trợ', 'Khách tại bàn 1 đang gọi nhân viên.', '1', '2026-04-09 16:32:15', '5', '2026-04-09 16:27:00');
INSERT INTO `order_notifications` VALUES ('14', '7', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 16:46:44', '5', '2026-04-09 16:27:12');
INSERT INTO `order_notifications` VALUES ('15', '7', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 16:46:44', '5', '2026-04-09 16:30:40');
INSERT INTO `order_notifications` VALUES ('16', '7', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 16:32:09', '5', '2026-04-09 16:30:59');
INSERT INTO `order_notifications` VALUES ('17', '7', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 16:32:09', '5', '2026-04-09 16:31:11');
INSERT INTO `order_notifications` VALUES ('18', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 16:47:44');
INSERT INTO `order_notifications` VALUES ('19', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 16:54:23');
INSERT INTO `order_notifications` VALUES ('20', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 16:57:00');
INSERT INTO `order_notifications` VALUES ('21', '8', '34', 'order_item', 'Bàn 701: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 16:57:58');
INSERT INTO `order_notifications` VALUES ('22', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 16:58:02');
INSERT INTO `order_notifications` VALUES ('23', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:19:46');
INSERT INTO `order_notifications` VALUES ('24', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:21:55');
INSERT INTO `order_notifications` VALUES ('25', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:21:56');
INSERT INTO `order_notifications` VALUES ('26', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:21:59');
INSERT INTO `order_notifications` VALUES ('27', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:22:30');
INSERT INTO `order_notifications` VALUES ('28', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:22:39');
INSERT INTO `order_notifications` VALUES ('29', '8', '34', 'payment_request', 'Bàn 34: Yêu cầu thanh toán', 'Khách tại bàn 34 yêu cầu tính tiền.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:22:46');
INSERT INTO `order_notifications` VALUES ('30', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:25:00');
INSERT INTO `order_notifications` VALUES ('31', '8', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 17:25:18');
INSERT INTO `order_notifications` VALUES ('32', '9', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 18:15:47');
INSERT INTO `order_notifications` VALUES ('33', '9', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:19:34', '7', '2026-04-09 18:19:22');
INSERT INTO `order_notifications` VALUES ('34', '9', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 19:11:25', '7', '2026-04-09 18:20:26');
INSERT INTO `order_notifications` VALUES ('35', '9', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 19:11:25', '7', '2026-04-09 18:20:40');
INSERT INTO `order_notifications` VALUES ('36', '9', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 19:11:25', '7', '2026-04-09 18:20:45');
INSERT INTO `order_notifications` VALUES ('37', '9', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 19:11:25', '7', '2026-04-09 18:21:48');
INSERT INTO `order_notifications` VALUES ('38', '9', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:29:54', '5', '2026-04-09 18:21:53');
INSERT INTO `order_notifications` VALUES ('39', '9', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:29:54', '5', '2026-04-09 18:23:06');
INSERT INTO `order_notifications` VALUES ('40', '9', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 18:29:53', '5', '2026-04-09 18:23:18');
INSERT INTO `order_notifications` VALUES ('41', '9', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 18:29:49', '5', '2026-04-09 18:27:57');
INSERT INTO `order_notifications` VALUES ('42', '9', '1', 'support_request', 'Bàn 1: Cần hỗ trợ', 'Khách tại bàn 1 đang gọi nhân viên.', '1', '2026-04-09 18:29:48', '5', '2026-04-09 18:28:08');
INSERT INTO `order_notifications` VALUES ('43', '10', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 19:07:01', '5', '2026-04-09 19:05:36');
INSERT INTO `order_notifications` VALUES ('44', '10', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 19:07:00', '5', '2026-04-09 19:05:57');
INSERT INTO `order_notifications` VALUES ('45', '10', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 19:06:59', '5', '2026-04-09 19:06:12');
INSERT INTO `order_notifications` VALUES ('46', '10', '1', 'support_request', 'Bàn 1: Cần hỗ trợ', 'Khách tại bàn 1 đang gọi nhân viên.', '1', '2026-04-09 19:11:25', '7', '2026-04-09 19:07:17');
INSERT INTO `order_notifications` VALUES ('47', '10', '1', 'payment_request', 'Bàn 1: Yêu cầu thanh toán', 'Khách tại bàn 1 yêu cầu tính tiền.', '1', '2026-04-09 19:07:54', '5', '2026-04-09 19:07:27');
INSERT INTO `order_notifications` VALUES ('48', '11', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-09 19:11:25', '7', '2026-04-09 19:10:45');
INSERT INTO `order_notifications` VALUES ('49', '11', '1', 'order_item', 'Bàn A.01: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-09 19:11:25', '7', '2026-04-09 19:10:58');
INSERT INTO `order_notifications` VALUES ('50', '13', '18', 'scan_qr', 'Khách xem menu', 'Bàn C.06 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:09', '5', '2026-04-10 08:42:38');
INSERT INTO `order_notifications` VALUES ('51', '14', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:09', '5', '2026-04-10 08:43:31');
INSERT INTO `order_notifications` VALUES ('52', '14', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:09', '5', '2026-04-10 08:43:47');
INSERT INTO `order_notifications` VALUES ('53', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:09', '5', '2026-04-10 09:25:24');
INSERT INTO `order_notifications` VALUES ('54', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:04', '5', '2026-04-10 09:26:29');
INSERT INTO `order_notifications` VALUES ('55', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:08', '5', '2026-04-10 09:32:32');
INSERT INTO `order_notifications` VALUES ('56', '16', '2', 'order_item', 'Bàn A.02: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-11 08:09:03', '5', '2026-04-10 09:32:45');
INSERT INTO `order_notifications` VALUES ('57', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:01', '5', '2026-04-10 09:35:15');
INSERT INTO `order_notifications` VALUES ('58', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:01', '5', '2026-04-10 09:38:15');
INSERT INTO `order_notifications` VALUES ('59', '16', '2', 'order_item', 'Bàn A.02: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-11 08:09:00', '5', '2026-04-10 09:38:26');
INSERT INTO `order_notifications` VALUES ('60', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:00', '5', '2026-04-10 09:43:29');
INSERT INTO `order_notifications` VALUES ('61', '16', '2', 'order_item', 'Bàn A.02: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-11 08:09:00', '5', '2026-04-10 09:43:44');
INSERT INTO `order_notifications` VALUES ('62', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:00', '5', '2026-04-10 09:47:08');
INSERT INTO `order_notifications` VALUES ('63', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:00', '5', '2026-04-10 09:47:41');
INSERT INTO `order_notifications` VALUES ('64', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:09:00', '5', '2026-04-10 09:49:33');
INSERT INTO `order_notifications` VALUES ('65', '16', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:08:59', '5', '2026-04-10 09:50:01');
INSERT INTO `order_notifications` VALUES ('66', '16', '2', 'order_item', 'Bàn A.02: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-11 08:08:59', '5', '2026-04-10 09:50:08');
INSERT INTO `order_notifications` VALUES ('67', '17', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:10:15', '5', '2026-04-11 08:09:52');
INSERT INTO `order_notifications` VALUES ('68', '17', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '0', NULL, NULL, '2026-04-11 08:10:33');
INSERT INTO `order_notifications` VALUES ('69', '17', '2', 'order_item', 'Bàn A.02: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '0', NULL, NULL, '2026-04-11 08:10:39');
INSERT INTO `order_notifications` VALUES ('70', '17', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '0', NULL, NULL, '2026-04-11 08:11:00');
INSERT INTO `order_notifications` VALUES ('71', '17', '2', 'order_item', 'Bàn A.02: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-04-11 08:12:39', '5', '2026-04-11 08:12:15');
INSERT INTO `order_notifications` VALUES ('72', '17', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '1', '2026-04-11 08:12:38', '5', '2026-04-11 08:12:21');
INSERT INTO `order_notifications` VALUES ('73', '17', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '0', NULL, NULL, '2026-04-11 08:17:40');
INSERT INTO `order_notifications` VALUES ('74', '17', '2', 'order_item', 'Bàn A.02: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '0', NULL, NULL, '2026-04-11 08:18:11');
INSERT INTO `order_notifications` VALUES ('75', '17', '2', 'scan_qr', 'Khách xem menu', 'Bàn A.02 vừa quét mã xem thực đơn.', '0', NULL, NULL, '2026-04-11 08:18:20');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `waiter_id` int(10) unsigned DEFAULT NULL,
  `shift_id` int(10) unsigned DEFAULT NULL,
  `guest_count` tinyint(3) unsigned DEFAULT 1 COMMENT 'Số khách',
  `note` text DEFAULT NULL COMMENT 'Ghi chú cho cả order',
  `customer_notes` text DEFAULT NULL COMMENT 'Ghi chú từ khách hàng (lý do hủy, đặc biệt...)',
  `requires_confirmation` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Cần xác nhận từ nhân viên: 1=Có, 0=Không',
  `status` enum('open','closed') NOT NULL DEFAULT 'open' COMMENT 'open=đang phục vụ, closed=khách ra',
  `order_source` enum('waiter','customer_qr') NOT NULL DEFAULT 'waiter' COMMENT 'Nguồn tạo order: waiter (phục vụ) hoặc customer_qr (khách quét QR)',
  `is_realtime_hidden` tinyint(1) DEFAULT 0,
  `payment_method` varchar(50) DEFAULT 'cash',
  `payment_status` enum('pending','paid','canceled') DEFAULT 'pending',
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Giờ mở bàn',
  `closed_at` timestamp NULL DEFAULT NULL COMMENT 'Giờ đóng bàn',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_orders_table` (`table_id`),
  KEY `idx_orders_waiter` (`waiter_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_opened` (`opened_at`),
  KEY `idx_order_source` (`order_source`),
  KEY `idx_orders_session` (`session_id`),
  CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_waiter` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` VALUES ('1', '34', '5', '3', '2', '', NULL, '1', 'closed', 'waiter', '1', 'cash', 'canceled', '2026-04-08 17:24:22', '2026-04-09 15:49:35', '2026-04-08 17:24:22', '2026-04-09 15:49:35', NULL);
INSERT INTO `orders` VALUES ('2', '1', '5', '3', '2', '', NULL, '1', 'closed', 'waiter', '1', 'cash', 'canceled', '2026-04-08 17:25:01', '2026-04-09 15:49:32', '2026-04-08 17:25:01', '2026-04-09 15:49:32', NULL);
INSERT INTO `orders` VALUES ('3', '35', '5', '3', '2', '', NULL, '1', 'closed', 'waiter', '1', 'cash', 'canceled', '2026-04-08 17:25:09', '2026-04-09 15:49:27', '2026-04-08 17:25:09', '2026-04-09 15:49:27', NULL);
INSERT INTO `orders` VALUES ('4', '2', NULL, NULL, '1', 'Hệ thống tự động huỷ do không đặt món sau 5 phút', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'pending', '2026-04-08 17:58:59', '2026-04-08 21:44:06', '2026-04-08 17:58:59', '2026-04-08 21:44:06', '7951339140ed78a108548a990141c1ff');
INSERT INTO `orders` VALUES ('5', '27', '5', '3', '1', '', NULL, '1', 'closed', 'waiter', '0', 'cash', 'paid', '2026-04-08 21:45:21', '2026-04-09 14:29:34', '2026-04-08 21:45:21', '2026-04-09 14:29:34', NULL);
INSERT INTO `orders` VALUES ('6', '1', NULL, NULL, '1', 'Khách quét QR mở bàn | KHÁCH YÊU CẦU THANH TOÁN', NULL, '1', 'closed', 'customer_qr', '1', 'cash', 'paid', '2026-04-09 15:50:27', '2026-04-09 15:58:38', '2026-04-09 15:50:27', '2026-04-09 16:00:19', 'ba33bd9f9138c7045dce23f24cba724e');
INSERT INTO `orders` VALUES ('7', '1', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'paid', '2026-04-09 16:25:26', '2026-04-09 16:33:10', '2026-04-09 16:25:26', '2026-04-09 16:33:10', '475c455fb9c07ba1a5b826e35d3b0e7e');
INSERT INTO `orders` VALUES ('8', '34', NULL, NULL, '1', 'Khách quét QR mở bàn | KHÁCH YÊU CẦU THANH TOÁN', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'paid', '2026-04-09 16:47:44', '2026-04-09 17:25:24', '2026-04-09 16:47:44', '2026-04-09 17:25:24', '4deeb5d49dab340817bb5d0b17571f42');
INSERT INTO `orders` VALUES ('9', '1', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '1', 'cash', 'paid', '2026-04-09 18:15:47', '2026-04-09 18:53:13', '2026-04-09 18:15:47', '2026-04-09 19:11:54', '4bcebd09ef806a891c92289a3561f8ef');
INSERT INTO `orders` VALUES ('10', '1', NULL, NULL, '1', 'Khách quét QR mở bàn | KHÁCH YÊU CẦU THANH TOÁN', NULL, '1', 'closed', 'customer_qr', '1', 'cash', 'paid', '2026-04-09 19:05:36', '2026-04-09 19:08:01', '2026-04-09 19:05:36', '2026-04-09 19:11:57', '3cdf7668bc450e0eb4973ed6881e0c19');
INSERT INTO `orders` VALUES ('11', '1', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '1', 'cash', 'paid', '2026-04-09 19:10:45', '2026-04-09 19:15:06', '2026-04-09 19:10:45', '2026-04-09 19:15:06', '6d14031a6d76828f12f28d393f55e06a');
INSERT INTO `orders` VALUES ('12', '1', '5', '3', '2', '', NULL, '1', 'closed', 'waiter', '0', 'cash', 'canceled', '2026-04-10 08:41:04', '2026-04-10 08:42:21', '2026-04-10 08:41:04', '2026-04-10 08:42:21', NULL);
INSERT INTO `orders` VALUES ('13', '18', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'canceled', '2026-04-10 08:42:38', '2026-04-10 08:48:10', '2026-04-10 08:42:38', '2026-04-10 08:48:10', '9b178b5af62a5c507ced7aa23ef504b6');
INSERT INTO `orders` VALUES ('14', '19', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'canceled', '2026-04-10 08:43:31', '2026-04-10 08:48:20', '2026-04-10 08:43:31', '2026-04-10 08:48:20', '9b178b5af62a5c507ced7aa23ef504b6');
INSERT INTO `orders` VALUES ('15', '2', '5', '3', '2', '', NULL, '1', 'closed', 'waiter', '0', 'cash', 'canceled', '2026-04-10 08:44:00', '2026-04-10 08:48:16', '2026-04-10 08:44:00', '2026-04-10 08:48:16', NULL);
INSERT INTO `orders` VALUES ('16', '2', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'paid', '2026-04-10 09:25:24', '2026-04-11 08:09:24', '2026-04-10 09:25:24', '2026-04-11 08:09:24', '081827ec611553d35e78c6d126bdb37f');
INSERT INTO `orders` VALUES ('17', '2', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'open', 'customer_qr', '0', 'cash', 'pending', '2026-04-11 08:09:52', NULL, '2026-04-11 08:09:52', '2026-04-11 08:09:52', '35e421a81e6f86bc30c264da6fae7379');

DROP TABLE IF EXISTS `qr_tables`;
CREATE TABLE `qr_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL COMMENT 'Mã bàn (foreign key)',
  `qr_code` varchar(255) DEFAULT NULL COMMENT 'URL hoặc nội dung QR code',
  `qr_hash` varchar(64) NOT NULL COMMENT 'Mã hash duy nhất cho QR (dùng cho URL)',
  `generated_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo QR',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=đ aktiv, 0=ẩn',
  `scan_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Số lần quét QR code',
  `last_scanned_at` timestamp NULL DEFAULT NULL COMMENT 'Lần quét cuối cùng',
  `is_printed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_id` (`table_id`),
  UNIQUE KEY `qr_hash` (`qr_hash`),
  KEY `idx_qr_active` (`is_active`),
  CONSTRAINT `fk_qr_tables_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `qr_tables` VALUES ('2', '2', '/qr/menu?table_id=2&token=5aea0ec36591ecddd837fa739b2a5786', '5aea0ec36591ecddd837fa739b2a5786', '2026-03-08 16:50:22', '2026-03-23 09:01:50', '1', '5', '2026-03-23 09:01:50', '0');
INSERT INTO `qr_tables` VALUES ('3', '3', '/qr/menu?table_id=3&token=42a2c52875c7bfc390916ca1c33a7157', '42a2c52875c7bfc390916ca1c33a7157', '2026-03-08 16:50:22', '2026-03-23 09:15:33', '1', '25', '2026-03-23 09:15:33', '0');
INSERT INTO `qr_tables` VALUES ('4', '4', '/qr/menu?table_id=4&token=59151733a4403e2ba90a3668b91ef209', '59151733a4403e2ba90a3668b91ef209', '2026-03-08 16:50:22', '2026-03-23 09:16:48', '1', '4', '2026-03-23 09:16:48', '0');
INSERT INTO `qr_tables` VALUES ('5', '5', '/qr/menu?table_id=5&token=618e597619b7339cb04747a43747b086', '618e597619b7339cb04747a43747b086', '2026-03-08 16:50:22', '2026-03-17 18:30:03', '1', '2', '2026-03-17 18:30:03', '0');
INSERT INTO `qr_tables` VALUES ('6', '6', '/qr/menu?table_id=6&token=4d594e96b4578dd6eb6a2772eeb342d4', '4d594e96b4578dd6eb6a2772eeb342d4', '2026-03-08 16:50:22', '2026-03-23 09:21:29', '1', '10', '2026-03-23 09:21:29', '0');
INSERT INTO `qr_tables` VALUES ('7', '7', '/qr/menu?table_id=7&token=9debf619d8155b5e4218cdd77c9caa19', '9debf619d8155b5e4218cdd77c9caa19', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('8', '8', '/qr/menu?table_id=8&token=1a19d6276459abb3b0c92c9d7dd7dc0f', '1a19d6276459abb3b0c92c9d7dd7dc0f', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('9', '9', '/qr/menu?table_id=9&token=399e1ce0c5dd5fcaeb4cc583e17b45c5', '399e1ce0c5dd5fcaeb4cc583e17b45c5', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('10', '10', '/qr/menu?table_id=10&token=fb7e119368932a90d53465b48456597e', 'fb7e119368932a90d53465b48456597e', '2026-03-08 16:50:22', '2026-03-18 11:00:45', '1', '1', '2026-03-18 11:00:45', '0');
INSERT INTO `qr_tables` VALUES ('11', '11', '/qr/menu?table_id=11&token=d6590664eb462875de436efba585885b', 'd6590664eb462875de436efba585885b', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('12', '12', '/qr/menu?table_id=12&token=7714f8eb8cd1d9f1f4119665b538b9ec', '7714f8eb8cd1d9f1f4119665b538b9ec', '2026-03-08 16:50:22', '2026-03-18 21:00:18', '1', '8', '2026-03-18 21:00:18', '0');
INSERT INTO `qr_tables` VALUES ('13', '13', '/qr/menu?table_id=13&token=77d2001b22efcd63714ee5ec39cd624f', '77d2001b22efcd63714ee5ec39cd624f', '2026-03-08 16:50:22', '2026-03-19 08:59:30', '1', '3', '2026-03-19 08:59:30', '0');
INSERT INTO `qr_tables` VALUES ('14', '14', '/qr/menu?table_id=14&token=4f49388e51b134fabd30a789026ef9d0', '4f49388e51b134fabd30a789026ef9d0', '2026-03-08 16:50:22', '2026-03-18 15:18:58', '1', '4', '2026-03-18 15:18:58', '0');
INSERT INTO `qr_tables` VALUES ('15', '15', '/qr/menu?table_id=15&token=e939bdc269e7ea98da7f779691f9c63f', 'e939bdc269e7ea98da7f779691f9c63f', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('16', '16', '/qr/menu?table_id=16&token=5271c6745193cbd78b6d60fff9fb2863', '5271c6745193cbd78b6d60fff9fb2863', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('17', '17', '/qr/menu?table_id=17&token=ced798a790cc9fc268d03bcb7d5ccb93', 'ced798a790cc9fc268d03bcb7d5ccb93', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('18', '18', '/qr/menu?table_id=18&token=fa8932bc456533b1d680f807d16f35e9', 'fa8932bc456533b1d680f807d16f35e9', '2026-03-08 16:50:22', '2026-03-23 08:11:18', '1', '2', '2026-03-23 08:11:18', '1');
INSERT INTO `qr_tables` VALUES ('19', '19', '/qr/menu?table_id=19&token=b756d7566f9984bbc5190a823b00497a', 'b756d7566f9984bbc5190a823b00497a', '2026-03-08 16:50:22', '2026-03-25 10:52:32', '1', '0', NULL, '1');
INSERT INTO `qr_tables` VALUES ('20', '20', '/qr/menu?table_id=20&token=21b46e1f5b1d62e2f92ff2f02b28cf20', '21b46e1f5b1d62e2f92ff2f02b28cf20', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('21', '21', '/qr/menu?table_id=21&token=8a76b7a961c5343e09d947e7d762e032', '8a76b7a961c5343e09d947e7d762e032', '2026-03-08 16:50:22', '2026-03-17 21:46:45', '1', '3', '2026-03-17 21:46:45', '0');
INSERT INTO `qr_tables` VALUES ('22', '22', '/qr/menu?table_id=22&token=bd35d8e8560defeda360b4d99677350e', 'bd35d8e8560defeda360b4d99677350e', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('23', '23', '/qr/menu?table_id=23&token=36fcd88597412647c56d588a4f136e49', '36fcd88597412647c56d588a4f136e49', '2026-03-08 16:50:22', '2026-03-18 21:31:34', '1', '5', '2026-03-18 21:31:34', '0');
INSERT INTO `qr_tables` VALUES ('25', '25', '/qr/menu?table_id=25&token=4feee1c893e3c3ae36a029a11fdcd143', '4feee1c893e3c3ae36a029a11fdcd143', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('26', '26', '/qr/menu?table_id=26&token=a13c5111c2fea2dbf170ad159d88b3d5', 'a13c5111c2fea2dbf170ad159d88b3d5', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('27', '27', '/qr/menu?table_id=27&token=ab4722e5df763a17d54a46afa3506d39', 'ab4722e5df763a17d54a46afa3506d39', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('28', '28', '/qr/menu?table_id=28&token=197c68ac3e576c4ca2ecc90ba0035749', '197c68ac3e576c4ca2ecc90ba0035749', '2026-03-08 16:50:22', '2026-03-18 21:29:40', '1', '10', '2026-03-18 21:29:40', '0');
INSERT INTO `qr_tables` VALUES ('29', '29', '/qr/menu?table_id=29&token=540c56c80e81ad174c96292e2a32e55b', '540c56c80e81ad174c96292e2a32e55b', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('30', '30', '/qr/menu?table_id=30&token=892c41525bae1635bc4dd6e00049db09', '892c41525bae1635bc4dd6e00049db09', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('31', '31', '/qr/menu?table_id=31&token=c4d60af58f19e189cfbae688a169a499', 'c4d60af58f19e189cfbae688a169a499', '2026-03-08 16:50:22', '2026-03-18 21:41:17', '1', '7', '2026-03-18 21:41:17', '0');
INSERT INTO `qr_tables` VALUES ('32', '32', '/qr/menu?table_id=32&token=3132152b936f32b9bd4833020db70d8e', '3132152b936f32b9bd4833020db70d8e', '2026-03-08 16:50:22', '2026-03-18 21:36:11', '1', '12', '2026-03-18 21:36:11', '0');
INSERT INTO `qr_tables` VALUES ('64', '1', '/qr/menu?table_id=1&token=c1674174442ac69294484eb54ffe1e2b', 'c1674174442ac69294484eb54ffe1e2b', '2026-03-17 17:59:01', '2026-03-23 09:02:04', '1', '74', '2026-03-23 09:02:04', '1');
INSERT INTO `qr_tables` VALUES ('99', '34', '/qr/menu?table_id=34&token=9853c359c274bc07', '9853c359c274bc07', '2026-03-21 18:15:32', '2026-03-22 14:23:53', '1', '5', '2026-03-22 14:23:53', '0');
INSERT INTO `qr_tables` VALUES ('100', '35', '/qr/menu?table_id=35&token=657bb64a5236cd07', '657bb64a5236cd07', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('101', '36', '/qr/menu?table_id=36&token=9e6e864e8dcd4d16', '9e6e864e8dcd4d16', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('102', '37', '/qr/menu?table_id=37&token=542e9fb5755eb267', '542e9fb5755eb267', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('103', '38', '/qr/menu?table_id=38&token=e1deba631cedf38e', 'e1deba631cedf38e', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('104', '39', '/qr/menu?table_id=39&token=d14c1f3cc89aac11', 'd14c1f3cc89aac11', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('105', '40', '/qr/menu?table_id=40&token=3bf496f44e08b889', '3bf496f44e08b889', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('106', '41', '/qr/menu?table_id=41&token=0d260ec51b770ce8', '0d260ec51b770ce8', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('107', '42', '/qr/menu?table_id=42&token=9001444b50b373db', '9001444b50b373db', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('108', '43', '/qr/menu?table_id=43&token=dbf00f10b62db39a', 'dbf00f10b62db39a', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('109', '44', '/qr/menu?table_id=44&token=cb05824f42f77532', 'cb05824f42f77532', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('110', '45', '/qr/menu?table_id=45&token=fdc33c6963b61a5f', 'fdc33c6963b61a5f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('111', '46', '/qr/menu?table_id=46&token=ceddfbf3399ab9cb', 'ceddfbf3399ab9cb', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('112', '47', '/qr/menu?table_id=47&token=b9dc9f230ab371ca', 'b9dc9f230ab371ca', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('113', '48', '/qr/menu?table_id=48&token=92778081df46e36a', '92778081df46e36a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('114', '49', '/qr/menu?table_id=49&token=4ed6c469b2367b6b', '4ed6c469b2367b6b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('115', '50', '/qr/menu?table_id=50&token=5caa7d2b02b578a3', '5caa7d2b02b578a3', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('116', '51', '/qr/menu?table_id=51&token=e92de0aa41aebbd1', 'e92de0aa41aebbd1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('117', '52', '/qr/menu?table_id=52&token=1f3bd48265060d7f', '1f3bd48265060d7f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('118', '53', '/qr/menu?table_id=53&token=95e7f49e988c9349', '95e7f49e988c9349', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('119', '54', '/qr/menu?table_id=54&token=074d0a40b61ad55f', '074d0a40b61ad55f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('120', '55', '/qr/menu?table_id=55&token=4a40e346b77da1a1', '4a40e346b77da1a1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('121', '56', '/qr/menu?table_id=56&token=c426d510ddc9d02e', 'c426d510ddc9d02e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('122', '57', '/qr/menu?table_id=57&token=0e0fce6b5913b27d', '0e0fce6b5913b27d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('123', '58', '/qr/menu?table_id=58&token=ca71a8d84a9f53a1', 'ca71a8d84a9f53a1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('124', '59', '/qr/menu?table_id=59&token=2f722fa35a1b19a0', '2f722fa35a1b19a0', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('125', '60', '/qr/menu?table_id=60&token=8f4cdfd92d0da925', '8f4cdfd92d0da925', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('126', '61', '/qr/menu?table_id=61&token=c26b7a5d6e373c8d', 'c26b7a5d6e373c8d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('127', '62', '/qr/menu?table_id=62&token=75f24d352b7930e0', '75f24d352b7930e0', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('128', '63', '/qr/menu?table_id=63&token=b233ed219adb5ff6', 'b233ed219adb5ff6', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('129', '64', '/qr/menu?table_id=64&token=faf4cc3087786929', 'faf4cc3087786929', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('130', '65', '/qr/menu?table_id=65&token=55ef6da9717fe36c', '55ef6da9717fe36c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('131', '66', '/qr/menu?table_id=66&token=b5cbe2119ca3584b', 'b5cbe2119ca3584b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('132', '67', '/qr/menu?table_id=67&token=34864e0af2f15b4e', '34864e0af2f15b4e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('133', '68', '/qr/menu?table_id=68&token=13b52b9cdc71396b', '13b52b9cdc71396b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('134', '69', '/qr/menu?table_id=69&token=3e9b4e4c66093170', '3e9b4e4c66093170', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('135', '70', '/qr/menu?table_id=70&token=c8fc938812da7595', 'c8fc938812da7595', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('136', '71', '/qr/menu?table_id=71&token=72ed0a67f3e418bc', '72ed0a67f3e418bc', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('137', '72', '/qr/menu?table_id=72&token=5f63dba64276ec9b', '5f63dba64276ec9b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('138', '73', '/qr/menu?table_id=73&token=18d2b01d34b02538', '18d2b01d34b02538', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('139', '74', '/qr/menu?table_id=74&token=c78d0727cc42e972', 'c78d0727cc42e972', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('140', '75', '/qr/menu?table_id=75&token=6c564c3fe3cb9817', '6c564c3fe3cb9817', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('141', '76', '/qr/menu?table_id=76&token=c33726dbdb0358a2', 'c33726dbdb0358a2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('142', '77', '/qr/menu?table_id=77&token=96f602f4e71c331a', '96f602f4e71c331a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('143', '78', '/qr/menu?table_id=78&token=572dab2f380d1ca7', '572dab2f380d1ca7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('144', '79', '/qr/menu?table_id=79&token=c522360dfc9445d2', 'c522360dfc9445d2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('145', '80', '/qr/menu?table_id=80&token=a88b48db14c892da', 'a88b48db14c892da', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('146', '81', '/qr/menu?table_id=81&token=4df8d4ba2e32312d', '4df8d4ba2e32312d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('147', '82', '/qr/menu?table_id=82&token=cb3f6608ec4d5a72', 'cb3f6608ec4d5a72', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('148', '83', '/qr/menu?table_id=83&token=5aaedb0e390a7313', '5aaedb0e390a7313', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('149', '84', '/qr/menu?table_id=84&token=0ad57b083cac6260', '0ad57b083cac6260', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('150', '85', '/qr/menu?table_id=85&token=af6af4b4511a99e4', 'af6af4b4511a99e4', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('151', '86', '/qr/menu?table_id=86&token=b4420cde02c1b182', 'b4420cde02c1b182', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('152', '87', '/qr/menu?table_id=87&token=4ea6748a036a17a9', '4ea6748a036a17a9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('153', '88', '/qr/menu?table_id=88&token=7eb45a95a506e6e2', '7eb45a95a506e6e2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('154', '89', '/qr/menu?table_id=89&token=d63333a8c7b4ca3f', 'd63333a8c7b4ca3f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('155', '90', '/qr/menu?table_id=90&token=4e2eb855787bcaf3', '4e2eb855787bcaf3', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('156', '91', '/qr/menu?table_id=91&token=2c90fda9e1180a1c', '2c90fda9e1180a1c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('157', '92', '/qr/menu?table_id=92&token=6167dc28edb38a55', '6167dc28edb38a55', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('158', '93', '/qr/menu?table_id=93&token=1e93ecca46389d15', '1e93ecca46389d15', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('159', '94', '/qr/menu?table_id=94&token=2805dc1870802cfe', '2805dc1870802cfe', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('160', '95', '/qr/menu?table_id=95&token=97d9f38183834cd4', '97d9f38183834cd4', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('161', '96', '/qr/menu?table_id=96&token=14aacaae5e8a2d9a', '14aacaae5e8a2d9a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('162', '97', '/qr/menu?table_id=97&token=4de1fb2e24430677', '4de1fb2e24430677', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('163', '98', '/qr/menu?table_id=98&token=b938fa1231a36d2d', 'b938fa1231a36d2d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('164', '99', '/qr/menu?table_id=99&token=2b3b89ba01f3f085', '2b3b89ba01f3f085', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('165', '100', '/qr/menu?table_id=100&token=446bfe04c58be616', '446bfe04c58be616', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('166', '101', '/qr/menu?table_id=101&token=82c6002b648817c7', '82c6002b648817c7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('167', '102', '/qr/menu?table_id=102&token=d6edbc3d315a0ef1', 'd6edbc3d315a0ef1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('168', '103', '/qr/menu?table_id=103&token=74f7a42d4a33b9a7', '74f7a42d4a33b9a7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('169', '104', '/qr/menu?table_id=104&token=e875bbb203cb84d7', 'e875bbb203cb84d7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('170', '105', '/qr/menu?table_id=105&token=1f7c164a788f78dc', '1f7c164a788f78dc', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('171', '106', '/qr/menu?table_id=106&token=37b088c946864e19', '37b088c946864e19', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('172', '107', '/qr/menu?table_id=107&token=7c24fa1b0ceb862b', '7c24fa1b0ceb862b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('173', '108', '/qr/menu?table_id=108&token=2c7ae1c5c5a61adf', '2c7ae1c5c5a61adf', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('174', '109', '/qr/menu?table_id=109&token=bc03b90c607c4bc9', 'bc03b90c607c4bc9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('175', '110', '/qr/menu?table_id=110&token=fc067eba34f4dba9', 'fc067eba34f4dba9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('176', '111', '/qr/menu?table_id=111&token=f5926dec48bfbe9c', 'f5926dec48bfbe9c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('177', '112', '/qr/menu?table_id=112&token=2383544b8b0258e7', '2383544b8b0258e7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('178', '113', '/qr/menu?table_id=113&token=db8c74cc5321febd', 'db8c74cc5321febd', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('179', '114', '/qr/menu?table_id=114&token=03516ce24046399e', '03516ce24046399e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('180', '115', '/qr/menu?table_id=115&token=56c2454f1b84c62c', '56c2454f1b84c62c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('181', '116', '/qr/menu?table_id=116&token=da286833edbd8947', 'da286833edbd8947', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('182', '117', '/qr/menu?table_id=117&token=b99a416c123bbb03', 'b99a416c123bbb03', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('183', '118', '/qr/menu?table_id=118&token=4bc458bc62aa55d7', '4bc458bc62aa55d7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('184', '119', '/qr/menu?table_id=119&token=ac330ef97f991520', 'ac330ef97f991520', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('185', '120', '/qr/menu?table_id=120&token=6d204a5f0433b0e8', '6d204a5f0433b0e8', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('186', '121', '/qr/menu?table_id=121&token=9e26e6b6c034b261', '9e26e6b6c034b261', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('187', '122', '/qr/menu?table_id=122&token=fcc56cc9ac7f6c8e', 'fcc56cc9ac7f6c8e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('188', '123', '/qr/menu?table_id=123&token=d11f52647ab07b8b', 'd11f52647ab07b8b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('189', '124', '/qr/menu?table_id=124&token=38a0175b8c3cce4b', '38a0175b8c3cce4b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('190', '125', '/qr/menu?table_id=125&token=cad72d36fe9ca269', 'cad72d36fe9ca269', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('191', '126', '/qr/menu?table_id=126&token=83f76774214ee84e', '83f76774214ee84e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('192', '127', '/qr/menu?table_id=127&token=9991fa34d9fb2d59', '9991fa34d9fb2d59', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('193', '128', '/qr/menu?table_id=128&token=86ae7a9242ada453', '86ae7a9242ada453', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('194', '129', '/qr/menu?table_id=129&token=23f166f0940f2ef6', '23f166f0940f2ef6', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('195', '130', '/qr/menu?table_id=130&token=b499b3b3782bd9db', 'b499b3b3782bd9db', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('196', '131', '/qr/menu?table_id=131&token=f85e5f115dd00f1b', 'f85e5f115dd00f1b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('197', '132', '/qr/menu?table_id=132&token=ec420c13716ca4fa', 'ec420c13716ca4fa', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('198', '133', '/qr/menu?table_id=133&token=6df5879b353e73ac', '6df5879b353e73ac', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('199', '134', '/qr/menu?table_id=134&token=cafdfa0c96dc556b', 'cafdfa0c96dc556b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('200', '135', '/qr/menu?table_id=135&token=3cc749ee1b83a365', '3cc749ee1b83a365', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('201', '136', '/qr/menu?table_id=136&token=d253f49e98fa7d34', 'd253f49e98fa7d34', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('202', '137', '/qr/menu?table_id=137&token=0c6cb912e525da5e', '0c6cb912e525da5e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('203', '138', '/qr/menu?table_id=138&token=1968c9b0b8de1f5a', '1968c9b0b8de1f5a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('204', '139', '/qr/menu?table_id=139&token=28952c5a3abedad9', '28952c5a3abedad9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('205', '140', '/qr/menu?table_id=140&token=d2fbb6f6deef5f30', 'd2fbb6f6deef5f30', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('206', '141', '/qr/menu?table_id=141&token=f9f63ab0e0db6707', 'f9f63ab0e0db6707', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('207', '142', '/qr/menu?table_id=142&token=d644c2729c67fa8d', 'd644c2729c67fa8d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('208', '143', '/qr/menu?table_id=143&token=fc38c53b4c412aad', 'fc38c53b4c412aad', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('209', '144', '/qr/menu?table_id=144&token=7a1bcb991914757a', '7a1bcb991914757a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('210', '145', '/qr/menu?table_id=145&token=4cbb75dbb4fcbf09', '4cbb75dbb4fcbf09', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('211', '146', '/qr/menu?table_id=146&token=c7d6d2391254259c', 'c7d6d2391254259c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('212', '147', '/qr/menu?table_id=147&token=4a84f03a016fe02d', '4a84f03a016fe02d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('213', '148', '/qr/menu?table_id=148&token=09d06f72cd8bd631', '09d06f72cd8bd631', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('214', '149', '/qr/menu?table_id=149&token=36d48dbe9b2888a9', '36d48dbe9b2888a9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('215', '150', '/qr/menu?table_id=150&token=c9c3520512677d78', 'c9c3520512677d78', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('216', '151', '/qr/menu?table_id=151&token=46d4fd06efb382d8', '46d4fd06efb382d8', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('217', '152', '/qr/menu?table_id=152&token=047cf64bdd192fca', '047cf64bdd192fca', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('218', '153', '/qr/menu?table_id=153&token=1ab3d2b7971a925e', '1ab3d2b7971a925e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('219', '154', '/qr/menu?table_id=154&token=9ba8818156b831f5', '9ba8818156b831f5', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('220', '155', '/qr/menu?table_id=155&token=b0a92130b038239b', 'b0a92130b038239b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');

DROP TABLE IF EXISTS `realtime_notifications`;
CREATE TABLE `realtime_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(50) NOT NULL COMMENT 'Kênh: waiter_1, admin, table_5, all',
  `event_type` varchar(50) NOT NULL COMMENT 'Loại event: new_order, order_confirmed, table_occupied',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Dữ liệu notification dạng JSON' CHECK (json_valid(`payload`)),
  `is_delivered` tinyint(1) NOT NULL DEFAULT 0,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Hết hạn sau 24h',
  PRIMARY KEY (`id`),
  KEY `idx_channel` (`channel`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_delivered` (`is_delivered`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Real-time push notifications';


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` VALUES ('1', 'dev_mode', '1', 'Chế độ phát triển - Tắt kiểm tra vị trí để dev có thể test từ xa', '2026-04-10 08:58:02', '2026-04-10 09:49:29');
INSERT INTO `settings` VALUES ('2', 'maintenance_mode', '0', 'Chế độ bảo trì - Ẩn website khỏi khách hàng', '2026-04-10 08:58:02', '2026-04-10 08:58:02');
INSERT INTO `settings` VALUES ('3', 'allow_online_payment', '1', 'Cho phép thanh toán online', '2026-04-10 08:58:02', '2026-04-10 08:58:02');
INSERT INTO `settings` VALUES ('4', 'auto_print_orders', '1', 'Tự động in đơn hàng khi có order mới', '2026-04-10 08:58:02', '2026-04-10 08:58:02');

DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Tên ca: Sáng, Chiều, Tối...',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `shifts` VALUES ('3', 'Ca Sáng', '06:00:00', '14:00:00', '2026-04-07 09:07:03');
INSERT INTO `shifts` VALUES ('4', 'Ca Chiều', '14:00:00', '22:00:00', '2026-04-07 09:07:03');

DROP TABLE IF EXISTS `support_requests`;
CREATE TABLE `support_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `type` enum('support','payment','scan_qr','new_order') NOT NULL DEFAULT 'support' COMMENT 'Loại yêu cầu: support=hỗ trợ, payment=thanh toán, scan_qr=quét QR, new_order=order mới',
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_support_table` (`table_id`),
  CONSTRAINT `fk_support_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `support_requests` VALUES ('1', '1', 'scan_qr', 'pending', '2026-04-08 14:47:40', '2026-04-08 14:47:40');
INSERT INTO `support_requests` VALUES ('2', '34', 'scan_qr', 'pending', '2026-04-08 17:24:23', '2026-04-08 17:24:23');
INSERT INTO `support_requests` VALUES ('3', '27', 'scan_qr', 'pending', '2026-04-08 21:45:19', '2026-04-08 21:45:19');
INSERT INTO `support_requests` VALUES ('4', '35', 'scan_qr', 'pending', '2026-04-08 21:45:36', '2026-04-08 21:45:36');
INSERT INTO `support_requests` VALUES ('5', '5', 'scan_qr', 'pending', '2026-04-08 21:46:09', '2026-04-08 21:46:09');
INSERT INTO `support_requests` VALUES ('6', '28', 'scan_qr', 'pending', '2026-04-09 14:25:13', '2026-04-09 14:25:13');
INSERT INTO `support_requests` VALUES ('7', '30', 'scan_qr', 'pending', '2026-04-09 14:25:48', '2026-04-09 14:25:48');
INSERT INTO `support_requests` VALUES ('8', '6', 'scan_qr', 'pending', '2026-04-09 14:29:21', '2026-04-09 14:29:21');
INSERT INTO `support_requests` VALUES ('9', '2', 'scan_qr', 'pending', '2026-04-09 16:47:16', '2026-04-09 16:47:16');
INSERT INTO `support_requests` VALUES ('10', '1', 'scan_qr', 'pending', '2026-04-09 17:54:15', '2026-04-09 17:54:15');
INSERT INTO `support_requests` VALUES ('11', '1', 'scan_qr', 'pending', '2026-04-10 08:41:06', '2026-04-10 08:41:06');
INSERT INTO `support_requests` VALUES ('12', '2', 'scan_qr', 'pending', '2026-04-10 08:44:04', '2026-04-10 08:44:04');

DROP TABLE IF EXISTS `table_status_history`;
CREATE TABLE `table_status_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `previous_status` enum('available','occupied') NOT NULL,
  `current_status` enum('available','occupied') NOT NULL,
  `changed_by` int(10) unsigned DEFAULT NULL COMMENT 'User ID hoặc NULL nếu từ customer',
  `change_reason` varchar(100) DEFAULT NULL COMMENT 'Lý do: scan_qr, waiter_open, manual_close, auto_close',
  `order_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_table_history` (`table_id`),
  KEY `idx_table_status_time` (`created_at`),
  KEY `idx_table_change_reason` (`change_reason`),
  CONSTRAINT `fk_history_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử thay đổi trạng thái bàn';


DROP TABLE IF EXISTS `tables`;
CREATE TABLE `tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `type` enum('table','room') NOT NULL DEFAULT 'table',
  `name` varchar(50) NOT NULL COMMENT 'Tên bàn: Bàn 01, VIP 1...',
  `area` varchar(50) DEFAULT NULL COMMENT 'Khu vực: Trong, Ngoài, VIP...',
  `capacity` tinyint(3) unsigned NOT NULL DEFAULT 4 COMMENT 'Sức chứa (số ghế)',
  `status` enum('available','occupied') NOT NULL DEFAULT 'available',
  `position_x` smallint(5) unsigned DEFAULT 0 COMMENT 'Toạ độ X trên sơ đồ',
  `position_y` smallint(5) unsigned DEFAULT 0 COMMENT 'Toạ độ Y trên sơ đồ',
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_tables_parent` (`parent_id`),
  KEY `idx_parent_id` (`parent_id`),
  CONSTRAINT `fk_tables_parent` FOREIGN KEY (`parent_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tables_parent_new` FOREIGN KEY (`parent_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tables` VALUES ('1', NULL, 'table', 'A.01', 'A1', '4', 'available', '0', '0', '1', '1', '2026-03-07 18:20:45', '2026-04-10 08:42:21');
INSERT INTO `tables` VALUES ('2', NULL, 'table', 'A.02', 'A1', '4', 'occupied', '0', '0', '2', '1', '2026-03-07 18:20:45', '2026-04-11 08:09:52');
INSERT INTO `tables` VALUES ('3', NULL, 'table', 'A.03', 'A1', '4', 'available', '0', '0', '3', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('4', NULL, 'table', 'A.04', 'A1', '4', 'available', '0', '0', '4', '1', '2026-03-07 18:20:45', '2026-03-31 14:50:43');
INSERT INTO `tables` VALUES ('5', NULL, 'table', 'A.05', 'A1', '4', 'available', '0', '0', '5', '1', '2026-03-07 18:20:45', '2026-03-31 14:50:43');
INSERT INTO `tables` VALUES ('6', NULL, 'table', 'A.06', 'A1', '4', 'available', '0', '0', '6', '1', '2026-03-07 18:20:45', '2026-04-01 14:21:57');
INSERT INTO `tables` VALUES ('7', NULL, 'table', 'B.01', 'B1', '4', 'available', '0', '0', '7', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('8', NULL, 'table', 'B.02', 'B1', '4', 'available', '0', '0', '8', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('9', NULL, 'table', 'B.03', 'B1', '4', 'available', '0', '0', '9', '1', '2026-03-07 18:20:45', '2026-03-30 21:39:48');
INSERT INTO `tables` VALUES ('10', NULL, 'table', 'B.04', 'B1', '4', 'available', '0', '0', '10', '1', '2026-03-07 18:20:45', '2026-03-30 22:00:27');
INSERT INTO `tables` VALUES ('11', NULL, 'table', 'B.05', 'B1', '4', 'available', '0', '0', '11', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:08');
INSERT INTO `tables` VALUES ('12', NULL, 'table', 'B.06', 'B1', '4', 'available', '0', '0', '12', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:08');
INSERT INTO `tables` VALUES ('13', NULL, 'table', 'C.01', 'C1', '4', 'available', '0', '0', '13', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:18');
INSERT INTO `tables` VALUES ('14', NULL, 'table', 'C.02', 'C1', '4', 'available', '0', '0', '14', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('15', NULL, 'table', 'C.03', 'C1', '4', 'available', '0', '0', '15', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:34');
INSERT INTO `tables` VALUES ('16', NULL, 'table', 'C.04', 'C1', '4', 'available', '0', '0', '16', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('17', NULL, 'table', 'C.05', 'C1', '4', 'available', '0', '0', '17', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('18', NULL, 'table', 'C.06', 'C1', '4', 'available', '0', '0', '18', '1', '2026-03-07 18:20:45', '2026-04-10 08:48:10');
INSERT INTO `tables` VALUES ('19', NULL, 'table', 'VIP 1.1', 'VIP 1', '8', 'available', '0', '0', '19', '1', '2026-03-07 18:20:45', '2026-04-10 08:48:20');
INSERT INTO `tables` VALUES ('20', NULL, 'table', 'VIP 1.2', 'VIP 1', '8', 'available', '0', '0', '20', '1', '2026-03-07 18:20:45', '2026-03-30 22:01:31');
INSERT INTO `tables` VALUES ('21', NULL, 'table', 'VIP 2.1', 'VIP 2', '8', 'available', '0', '0', '21', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('22', NULL, 'table', 'VIP 2.2', 'VIP 2', '8', 'available', '0', '0', '22', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('23', NULL, 'table', 'VIP 3.1', 'VIP 3', '8', 'available', '0', '0', '23', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('24', NULL, 'table', 'VIP 3.2', 'VIP 3', '8', 'available', '0', '0', '24', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('25', NULL, 'table', 'VIP 4.1', 'VIP 4', '8', 'available', '0', '0', '25', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('26', NULL, 'table', 'VIP 4.2', 'VIP 4', '8', 'available', '0', '0', '26', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('27', NULL, 'table', 'Âu 01', 'Âu', '4', 'available', '0', '0', '27', '1', '2026-03-07 18:20:45', '2026-04-09 14:29:34');
INSERT INTO `tables` VALUES ('28', NULL, 'table', 'Âu 02', 'Âu', '4', 'available', '0', '0', '28', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('29', NULL, 'table', 'Âu 03', 'Âu', '4', 'available', '0', '0', '29', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('30', NULL, 'table', 'Âu 04', 'Âu', '4', 'available', '0', '0', '30', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('31', NULL, 'table', 'Âu 05', 'Âu', '4', 'available', '0', '0', '31', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('32', NULL, 'table', 'Âu 06', 'Âu', '4', 'available', '0', '0', '32', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('34', NULL, 'room', '701', 'Tầng 7', '3', 'available', '0', '0', '701', '1', '2026-03-21 18:15:32', '2026-04-09 17:25:24');
INSERT INTO `tables` VALUES ('35', NULL, 'room', '702', 'Tầng 7', '3', 'available', '0', '0', '702', '1', '2026-03-21 18:15:32', '2026-04-09 15:49:27');
INSERT INTO `tables` VALUES ('36', NULL, 'room', '703', 'Tầng 7', '3', 'available', '0', '0', '703', '1', '2026-03-21 18:15:32', '2026-04-07 19:25:37');
INSERT INTO `tables` VALUES ('37', NULL, 'room', '704', 'Tầng 7', '3', 'available', '0', '0', '704', '1', '2026-03-21 18:15:32', '2026-04-07 17:00:19');
INSERT INTO `tables` VALUES ('38', NULL, 'room', '705', 'Tầng 7', '3', 'available', '0', '0', '705', '1', '2026-03-21 18:15:32', '2026-03-21 19:00:33');
INSERT INTO `tables` VALUES ('39', NULL, 'room', '706', 'Tầng 7', '3', 'available', '0', '0', '706', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('40', NULL, 'room', '707', 'Tầng 7', '3', 'available', '0', '0', '707', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('41', NULL, 'room', '708', 'Tầng 7', '3', 'available', '0', '0', '708', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('42', NULL, 'room', '709', 'Tầng 7', '3', 'available', '0', '0', '709', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('43', NULL, 'room', '710', 'Tầng 7', '3', 'available', '0', '0', '710', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('44', NULL, 'room', '711', 'Tầng 7', '3', 'available', '0', '0', '711', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('45', NULL, 'room', '712', 'Tầng 7', '3', 'available', '0', '0', '712', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('46', NULL, 'room', '714', 'Tầng 7', '3', 'available', '0', '0', '714', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('47', NULL, 'room', '715', 'Tầng 7', '3', 'available', '0', '0', '715', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('48', NULL, 'room', '716', 'Tầng 7', '3', 'available', '0', '0', '716', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('49', NULL, 'room', '717', 'Tầng 7', '3', 'available', '0', '0', '717', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('50', NULL, 'room', '718', 'Tầng 7', '3', 'available', '0', '0', '718', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('51', NULL, 'room', '719', 'Tầng 7', '3', 'available', '0', '0', '719', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('52', NULL, 'room', '720', 'Tầng 7', '3', 'available', '0', '0', '720', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('53', NULL, 'room', '801', 'Tầng 8', '3', 'available', '0', '0', '801', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('54', NULL, 'room', '802', 'Tầng 8', '3', 'available', '0', '0', '802', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('55', NULL, 'room', '803', 'Tầng 8', '3', 'available', '0', '0', '803', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('56', NULL, 'room', '804', 'Tầng 8', '3', 'available', '0', '0', '804', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('57', NULL, 'room', '805', 'Tầng 8', '3', 'available', '0', '0', '805', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('58', NULL, 'room', '806', 'Tầng 8', '3', 'available', '0', '0', '806', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('59', NULL, 'room', '807', 'Tầng 8', '3', 'available', '0', '0', '807', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('60', NULL, 'room', '808', 'Tầng 8', '3', 'available', '0', '0', '808', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('61', NULL, 'room', '809', 'Tầng 8', '3', 'available', '0', '0', '809', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('62', NULL, 'room', '810', 'Tầng 8', '3', 'available', '0', '0', '810', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('63', NULL, 'room', '811', 'Tầng 8', '3', 'available', '0', '0', '811', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('64', NULL, 'room', '812', 'Tầng 8', '3', 'available', '0', '0', '812', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('65', NULL, 'room', '814', 'Tầng 8', '3', 'available', '0', '0', '814', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('66', NULL, 'room', '815', 'Tầng 8', '3', 'available', '0', '0', '815', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('67', NULL, 'room', '816', 'Tầng 8', '3', 'available', '0', '0', '816', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('68', NULL, 'room', '817', 'Tầng 8', '3', 'available', '0', '0', '817', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('69', NULL, 'room', '818', 'Tầng 8', '3', 'available', '0', '0', '818', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('70', NULL, 'room', '819', 'Tầng 8', '3', 'available', '0', '0', '819', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('71', NULL, 'room', '901', 'Tầng 9', '3', 'available', '0', '0', '901', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('72', NULL, 'room', '902', 'Tầng 9', '3', 'available', '0', '0', '902', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('73', NULL, 'room', '903', 'Tầng 9', '3', 'available', '0', '0', '903', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('74', NULL, 'room', '904', 'Tầng 9', '3', 'available', '0', '0', '904', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('75', NULL, 'room', '905', 'Tầng 9', '3', 'available', '0', '0', '905', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('76', NULL, 'room', '906', 'Tầng 9', '3', 'available', '0', '0', '906', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('77', NULL, 'room', '907', 'Tầng 9', '3', 'available', '0', '0', '907', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('78', NULL, 'room', '908', 'Tầng 9', '3', 'available', '0', '0', '908', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('79', NULL, 'room', '909', 'Tầng 9', '3', 'available', '0', '0', '909', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('80', NULL, 'room', '910', 'Tầng 9', '3', 'available', '0', '0', '910', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('81', NULL, 'room', '911', 'Tầng 9', '3', 'available', '0', '0', '911', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('82', NULL, 'room', '912', 'Tầng 9', '3', 'available', '0', '0', '912', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('83', NULL, 'room', '914', 'Tầng 9', '3', 'available', '0', '0', '914', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('84', NULL, 'room', '915', 'Tầng 9', '3', 'available', '0', '0', '915', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('85', NULL, 'room', '916', 'Tầng 9', '3', 'available', '0', '0', '916', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('86', NULL, 'room', '917', 'Tầng 9', '3', 'available', '0', '0', '917', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('87', NULL, 'room', '918', 'Tầng 9', '3', 'available', '0', '0', '918', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('88', NULL, 'room', '919', 'Tầng 9', '3', 'available', '0', '0', '919', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('89', NULL, 'room', '920', 'Tầng 9', '3', 'available', '0', '0', '920', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('90', NULL, 'room', '921', 'Tầng 9', '3', 'available', '0', '0', '921', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('91', NULL, 'room', '922', 'Tầng 9', '3', 'available', '0', '0', '922', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('92', NULL, 'room', '923', 'Tầng 9', '3', 'available', '0', '0', '923', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('93', NULL, 'room', '1001', 'Tầng 10', '3', 'available', '0', '0', '1001', '1', '2026-03-21 18:15:33', '2026-04-01 14:12:05');
INSERT INTO `tables` VALUES ('94', NULL, 'room', '1002', 'Tầng 10', '3', 'available', '0', '0', '1002', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('95', NULL, 'room', '1003', 'Tầng 10', '3', 'available', '0', '0', '1003', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('96', NULL, 'room', '1004', 'Tầng 10', '3', 'available', '0', '0', '1004', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('97', NULL, 'room', '1005', 'Tầng 10', '3', 'available', '0', '0', '1005', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('98', NULL, 'room', '1006', 'Tầng 10', '3', 'available', '0', '0', '1006', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('99', NULL, 'room', '1007', 'Tầng 10', '3', 'available', '0', '0', '1007', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('100', NULL, 'room', '1008', 'Tầng 10', '3', 'available', '0', '0', '1008', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('101', NULL, 'room', '1009', 'Tầng 10', '3', 'available', '0', '0', '1009', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('102', NULL, 'room', '1010', 'Tầng 10', '3', 'available', '0', '0', '1010', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('103', NULL, 'room', '1011', 'Tầng 10', '3', 'available', '0', '0', '1011', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('104', NULL, 'room', '1012', 'Tầng 10', '3', 'available', '0', '0', '1012', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('105', NULL, 'room', '1014', 'Tầng 10', '3', 'available', '0', '0', '1014', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('106', NULL, 'room', '1015', 'Tầng 10', '3', 'available', '0', '0', '1015', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('107', NULL, 'room', '1016', 'Tầng 10', '3', 'available', '0', '0', '1016', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('108', NULL, 'room', '1017', 'Tầng 10', '3', 'available', '0', '0', '1017', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('109', NULL, 'room', '1018', 'Tầng 10', '3', 'available', '0', '0', '1018', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('110', NULL, 'room', '1019', 'Tầng 10', '3', 'available', '0', '0', '1019', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('111', NULL, 'room', '1020', 'Tầng 10', '3', 'available', '0', '0', '1020', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('112', NULL, 'room', '1021', 'Tầng 10', '3', 'available', '0', '0', '1021', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('113', NULL, 'room', '1022', 'Tầng 10', '3', 'available', '0', '0', '1022', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('114', NULL, 'room', '1023', 'Tầng 10', '3', 'available', '0', '0', '1023', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('115', NULL, 'room', '1101', 'Tầng 11', '3', 'available', '0', '0', '1101', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('116', NULL, 'room', '1102', 'Tầng 11', '3', 'available', '0', '0', '1102', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('117', NULL, 'room', '1103', 'Tầng 11', '3', 'available', '0', '0', '1103', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('118', NULL, 'room', '1104', 'Tầng 11', '3', 'available', '0', '0', '1104', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('119', NULL, 'room', '1105', 'Tầng 11', '3', 'available', '0', '0', '1105', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('120', NULL, 'room', '1106', 'Tầng 11', '3', 'available', '0', '0', '1106', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('121', NULL, 'room', '1107', 'Tầng 11', '3', 'available', '0', '0', '1107', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('122', NULL, 'room', '1108', 'Tầng 11', '3', 'available', '0', '0', '1108', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('123', NULL, 'room', '1109', 'Tầng 11', '3', 'available', '0', '0', '1109', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('124', NULL, 'room', '1110', 'Tầng 11', '3', 'available', '0', '0', '1110', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('125', NULL, 'room', '1111', 'Tầng 11', '3', 'available', '0', '0', '1111', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('126', NULL, 'room', '1112', 'Tầng 11', '3', 'available', '0', '0', '1112', '1', '2026-03-21 18:15:33', '2026-03-26 10:14:55');
INSERT INTO `tables` VALUES ('127', NULL, 'room', '1114', 'Tầng 11', '3', 'available', '0', '0', '1114', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('128', NULL, 'room', '1115', 'Tầng 11', '3', 'available', '0', '0', '1115', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('129', NULL, 'room', '1116', 'Tầng 11', '3', 'available', '0', '0', '1116', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('130', NULL, 'room', '1117', 'Tầng 11', '3', 'available', '0', '0', '1117', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('131', NULL, 'room', '1118', 'Tầng 11', '3', 'available', '0', '0', '1118', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('132', NULL, 'room', '1119', 'Tầng 11', '3', 'available', '0', '0', '1119', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('133', NULL, 'room', '1120', 'Tầng 11', '3', 'available', '0', '0', '1120', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('134', NULL, 'room', '1121', 'Tầng 11', '3', 'available', '0', '0', '1121', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('135', NULL, 'room', '1122', 'Tầng 11', '3', 'available', '0', '0', '1122', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('136', NULL, 'room', '1123', 'Tầng 11', '3', 'available', '0', '0', '1123', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('137', NULL, 'room', '1201', 'Tầng 12', '3', 'available', '0', '0', '1201', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('138', NULL, 'room', '1202', 'Tầng 12', '3', 'available', '0', '0', '1202', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('139', NULL, 'room', '1203', 'Tầng 12', '3', 'available', '0', '0', '1203', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('140', NULL, 'room', '1204', 'Tầng 12', '3', 'available', '0', '0', '1204', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('141', NULL, 'room', '1205', 'Tầng 12', '3', 'available', '0', '0', '1205', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('142', NULL, 'room', '1206', 'Tầng 12', '3', 'available', '0', '0', '1206', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('143', NULL, 'room', '1207', 'Tầng 12', '3', 'available', '0', '0', '1207', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('144', NULL, 'room', '1208', 'Tầng 12', '3', 'available', '0', '0', '1208', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('145', NULL, 'room', '1209', 'Tầng 12', '3', 'available', '0', '0', '1209', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('146', NULL, 'room', '1210', 'Tầng 12', '3', 'available', '0', '0', '1210', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('147', NULL, 'room', '1211', 'Tầng 12', '3', 'available', '0', '0', '1211', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('148', NULL, 'room', '1212', 'Tầng 12', '3', 'available', '0', '0', '1212', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('149', NULL, 'room', '1214', 'Tầng 12', '3', 'available', '0', '0', '1214', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('150', NULL, 'room', '1215', 'Tầng 12', '3', 'available', '0', '0', '1215', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('151', NULL, 'room', '1216', 'Tầng 12', '3', 'available', '0', '0', '1216', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('152', NULL, 'room', '1217', 'Tầng 12', '3', 'available', '0', '0', '1217', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('153', NULL, 'room', '1218', 'Tầng 12', '3', 'available', '0', '0', '1218', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('154', NULL, 'room', '1219', 'Tầng 12', '3', 'available', '0', '0', '1219', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('155', NULL, 'room', '1220', 'Tầng 12', '3', 'available', '0', '0', '1220', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');

DROP TABLE IF EXISTS `user_shifts`;
CREATE TABLE `user_shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `shift_id` int(10) unsigned NOT NULL,
  `work_date` date NOT NULL COMMENT 'Ngày làm việc',
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user_shifts_user` (`user_id`),
  KEY `fk_user_shifts_shift` (`shift_id`),
  CONSTRAINT `fk_user_shifts_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_shifts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên nhân viên',
  `username` varchar(50) NOT NULL COMMENT 'Tên đăng nhập',
  `pin` char(4) NOT NULL COMMENT 'PIN 4 số đăng nhập iPad',
  `role` enum('waiter','admin','it') NOT NULL DEFAULT 'waiter',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'URL ảnh đại diện',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hoạt động, 0=vô hiệu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES ('2', 'IT System', 'it', '9999', 'it', NULL, '1', '2026-03-07 18:08:27', '2026-03-07 18:08:27');
INSERT INTO `users` VALUES ('5', 'Nhân Viên 01', 'waiter01', '1111', 'waiter', NULL, '1', '2026-04-08 14:41:58', '2026-04-08 14:41:58');
INSERT INTO `users` VALUES ('6', 'Nhân Viên 02', 'waiter02', '2222', 'waiter', NULL, '1', '2026-04-08 14:42:11', '2026-04-08 14:42:11');
INSERT INTO `users` VALUES ('7', 'Admin', 'admin01', '0000', 'admin', NULL, '1', '2026-04-08 14:42:29', '2026-04-08 14:42:29');

DROP TABLE IF EXISTS `v_activity_by_date`;
;

INSERT INTO `v_activity_by_date` VALUES ('2026-04-11', 'login', 'user', 'info', '4');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-11', 'logout', 'user', 'info', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-10', 'login', 'user', 'info', '5');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-10', 'update', 'setting', 'info', '4');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-10', 'create', 'order', 'info', '2');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-10', 'logout', 'user', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-09', 'login', 'user', 'info', '30');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-09', 'logout', 'user', 'info', '23');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-09', 'update', 'menu_item', 'info', '12');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-09', 'update', 'menu_category', 'info', '2');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-09', 'delete', 'menu_item', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'create', 'menu_category', 'info', '13');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'login', 'user', 'info', '8');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'delete', 'user', 'info', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'create', 'order', 'info', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'create', 'user', 'info', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'update', 'menu_item', 'info', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'logout', 'user', 'info', '2');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'create', 'order_item', 'info', '2');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'delete', 'menu_clear', 'warning', '2');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'create', 'menu_type', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'delete', 'menu_category', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'delete', 'menu_type', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'error', 'user', 'warning', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'update', 'menu_type', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'create', 'menu_item', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'login', 'user', 'info', '9');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'create', 'order_item', 'info', '7');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'logout', 'user', 'info', '5');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'error', 'user', 'warning', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'create', 'order', 'info', '2');

DROP TABLE IF EXISTS `v_activity_stats_today`;
;

INSERT INTO `v_activity_stats_today` VALUES ('7', '0', '0', '7', '2');

DROP TABLE IF EXISTS `vw_location_limit`;
;

INSERT INTO `vw_location_limit` VALUES ('1', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:36:35', '2026-03-08 16:36:35');

SET FOREIGN_KEY_CHECKS = 1;
