/*
SQLyog Ultimate v11.25 (64 bit)
MySQL - 5.5.16-log : Database - wdt_my_com
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`wdt_my_com` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `wdt_my_com`;

/*Table structure for table `gf_category` */

DROP TABLE IF EXISTS `gf_category`;

CREATE TABLE `gf_category` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `mid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `upid` int(10) NOT NULL DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `alias` char(50) NOT NULL DEFAULT '',
  `intro` char(255) NOT NULL DEFAULT '',
  `cate_tpl` char(80) NOT NULL DEFAULT '',
  `show_tpl` char(80) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `orderby` smallint(5) NOT NULL DEFAULT '0',
  `seo_title` char(80) NOT NULL DEFAULT '',
  `seo_keywords` char(80) NOT NULL DEFAULT '',
  `seo_description` char(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_category` */

/*Table structure for table `gf_cms_article` */

DROP TABLE IF EXISTS `gf_cms_article`;

CREATE TABLE `gf_cms_article` (
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(80) NOT NULL DEFAULT '',
  `color` char(6) NOT NULL DEFAULT '',
  `alias` char(50) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `intro` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(150) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` int(10) NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `imagenum` int(10) unsigned NOT NULL DEFAULT '0',
  `filenum` int(10) unsigned NOT NULL DEFAULT '0',
  `flags` varchar(20) NOT NULL DEFAULT '',
  `seo_title` varchar(80) NOT NULL DEFAULT '',
  `seo_keywords` varchar(80) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cid_id` (`cid`,`id`),
  KEY `cid_dateline` (`cid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article` */

/*Table structure for table `gf_cms_article_attach` */

DROP TABLE IF EXISTS `gf_cms_article_attach`;

CREATE TABLE `gf_cms_article_attach` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` char(80) NOT NULL DEFAULT '',
  `filetype` char(10) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `filepath` char(150) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `downloads` int(10) unsigned NOT NULL DEFAULT '0',
  `isimage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `id` (`id`,`aid`),
  KEY `uid` (`uid`,`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_attach` */

/*Table structure for table `gf_cms_article_comment` */

DROP TABLE IF EXISTS `gf_cms_article_comment`;

CREATE TABLE `gf_cms_article_comment` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `commentid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` char(30) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ip` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`),
  KEY `id` (`id`,`commentid`),
  KEY `ip` (`ip`,`commentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_comment` */

/*Table structure for table `gf_cms_article_comment_sort` */

DROP TABLE IF EXISTS `gf_cms_article_comment_sort`;

CREATE TABLE `gf_cms_article_comment_sort` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid_comments` (`cid`,`comments`),
  KEY `comments` (`comments`),
  KEY `cid_lastdate` (`cid`,`lastdate`),
  KEY `lastdate` (`lastdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_comment_sort` */

/*Table structure for table `gf_cms_article_data` */

DROP TABLE IF EXISTS `gf_cms_article_data`;

CREATE TABLE `gf_cms_article_data` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_data` */

/*Table structure for table `gf_cms_article_flag` */

DROP TABLE IF EXISTS `gf_cms_article_flag`;

CREATE TABLE `gf_cms_article_flag` (
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`flag`,`id`),
  KEY `flag_cid` (`flag`,`cid`,`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_flag` */

/*Table structure for table `gf_cms_article_tag` */

DROP TABLE IF EXISTS `gf_cms_article_tag`;

CREATE TABLE `gf_cms_article_tag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(10) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`tagid`),
  UNIQUE KEY `name` (`name`),
  KEY `count` (`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_tag` */

/*Table structure for table `gf_cms_article_tag_data` */

DROP TABLE IF EXISTS `gf_cms_article_tag_data`;

CREATE TABLE `gf_cms_article_tag_data` (
  `tagid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_tag_data` */

/*Table structure for table `gf_cms_article_views` */

DROP TABLE IF EXISTS `gf_cms_article_views`;

CREATE TABLE `gf_cms_article_views` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`views`),
  KEY `views` (`views`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_article_views` */

/*Table structure for table `gf_cms_page` */

DROP TABLE IF EXISTS `gf_cms_page`;

CREATE TABLE `gf_cms_page` (
  `cid` smallint(5) unsigned NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_page` */

/*Table structure for table `gf_cms_photo` */

DROP TABLE IF EXISTS `gf_cms_photo`;

CREATE TABLE `gf_cms_photo` (
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(80) NOT NULL DEFAULT '',
  `color` char(6) NOT NULL DEFAULT '',
  `alias` char(50) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `intro` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(150) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` int(10) NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `imagenum` int(10) unsigned NOT NULL DEFAULT '0',
  `filenum` int(10) unsigned NOT NULL DEFAULT '0',
  `flags` varchar(20) NOT NULL DEFAULT '',
  `seo_title` varchar(80) NOT NULL DEFAULT '',
  `seo_keywords` varchar(80) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cid_id` (`cid`,`id`),
  KEY `cid_dateline` (`cid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo` */

/*Table structure for table `gf_cms_photo_attach` */

DROP TABLE IF EXISTS `gf_cms_photo_attach`;

CREATE TABLE `gf_cms_photo_attach` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` char(80) NOT NULL DEFAULT '',
  `filetype` char(10) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `filepath` char(150) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `downloads` int(10) unsigned NOT NULL DEFAULT '0',
  `isimage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `id` (`id`,`aid`),
  KEY `uid` (`uid`,`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_attach` */

/*Table structure for table `gf_cms_photo_comment` */

DROP TABLE IF EXISTS `gf_cms_photo_comment`;

CREATE TABLE `gf_cms_photo_comment` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `commentid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` char(30) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ip` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`),
  KEY `id` (`id`,`commentid`),
  KEY `ip` (`ip`,`commentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_comment` */

/*Table structure for table `gf_cms_photo_comment_sort` */

DROP TABLE IF EXISTS `gf_cms_photo_comment_sort`;

CREATE TABLE `gf_cms_photo_comment_sort` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid_comments` (`cid`,`comments`),
  KEY `comments` (`comments`),
  KEY `cid_lastdate` (`cid`,`lastdate`),
  KEY `lastdate` (`lastdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_comment_sort` */

/*Table structure for table `gf_cms_photo_data` */

DROP TABLE IF EXISTS `gf_cms_photo_data`;

CREATE TABLE `gf_cms_photo_data` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `images` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_data` */

/*Table structure for table `gf_cms_photo_flag` */

DROP TABLE IF EXISTS `gf_cms_photo_flag`;

CREATE TABLE `gf_cms_photo_flag` (
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`flag`,`id`),
  KEY `flag_cid` (`flag`,`cid`,`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_flag` */

/*Table structure for table `gf_cms_photo_tag` */

DROP TABLE IF EXISTS `gf_cms_photo_tag`;

CREATE TABLE `gf_cms_photo_tag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(10) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`tagid`),
  UNIQUE KEY `name` (`name`),
  KEY `count` (`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_tag` */

/*Table structure for table `gf_cms_photo_tag_data` */

DROP TABLE IF EXISTS `gf_cms_photo_tag_data`;

CREATE TABLE `gf_cms_photo_tag_data` (
  `tagid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_tag_data` */

/*Table structure for table `gf_cms_photo_views` */

DROP TABLE IF EXISTS `gf_cms_photo_views`;

CREATE TABLE `gf_cms_photo_views` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`views`),
  KEY `views` (`views`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_photo_views` */

/*Table structure for table `gf_cms_product` */

DROP TABLE IF EXISTS `gf_cms_product`;

CREATE TABLE `gf_cms_product` (
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(80) NOT NULL DEFAULT '',
  `color` char(6) NOT NULL DEFAULT '',
  `alias` char(50) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `intro` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(150) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` int(10) NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `imagenum` int(10) unsigned NOT NULL DEFAULT '0',
  `filenum` int(10) unsigned NOT NULL DEFAULT '0',
  `flags` varchar(20) NOT NULL DEFAULT '',
  `seo_title` varchar(80) NOT NULL DEFAULT '',
  `seo_keywords` varchar(80) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cid_id` (`cid`,`id`),
  KEY `cid_dateline` (`cid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product` */

/*Table structure for table `gf_cms_product_attach` */

DROP TABLE IF EXISTS `gf_cms_product_attach`;

CREATE TABLE `gf_cms_product_attach` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` char(80) NOT NULL DEFAULT '',
  `filetype` char(10) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `filepath` char(150) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `downloads` int(10) unsigned NOT NULL DEFAULT '0',
  `isimage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `id` (`id`,`aid`),
  KEY `uid` (`uid`,`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_attach` */

/*Table structure for table `gf_cms_product_comment` */

DROP TABLE IF EXISTS `gf_cms_product_comment`;

CREATE TABLE `gf_cms_product_comment` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `commentid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` char(30) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ip` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`),
  KEY `id` (`id`,`commentid`),
  KEY `ip` (`ip`,`commentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_comment` */

/*Table structure for table `gf_cms_product_comment_sort` */

DROP TABLE IF EXISTS `gf_cms_product_comment_sort`;

CREATE TABLE `gf_cms_product_comment_sort` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid_comments` (`cid`,`comments`),
  KEY `comments` (`comments`),
  KEY `cid_lastdate` (`cid`,`lastdate`),
  KEY `lastdate` (`lastdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_comment_sort` */

/*Table structure for table `gf_cms_product_data` */

DROP TABLE IF EXISTS `gf_cms_product_data`;

CREATE TABLE `gf_cms_product_data` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `images` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_data` */

/*Table structure for table `gf_cms_product_flag` */

DROP TABLE IF EXISTS `gf_cms_product_flag`;

CREATE TABLE `gf_cms_product_flag` (
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`flag`,`id`),
  KEY `flag_cid` (`flag`,`cid`,`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_flag` */

/*Table structure for table `gf_cms_product_tag` */

DROP TABLE IF EXISTS `gf_cms_product_tag`;

CREATE TABLE `gf_cms_product_tag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(10) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`tagid`),
  UNIQUE KEY `name` (`name`),
  KEY `count` (`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_tag` */

/*Table structure for table `gf_cms_product_tag_data` */

DROP TABLE IF EXISTS `gf_cms_product_tag_data`;

CREATE TABLE `gf_cms_product_tag_data` (
  `tagid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_tag_data` */

/*Table structure for table `gf_cms_product_views` */

DROP TABLE IF EXISTS `gf_cms_product_views`;

CREATE TABLE `gf_cms_product_views` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`views`),
  KEY `views` (`views`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_cms_product_views` */

/*Table structure for table `gf_framework_count` */

DROP TABLE IF EXISTS `gf_framework_count`;

CREATE TABLE `gf_framework_count` (
  `name` char(32) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_framework_count` */

insert  into `gf_framework_count`(`name`,`count`) values ('runtime',0),('category',0),('cms_article',0),('cms_article_comment',0),('cms_product',0),('cms_product_comment',0),('cms_photo',0),('cms_photo_comment',0);

/*Table structure for table `gf_framework_maxid` */

DROP TABLE IF EXISTS `gf_framework_maxid`;

CREATE TABLE `gf_framework_maxid` (
  `name` char(32) NOT NULL DEFAULT '',
  `maxid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_framework_maxid` */

/*Table structure for table `gf_kv` */

DROP TABLE IF EXISTS `gf_kv`;

CREATE TABLE `gf_kv` (
  `k` char(32) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  `expiry` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_kv` */

insert  into `gf_kv`(`k`,`v`,`expiry`) values ('link_keywords','[\"tag\",\"tag_top\",\"comment\",\"index\",\"sitemap\",\"admin\",\"user\",\"space\"]',0),('cfg','{\"webname\":\"\\u529f\\u592bCMS\",\"webdomain\":\"wdt.my.com\",\"webdir\":\"\\/\",\"webmail\":\"admin@qq.com\",\"tongji\":\"<script type=\\\"text\\/javascript\\\">var _bdhmProtocol = ((\\\"https:\\\" == document.location.protocol) ? \\\" https:\\/\\/\\\" : \\\" http:\\/\\/\\\");document.write(unescape(\\\"%3Cscript src=\'\\\" + _bdhmProtocol + \\\"hm.baidu.com\\/h.js%3F948dba1e5d873b9c1f1c77078c521c89\' type=\'text\\/javascript\'%3E%3C\\/script%3E\\\"));<\\/script>\",\"beian\":\"\\u4eacICP\\u590720121225\\u53f7\",\"seo_title\":\"\\u8ba9\\u5efa\\u7ad9\\u53d8\\u7684\\u66f4\\u7b80\\u5355\\uff01\",\"seo_keywords\":\"\\u529f\\u592bCMS,GFCMS\",\"seo_description\":\"\\u529f\\u592bCMS\\uff0c\\u8ba9\\u5efa\\u7ad9\\u53d8\\u7684\\u66f4\\u7b80\\u5355\\uff01\",\"link_show\":\"{cate_alias}\\/{id}.html\",\"link_show_type\":2,\"link_show_end\":\".html\",\"link_cate_page_pre\":\"\\/page_\",\"link_cate_page_end\":\".html\",\"link_cate_end\":\"\\/\",\"link_tag_pre\":\"tag\\/\",\"link_tag_end\":\".html\",\"link_comment_pre\":\"comment\\/\",\"link_comment_end\":\".html\",\"link_index_end\":\".html\",\"up_img_ext\":\"jpg,jpeg,gif,png\",\"up_img_max_size\":\"3074\",\"up_file_ext\":\"zip,gz,rar,iso,xsl,doc,ppt,wps\",\"up_file_max_size\":\"10240\",\"thumb_article_w\":163,\"thumb_article_h\":124,\"thumb_product_w\":150,\"thumb_product_h\":150,\"thumb_photo_w\":150,\"thumb_photo_h\":150,\"thumb_type\":2,\"thumb_quality\":90,\"watermark_pos\":9,\"watermark_pct\":90}',0);

/*Table structure for table `gf_models` */

DROP TABLE IF EXISTS `gf_models`;

CREATE TABLE `gf_models` (
  `mid` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(10) NOT NULL DEFAULT '',
  `tablename` char(20) NOT NULL DEFAULT '',
  `index_tpl` char(80) NOT NULL DEFAULT '',
  `cate_tpl` char(80) NOT NULL DEFAULT '',
  `show_tpl` char(80) NOT NULL DEFAULT '',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `tablename` (`tablename`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `gf_models` */

insert  into `gf_models`(`mid`,`name`,`tablename`,`index_tpl`,`cate_tpl`,`show_tpl`,`system`) values (1,'单页','page','','page_show.htm','',1),(2,'文章','article','article_index.htm','article_list.htm','article_show.htm',1),(3,'产品','product','product_index.htm','product_list.htm','product_show.htm',1),(4,'图集','photo','photo_index.htm','photo_list.htm','photo_show.htm',1);

/*Table structure for table `gf_only_alias` */

DROP TABLE IF EXISTS `gf_only_alias`;

CREATE TABLE `gf_only_alias` (
  `alias` char(50) NOT NULL,
  `mid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`alias`),
  KEY `mid_id` (`mid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_only_alias` */

/*Table structure for table `gf_runtime` */

DROP TABLE IF EXISTS `gf_runtime`;

CREATE TABLE `gf_runtime` (
  `k` char(32) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  `expiry` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `gf_runtime` */

insert  into `gf_runtime`(`k`,`v`,`expiry`) values ('cfg','{\"webname\":\"\\u529f\\u592bCMS\",\"webdomain\":\"wdt.my.com\",\"webdir\":\"\\/\",\"webmail\":\"admin@qq.com\",\"tongji\":\"<script type=\\\"text\\/javascript\\\">var _bdhmProtocol = ((\\\"https:\\\" == document.location.protocol) ? \\\" https:\\/\\/\\\" : \\\" http:\\/\\/\\\");document.write(unescape(\\\"%3Cscript src=\'\\\" + _bdhmProtocol + \\\"hm.baidu.com\\/h.js%3F948dba1e5d873b9c1f1c77078c521c89\' type=\'text\\/javascript\'%3E%3C\\/script%3E\\\"));<\\/script>\",\"beian\":\"\\u4eacICP\\u590720121225\\u53f7\",\"seo_title\":\"\\u8ba9\\u5efa\\u7ad9\\u53d8\\u7684\\u66f4\\u7b80\\u5355\\uff01\",\"seo_keywords\":\"\\u529f\\u592bCMS,GFCMS\",\"seo_description\":\"\\u529f\\u592bCMS\\uff0c\\u8ba9\\u5efa\\u7ad9\\u53d8\\u7684\\u66f4\\u7b80\\u5355\\uff01\",\"link_show\":\"{cate_alias}\\/{id}.html\",\"link_show_type\":2,\"link_show_end\":\".html\",\"link_cate_page_pre\":\"\\/page_\",\"link_cate_page_end\":\".html\",\"link_cate_end\":\"\\/\",\"link_tag_pre\":\"tag\\/\",\"link_tag_end\":\".html\",\"link_comment_pre\":\"comment\\/\",\"link_comment_end\":\".html\",\"link_index_end\":\".html\",\"up_img_ext\":\"jpg,jpeg,gif,png\",\"up_img_max_size\":\"3074\",\"up_file_ext\":\"zip,gz,rar,iso,xsl,doc,ppt,wps\",\"up_file_max_size\":\"10240\",\"thumb_article_w\":163,\"thumb_article_h\":124,\"thumb_product_w\":150,\"thumb_product_h\":150,\"thumb_photo_w\":150,\"thumb_photo_h\":150,\"thumb_type\":2,\"thumb_quality\":90,\"watermark_pos\":9,\"watermark_pct\":90,\"theme\":\"default\",\"tpl\":\"\\/home\\/default\\/\",\"webroot\":\"http:\\/\\/wdt.my.com\",\"weburl\":\"http:\\/\\/wdt.my.com\\/\",\"table_arr\":{\"1\":\"page\",\"2\":\"article\",\"3\":\"product\",\"4\":\"photo\"},\"mod_name\":{\"2\":\"\\u6587\\u7ae0\",\"3\":\"\\u4ea7\\u54c1\",\"4\":\"\\u56fe\\u96c6\"},\"cate_arr\":[]}',0),('cate_by_mid_2','[]',0);

/*Table structure for table `gf_user` */

DROP TABLE IF EXISTS `gf_user`;

CREATE TABLE `gf_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(16) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `salt` char(16) NOT NULL DEFAULT '',
  `groupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `email` char(40) NOT NULL DEFAULT '',
  `homepage` char(40) NOT NULL DEFAULT '',
  `intro` text NOT NULL,
  `regip` int(10) NOT NULL DEFAULT '0',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` int(10) NOT NULL DEFAULT '0',
  `logindate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` int(10) NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  `contents` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `gf_user` */

insert  into `gf_user`(`uid`,`username`,`password`,`salt`,`groupid`,`email`,`homepage`,`intro`,`regip`,`regdate`,`loginip`,`logindate`,`lastip`,`lastdate`,`contents`,`comments`,`logins`) values (1,'admin','101721bb37ebe36cf7cb79210a8a7bda','PZyc^*8w8^PCle~y',1,'','','',2130706433,1468060300,2130706433,1481728462,2130706433,1479139232,0,0,5);

/*Table structure for table `gf_user_group` */

DROP TABLE IF EXISTS `gf_user_group`;

CREATE TABLE `gf_user_group` (
  `groupid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` char(20) NOT NULL DEFAULT '',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `purviews` text NOT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `gf_user_group` */

insert  into `gf_user_group`(`groupid`,`groupname`,`system`,`purviews`) values (1,'管理员组',1,''),(2,'主编组',1,''),(3,'编辑组',1,''),(6,'待验证用户组',1,''),(7,'禁止用户组',1,''),(11,'注册用户',1,'');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
