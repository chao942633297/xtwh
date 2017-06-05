/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : xtwh

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2017-06-05 15:36:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xt_address
-- ----------------------------
DROP TABLE IF EXISTS `xt_address`;
CREATE TABLE `xt_address` (
  `id` int(11) unsigned NOT NULL,
  `u2id` int(11) unsigned NOT NULL COMMENT '用户id(user2表)',
  `address` varchar(255) DEFAULT NULL COMMENT '省市区地址',
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `name` varchar(255) DEFAULT NULL COMMENT '收货人姓名',
  `detail` varchar(255) DEFAULT NULL COMMENT '地址详情',
  `youbian` varchar(255) DEFAULT NULL COMMENT '邮政编码',
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
  `createtime` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xt_admin
-- ----------------------------
INSERT INTO `xt_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', null, null);

-- ----------------------------
-- Table structure for xt_backmoney
-- ----------------------------
DROP TABLE IF EXISTS `xt_backmoney`;
CREATE TABLE `xt_backmoney` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `u2id` int(11) unsigned NOT NULL COMMENT 'user2表中的id ',
  `money` decimal(10,2) NOT NULL COMMENT '返佣金额',
  `source` int(11) NOT NULL COMMENT '来源id (user2 表)',
  `message` varchar(255) DEFAULT NULL COMMENT '信息(直营消费返佣,非直营消费返佣)',
  `createtime` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基金--返佣记录表';

-- ----------------------------
-- Records of xt_backmoney
-- ----------------------------

-- ----------------------------
-- Table structure for xt_category
-- ----------------------------
DROP TABLE IF EXISTS `xt_category`;
CREATE TABLE `xt_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT NULL COMMENT '类别上级的id',
  `name` varchar(255) DEFAULT NULL COMMENT '类别名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类: 一级为服务类型分类(录音棚,mv拍摄等),两级分类为(美术-素描,美术-速写)';

-- ----------------------------
-- Records of xt_category
-- ----------------------------

-- ----------------------------
-- Table structure for xt_course
-- ----------------------------
DROP TABLE IF EXISTS `xt_course`;
CREATE TABLE `xt_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categoryid` int(11) DEFAULT NULL COMMENT '分类id( category 表一级分类)',
  `user1id` int(11) unsigned DEFAULT NULL COMMENT '老师/合作机构 (user1 表)',
  `title` varchar(255) DEFAULT NULL COMMENT '视频标题',
  `luzhi` varchar(255) DEFAULT NULL COMMENT '录制人或者录制棚名称',
  `looknum` int(11) DEFAULT NULL COMMENT '点击量',
  `logo` varchar(255) DEFAULT NULL COMMENT '视频播放前显示图片',
  `video` varchar(255) DEFAULT NULL COMMENT '视频链接地址',
  `description` varchar(255) DEFAULT NULL COMMENT '视频简介',
  `createtime` varchar(255) DEFAULT NULL COMMENT '上传时间',
  `price` decimal(10,2) DEFAULT NULL COMMENT '视频价格(一级分类视频无价格)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频表';

-- ----------------------------
-- Records of xt_course
-- ----------------------------

-- ----------------------------
-- Table structure for xt_good
-- ----------------------------
DROP TABLE IF EXISTS `xt_good`;
CREATE TABLE `xt_good` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `desc` varchar(255) NOT NULL COMMENT '商品描述简介',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `content` text NOT NULL COMMENT '详情',
  `typeid` int(11) NOT NULL COMMENT '商品类别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xt_good
-- ----------------------------

-- ----------------------------
-- Table structure for xt_goodtype
-- ----------------------------
DROP TABLE IF EXISTS `xt_goodtype`;
CREATE TABLE `xt_goodtype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pinpai` varchar(255) DEFAULT NULL COMMENT '商品品牌',
  `zhognlei` varchar(255) DEFAULT NULL COMMENT '商品类别(吉他,钢琴等)',
  `cailiao` varchar(255) DEFAULT NULL COMMENT '商品材料',
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品类别 --- 待开发';

-- ----------------------------
-- Records of xt_goodtype
-- ----------------------------

-- ----------------------------
-- Table structure for xt_order
-- ----------------------------
DROP TABLE IF EXISTS `xt_order`;
CREATE TABLE `xt_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordercode` varchar(255) NOT NULL COMMENT '订单编码',
  `u2id` int(11) NOT NULL COMMENT 'user2表用户id',
  `money` decimal(10,2) DEFAULT NULL COMMENT '订单金额(例子: 充值为100 , 支出为-100)',
  `status` int(11) DEFAULT NULL COMMENT '(微信,支付宝支付使用字段) -- 1:待支付,2:已支付,3:待提现,4:已提现',
  `message` varchar(255) DEFAULT NULL COMMENT '直营余额充值 ,非直营余额充值 ,购买课程 ,够买乐器 ,基金提现 ,好友互转转出 ,好友互转转入',
  `paytype` varchar(255) DEFAULT NULL COMMENT '支付方式 --(支付宝、微信端微信、APP端微信、余额支付)',
  `createtime` varchar(255) DEFAULT NULL COMMENT '生成订单时间',
  `updatetime` varchar(255) DEFAULT NULL COMMENT '修改订单时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消费,充值生成订单表';

-- ----------------------------
-- Records of xt_order
-- ----------------------------

-- ----------------------------
-- Table structure for xt_user1
-- ----------------------------
DROP TABLE IF EXISTS `xt_user1`;
CREATE TABLE `xt_user1` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `loginname` varchar(255) DEFAULT NULL COMMENT '登录账号',
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `password` varchar(255) DEFAULT NULL COMMENT '登录密码',
  `teacherage` varchar(255) DEFAULT '' COMMENT '教龄',
  `title` varchar(255) DEFAULT NULL COMMENT '机构名称',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo图片',
  `detail` text COMMENT '详情',
  `teacher` varchar(255) DEFAULT NULL COMMENT '老师',
  `course` varchar(255) DEFAULT NULL COMMENT '课程(课程id -- 1,2,3  )',
  `categoryid` varchar(255) DEFAULT NULL COMMENT '分类ID ( category 表)',
  `rebate` varchar(255) DEFAULT NULL COMMENT '合作折扣',
  `province` varchar(255) DEFAULT NULL COMMENT '省份',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区/县',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `class` int(11) unsigned DEFAULT '1' COMMENT '(1-老师,2-合作机构) --老师/合作机构',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='老师/合作机构--用户表';

-- ----------------------------
-- Records of xt_user1
-- ----------------------------

-- ----------------------------
-- Table structure for xt_user2
-- ----------------------------
DROP TABLE IF EXISTS `xt_user2`;
CREATE TABLE `xt_user2` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT '上级id',
  `childrenid` int(11) unsigned DEFAULT NULL COMMENT '小孩id',
  `name` varchar(255) DEFAULT NULL COMMENT '真实姓名(学生为父母的真实姓名)',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号(学生为父母的手机号)',
  `password` varchar(255) DEFAULT NULL COMMENT '登录密码',
  `twopassword` varchar(255) DEFAULT NULL COMMENT '二级密码',
  `class` varchar(255) DEFAULT NULL COMMENT '身份(机构,家长,成人,学生)-学生为家长的小孩账号',
  `grade` int(11) unsigned DEFAULT '1' COMMENT '会员等级(1-路人甲,2-vip,3-VIP银卡,4-VIP金卡,5-VIP钻石,6-合伙人)',
  `onemoney` decimal(10,2) DEFAULT NULL COMMENT '直营余额--预存余额1',
  `twomoney` decimal(10,2) DEFAULT NULL COMMENT '非直营余额--预存余额2',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `province` varchar(255) DEFAULT NULL COMMENT '省份',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区/县',
  `headimg` varchar(255) DEFAULT NULL COMMENT '头像',
  `sex` varchar(255) DEFAULT NULL COMMENT '性别( 1为男 , 0为女)',
  `birthday` varchar(255) DEFAULT NULL COMMENT '生日',
  `interest` varchar(255) DEFAULT NULL COMMENT '兴趣',
  `motto` varchar(255) DEFAULT NULL COMMENT '座右铭',
  `createtime` varchar(255) DEFAULT NULL COMMENT '创建用户时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='机构,家长,成人-用户表';

-- ----------------------------
-- Records of xt_user2
-- ----------------------------
