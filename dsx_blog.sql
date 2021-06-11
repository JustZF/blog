SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for blog_admin
-- ----------------------------
DROP TABLE IF EXISTS `blog_admin`;
CREATE TABLE `blog_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `username` char(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码盐',
  `role_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of blog_admin
-- ----------------------------
INSERT INTO `blog_admin` VALUES (1, '超级管理员', 'admin', '3e353b70bdf52b2d402039b2668f7b81', 'G0xIgs', 1, 'admin@shunxin66.com', 0, 0);

-- ----------------------------
-- Table structure for blog_article
-- ----------------------------
DROP TABLE IF EXISTS `blog_article`;
CREATE TABLE `blog_article`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文章标题',
  `author` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '作者名',
  `cat_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属栏目',
  `img_url` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文章封面图',
  `is_hot` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐',
  `is_show` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否软删除',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `deleted`(`deleted`) USING BTREE COMMENT '是否删除',
  INDEX `cat_id`(`cat_id`) USING BTREE COMMENT '栏目'
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for blog_article_content
-- ----------------------------
DROP TABLE IF EXISTS `blog_article_content`;
CREATE TABLE `blog_article_content`  (
  `art_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '对应文章id',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`art_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '博客文章内容' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for blog_article_label
-- ----------------------------
DROP TABLE IF EXISTS `blog_article_label`;
CREATE TABLE `blog_article_label`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `art_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '文章id',
  `label_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '标签id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `artId_labelId`(`art_id`, `label_id`) USING BTREE,
  INDEX `label_id`(`label_id`) USING BTREE,
  INDEX `art_id`(`art_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章标签对应关系' ROW_FORMAT = Fixed;


-- ----------------------------
-- Table structure for blog_cat
-- ----------------------------
DROP TABLE IF EXISTS `blog_cat`;
CREATE TABLE `blog_cat`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `cat_name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '栏目名',
  `content` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '栏目介绍',
  `order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否软删除',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '栏目' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of blog_cat
-- ----------------------------
INSERT INTO `blog_cat` VALUES (1, 0, '学海无涯', '<img src=\"http://blog.dsx.com/static/layui/images/face/39.gif\" alt=\"[鼓掌]\">记录博主web学习过程中的笔记', 0, 1, 0, 1534649146, 1534649146);
INSERT INTO `blog_cat` VALUES (2, 1, 'PHP', '博主的PHP笔记', 100, 1, 0, 1534649273, 1534649273);
INSERT INTO `blog_cat` VALUES (3, 1, 'MySQL', '博主的MySQL笔记', 101, 1, 0, 1534649452, 1534649452);

-- ----------------------------
-- Table structure for blog_comment
-- ----------------------------
DROP TABLE IF EXISTS `blog_comment`;
CREATE TABLE `blog_comment`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `to_user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回复人id',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `art_id` int(10) UNSIGNED NOT NULL COMMENT '文章id，0代表留言',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '留言内容',
  `is_show` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `update_time` int(10) UNSIGNED NOT NULL COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `art_id`(`art_id`) USING BTREE COMMENT '文章id',
  INDEX `parent_id`(`parent_id`) USING BTREE COMMENT '父级id'
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章评论' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for blog_image
-- ----------------------------
DROP TABLE IF EXISTS `blog_image`;
CREATE TABLE `blog_image`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片名',
  `path` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '原图图片路径',
  `middling_path` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '中等缩略图路径',
  `small_path` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '小缩略图路径',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否软删除',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for blog_label
-- ----------------------------
DROP TABLE IF EXISTS `blog_label`;
CREATE TABLE `blog_label`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label_name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标签名',
  `order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否软删除',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '标签表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of blog_label
-- ----------------------------
INSERT INTO `blog_label` VALUES (1, '学习笔记', 0, 1, 0, 1534649594, 1534649594);
INSERT INTO `blog_label` VALUES (2, 'Thinkphp', 0, 1, 0, 1534649649, 1534649649);
INSERT INTO `blog_label` VALUES (3, 'MySQL', 0, 1, 0, 1534649749, 1534649749);
INSERT INTO `blog_label` VALUES (4, 'JavaScript', 0, 1, 0, 1534649773, 1534649773);

-- ----------------------------
-- Table structure for blog_link
-- ----------------------------
DROP TABLE IF EXISTS `blog_link`;
CREATE TABLE `blog_link`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接名',
  `link_url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接地址',
  `order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示，1显示，0不显示',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除，1删除，0未删除',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '友情链接' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for blog_record
-- ----------------------------
DROP TABLE IF EXISTS `blog_record`;
CREATE TABLE `blog_record`  (
  `rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(11) NOT NULL DEFAULT 0,
  `ipdata` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `rec_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`rec_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for blog_role
-- ----------------------------
DROP TABLE IF EXISTS `blog_role`;
CREATE TABLE `blog_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '角色名',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员角色' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of blog_role
-- ----------------------------
INSERT INTO `blog_role` VALUES (1, '超级管理员', 0, 0);

-- ----------------------------
-- Table structure for blog_user
-- ----------------------------
DROP TABLE IF EXISTS `blog_user`;
CREATE TABLE `blog_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` char(15) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '用户名',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `nickname` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `head_img` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '/static/images/head/1.svg' COMMENT '头像',
  `salt` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '随机码',
  `github_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT 'github ID',
  `github_login` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'github用户名',
  `github_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'github昵称',
  `github_avatar_url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'github头像',
  `qq_openid` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'qq openid',
  `qq_nickname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'qq昵称',
  `qq_figureurl` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'qq头像',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE COMMENT '用户名唯一',
  UNIQUE INDEX `email`(`email`) USING BTREE COMMENT '邮箱唯一',
  UNIQUE INDEX `qq_openid`(`qq_openid`) USING BTREE COMMENT 'qq绑定唯一',
  UNIQUE INDEX `github_id`(`github_id`) USING BTREE COMMENT 'github绑定唯一'
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
