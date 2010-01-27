-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- ����: localhost
-- ����� ��������: ��� 27 2010 �., 10:43
-- ������ �������: 4.0.15
-- ������ PHP: 4.3.3



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;

--
-- ���� ������: `zaomppsklads`
--

-- --------------------------------------------------------

--
-- ��������� ������� `coments`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 14 2010 �., 11:38
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(10) NOT NULL auto_increment,
  `comment` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `comment` (`comment`)
) TYPE=MyISAM COMMENT='������ ����������� � ��������� ����������' AUTO_INCREMENT=2725 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_hal__dvizh`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
-- ��������� ��������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_hal__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=302 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_hal__ost`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_hal__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_hal__spr`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_hal__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_him1__dvizh`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
-- ��������� ��������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_him1__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=1488 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_him1__ost`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_him1__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=149 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_him1__spr`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_him1__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=162 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_him__dvizh`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
-- ��������� ��������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_him__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=2044 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_him__ost`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 11 2009 �., 10:24
--

CREATE TABLE IF NOT EXISTS `sk_arc_him__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_him__spr`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 11 2009 �., 10:24
--

CREATE TABLE IF NOT EXISTS `sk_arc_him__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=254 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_inst__dvizh`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
-- ��������� ��������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_inst__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=265 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_inst__ost`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_inst__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_inst__spr`
--
-- ��������: ��� 19 2009 �., 15:44
-- ��������� ����������: ��� 19 2009 �., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_inst__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_maloc__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_maloc__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_maloc__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_maloc__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_maloc__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_maloc__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_mat__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_mat__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=1200 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_mat__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_mat__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_mat__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_mat__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=126 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_nepon__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_nepon__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=524 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_nepon__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_nepon__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_nepon__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_nepon__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_stroy__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_stroy__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_stroy__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_stroy__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_stroy__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_stroy__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_sver__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_sver__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=612 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_sver__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_sver__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_sver__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_sver__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_test__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_test__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_test__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_test__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_test__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_test__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_zap__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_zap__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=115 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_zap__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_zap__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_arc_zap__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_zap__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_hal__dvizh`
--
-- ��������: ��� 14 2010 �., 11:40
-- ��������� ����������: ��� 15 2010 �., 15:14
--

CREATE TABLE IF NOT EXISTS `sk_hal__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_hal__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:40
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_hal__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=497 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_hal__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 18 2010 �., 15:19
--

CREATE TABLE IF NOT EXISTS `sk_hal__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_hal__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_hal__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_hal__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_hal__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him1__dvizh`
--
-- ��������: ��� 14 2010 �., 11:40
-- ��������� ����������: ��� 25 2010 �., 16:26
--

CREATE TABLE IF NOT EXISTS `sk_him1__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him1__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:40
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_him1__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=834 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him1__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 25 2010 �., 16:26
--

CREATE TABLE IF NOT EXISTS `sk_him1__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him1__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_him1__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him1__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 29 2009 �., 16:44
--

CREATE TABLE IF NOT EXISTS `sk_him1__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him__dvizh`
--
-- ��������: ��� 14 2010 �., 11:38
-- ��������� ����������: ��� 26 2010 �., 16:45
--

CREATE TABLE IF NOT EXISTS `sk_him__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=117 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:38
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_him__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=5191 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 26 2010 �., 16:45
--

CREATE TABLE IF NOT EXISTS `sk_him__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=134 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 25 2010 �., 16:47
--

CREATE TABLE IF NOT EXISTS `sk_him__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=224 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_him__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 26 2010 �., 10:05
--

CREATE TABLE IF NOT EXISTS `sk_him__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=254 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_inst__dvizh`
--
-- ��������: ��� 14 2010 �., 11:40
-- ��������� ����������: ��� 14 2010 �., 11:40
--

CREATE TABLE IF NOT EXISTS `sk_inst__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_inst__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:40
-- ��������� ��������: ��� 14 2010 �., 11:40
--

CREATE TABLE IF NOT EXISTS `sk_inst__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_inst__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_inst__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_inst__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_inst__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_inst__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_inst__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_maloc_test__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_maloc_test__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_maloc__dvizh`
--
-- ��������: ��� 14 2010 �., 11:41
-- ��������� ����������: ��� 19 2010 �., 11:08
--

CREATE TABLE IF NOT EXISTS `sk_maloc__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=206 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_maloc__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:41
-- ��������� ��������: ��� 14 2010 �., 11:41
--

CREATE TABLE IF NOT EXISTS `sk_maloc__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=536 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_maloc__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2010 �., 11:08
--

CREATE TABLE IF NOT EXISTS `sk_maloc__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=152 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_maloc__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_maloc__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_maloc__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_maloc__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=249 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_mat__dvizh`
--
-- ��������: ��� 14 2010 �., 11:39
-- ��������� ����������: ��� 25 2010 �., 16:23
--

CREATE TABLE IF NOT EXISTS `sk_mat__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_mat__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:39
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_mat__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=2266 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_mat__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 25 2010 �., 16:23
--

CREATE TABLE IF NOT EXISTS `sk_mat__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_mat__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_mat__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_mat__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_mat__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_nepon__dvizh`
--
-- ��������: ��� 14 2010 �., 11:40
-- ��������� ����������: ��� 26 2010 �., 17:09
--

CREATE TABLE IF NOT EXISTS `sk_nepon__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=90 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_nepon__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:40
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_nepon__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=689 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_nepon__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 26 2010 �., 17:09
--

CREATE TABLE IF NOT EXISTS `sk_nepon__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=158 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_nepon__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_nepon__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_nepon__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 26 2010 �., 17:08
--

CREATE TABLE IF NOT EXISTS `sk_nepon__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=158 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_stroy__dvizh`
--
-- ��������: ��� 14 2010 �., 11:41
-- ��������� ����������: ��� 14 2010 �., 11:41
--

CREATE TABLE IF NOT EXISTS `sk_stroy__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_stroy__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:41
-- ��������� ��������: ��� 14 2010 �., 11:41
--

CREATE TABLE IF NOT EXISTS `sk_stroy__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_stroy__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_stroy__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_stroy__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_stroy__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_stroy__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_stroy__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_sver__dvizh`
--
-- ��������: ��� 14 2010 �., 11:40
-- ��������� ����������: ��� 14 2010 �., 11:40
--

CREATE TABLE IF NOT EXISTS `sk_sver__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_sver__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:40
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_sver__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=2561 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_sver__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 16 2009 �., 10:40
--

CREATE TABLE IF NOT EXISTS `sk_sver__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_sver__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_sver__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_sver__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_sver__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_test__dvizh`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_test__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_test__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
-- ��������� ��������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_test__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=165 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_test__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_test__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_test__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_test__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_test__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_test__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_zap__dvizh`
--
-- ��������: ��� 14 2010 �., 11:41
-- ��������� ����������: ��� 26 2010 �., 09:59
--

CREATE TABLE IF NOT EXISTS `sk_zap__dvizh` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_zap__dvizh_arc`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 14 2010 �., 11:42
-- ��������� ��������: ��� 14 2010 �., 11:41
--

CREATE TABLE IF NOT EXISTS `sk_zap__dvizh_arc` (
  `id` bigint(10) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `numd` varchar(10) NOT NULL default '',
  `numdf` varchar(10) NOT NULL default '',
  `docyr` int(11) NOT NULL default '0',
  `spr_id` bigint(10) NOT NULL default '0',
  `quant` float NOT NULL default '0',
  `ddate` date NOT NULL default '0000-00-00',
  `post_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=180 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_zap__ost`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 26 2010 �., 09:59
--

CREATE TABLE IF NOT EXISTS `sk_zap__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=114 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_zap__postav`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 19 2009 �., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_zap__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- ��������� ������� `sk_zap__spr`
--
-- ��������: ��� 19 2009 �., 15:45
-- ��������� ����������: ��� 22 2009 �., 09:59
--

CREATE TABLE IF NOT EXISTS `sk_zap__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=114 ;
