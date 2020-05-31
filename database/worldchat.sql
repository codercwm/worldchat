/*
 Navicat Premium Data Transfer

 Source Server         : homestead
 Source Server Type    : MySQL
 Source Server Version : 80020
 Source Host           : 192.168.10.10:3306
 Source Schema         : worldchat

 Target Server Type    : MySQL
 Target Server Version : 80020
 File Encoding         : 65001

 Date: 31/05/2020 21:57:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for counts
-- ----------------------------
DROP TABLE IF EXISTS `counts`;
CREATE TABLE `counts`  (
  `id` bigint unsigned NOT NULL,
  `user_id` bigint(0) NOT NULL,
  `room_id` smallint(0) NOT NULL,
  `count` int(0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of counts
-- ----------------------------
INSERT INTO `counts` VALUES (1, 1, 1, 1);
INSERT INTO `counts` VALUES (2, 1, 2, 0);
INSERT INTO `counts` VALUES (3, 1, 1, 1);
INSERT INTO `counts` VALUES (4, 1, 1, 1);
INSERT INTO `counts` VALUES (5, 1, 1, 1);
INSERT INTO `counts` VALUES (6, 2, 1, 0);
INSERT INTO `counts` VALUES (7, 3, 1, 20);
INSERT INTO `counts` VALUES (8, 1, 2, 1);
INSERT INTO `counts` VALUES (9, 1, 2, 1);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint unsigned NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages`  (
  `id` bigint unsigned NOT NULL,
  `msg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本消息',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图片消息',
  `user_id` bigint(0) NOT NULL COMMENT '用户id',
  `room_id` smallint(0) NOT NULL COMMENT '房间id',
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of messages
-- ----------------------------
INSERT INTO `messages` VALUES (1, '1', '', 1, 1, '2020-05-31 16:34:55');
INSERT INTO `messages` VALUES (2, '2', '', 2, 1, '2020-05-31 16:35:06');
INSERT INTO `messages` VALUES (3, '3', '', 1, 1, '2020-05-31 16:35:16');
INSERT INTO `messages` VALUES (4, '4', '', 1, 1, '2020-05-31 16:35:19');
INSERT INTO `messages` VALUES (5, '5', '', 1, 1, '2020-05-31 16:35:20');
INSERT INTO `messages` VALUES (6, '6', '', 1, 1, '2020-05-31 16:35:21');
INSERT INTO `messages` VALUES (7, '7', '', 1, 1, '2020-05-31 16:35:22');
INSERT INTO `messages` VALUES (8, '8', '', 1, 1, '2020-05-31 16:35:22');
INSERT INTO `messages` VALUES (9, '9', '', 1, 1, '2020-05-31 16:35:23');
INSERT INTO `messages` VALUES (10, '10', '', 1, 1, '2020-05-31 16:35:25');
INSERT INTO `messages` VALUES (11, '11', '', 2, 1, '2020-05-31 16:35:28');
INSERT INTO `messages` VALUES (12, '12', '', 2, 1, '2020-05-31 16:35:29');
INSERT INTO `messages` VALUES (13, '13', '', 2, 1, '2020-05-31 16:35:30');
INSERT INTO `messages` VALUES (14, '14', '', 2, 1, '2020-05-31 16:35:31');
INSERT INTO `messages` VALUES (15, '15', '', 2, 1, '2020-05-31 16:35:32');
INSERT INTO `messages` VALUES (16, '16', '', 2, 1, '2020-05-31 16:35:34');
INSERT INTO `messages` VALUES (17, '17', '', 2, 1, '2020-05-31 16:35:35');
INSERT INTO `messages` VALUES (18, '18', '', 2, 1, '2020-05-31 16:35:37');
INSERT INTO `messages` VALUES (19, '19', '', 2, 1, '2020-05-31 16:35:38');
INSERT INTO `messages` VALUES (20, '20', '', 2, 1, '2020-05-31 16:35:40');
INSERT INTO `messages` VALUES (21, '21', '', 1, 1, '2020-05-31 16:35:45');
INSERT INTO `messages` VALUES (22, '22', '', 1, 1, '2020-05-31 16:35:45');
INSERT INTO `messages` VALUES (23, '23', '', 1, 1, '2020-05-31 16:35:47');
INSERT INTO `messages` VALUES (24, '24', '', 1, 1, '2020-05-31 16:35:48');
INSERT INTO `messages` VALUES (25, '25', '', 1, 1, '2020-05-31 16:35:49');
INSERT INTO `messages` VALUES (26, '26', '', 1, 1, '2020-05-31 16:35:50');
INSERT INTO `messages` VALUES (27, '27', '', 1, 1, '2020-05-31 16:35:51');
INSERT INTO `messages` VALUES (28, '28', '', 1, 1, '2020-05-31 16:35:52');
INSERT INTO `messages` VALUES (29, '29', '', 1, 1, '2020-05-31 16:35:53');
INSERT INTO `messages` VALUES (30, '30', '', 1, 1, '2020-05-31 16:35:54');
INSERT INTO `messages` VALUES (31, '31', '', 2, 1, '2020-05-31 16:35:58');
INSERT INTO `messages` VALUES (32, '32', '', 2, 1, '2020-05-31 16:36:00');
INSERT INTO `messages` VALUES (33, '33', '', 2, 1, '2020-05-31 16:36:02');
INSERT INTO `messages` VALUES (34, '34', '', 2, 1, '2020-05-31 16:36:02');
INSERT INTO `messages` VALUES (35, '35', '', 2, 1, '2020-05-31 16:36:03');
INSERT INTO `messages` VALUES (36, '36', '', 2, 1, '2020-05-31 16:36:05');
INSERT INTO `messages` VALUES (37, '37', '', 2, 1, '2020-05-31 16:36:05');
INSERT INTO `messages` VALUES (38, '38', '', 2, 1, '2020-05-31 16:36:07');
INSERT INTO `messages` VALUES (39, '38', '', 2, 1, '2020-05-31 16:36:09');
INSERT INTO `messages` VALUES (40, '39', '', 2, 1, '2020-05-31 16:36:12');
INSERT INTO `messages` VALUES (41, '40', '', 2, 1, '2020-05-31 16:36:15');
INSERT INTO `messages` VALUES (42, '41', '', 1, 1, '2020-05-31 16:49:15');
INSERT INTO `messages` VALUES (43, '42', '', 1, 1, '2020-05-31 16:49:43');
INSERT INTO `messages` VALUES (44, '43', '', 1, 1, '2020-05-31 16:49:54');
INSERT INTO `messages` VALUES (45, '44', '', 1, 1, '2020-05-31 16:53:46');
INSERT INTO `messages` VALUES (46, '45', '', 1, 1, '2020-05-31 16:56:30');
INSERT INTO `messages` VALUES (47, '46', '', 1, 1, '2020-05-31 17:25:59');
INSERT INTO `messages` VALUES (48, '46', '', 1, 1, '2020-05-31 17:28:26');
INSERT INTO `messages` VALUES (49, '47', '', 2, 1, '2020-05-31 17:32:02');
INSERT INTO `messages` VALUES (50, '48', '', 2, 1, '2020-05-31 17:32:04');
INSERT INTO `messages` VALUES (51, '49', '', 2, 1, '2020-05-31 17:32:06');
INSERT INTO `messages` VALUES (52, '1', '', 3, 1, '2020-05-31 21:25:49');
INSERT INTO `messages` VALUES (53, '2', '', 3, 1, '2020-05-31 21:25:56');
INSERT INTO `messages` VALUES (54, '2', '', 3, 1, '2020-05-31 21:27:54');
INSERT INTO `messages` VALUES (55, '4', '', 3, 1, '2020-05-31 21:27:56');
INSERT INTO `messages` VALUES (56, '5', '', 3, 1, '2020-05-31 21:27:58');
INSERT INTO `messages` VALUES (57, '1', '', 1, 1, '2020-05-31 21:35:34');
INSERT INTO `messages` VALUES (58, '5', '', 1, 1, '2020-05-31 21:35:40');
INSERT INTO `messages` VALUES (59, '7', '', 1, 1, '2020-05-31 21:36:56');
INSERT INTO `messages` VALUES (60, '8', '', 1, 1, '2020-05-31 21:36:59');
INSERT INTO `messages` VALUES (61, '9', '', 1, 1, '2020-05-31 21:37:02');
INSERT INTO `messages` VALUES (62, '1', '', 2, 1, '2020-05-31 21:37:56');
INSERT INTO `messages` VALUES (63, '1', '', 2, 1, '2020-05-31 21:38:01');
INSERT INTO `messages` VALUES (64, '2', '', 2, 1, '2020-05-31 21:38:03');
INSERT INTO `messages` VALUES (65, '3', '', 2, 1, '2020-05-31 21:38:05');
INSERT INTO `messages` VALUES (66, '4', '', 2, 1, '2020-05-31 21:38:06');
INSERT INTO `messages` VALUES (67, '5', '', 2, 1, '2020-05-31 21:38:08');
INSERT INTO `messages` VALUES (68, '6', '', 2, 1, '2020-05-31 21:38:10');
INSERT INTO `messages` VALUES (69, '1', '', 1, 1, '2020-05-31 21:45:56');
INSERT INTO `messages` VALUES (70, '2', '', 1, 1, '2020-05-31 21:46:08');
INSERT INTO `messages` VALUES (71, '3', '', 1, 1, '2020-05-31 21:46:10');
INSERT INTO `messages` VALUES (72, '4', '', 1, 1, '2020-05-31 21:46:11');
INSERT INTO `messages` VALUES (73, '1', '', 2, 1, '2020-05-31 21:48:14');
INSERT INTO `messages` VALUES (74, '2', '', 1, 1, '2020-05-31 21:48:20');
INSERT INTO `messages` VALUES (75, '4', '', 2, 1, '2020-05-31 21:49:48');
INSERT INTO `messages` VALUES (76, '有人吗', '', 1, 2, '2020-05-31 21:52:29');
INSERT INTO `messages` VALUES (77, '👕', '', 2, 1, '2020-05-31 21:54:51');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int unsigned NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2020_05_25_085409_alter_users_add_avatar_column', 1);
INSERT INTO `migrations` VALUES (5, '2020_05_25_124025_create_messages_table', 1);
INSERT INTO `migrations` VALUES (6, '2020_05_25_124947_alter_users_add_api_token', 2);
INSERT INTO `migrations` VALUES (7, '2020_05_28_211147_create_counts_table', 3);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户头像',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `api_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE,
  UNIQUE INDEX `users_api_token_unique`(`api_token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '测试1号', 'test1', 'test1@qq.com', NULL, '$2y$10$A3G7vjmBc3bERFryjZFcLe16hRxm5Yxoqxz2kIxkot81/nn0u3uVe', '//worldchat.test/img/house.png', NULL, 'LnahiW1Qz6KR6PMctyR15dLOhoghixmRqXiyMABXA5IDv3UsiEOBfeZWmlsA', '2020-05-25 13:28:24', '2020-05-31 21:30:04');
INSERT INTO `users` VALUES (2, '测试2号', 'test2', 'test2@qq.com', NULL, '$2y$10$0tqHJWAYAl/QJ6l45gMeL.3Q.yvw8gwzpskIpVuHcUEg.LJzOk2Hm', '', NULL, '6eZsldIWPVuPPGRdj9bPk5toTAiK5FSbHqvY9UX7QdlI4s3LQvNOelp7Gjs5', '2020-05-25 21:32:19', '2020-05-31 15:55:06');
INSERT INTO `users` VALUES (3, NULL, 'test3', 'test3@qq.com', NULL, '$2y$10$TIf.6kmCCusVV/1pSQyXaee5PGKMejoEw0WPMhE7Hf1.JRmDSfBWS', '', NULL, 'unkM4tSDj0LVUq8x0yStWmsW3qNpf9AXDS3z8efSvtvdLXzULSaG45amyEYc', '2020-05-27 10:42:34', '2020-05-31 21:25:33');
INSERT INTO `users` VALUES (4, NULL, 'test5', 'test5@qq.com', NULL, '$2y$10$RXLm/co1YDuRgMjpfd9qAeBYthGql6W9Ybz8/271EHYdaKHyVQmVu', '', NULL, '7L2eBAOVMWn0MTlkP6BMmTCCVLOfMLLqVMeG0Z0xjwXhpWvakcmQHYJ3aIzC', '2020-05-27 10:49:49', '2020-05-27 10:49:49');
INSERT INTO `users` VALUES (5, NULL, 'test6', 'test6@qq.com', NULL, '$2y$10$S0OSP..nITatZp3zIwno9.oMd/6CpN1UCZWsRVtyTRdfp48S9//M6', '', NULL, 'iZ7fub84T4gYtEVFM9Ku5KpXotx1JwzfvkOJYSyl5VEbljA83hSNb59WnQ5F', '2020-05-27 10:50:23', '2020-05-27 10:50:23');
INSERT INTO `users` VALUES (6, NULL, 'test7', 'test7@qq.com', NULL, '$2y$10$RKRuATieTJUzue0XTcWDe.5AiXC0IPWPQK9ZgzoU.DmPVIQTvQJqG', '', NULL, 'yOKjvVoinzpHZmG3b6nCl1rFMnjlnaH45Ln88ZPxOWRjHhktwowZrdjPGS6o', '2020-05-27 10:55:20', '2020-05-27 10:55:20');
INSERT INTO `users` VALUES (7, NULL, 'test8', 'test8@qq.com', NULL, '$2y$10$Fon5AYoDrPwTxaykVOTk3.EA7ftrwAo0gpu.TEv91wLX6r2GNc97i', '', NULL, 'uqtA8jxc8ryWKtuuOeuYRhBp6KHKkJGrS4nahHWM7mRnqdSbMZV06NCNFoUY', '2020-05-27 10:59:20', '2020-05-27 10:59:20');
INSERT INTO `users` VALUES (8, NULL, 'test9', 'test9@qq.com', NULL, '$2y$10$sg9XU4NGRPVMYzLu9ItmR.HDPv6ulFS5pn/Iza0g9UDBjDGBvp3wS', '', NULL, 'rkljLYJzWyLvgysP5jEogfkvdBOyh00uArXcTlEHiAuXiM0CNRAe9F4nAeOS', '2020-05-27 10:59:57', '2020-05-27 10:59:57');
INSERT INTO `users` VALUES (9, NULL, 'test10', 'test10@qq.com', NULL, '$2y$10$oXLHthksV8GQ/NiuaLl.z.W0kBQxg9kjJ/V8fzukqfUXWzWYMAUO6', '', NULL, 'rjP8MfmJlOPmvGNzy8rZSXXoHpmMMHWVAA7LTEj3NEjnZNK8TfDbF2gDQpu3', '2020-05-27 11:05:51', '2020-05-27 11:05:51');
INSERT INTO `users` VALUES (10, NULL, 'test11', 'test11@qq.com', NULL, '$2y$10$SZ54/BDtyv4q3UI6o9qYOuNdLzbAsFvzZLBjad.e/6j/5KYxb5iWG', '', NULL, 'bf7OMYYV5PGg4eyZ5HF6opDo4R9vremyBBg2wKfaIgxsyLOpRIjp7BsAD5Sw', '2020-05-31 14:08:15', '2020-05-31 14:08:15');
INSERT INTO `users` VALUES (11, NULL, 'test12', 'test12@qq.com', NULL, '$2y$10$VGmpa9nfSISfwtXaenLym.UwjHjQDbQLYnTNVF2fuwgT1iiX9gH0u', NULL, NULL, 'pj7rQxQ0jCYydk4EDRmCoMMuBMjX1wusnndJkmoqBwYyLRpgoUrukFygyLe1', '2020-05-31 14:47:43', '2020-05-31 14:47:43');

SET FOREIGN_KEY_CHECKS = 1;
