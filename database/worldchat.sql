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

 Date: 30/05/2020 22:25:26
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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of counts
-- ----------------------------
INSERT INTO `counts` VALUES (1, 1, 1, 0);
INSERT INTO `counts` VALUES (2, 1, 2, 0);
INSERT INTO `counts` VALUES (3, 1, 1, 1);
INSERT INTO `counts` VALUES (4, 1, 1, 1);
INSERT INTO `counts` VALUES (5, 1, 1, 1);
INSERT INTO `counts` VALUES (6, 2, 1, 0);

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of messages
-- ----------------------------
INSERT INTO `messages` VALUES (1, '大家好', NULL, 1, 1, '2020-05-30 15:06:37');
INSERT INTO `messages` VALUES (2, 'fsdaf', NULL, 1, 1, '2020-05-28 13:40:25');
INSERT INTO `messages` VALUES (3, 'gersgs', NULL, 2, 1, '2020-05-29 13:40:33');
INSERT INTO `messages` VALUES (4, '55', '', 1, 1, '2020-05-30 22:16:29');
INSERT INTO `messages` VALUES (5, '得分回复', '', 1, 1, '2020-05-30 22:16:39');
INSERT INTO `messages` VALUES (6, '啊手动阀', '', 1, 1, '2020-05-30 22:16:51');
INSERT INTO `messages` VALUES (7, 'dhd', '', 2, 1, '2020-05-30 22:17:35');
INSERT INTO `messages` VALUES (8, '阿道夫', '', 1, 1, '2020-05-30 22:17:41');
INSERT INTO `messages` VALUES (9, '你好', '', 1, 1, '2020-05-30 22:17:46');
INSERT INTO `messages` VALUES (10, 'hh', '', 2, 1, '2020-05-30 22:17:54');
INSERT INTO `messages` VALUES (11, '士大夫感到附属国', '', 1, 1, '2020-05-30 22:18:44');
INSERT INTO `messages` VALUES (12, '你好', '', 1, 1, '2020-05-30 22:20:25');
INSERT INTO `messages` VALUES (13, 'hi', '', 1, 1, '2020-05-30 22:21:17');
INSERT INTO `messages` VALUES (14, '在吗', '', 1, 1, '2020-05-30 22:21:22');
INSERT INTO `messages` VALUES (15, '在的', '', 2, 1, '2020-05-30 22:21:30');
INSERT INTO `messages` VALUES (16, '啥事？', '', 2, 1, '2020-05-30 22:21:36');
INSERT INTO `messages` VALUES (17, '好的，聊天功能完成了', '', 1, 1, '2020-05-30 22:21:58');
INSERT INTO `messages` VALUES (18, '😍', '', 2, 1, '2020-05-30 22:22:06');
INSERT INTO `messages` VALUES (19, '👏', '', 1, 1, '2020-05-30 22:22:20');

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
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '测试1号', 'test1', 'test1@qq.com', NULL, '$2y$10$A3G7vjmBc3bERFryjZFcLe16hRxm5Yxoqxz2kIxkot81/nn0u3uVe', NULL, NULL, 'Z5LqbrsBd037pf5mOgeVXYjdmRPctiTYAcVB5iym81UEn1UkEo0jzaMHLlPh', '2020-05-25 13:28:24', '2020-05-29 12:15:29');
INSERT INTO `users` VALUES (2, '测试2号', 'test2', 'test2@qq.com', NULL, '$2y$10$0tqHJWAYAl/QJ6l45gMeL.3Q.yvw8gwzpskIpVuHcUEg.LJzOk2Hm', NULL, NULL, 'inSa64ieit0AUgsUKsPZxj5RTOfx9l6iQV8fsqeFF0Pbe4UQOu7fNUGBO4nd', '2020-05-25 21:32:19', '2020-05-30 17:33:21');
INSERT INTO `users` VALUES (3, NULL, 'test3', 'test3@qq.com', NULL, '$2y$10$TIf.6kmCCusVV/1pSQyXaee5PGKMejoEw0WPMhE7Hf1.JRmDSfBWS', '//s3.qiufengh.com/avatar/32.jpeg', NULL, 'FLWIDp8imPSJ6ZLmp0kzVRl58aLfJEdC06qnVz7GYPIzQUWnSvWzbVdh80ob', '2020-05-27 10:42:34', '2020-05-27 10:42:34');
INSERT INTO `users` VALUES (4, NULL, 'test5', 'test5@qq.com', NULL, '$2y$10$RXLm/co1YDuRgMjpfd9qAeBYthGql6W9Ybz8/271EHYdaKHyVQmVu', '//s3.qiufengh.com/avatar/237.jpeg', NULL, '7L2eBAOVMWn0MTlkP6BMmTCCVLOfMLLqVMeG0Z0xjwXhpWvakcmQHYJ3aIzC', '2020-05-27 10:49:49', '2020-05-27 10:49:49');
INSERT INTO `users` VALUES (5, NULL, 'test6', 'test6@qq.com', NULL, '$2y$10$S0OSP..nITatZp3zIwno9.oMd/6CpN1UCZWsRVtyTRdfp48S9//M6', '//s3.qiufengh.com/avatar/76.jpeg', NULL, 'iZ7fub84T4gYtEVFM9Ku5KpXotx1JwzfvkOJYSyl5VEbljA83hSNb59WnQ5F', '2020-05-27 10:50:23', '2020-05-27 10:50:23');
INSERT INTO `users` VALUES (6, NULL, 'test7', 'test7@qq.com', NULL, '$2y$10$RKRuATieTJUzue0XTcWDe.5AiXC0IPWPQK9ZgzoU.DmPVIQTvQJqG', '//s3.qiufengh.com/avatar/149.jpeg', NULL, 'yOKjvVoinzpHZmG3b6nCl1rFMnjlnaH45Ln88ZPxOWRjHhktwowZrdjPGS6o', '2020-05-27 10:55:20', '2020-05-27 10:55:20');
INSERT INTO `users` VALUES (7, NULL, 'test8', 'test8@qq.com', NULL, '$2y$10$Fon5AYoDrPwTxaykVOTk3.EA7ftrwAo0gpu.TEv91wLX6r2GNc97i', '//s3.qiufengh.com/avatar/68.jpeg', NULL, 'uqtA8jxc8ryWKtuuOeuYRhBp6KHKkJGrS4nahHWM7mRnqdSbMZV06NCNFoUY', '2020-05-27 10:59:20', '2020-05-27 10:59:20');
INSERT INTO `users` VALUES (8, NULL, 'test9', 'test9@qq.com', NULL, '$2y$10$sg9XU4NGRPVMYzLu9ItmR.HDPv6ulFS5pn/Iza0g9UDBjDGBvp3wS', '//s3.qiufengh.com/avatar/169.jpeg', NULL, 'rkljLYJzWyLvgysP5jEogfkvdBOyh00uArXcTlEHiAuXiM0CNRAe9F4nAeOS', '2020-05-27 10:59:57', '2020-05-27 10:59:57');
INSERT INTO `users` VALUES (9, NULL, 'test10', 'test10@qq.com', NULL, '$2y$10$oXLHthksV8GQ/NiuaLl.z.W0kBQxg9kjJ/V8fzukqfUXWzWYMAUO6', '//s3.qiufengh.com/avatar/217.jpeg', NULL, 'rjP8MfmJlOPmvGNzy8rZSXXoHpmMMHWVAA7LTEj3NEjnZNK8TfDbF2gDQpu3', '2020-05-27 11:05:51', '2020-05-27 11:05:51');

SET FOREIGN_KEY_CHECKS = 1;
