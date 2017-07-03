/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : xtwh

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2017-06-20 09:15:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xt_address
-- ----------------------------
DROP TABLE IF EXISTS `xt_address`;
CREATE TABLE `xt_address` (
  `id` int(11) unsigned NOT NULL,
  `u2id` int(11) unsigned NOT NULL COMMENT '用户id(user2表)',
  `name` varchar(255) DEFAULT NULL COMMENT '收货人姓名',
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `province` varchar(255) DEFAULT NULL COMMENT '省',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区/县',
  `street` varchar(255) DEFAULT NULL COMMENT '地址详情',
  `zipcode` varchar(255) DEFAULT NULL COMMENT '邮政编码',
  `default` tinyint(3) DEFAULT '0' COMMENT '1默认0非默认',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user2表用户购买商品,收货地址';

-- ----------------------------
-- Records of xt_address
-- ----------------------------

-- ----------------------------
-- Table structure for xt_admin
-- ----------------------------
DROP TABLE IF EXISTS `xt_admin`;
CREATE TABLE `xt_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `logintime` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xt_admin
-- ----------------------------
INSERT INTO `xt_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1497863836', null);
INSERT INTO `xt_admin` VALUES ('2', 'chao', 'e10adc3949ba59abbe56e057f20f883e', '1497669161', null);

-- ----------------------------
-- Table structure for xt_article
-- ----------------------------
DROP TABLE IF EXISTS `xt_article`;
CREATE TABLE `xt_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL COMMENT '( 1:平台介绍  2:合作机构展示  3:平台新闻 4:活动 )',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo图',
  `desc` varchar(255) DEFAULT NULL COMMENT '简短描述',
  `looknum` int(11) unsigned DEFAULT '0' COMMENT '点击数',
  `detail` text COMMENT '详情内容',
  `create_at` varchar(255) DEFAULT NULL COMMENT '发布时间',
  `is_del` int(11) DEFAULT '0' COMMENT '( 1为删除, 0 未删除 )',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xt_article
-- ----------------------------
INSERT INTO `xt_article` VALUES ('4', '4', '【思汇教育】夏令营火爆开营活动', '/xtwh/Uploads/jigou_pic/20170615/95ecd7ac9db4580d94967df383fb1136.png', '', '0', '<p><img src=\"/xtwh/Uploads/ueditor/image/20170615/59425799dec2c.png\" title=\"59425799dec2c.png\" alt=\"下载.png\" width=\"427\" height=\"199\" style=\"width: 427px; height: 199px;\"/></p><p><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">【思汇教育】夏令营火爆开营活动</span></p><p><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">发布时间：2017-6-15 15:15:55 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span style=\"font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; color: rgb(0, 176, 80); background-color: rgb(255, 255, 255);\"><span style=\"font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">¥120.00/人</span></span></span></p><p><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><span style=\"font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; color: rgb(0, 176, 80); background-color: rgb(255, 255, 255);\"><span style=\"font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><br/></span></span></span></p><p><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><span style=\"font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; color: rgb(0, 176, 80); background-color: rgb(255, 255, 255);\"><span style=\"font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><br/></span></span></span></p><p><span style=\"font-size: 16px; color: rgb(0, 176, 80);\"><strong><span style=\"font-size: 16px; font-family: Consolas, &#39;Lucida Console&#39;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">活动详情</span></strong></span></p><p><span style=\"font-size: 16px; color: rgb(0, 176, 80);\"><strong><span style=\"font-size: 16px; font-family: Consolas, &#39;Lucida Console&#39;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><br/></span></strong></span></p><p><span style=\"font-size: 16px; color: rgb(0, 176, 80);\"><span style=\"font-size: 16px; font-family: Consolas, &#39;Lucida Console&#39;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">推荐指数：★ ★ ★ ★ ★</span></span></span></p><p><span style=\"font-size: 16px; color: rgb(0, 176, 80);\"><span style=\"font-size: 16px; font-family: Consolas, &#39;Lucida Console&#39;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">安全措施：首家全程对营员采取GPS卫星定位的夏令营机构。</span></span></span></p><p><span style=\"font-size: 16px; color: rgb(0, 176, 80);\"><span style=\"font-size: 16px; font-family: Consolas, &#39;Lucida Console&#39;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">适宜人群：想更独立勇敢，全面成长的10岁以上的小鬼头们。</span></span></span></p><p><span style=\"font-size: 16px; color: rgb(0, 176, 80);\"><span style=\"font-size: 16px; font-family: Consolas, &#39;Lucida Console&#39;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">活动地： &nbsp; 奥德曼国内下属地区素质 教育成长基地</span></span></span></p><p><span style=\"font-size: 16px; color: rgb(0, 176, 80);\"><span style=\"font-size: 16px; font-family: Consolas, &#39;Lucida Console&#39;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255);\"><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &#39;Lucida Console&#39;, monospace; font-size: 12px; white-space: pre-wrap; background-color: rgb(255, 255, 255);\">奥德曼西式夏令营是安全透明的</span></span></span></p>', '1497520232', '0');

-- ----------------------------
-- Table structure for xt_backmoney
-- ----------------------------
DROP TABLE IF EXISTS `xt_backmoney`;
CREATE TABLE `xt_backmoney` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `u2id` int(11) unsigned NOT NULL COMMENT 'user2表中的id ',
  `money` decimal(10,2) NOT NULL COMMENT '返佣金额',
  `source` int(11) NOT NULL COMMENT '来源id (user2 表)',
  `message` varchar(255) DEFAULT NULL COMMENT '信息(直营消费返佣,非直营消费返佣,提现,购物)',
  `create_at` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned DEFAULT NULL COMMENT '订单id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 COMMENT='基金--返佣记录表';

-- ----------------------------
-- Records of xt_backmoney
-- ----------------------------
INSERT INTO `xt_backmoney` VALUES ('1', '14', '100.00', '2', '直营分佣基金', '1496806081', null);
INSERT INTO `xt_backmoney` VALUES ('2', '14', '100.00', '2', '直营分佣基金', '1496806081', null);
INSERT INTO `xt_backmoney` VALUES ('3', '14', '100.00', '2', '非直营分佣基金', '1496823008', null);
INSERT INTO `xt_backmoney` VALUES ('7', '16', '100.00', '2', '直营分佣基金', '1496890574', null);
INSERT INTO `xt_backmoney` VALUES ('8', '16', '200.00', '2', '直营分佣基金', '1496890574', null);
INSERT INTO `xt_backmoney` VALUES ('44', '20', '15.00', '14', '非直营分佣基金', '1497491885', null);
INSERT INTO `xt_backmoney` VALUES ('45', '16', '50.00', '14', '直营分佣基金', '1497491885', null);
INSERT INTO `xt_backmoney` VALUES ('47', '20', '15.00', '14', '非直营分佣基金', '1497491935', null);
INSERT INTO `xt_backmoney` VALUES ('48', '16', '50.00', '14', '直营分佣基金', '1497491935', null);
INSERT INTO `xt_backmoney` VALUES ('50', '20', '15.00', '14', '非直营分佣基金', '1497491954', null);
INSERT INTO `xt_backmoney` VALUES ('51', '16', '50.00', '14', '直营分佣基金', '1497491954', null);
INSERT INTO `xt_backmoney` VALUES ('54', '16', '50.00', '14', '直营分佣基金', '1497492112', null);
INSERT INTO `xt_backmoney` VALUES ('55', '16', '210.00', '15', '直营分佣基金', '1497669552', null);
INSERT INTO `xt_backmoney` VALUES ('57', '16', '360.00', '15', '直营分佣基金', '1497671115', null);
INSERT INTO `xt_backmoney` VALUES ('58', '16', '360.00', '15', '直营分佣基金', '1497671121', null);
INSERT INTO `xt_backmoney` VALUES ('59', '16', '360.00', '15', '直营分佣基金', '1497671168', null);
INSERT INTO `xt_backmoney` VALUES ('60', '16', '360.00', '15', '直营分佣基金', '1497671169', null);
INSERT INTO `xt_backmoney` VALUES ('61', '16', '360.00', '15', '直营分佣基金', '1497671171', null);
INSERT INTO `xt_backmoney` VALUES ('62', '16', '360.00', '15', '直营分佣基金', '1497671188', null);
INSERT INTO `xt_backmoney` VALUES ('63', '16', '360.00', '15', '直营分佣基金', '1497671431', null);
INSERT INTO `xt_backmoney` VALUES ('64', '16', '360.00', '15', '直营分佣基金', '1497671452', null);
INSERT INTO `xt_backmoney` VALUES ('65', '16', '360.00', '15', '直营分佣基金', '1497671588', null);
INSERT INTO `xt_backmoney` VALUES ('66', '16', '360.00', '15', '直营分佣基金', '1497671617', null);
INSERT INTO `xt_backmoney` VALUES ('67', '16', '360.00', '15', '直营分佣基金', '1497671646', null);
INSERT INTO `xt_backmoney` VALUES ('68', '16', '360.00', '15', '直营分佣基金', '1497671658', null);
INSERT INTO `xt_backmoney` VALUES ('69', '16', '50.00', '15', '直营分佣基金', '1497671722', null);
INSERT INTO `xt_backmoney` VALUES ('70', '16', '50.00', '15', '直营分佣基金', '1497671769', null);
INSERT INTO `xt_backmoney` VALUES ('71', '16', '50.00', '15', '直营分佣基金', '1497671770', null);
INSERT INTO `xt_backmoney` VALUES ('72', '16', '50.00', '15', '直营分佣基金', '1497671781', null);
INSERT INTO `xt_backmoney` VALUES ('73', '16', '50.00', '15', '直营分佣基金', '1497671781', null);
INSERT INTO `xt_backmoney` VALUES ('74', '16', '50.00', '15', '直营分佣基金', '1497671828', null);
INSERT INTO `xt_backmoney` VALUES ('75', '16', '50.00', '15', '直营分佣基金', '1497671860', null);
INSERT INTO `xt_backmoney` VALUES ('76', '16', '50.00', '15', '直营分佣基金', '1497671898', null);
INSERT INTO `xt_backmoney` VALUES ('77', '16', '50.00', '15', '直营分佣基金', '1497671924', null);
INSERT INTO `xt_backmoney` VALUES ('78', '16', '50.00', '15', '直营分佣基金', '1497671955', null);
INSERT INTO `xt_backmoney` VALUES ('79', '16', '50.00', '15', '直营分佣基金', '1497671959', null);
INSERT INTO `xt_backmoney` VALUES ('80', '16', '50.00', '15', '直营分佣基金', '1497671960', null);
INSERT INTO `xt_backmoney` VALUES ('81', '16', '50.00', '15', '直营分佣基金', '1497671974', null);
INSERT INTO `xt_backmoney` VALUES ('82', '16', '50.00', '15', '直营分佣基金', '1497672004', null);
INSERT INTO `xt_backmoney` VALUES ('83', '16', '50.00', '15', '直营分佣基金', '1497672005', null);
INSERT INTO `xt_backmoney` VALUES ('84', '16', '50.00', '15', '直营分佣基金', '1497672005', null);
INSERT INTO `xt_backmoney` VALUES ('85', '16', '50.00', '15', '直营分佣基金', '1497672006', null);
INSERT INTO `xt_backmoney` VALUES ('86', '16', '50.00', '15', '直营分佣基金', '1497672006', null);
INSERT INTO `xt_backmoney` VALUES ('87', '16', '50.00', '15', '直营分佣基金', '1497672006', null);
INSERT INTO `xt_backmoney` VALUES ('88', '16', '50.00', '15', '直营分佣基金', '1497672007', null);
INSERT INTO `xt_backmoney` VALUES ('89', '16', '50.00', '15', '直营分佣基金', '1497672007', null);
INSERT INTO `xt_backmoney` VALUES ('90', '16', '50.00', '15', '直营分佣基金', '1497672142', null);
INSERT INTO `xt_backmoney` VALUES ('91', '16', '50.00', '15', '直营分佣基金', '1497672159', null);
INSERT INTO `xt_backmoney` VALUES ('92', '16', '50.00', '15', '直营分佣基金', '1497672170', null);
INSERT INTO `xt_backmoney` VALUES ('93', '16', '50.00', '15', '直营分佣基金', '1497672222', null);
INSERT INTO `xt_backmoney` VALUES ('94', '16', '50.00', '15', '直营分佣基金', '1497672224', null);
INSERT INTO `xt_backmoney` VALUES ('95', '16', '50.00', '15', '直营分佣基金', '1497672243', null);
INSERT INTO `xt_backmoney` VALUES ('96', '16', '50.00', '15', '直营分佣基金', '1497672263', null);
INSERT INTO `xt_backmoney` VALUES ('97', '16', '50.00', '15', '直营分佣基金', '1497672264', null);
INSERT INTO `xt_backmoney` VALUES ('98', '16', '50.00', '15', '直营分佣基金', '1497672277', null);
INSERT INTO `xt_backmoney` VALUES ('99', '16', '50.00', '15', '直营分佣基金', '1497672279', null);
INSERT INTO `xt_backmoney` VALUES ('100', '16', '50.00', '15', '直营分佣基金', '1497672300', null);
INSERT INTO `xt_backmoney` VALUES ('101', '16', '50.00', '15', '直营分佣基金', '1497672301', null);
INSERT INTO `xt_backmoney` VALUES ('102', '16', '50.00', '15', '直营分佣基金', '1497672301', null);
INSERT INTO `xt_backmoney` VALUES ('103', '16', '50.00', '15', '直营分佣基金', '1497672311', null);
INSERT INTO `xt_backmoney` VALUES ('105', '16', '50.00', '15', '直营分佣基金', '1497672350', null);
INSERT INTO `xt_backmoney` VALUES ('107', '16', '50.00', '15', '直营分佣基金', '1497672381', null);
INSERT INTO `xt_backmoney` VALUES ('109', '16', '50.00', '15', '直营分佣基金', '1497672388', null);
INSERT INTO `xt_backmoney` VALUES ('111', '16', '50.00', '15', '直营分佣基金', '1497681611', null);
INSERT INTO `xt_backmoney` VALUES ('113', '16', '50.00', '15', '直营分佣基金', '1497681612', null);
INSERT INTO `xt_backmoney` VALUES ('115', '16', '50.00', '15', '直营分佣基金', '1497681620', null);
INSERT INTO `xt_backmoney` VALUES ('117', '16', '50.00', '15', '直营分佣基金', '1497681620', null);
INSERT INTO `xt_backmoney` VALUES ('118', '16', '50.00', '15', '直营分佣基金', '1497681647', null);
INSERT INTO `xt_backmoney` VALUES ('120', '16', '50.00', '15', '直营分佣基金', '1497681699', null);
INSERT INTO `xt_backmoney` VALUES ('122', '16', '50.00', '15', '直营分佣基金', '1497681709', null);
INSERT INTO `xt_backmoney` VALUES ('124', '16', '50.00', '15', '直营分佣基金', '1497682436', null);
INSERT INTO `xt_backmoney` VALUES ('126', '16', '50.00', '15', '直营分佣基金', '1497682464', null);
INSERT INTO `xt_backmoney` VALUES ('128', '16', '50.00', '15', '直营分佣基金', '1497682466', null);
INSERT INTO `xt_backmoney` VALUES ('130', '16', '50.00', '15', '直营分佣基金', '1497682490', null);
INSERT INTO `xt_backmoney` VALUES ('132', '16', '50.00', '15', '直营分佣基金', '1497682555', null);
INSERT INTO `xt_backmoney` VALUES ('134', '16', '50.00', '15', '直营分佣基金', '1497683054', null);
INSERT INTO `xt_backmoney` VALUES ('136', '16', '50.00', '15', '直营分佣基金', '1497684001', null);
INSERT INTO `xt_backmoney` VALUES ('138', '16', '50.00', '15', '直营分佣基金', '1497684042', null);
INSERT INTO `xt_backmoney` VALUES ('140', '16', '50.00', '15', '直营分佣基金', '1497684061', null);
INSERT INTO `xt_backmoney` VALUES ('142', '16', '50.00', '15', '直营分佣基金', '1497875988', null);

-- ----------------------------
-- Table structure for xt_category
-- ----------------------------
DROP TABLE IF EXISTS `xt_category`;
CREATE TABLE `xt_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT NULL COMMENT '类别上级的id,0表示1级分类',
  `icon_img` varchar(255) DEFAULT NULL COMMENT '小图标',
  `name` varchar(255) DEFAULT NULL COMMENT '类别名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `is_service` tinyint(4) DEFAULT NULL COMMENT '1是视频分类(声乐,乐器)2是服务分类(录音棚,MV拍摄)',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='分类: 一级为服务类型分类(录音棚,mv拍摄等),两级分类为(美术-素描,美术-速写)';

-- ----------------------------
-- Records of xt_category
-- ----------------------------
INSERT INTO `xt_category` VALUES ('3', '0', '/Uploads/goods_pic/20170607/ba7bb2d89cd1b0f528dd5358a6457b8f.jpg', '声乐', null, '1', '1496839918', null);
INSERT INTO `xt_category` VALUES ('4', '0', '/Uploads/goods_pic/20170607/9d48f2a996d91cd7c0b1270383de1bd6.jpg', '体育', null, '1', '1496839974', null);
INSERT INTO `xt_category` VALUES ('5', '0', '/Uploads/goods_pic/20170607/ed8652c9d86ec2cc0b6873a38a4c59fc.jpg', '电脑', null, '1', '1496839986', '1497495544');
INSERT INTO `xt_category` VALUES ('6', '3', null, '美声唱法', null, '1', '1496840638', null);
INSERT INTO `xt_category` VALUES ('7', '4', null, '武术', null, '1', '1496841421', null);
INSERT INTO `xt_category` VALUES ('8', '0', null, '录音棚', null, '2', '1496842408', '1497520762');
INSERT INTO `xt_category` VALUES ('9', '4', null, '跑步', null, '1', '1496907790', null);
INSERT INTO `xt_category` VALUES ('10', '4', null, '跳高', null, '1', '1496907806', null);
INSERT INTO `xt_category` VALUES ('11', '5', null, 'php', null, '1', '1496907813', null);
INSERT INTO `xt_category` VALUES ('12', '5', null, 'javascript', null, '1', '1496907820', '1497495638');
INSERT INTO `xt_category` VALUES ('13', '5', null, '前段', null, '1', '1496907828', '1497495584');
INSERT INTO `xt_category` VALUES ('14', '3', null, '海豚音', null, '1', '1496907842', null);
INSERT INTO `xt_category` VALUES ('15', '3', null, '唢呐', null, '1', '1496907851', null);
INSERT INTO `xt_category` VALUES ('16', '0', null, 'mv拍摄', null, '2', '1497487354', null);
INSERT INTO `xt_category` VALUES ('17', '0', null, '吉他', null, '1', '1497495765', null);
INSERT INTO `xt_category` VALUES ('18', '17', null, '小吉他', null, '1', '1497495782', '1497495797');
INSERT INTO `xt_category` VALUES ('19', '0', null, '小视频', null, '2', '1497496765', null);

-- ----------------------------
-- Table structure for xt_children
-- ----------------------------
DROP TABLE IF EXISTS `xt_children`;
CREATE TABLE `xt_children` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL COMMENT '父母id',
  `password` char(32) NOT NULL COMMENT '密码',
  `sex` tinyint(3) DEFAULT NULL COMMENT '0保密1男2女',
  `birthday` datetime DEFAULT NULL COMMENT '生日',
  `grade` varchar(30) DEFAULT NULL COMMENT '年级',
  `interest` varchar(90) DEFAULT NULL COMMENT '兴趣',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xt_children
-- ----------------------------

-- ----------------------------
-- Table structure for xt_collection
-- ----------------------------
DROP TABLE IF EXISTS `xt_collection`;
CREATE TABLE `xt_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u2id` int(11) DEFAULT NULL COMMENT '用户id',
  `courseid` int(11) DEFAULT NULL COMMENT '收藏视频id',
  `online` tinyint(3) DEFAULT NULL COMMENT '1线上视频2线下视频',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表';

-- ----------------------------
-- Records of xt_collection
-- ----------------------------

-- ----------------------------
-- Table structure for xt_course
-- ----------------------------
DROP TABLE IF EXISTS `xt_course`;
CREATE TABLE `xt_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categoryid` int(11) unsigned NOT NULL COMMENT '科目分类id( category 表一级分类)',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '视频所属(0代表平台自己)',
  `title` varchar(255) NOT NULL COMMENT '课程标题',
  `logo` varchar(255) NOT NULL COMMENT '课程前显示图片',
  `description` varchar(255) NOT NULL COMMENT '课程简介',
  `start_time` varchar(25) DEFAULT NULL COMMENT '开课时间',
  `price` decimal(10,2) NOT NULL COMMENT '课程价格(一级分类视频无价格)',
  `discount` decimal(10,2) DEFAULT NULL COMMENT '会员打折',
  `click` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0上架1下架',
  `line` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0线上1线下',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) DEFAULT NULL,
  `limit_price` decimal(10,0) DEFAULT NULL COMMENT '限制额度',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='视频表';

-- ----------------------------
-- Records of xt_course
-- ----------------------------
INSERT INTO `xt_course` VALUES ('1', '123123', '45', '正好', '', '', null, '4000.00', null, '0', '0', '0', '0', null, '1000');
INSERT INTO `xt_course` VALUES ('2', '0', '45', '匹配', '', '', null, '0.00', null, '0', '0', '0', '0', null, null);
INSERT INTO `xt_course` VALUES ('3', '0', '45', 'hhhhhhhhh', '/Uploads/course/20170609/3af9bcd4c25a2f2a2b4dd133c9f0f526.jpg', 'dddhhhhhhhha', '', '74.00', '9.00', '74', '0', '0', '1497004190', '1497008075', null);
INSERT INTO `xt_course` VALUES ('4', '11', '46', 'php是最好的语言', '/Uploads/course/20170609/20c22dadb45f80491bb982370effe88f.jpg', '霍华德和', '', '89.00', '4.00', '41', '0', '0', '1497008251', null, null);

-- ----------------------------
-- Table structure for xt_good
-- ----------------------------
DROP TABLE IF EXISTS `xt_good`;
CREATE TABLE `xt_good` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL COMMENT '品牌',
  `instruments_id` int(11) NOT NULL COMMENT '乐器',
  `material_id` int(11) NOT NULL COMMENT '材质',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `pic` varchar(100) NOT NULL COMMENT '封面',
  `desc` varchar(255) NOT NULL COMMENT '商品描述简介',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `discount` decimal(10,2) DEFAULT NULL COMMENT '会员打折',
  `content` text NOT NULL COMMENT '详情',
  `status` int(11) DEFAULT '0' COMMENT '是否下架 ( 1:下架 0:上线)',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xt_good
-- ----------------------------
INSERT INTO `xt_good` VALUES ('4', '6', '16', '19', '卡马吉他', '/xtwh/Uploads/goods_pic/20170615/68f416039fb46db111a470ad9961dcb4.jpg', '订单', '45.00', '9.00', '<p><img src=\"http://img.baidu.com/hi/jx2/j_0015.gif\"/><img src=\"http://img.baidu.com/hi/jx2/j_0019.gif\"/><img src=\"http://img.baidu.com/hi/jx2/j_0006.gif\"/><img src=\"http://img.baidu.com/hi/jx2/j_0012.gif\"/><img src=\"http://img.baidu.com/hi/jx2/j_0073.gif\"/></p><p><br/></p>', '0', '1496817834', '1497513486');
INSERT INTO `xt_good` VALUES ('5', '6', '16', '19', '卡马吉他', '/xtwh/Uploads/goods_pic/20170615/0d483358646be14488303a90233b22c1.png', '订单', '45.00', '9.00', '<h1 label=\"Title left\" name=\"tl\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;margin:0px 0px 10px;\"><span style=\"color:#e36c09;\" class=\"ue_t\">[此处键入简历标题]</span></h1><p><span style=\"color:#e36c09;\"><br/></span></p><table width=\"100%\" border=\"1\" bordercolor=\"#95B3D7\" style=\"border-collapse:collapse;\"><tbody><tr class=\"firstRow\"><td width=\"200\" style=\"text-align:center;\" class=\"ue_t\">【此处插入照片】</td><td><p><br/></p><p>联系电话：<span class=\"ue_t\">[键入您的电话]</span></p><p><br/></p><p>电子邮件：<span class=\"ue_t\">[键入您的电子邮件地址]</span></p><p><br/></p><p>家庭住址：<span class=\"ue_t\">[键入您的地址]</span></p><p><br/></p></td></tr></tbody></table><h3><span style=\"color:#e36c09;font-size:20px;\">目标职位</span></h3><p style=\"text-indent:2em;\" class=\"ue_t\">[此处键入您的期望职位]</p><h3><span style=\"color:#e36c09;font-size:20px;\">学历</span></h3><p><br/></p><ol style=\"list-style-type: decimal;\" class=\" list-paddingleft-2\"><li><p><span class=\"ue_t\">[键入起止时间]</span> <span class=\"ue_t\">[键入学校名称] </span> <span class=\"ue_t\">[键入所学专业]</span> <span class=\"ue_t\">[键入所获学位]</span></p></li><li><p><span class=\"ue_t\">[键入起止时间]</span> <span class=\"ue_t\">[键入学校名称]</span> <span class=\"ue_t\">[键入所学专业]</span> <span class=\"ue_t\">[键入所获学位]</span></p></li></ol><h3><span style=\"color:#e36c09;font-size:20px;\" class=\"ue_t\">工作经验</span></h3><ol style=\"list-style-type: decimal;\" class=\" list-paddingleft-2\"><li><p><span class=\"ue_t\">[键入起止时间]</span> <span class=\"ue_t\">[键入公司名称]</span> <span class=\"ue_t\">[键入职位名称]</span></p></li><ol style=\"list-style-type: lower-alpha;\" class=\" list-paddingleft-2\"><li><p><span class=\"ue_t\">[键入负责项目]</span> <span class=\"ue_t\">[键入项目简介]</span></p></li><li><p><span class=\"ue_t\">[键入负责项目]</span> <span class=\"ue_t\">[键入项目简介]</span></p></li></ol><li><p><span class=\"ue_t\">[键入起止时间]</span> <span class=\"ue_t\">[键入公司名称]</span> <span class=\"ue_t\">[键入职位名称]</span></p></li><ol style=\"list-style-type: lower-alpha;\" class=\" list-paddingleft-2\"><li><p><span class=\"ue_t\">[键入负责项目]</span> <span class=\"ue_t\">[键入项目简介]</span></p></li></ol></ol><p><span style=\"color:#e36c09;font-size:20px;\">掌握技能</span></p><p style=\"text-indent:2em;\">&nbsp;<span class=\"ue_t\">[这里可以键入您所掌握的技能]</span><br/></p><p><br/></p>', '1', '1496817834', '1497520859');

-- ----------------------------
-- Table structure for xt_goodtype
-- ----------------------------
DROP TABLE IF EXISTS `xt_goodtype`;
CREATE TABLE `xt_goodtype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '类别名称',
  `type` tinyint(4) NOT NULL COMMENT '1品牌2,乐器，3材质',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='商品类别 --- 待开发';

-- ----------------------------
-- Records of xt_goodtype
-- ----------------------------
INSERT INTO `xt_goodtype` VALUES ('6', '雅马哈', '1', '1496747511', null);
INSERT INTO `xt_goodtype` VALUES ('8', '星海', '1', '1496747511', null);
INSERT INTO `xt_goodtype` VALUES ('14', '英伦', '1', '1496801730', null);
INSERT INTO `xt_goodtype` VALUES ('15', '大提琴', '2', '1496801744', null);
INSERT INTO `xt_goodtype` VALUES ('16', '小提琴', '2', '1496801754', null);
INSERT INTO `xt_goodtype` VALUES ('17', '钢琴', '2', '1496801764', null);
INSERT INTO `xt_goodtype` VALUES ('18', '二胡', '2', '1496801779', null);
INSERT INTO `xt_goodtype` VALUES ('19', '木质', '3', '1496801821', null);
INSERT INTO `xt_goodtype` VALUES ('20', '拉丝', '3', '1496801830', null);
INSERT INTO `xt_goodtype` VALUES ('21', '烤漆', '3', '1496801838', null);
INSERT INTO `xt_goodtype` VALUES ('22', '阿西吧', '1', '1496826002', null);
INSERT INTO `xt_goodtype` VALUES ('23', '3611', '1', '1497497864', '1497497871');

-- ----------------------------
-- Table structure for xt_lunbo
-- ----------------------------
DROP TABLE IF EXISTS `xt_lunbo`;
CREATE TABLE `xt_lunbo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `img` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `createtime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='轮播图表';

-- ----------------------------
-- Records of xt_lunbo
-- ----------------------------
INSERT INTO `xt_lunbo` VALUES ('2', '/xtwh/Uploads/2017-06-09/593a5ac7806d0.jpg', null, '1496996552');
INSERT INTO `xt_lunbo` VALUES ('3', '/xtwh/Uploads/2017-06-09/593a5ace1c6b9.jpg', null, '1496996558');
INSERT INTO `xt_lunbo` VALUES ('4', '/xtwh/Uploads/2017-06-09/593a5ad534337.jpg', null, '1496996565');
INSERT INTO `xt_lunbo` VALUES ('5', '/xtwh/Uploads/2017-06-09/593a5adb148b6.jpg', null, '1496996573');

-- ----------------------------
-- Table structure for xt_order
-- ----------------------------
DROP TABLE IF EXISTS `xt_order`;
CREATE TABLE `xt_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordercode` varchar(255) NOT NULL COMMENT '订单编码',
  `u2id` int(11) NOT NULL COMMENT 'user2表用户id',
  `real_money` decimal(10,0) DEFAULT NULL COMMENT '实付金额',
  `money` decimal(10,2) DEFAULT NULL COMMENT '订单金额(例子: 充值为100 , 支出为-100)',
  `status` int(11) DEFAULT NULL COMMENT '(微信,支付宝支付使用字段) -- 1:待支付,2:已支付,3:待提现,4:已提现,5:驳回',
  `message` varchar(255) DEFAULT NULL COMMENT '充值余额,购买课程 ,够买乐器 ,基金提现 ,好友互转转出 ,好友互转转入',
  `paytype` varchar(255) DEFAULT NULL COMMENT '支付方式 --(支付宝、微信、余额)',
  `payee_man` varchar(30) DEFAULT '' COMMENT '收款人',
  `payee_account` varchar(20) DEFAULT '' COMMENT '收款账号',
  `courseid` int(30) DEFAULT NULL COMMENT '课程id',
  `addressid` int(30) DEFAULT NULL COMMENT '收货地址id',
  `goodprice` decimal(10,2) DEFAULT NULL COMMENT '商品/课程价格',
  `goodid` int(30) DEFAULT NULL COMMENT '商城商品id',
  `create_at` int(10) unsigned DEFAULT '0' COMMENT '生成订单时间',
  `update_at` int(10) unsigned DEFAULT '0' COMMENT '修改订单时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='消费,充值生成订单表';

-- ----------------------------
-- Records of xt_order
-- ----------------------------
INSERT INTO `xt_order` VALUES ('15', 'G608905744145666', '1', null, '-0.10', '4', '基金提现', '支付宝', '纪春雷', '18937036101', null, null, null, null, '1497339934', '1497853976');
INSERT INTO `xt_order` VALUES ('16', 'G608905744115839', '1', null, '-95.00', '3', '基金提现', '微信', '张三', '464655656', null, null, null, null, '1497840361', '1497339923');
INSERT INTO `xt_order` VALUES ('17', 'G608905744145678', '15', null, null, '1', '购买课程', '余额', '', '', '1', '1', '4000.00', null, '1496890574', '1497684062');
INSERT INTO `xt_order` VALUES ('18', 'G608905744125829', '13', null, null, '2', '购买课程', '微信', '', '', '2', '1', '550.00', null, '1496890574', '0');
INSERT INTO `xt_order` VALUES ('22', 'G619420757745205', '16', null, '-95.00', '5', '基金提现', '支付宝', '小白', '123456', null, null, null, null, null, '0');
INSERT INTO `xt_order` VALUES ('23', 'G619420757745206', '1', null, '1000.00', '1', '充值余额', '支付宝', '', '', null, null, null, null, '1497861183', '0');
INSERT INTO `xt_order` VALUES ('24', 'G619420757745207', '1', null, '1050.00', '2', '充值余额', '支付宝', '', '', null, null, null, null, '1497861183', '0');
INSERT INTO `xt_order` VALUES ('25', 'G619420757745208', '1', null, '1000.00', '2', '充值余额', '微信', '', '', null, null, null, null, '1497861183', '0');

-- ----------------------------
-- Table structure for xt_user1
-- ----------------------------
DROP TABLE IF EXISTS `xt_user1`;
CREATE TABLE `xt_user1` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '教师所属机构id',
  `logo` varchar(255) NOT NULL COMMENT '封面',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `detail` text NOT NULL COMMENT '介绍',
  `teacherage` varchar(255) DEFAULT '' COMMENT '教龄',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `level` varchar(255) DEFAULT NULL COMMENT '1颗星2颗星3颗星',
  `motto` varchar(255) DEFAULT NULL COMMENT '座右铭',
  `content` varchar(255) DEFAULT NULL COMMENT '教学内容',
  `categoryid` varchar(255) DEFAULT NULL COMMENT '分类ID ( category 表)',
  `rebate` varchar(255) DEFAULT NULL COMMENT '合作折扣(单位%)',
  `province` varchar(255) DEFAULT NULL COMMENT '省份',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区/县',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `class` int(11) unsigned DEFAULT '1' COMMENT '(1-老师,2-合作机构) --老师/合作机构',
  `major` varchar(255) DEFAULT NULL COMMENT '主营类型(音乐,街舞等)',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COMMENT='老师/合作机构--用户表';

-- ----------------------------
-- Records of xt_user1
-- ----------------------------
INSERT INTO `xt_user1` VALUES ('45', '李木子', '0', '/xtwh/Uploads/jigou_pic/20170613/ca981538eba2bb5964ea93e406bc5bf7.jpg', '13271578969', '哈哈哈哈', '1', null, '2', '牛逼的老师', null, null, '50.2', '河南省', '郑州市', '中牟县', '呵呵哈哈哈或', null, '1', null, '1496977310', '1497344446');
INSERT INTO `xt_user1` VALUES ('46', '金帮手', '0', '/xtwh/Uploads/jigou_pic/20170613/58ed95aacb9eac81536e764440e57263.jpg', '13271578947', '哈哈哈', '', null, null, null, null, null, '80', '河南省', '郑州市', '二七区', '哈哈哈', null, '2', null, '1496977804', '1497344965');
INSERT INTO `xt_user1` VALUES ('47', '小王', '0', '/xtwh/Uploads/jigou_pic/20170613/bc122f413b3cbfe39551f98afc61737b.png', '13271578945', '哈哈哈', '4', null, '2', '是是是教师教师', null, null, null, '河南省', '济源市', '济源市', '大大大', null, null, null, '1496977859', '1497336079');
INSERT INTO `xt_user1` VALUES ('48', '测试', '46', '/xtwh/Uploads/jigou_pic/20170613/87f9f87aa4a9d1cb37ee4b6fa48d7033.jpg', '18937036110', '我是个老师,我厉害了', '2', null, '2', '什么都是浮云', null, null, null, '山东省', '东营市', '垦利县', '黄河大道', null, '1', null, '1497336501', '1497338971');
INSERT INTO `xt_user1` VALUES ('49', '测试1', '46', '/xtwh/Uploads/jigou_pic/20170613/f14d86c2431048f4af83fa6a76b97231.jpg', '18937036111', '老师很懒,什么都没有留下,厉害了', '2', null, '4', '学习才是王道', null, null, null, '安徽省', '蚌埠市', '固镇县', '101号', null, '1', null, '1497337042', '1497339042');
INSERT INTO `xt_user1` VALUES ('50', '测试3', '46', '/xtwh/Uploads/jigou_pic/20170613/438ef4cc54d7e4ca7f3083eef4571e20.jpg', '18937036222', '老师很懒,什么都没有留下', '4', null, '2', '好好学习,天天向上', null, null, null, '河南省', '济源市', '济源市', '门牌号212号', null, '1', null, '1497337239', '1497338096');
INSERT INTO `xt_user1` VALUES ('51', '阿里巴巴', '0', '/xtwh/Uploads/jigou_pic/20170613/64759f7d602cf2ef5adf2ac5736a361e.jpg', '18937036123', '这个机构很懒,什么都没有留下', '', null, null, null, null, null, '70', '河南省', '驻马店市', '驿城区', '门前101号', null, '2', null, '1497337498', '1497344971');
INSERT INTO `xt_user1` VALUES ('52', '测试4', '51', '/xtwh/Uploads/jigou_pic/20170613/ccfe249767f52c8b1438c697c3282557.jpg', '18937036333', '老师很懒,什么都没有留下', '2', null, '2', '好好学习天天向上', null, null, null, '安徽省', '蚌埠市', '淮上区', '门前10号', null, '1', null, '1497337583', '1497338012');
INSERT INTO `xt_user1` VALUES ('53', '测试老师', '0', '/xtwh/Uploads/jigou_pic/20170613/994eee8b4bb30dbd3298a92a46c0085a.jpg', '18937036444', '这个人很烂,没有留下什么', '2', null, '3', '好好学习天天向上', null, null, '48.2', '澳门', '澳门', '市区', '110门牌号', null, '1', null, '1497344491', '1497344518');
INSERT INTO `xt_user1` VALUES ('54', '测试5', '0', '/xtwh/Uploads/jigou_pic/20170613/662e35633162d30f0549f274b8686e9a.jpg', '18937036555', '这个人很懒,什么都没有留下', '', null, null, null, null, null, '38.5', '河南省', '新乡市', '卫辉市', '110门牌号', null, '2', null, '1497344904', '1497344979');
INSERT INTO `xt_user1` VALUES ('55', '测试', '0', '/xtwh/Uploads/jigou_pic/20170614/c76d123e10fcdc990817916393ecd2b6.jpg', '18937036777', '老师很懒', '2', null, '5', '好好学习天天向上', null, null, '54', '澳门', '澳门', '市区', '110牌', null, '1', null, '1497438976', null);
INSERT INTO `xt_user1` VALUES ('56', '贾建超', '0', '/xtwh/Uploads/jigou_pic/20170614/4e6c9dfd1b39f1fc1d740d6956fc1d52.jpg', '18937036888', '这个人很懒.', '3', null, '4', '好好学习天天向上', null, null, '99', '江苏省', '泰州市', '高港区', '110牌', null, '1', null, '1497439110', null);
INSERT INTO `xt_user1` VALUES ('57', '腾讯', '0', '/xtwh/Uploads/jigou_pic/20170614/39caf56047cdeb00b5e2a4e6da209572.jpg', '18937036999', '这个机构很懒', '', null, null, null, null, null, '77', '江苏省', '无锡市', '北塘区', '110门牌', null, '2', null, '1497440075', null);

-- ----------------------------
-- Table structure for xt_user2
-- ----------------------------
DROP TABLE IF EXISTS `xt_user2`;
CREATE TABLE `xt_user2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `refereeid` int(11) DEFAULT '0' COMMENT '推荐人id(0为平台注册)',
  `u1id` int(10) unsigned DEFAULT NULL COMMENT '和user1表绑定的用户id',
  `name` varchar(255) DEFAULT NULL COMMENT '真实姓名(学生为父母的真实姓名)',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称（或者机构名称）',
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `password` varchar(255) DEFAULT NULL COMMENT '登录密码',
  `twopassword` varchar(255) DEFAULT NULL COMMENT '二级密码',
  `class` tinyint(7) NOT NULL DEFAULT '0' COMMENT '身份(3教师,2机构,1家长,0成人)',
  `grade` tinyint(7) NOT NULL DEFAULT '0' COMMENT '等级(0路人甲,1-vip,2-VIP银卡,3-VIP金卡,4-VIP钻石,5-合伙人)',
  `onemoney` decimal(10,2) DEFAULT NULL COMMENT '直营余额--预存余额1',
  `rebate_money` decimal(10,2) DEFAULT NULL COMMENT '参与1级返佣的钱(累计)',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0未激活1激活',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `province` varchar(255) NOT NULL COMMENT '省份',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区/县',
  `headimg` varchar(255) DEFAULT NULL COMMENT '头像',
  `source` tinyint(5) NOT NULL DEFAULT '0' COMMENT '0前台注册,1后台添加',
  `sex` varchar(255) DEFAULT NULL COMMENT '性别( 1为男 , 0为女)',
  `birthday` varchar(255) DEFAULT NULL COMMENT '生日',
  `create_at` int(11) DEFAULT NULL COMMENT '创建用户时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='机构,家长,成人-用户表';

-- ----------------------------
-- Records of xt_user2
-- ----------------------------
INSERT INTO `xt_user2` VALUES ('1', '0', null, '纪春雷', null, '18937036101', 'dc483e80a7a0bd9ef71d8cf973673924', 'dc483e80a7a0bd9ef71d8cf973673924', '0', '0', null, null, '0', null, '', null, null, null, '0', null, null, null, null);
INSERT INTO `xt_user2` VALUES ('2', '0', null, '第三方', null, '18937036110', 'dc483e80a7a0bd9ef71d8cf973673924', 'dc483e80a7a0bd9ef71d8cf973673924', '0', '0', null, null, '0', null, '', null, null, null, '0', null, null, null, null);
INSERT INTO `xt_user2` VALUES ('14', '15', '55', null, '测试', '18937036777', 'dc483e80a7a0bd9ef71d8cf973673924', 'dc483e80a7a0bd9ef71d8cf973673924', '3', '2', null, '1800.00', '0', '110牌', '澳门', '澳门', '市区', '/xtwh/Uploads/jigou_pic/20170614/c76d123e10fcdc990817916393ecd2b6.jpg', '1', null, null, '1497438976', null);
INSERT INTO `xt_user2` VALUES ('15', '16', '56', null, '贾建超', '18937036888', 'dc483e80a7a0bd9ef71d8cf973673924', 'dc483e80a7a0bd9ef71d8cf973673924', '3', '3', '3000.00', '3000.00', '0', '110牌', '江苏省', '泰州市', '高港区', '/xtwh/Uploads/jigou_pic/20170614/4e6c9dfd1b39f1fc1d740d6956fc1d52.jpg', '1', null, null, '1497439110', '1497684062');
INSERT INTO `xt_user2` VALUES ('16', '18', '57', null, '腾讯', '18937036999', 'dc483e80a7a0bd9ef71d8cf973673924', 'dc483e80a7a0bd9ef71d8cf973673924', '2', '2', null, null, '0', '110门牌', '江苏省', '无锡市', '北塘区', '/xtwh/Uploads/jigou_pic/20170614/39caf56047cdeb00b5e2a4e6da209572.jpg', '0', null, null, '1497440075', null);
INSERT INTO `xt_user2` VALUES ('17', '18', null, null, '亚威', null, null, null, '0', '3', null, null, '0', null, '', null, null, null, '0', null, null, null, null);

-- ----------------------------
-- Table structure for xt_usercate
-- ----------------------------
DROP TABLE IF EXISTS `xt_usercate`;
CREATE TABLE `xt_usercate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1_id` int(11) DEFAULT NULL,
  `categoryid` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='机构/老师 和 分类关联表';

-- ----------------------------
-- Records of xt_usercate
-- ----------------------------
INSERT INTO `xt_usercate` VALUES ('7', '56', '6');
INSERT INTO `xt_usercate` VALUES ('8', '56', '7');
INSERT INTO `xt_usercate` VALUES ('9', '56', '9');
INSERT INTO `xt_usercate` VALUES ('10', '56', '10');
INSERT INTO `xt_usercate` VALUES ('11', '57', '6');
INSERT INTO `xt_usercate` VALUES ('12', '57', '14');
INSERT INTO `xt_usercate` VALUES ('13', '57', '15');

-- ----------------------------
-- Table structure for xt_video
-- ----------------------------
DROP TABLE IF EXISTS `xt_video`;
CREATE TABLE `xt_video` (
  `id` varchar(255) NOT NULL COMMENT '视频id',
  `name` varchar(255) DEFAULT NULL COMMENT '视频标题',
  `rtmpurl` varchar(255) DEFAULT NULL COMMENT '视频地址',
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='直播列表';

-- ----------------------------
-- Records of xt_video
-- ----------------------------

-- ----------------------------
-- Event structure for 查询商品
-- ----------------------------
DROP EVENT IF EXISTS `查询商品`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` EVENT `查询商品` ON SCHEDULE AT '2017-06-12 16:34:23' ON COMPLETION NOT PRESERVE ENABLE DO SELECT * FROM good
;;
DELIMITER ;
