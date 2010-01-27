-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 27 2010 г., 10:43
-- Версия сервера: 4.0.15
-- Версия PHP: 4.3.3



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;

--
-- База данных: `zaomppsklads`
--

-- --------------------------------------------------------

--
-- Структура таблицы `coments`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Янв 14 2010 г., 11:38
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(10) NOT NULL auto_increment,
  `comment` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `comment` (`comment`)
) TYPE=MyISAM COMMENT='Свалка коментариев к различным документам' AUTO_INCREMENT=2725 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_hal__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
-- Последняя проверка: Ноя 19 2009 г., 15:44
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
-- Структура таблицы `sk_arc_hal__ost`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_hal__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_hal__spr`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
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
-- Структура таблицы `sk_arc_him1__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
-- Последняя проверка: Ноя 19 2009 г., 15:44
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
-- Структура таблицы `sk_arc_him1__ost`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_him1__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=149 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him1__spr`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
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
-- Структура таблицы `sk_arc_him__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
-- Последняя проверка: Ноя 19 2009 г., 15:44
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
-- Структура таблицы `sk_arc_him__ost`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Дек 11 2009 г., 10:24
--

CREATE TABLE IF NOT EXISTS `sk_arc_him__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him__spr`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Дек 11 2009 г., 10:24
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
-- Структура таблицы `sk_arc_inst__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
-- Последняя проверка: Ноя 19 2009 г., 15:44
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
-- Структура таблицы `sk_arc_inst__ost`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
--

CREATE TABLE IF NOT EXISTS `sk_arc_inst__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_inst__spr`
--
-- Создание: Ноя 19 2009 г., 15:44
-- Последнее обновление: Ноя 19 2009 г., 15:44
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
-- Структура таблицы `sk_arc_maloc__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_maloc__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_maloc__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_maloc__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_mat__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_mat__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_mat__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_mat__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_nepon__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_nepon__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_nepon__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_nepon__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_stroy__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_stroy__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_stroy__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_stroy__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_sver__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_sver__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_sver__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_sver__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_test__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_test__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_test__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_test__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_zap__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_arc_zap__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_arc_zap__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_zap__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_hal__dvizh`
--
-- Создание: Янв 14 2010 г., 11:40
-- Последнее обновление: Янв 15 2010 г., 15:14
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
-- Структура таблицы `sk_hal__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:40
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_hal__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 18 2010 г., 15:19
--

CREATE TABLE IF NOT EXISTS `sk_hal__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_hal__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_hal__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_hal__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_him1__dvizh`
--
-- Создание: Янв 14 2010 г., 11:40
-- Последнее обновление: Янв 25 2010 г., 16:26
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
-- Структура таблицы `sk_him1__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:40
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_him1__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 25 2010 г., 16:26
--

CREATE TABLE IF NOT EXISTS `sk_him1__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him1__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_him1__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him1__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Дек 29 2009 г., 16:44
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
-- Структура таблицы `sk_him__dvizh`
--
-- Создание: Янв 14 2010 г., 11:38
-- Последнее обновление: Янв 26 2010 г., 16:45
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
-- Структура таблицы `sk_him__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:38
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_him__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 26 2010 г., 16:45
--

CREATE TABLE IF NOT EXISTS `sk_him__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=134 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 25 2010 г., 16:47
--

CREATE TABLE IF NOT EXISTS `sk_him__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=224 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 26 2010 г., 10:05
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
-- Структура таблицы `sk_inst__dvizh`
--
-- Создание: Янв 14 2010 г., 11:40
-- Последнее обновление: Янв 14 2010 г., 11:40
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
-- Структура таблицы `sk_inst__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:40
-- Последняя проверка: Янв 14 2010 г., 11:40
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
-- Структура таблицы `sk_inst__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_inst__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_inst__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_inst__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_inst__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_maloc_test__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_maloc__dvizh`
--
-- Создание: Янв 14 2010 г., 11:41
-- Последнее обновление: Янв 19 2010 г., 11:08
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
-- Структура таблицы `sk_maloc__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:41
-- Последняя проверка: Янв 14 2010 г., 11:41
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
-- Структура таблицы `sk_maloc__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 19 2010 г., 11:08
--

CREATE TABLE IF NOT EXISTS `sk_maloc__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=152 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_maloc__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_maloc__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_maloc__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_mat__dvizh`
--
-- Создание: Янв 14 2010 г., 11:39
-- Последнее обновление: Янв 25 2010 г., 16:23
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
-- Структура таблицы `sk_mat__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:39
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_mat__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 25 2010 г., 16:23
--

CREATE TABLE IF NOT EXISTS `sk_mat__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_mat__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_mat__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_mat__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_nepon__dvizh`
--
-- Создание: Янв 14 2010 г., 11:40
-- Последнее обновление: Янв 26 2010 г., 17:09
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
-- Структура таблицы `sk_nepon__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:40
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_nepon__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 26 2010 г., 17:09
--

CREATE TABLE IF NOT EXISTS `sk_nepon__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=158 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_nepon__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_nepon__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_nepon__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 26 2010 г., 17:08
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
-- Структура таблицы `sk_stroy__dvizh`
--
-- Создание: Янв 14 2010 г., 11:41
-- Последнее обновление: Янв 14 2010 г., 11:41
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
-- Структура таблицы `sk_stroy__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:41
-- Последняя проверка: Янв 14 2010 г., 11:41
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
-- Структура таблицы `sk_stroy__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_stroy__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_stroy__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_stroy__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_stroy__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_sver__dvizh`
--
-- Создание: Янв 14 2010 г., 11:40
-- Последнее обновление: Янв 14 2010 г., 11:40
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
-- Структура таблицы `sk_sver__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:40
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_sver__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Дек 16 2009 г., 10:40
--

CREATE TABLE IF NOT EXISTS `sk_sver__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_sver__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_sver__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_sver__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_test__dvizh`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_test__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
-- Последняя проверка: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_test__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_test__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_test__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
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
-- Структура таблицы `sk_zap__dvizh`
--
-- Создание: Янв 14 2010 г., 11:41
-- Последнее обновление: Янв 26 2010 г., 09:59
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
-- Структура таблицы `sk_zap__dvizh_arc`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 14 2010 г., 11:42
-- Последняя проверка: Янв 14 2010 г., 11:41
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
-- Структура таблицы `sk_zap__ost`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Янв 26 2010 г., 09:59
--

CREATE TABLE IF NOT EXISTS `sk_zap__ost` (
  `id` bigint(10) NOT NULL auto_increment,
  `spr_id` bigint(10) NOT NULL default '0',
  `ost` float(12,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=114 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_zap__postav`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Ноя 19 2009 г., 15:45
--

CREATE TABLE IF NOT EXISTS `sk_zap__postav` (
  `id` bigint(10) NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_zap__spr`
--
-- Создание: Ноя 19 2009 г., 15:45
-- Последнее обновление: Дек 22 2009 г., 09:59
--

CREATE TABLE IF NOT EXISTS `sk_zap__spr` (
  `id` bigint(10) NOT NULL auto_increment,
  `nazv` varchar(50) NOT NULL default '',
  `edizm` varchar(10) NOT NULL default '',
  `krost` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=114 ;
