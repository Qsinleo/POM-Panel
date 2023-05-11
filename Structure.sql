-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2023-05-08 19:03:02
-- 服务器版本： 5.7.40-log
-- PHP 版本： 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `StateGridpost`
--

-- --------------------------------------------------------

--
-- 表的结构 `data_analyze`
--

CREATE TABLE `data_analyze` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `level` int(1) NOT NULL DEFAULT '1' COMMENT '评级',
  `excomm` tinytext COMMENT '额外评论'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='帖子评级';

-- --------------------------------------------------------

--
-- 表的结构 `data_count`
--

CREATE TABLE `data_count` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `from_device` varchar(20) DEFAULT NULL COMMENT '来自的设备',
  `likenum` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞数量',
  `commentnum` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数量',
  `repostnum` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '转发数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='帖子数据';

-- --------------------------------------------------------

--
-- 表的结构 `data_main`
--

CREATE TABLE `data_main` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `avator` varchar(200) DEFAULT NULL COMMENT '头像路径',
  `nickname` varchar(20) DEFAULT NULL COMMENT '名称',
  `postcont` text COMMENT '内容',
  `posttime` datetime DEFAULT NULL COMMENT '发布时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='帖子内容';

-- --------------------------------------------------------

--
-- 表的结构 `data_pic`
--

CREATE TABLE `data_pic` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `picpath` varchar(50) DEFAULT NULL COMMENT '图片路径'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='帖子图片存储';

-- --------------------------------------------------------

--
-- 表的结构 `data_raw`
--

CREATE TABLE `data_raw` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `raws` mediumtext COMMENT '生的数据'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='未处理数据';

--
-- 转储表的索引
--

--
-- 表的索引 `data_analyze`
--
ALTER TABLE `data_analyze`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `data_count`
--
ALTER TABLE `data_count`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `data_main`
--
ALTER TABLE `data_main`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nickname` (`nickname`);
ALTER TABLE `data_main` ADD FULLTEXT KEY `postcont` (`postcont`);

--
-- 表的索引 `data_pic`
--
ALTER TABLE `data_pic`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `data_raw`
--
ALTER TABLE `data_raw`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `data_analyze`
--
ALTER TABLE `data_analyze`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `data_count`
--
ALTER TABLE `data_count`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `data_main`
--
ALTER TABLE `data_main`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `data_pic`
--
ALTER TABLE `data_pic`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `data_raw`
--
ALTER TABLE `data_raw`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 限制导出的表
--

--
-- 限制表 `data_analyze`
--
ALTER TABLE `data_analyze`
  ADD CONSTRAINT `data_analyze_ibfk_1` FOREIGN KEY (`id`) REFERENCES `data_main` (`id`);

--
-- 限制表 `data_count`
--
ALTER TABLE `data_count`
  ADD CONSTRAINT `data_count_ibfk_1` FOREIGN KEY (`id`) REFERENCES `data_main` (`id`);

--
-- 限制表 `data_raw`
--
ALTER TABLE `data_raw`
  ADD CONSTRAINT `data_raw_ibfk_1` FOREIGN KEY (`id`) REFERENCES `data_main` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
