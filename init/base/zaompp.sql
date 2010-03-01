-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 01 2010 г., 11:44
-- Версия сервера: 4.0.15
-- Версия PHP: 4.3.3



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;

--
-- База данных: `zaompp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blockpos`
--

CREATE TABLE IF NOT EXISTS `blockpos` (
  `id` bigint(10) NOT NULL auto_increment,
  `block_id` bigint(10) NOT NULL default '0',
  `board_id` bigint(10) NOT NULL default '0',
  `nib` int(11) NOT NULL default '0',
  `nx` int(11) NOT NULL default '0',
  `ny` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `block_id` (`block_id`,`board_id`)
) TYPE=MyISAM COMMENT='Позиции в блоках' AUTO_INCREMENT=591 ;

-- --------------------------------------------------------

--
-- Структура таблицы `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` bigint(10) NOT NULL auto_increment,
  `customer_id` bigint(10) NOT NULL default '0',
  `blockname` varchar(100) NOT NULL default '',
  `sizex` float(5,3) NOT NULL default '0.000',
  `sizey` float(5,3) NOT NULL default '0.000',
  `thickness` float(3,2) NOT NULL default '0.00',
  `scomp` float(10,2) NOT NULL default '0.00',
  `ssolder` float(10,2) NOT NULL default '0.00',
  `drlname` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `blockname` (`blockname`)
) TYPE=MyISAM COMMENT='Блоки плат' AUTO_INCREMENT=719 ;

-- --------------------------------------------------------

--
-- Структура таблицы `boards`
--

CREATE TABLE IF NOT EXISTS `boards` (
  `id` bigint(10) NOT NULL auto_increment,
  `board_name` varchar(255) NOT NULL default '0',
  `customer_id` bigint(10) NOT NULL default '0',
  `sizex` float(5,3) NOT NULL default '0.000',
  `sizey` float(5,3) NOT NULL default '0.000',
  `thickness` varchar(10) NOT NULL default '0.00',
  `drawing_id` bigint(10) NOT NULL default '0',
  `texеolite` varchar(50) NOT NULL default '0',
  `textolitepsi` varchar(50) NOT NULL default '',
  `thick_tol` varchar(10) NOT NULL default '',
  `rmark` tinyint(1) NOT NULL default '0',
  `frezcorner` tinyint(1) NOT NULL default '0',
  `layers` int(2) NOT NULL default '0',
  `razr` tinyint(1) NOT NULL default '0',
  `pallad` tinyint(1) NOT NULL default '0',
  `immer` tinyint(1) NOT NULL default '0',
  `aurum` tinyint(1) NOT NULL default '0',
  `numlam` int(11) NOT NULL default '0',
  `lsizex` float(3,2) NOT NULL default '0.00',
  `lsizey` float(3,2) NOT NULL default '0.00',
  `mask` varchar(20) NOT NULL default '0',
  `mark` varchar(20) NOT NULL default '0',
  `glasscloth` varchar(40) default NULL,
  `class` enum('1','2','3','4','5','6') NOT NULL default '1',
  `complexity_factor` float(3,1) NOT NULL default '0.0',
  `frez_factor` float(3,1) NOT NULL default '0.0',
  PRIMARY KEY  (`id`),
  KEY `board_id` (`board_name`,`texеolite`)
) TYPE=MyISAM COMMENT='Даные по плате, только те что относятся к плате' AUTO_INCREMENT=591 ;

-- --------------------------------------------------------

--
-- Структура таблицы `coments`
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(10) NOT NULL auto_increment,
  `comment` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `comment` (`comment`)
) TYPE=MyISAM COMMENT='Свалка коментариев к различным документам' AUTO_INCREMENT=2722 ;

-- --------------------------------------------------------

--
-- Структура таблицы `conductors`
--

CREATE TABLE IF NOT EXISTS `conductors` (
  `id` bigint(11) NOT NULL auto_increment,
  `board_id` bigint(11) NOT NULL default '0',
  `side` enum('TOP','BOT','TOPBOT') NOT NULL default 'TOP',
  `lays` enum('3','5') NOT NULL default '3',
  `sizex` int(11) NOT NULL default '0',
  `sizey` int(11) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  `user_id` bigint(11) NOT NULL default '0',
  `dir` varchar(255) NOT NULL default '',
  `dirk` varchar(255) NOT NULL default '',
  `pib` int(11) NOT NULL default '1',
  `comment_id` bigint(20) NOT NULL default '0',
  `ready` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `coppers`
--

CREATE TABLE IF NOT EXISTS `coppers` (
  `id` int(10) NOT NULL auto_increment,
  `customer_id` int(10) NOT NULL default '0',
  `plate_id` int(10) NOT NULL default '0',
  `scomp` float NOT NULL default '0',
  `ssolder` float NOT NULL default '0',
  `drlname` varchar(10) NOT NULL default '',
  `sizex` float(5,3) NOT NULL default '0.000',
  `sizey` float(5,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`),
  KEY `customer_id` (`customer_id`,`plate_id`)
) TYPE=MyISAM COMMENT='Таблица площадей плат' AUTO_INCREMENT=1563 ;

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) NOT NULL auto_increment,
  `customer` varchar(40) NOT NULL default '',
  `fullname` varchar(200) NOT NULL default '',
  `kdir` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `customer` (`customer`)
) TYPE=MyISAM COMMENT='Заказчики' AUTO_INCREMENT=88 ;

-- --------------------------------------------------------

--
-- Структура таблицы `docs`
--

CREATE TABLE IF NOT EXISTS `docs` (
  `id` bigint(11) NOT NULL auto_increment,
  `did` bigint(20) NOT NULL default '0',
  `table` varchar(50) NOT NULL default '',
  `user_id` bigint(20) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `did` (`did`,`table`,`user_id`,`ts`)
) TYPE=MyISAM COMMENT='Сборник всех документов базы' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `eltest`
--

CREATE TABLE IF NOT EXISTS `eltest` (
  `id` bigint(10) NOT NULL auto_increment,
  `board_id` bigint(10) NOT NULL default '0',
  `type` varchar(40) NOT NULL default '',
  `points` int(11) NOT NULL default '0',
  `pib` int(11) NOT NULL default '0',
  `pointsb` int(11) NOT NULL default '0',
  `factor` float(5,3) NOT NULL default '0.000',
  `numcomp` enum('1','2') NOT NULL default '1',
  `sizex` float(5,3) NOT NULL default '0.000',
  `sizey` float(5,3) NOT NULL default '0.000',
  `numpl` varchar(15) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Электроконторь' AUTO_INCREMENT=90 ;

-- --------------------------------------------------------

--
-- Структура таблицы `filelinks`
--

CREATE TABLE IF NOT EXISTS `filelinks` (
  `id` bigint(10) NOT NULL auto_increment,
  `file_link` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Ссылки на файлы' AUTO_INCREMENT=1739 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lanch`
--

CREATE TABLE IF NOT EXISTS `lanch` (
  `id` bigint(10) NOT NULL auto_increment,
  `ldate` date NOT NULL default '0000-00-00',
  `board_id` bigint(10) NOT NULL default '0',
  `part` int(11) NOT NULL default '0',
  `numbz` int(11) NOT NULL default '0',
  `numbp` int(11) NOT NULL default '0',
  `comment_id` bigint(20) NOT NULL default '0',
  `file_link_id` bigint(10) NOT NULL default '0',
  `user_id` bigint(10) NOT NULL default '0',
  `pos_in_tz` int(1) NOT NULL default '0',
  `tz_id` bigint(10) NOT NULL default '0',
  `pos_in_tz_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pos_in_tz` (`pos_in_tz`)
) TYPE=MyISAM COMMENT='Заупски' AUTO_INCREMENT=2546 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lanched`
--

CREATE TABLE IF NOT EXISTS `lanched` (
  `board_id` bigint(10) NOT NULL default '0',
  `lastdate` date default NULL,
  PRIMARY KEY  (`board_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint(10) NOT NULL auto_increment,
  `logdate` timestamp(14) NOT NULL,
  `user_id` bigint(10) NOT NULL default '0',
  `sqltext` text NOT NULL,
  `action` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `logdate` (`logdate`,`user_id`)
) TYPE=MyISAM COMMENT='Логирование удалений' AUTO_INCREMENT=2856 ;

-- --------------------------------------------------------

--
-- Структура таблицы `masterplate`
--

CREATE TABLE IF NOT EXISTS `masterplate` (
  `id` bigint(10) NOT NULL auto_increment,
  `tz_id` bigint(10) NOT NULL default '0',
  `posintz` int(11) NOT NULL default '0',
  `mpdate` date NOT NULL default '0000-00-00',
  `user_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Мастерплаты' AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mppdop`
--

CREATE TABLE IF NOT EXISTS `mppdop` (
  `id` bigint(10) NOT NULL auto_increment,
  `block_id` bigint(10) NOT NULL default '0',
  `ndraw` varchar(50) NOT NULL default '',
  `osob` varchar(50) NOT NULL default '',
  `nstek` varchar(50) NOT NULL default '',
  `dop` varchar(50) NOT NULL default '',
  `nprokl` varchar(50) NOT NULL default '',
  `tprokl` varchar(50) NOT NULL default '',
  `dfrez` varchar(50) NOT NULL default '',
  `mn1` varchar(50) NOT NULL default '',
  `mn2` varchar(50) NOT NULL default '',
  `mn3` varchar(50) NOT NULL default '',
  `mn4` varchar(50) NOT NULL default '',
  `mn5` varchar(50) NOT NULL default '',
  `mn6` varchar(50) NOT NULL default '',
  `mn7` varchar(50) NOT NULL default '',
  `mn8` varchar(50) NOT NULL default '',
  `m1` varchar(50) NOT NULL default '',
  `m2` varchar(50) NOT NULL default '',
  `m3` varchar(50) NOT NULL default '',
  `m4` varchar(50) NOT NULL default '',
  `m5` varchar(50) NOT NULL default '',
  `m6` varchar(50) NOT NULL default '',
  `m7` varchar(50) NOT NULL default '',
  `m8` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Доплнительные материалы к многослойкам для сопроводительного' AUTO_INCREMENT=90 ;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(10) NOT NULL auto_increment,
  `orderdate` date NOT NULL default '0000-00-00',
  `number` varchar(50) NOT NULL default '',
  `customer_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Заказы' AUTO_INCREMENT=548 ;

-- --------------------------------------------------------

--
-- Структура таблицы `phototemplates`
--

CREATE TABLE IF NOT EXISTS `phototemplates` (
  `id` bigint(10) NOT NULL auto_increment,
  `ts` timestamp(14) NOT NULL,
  `user_id` bigint(10) NOT NULL default '0',
  `filenames` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Фотошаблоны отправленные на рисование' AUTO_INCREMENT=5853 ;

-- --------------------------------------------------------

--
-- Структура таблицы `plates`
--

CREATE TABLE IF NOT EXISTS `plates` (
  `id` int(10) NOT NULL auto_increment,
  `plate` varchar(255) NOT NULL default '',
  `customer_id` int(10) NOT NULL default '0',
  `isblock` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `plate` (`plate`),
  KEY `customer_id` (`customer_id`)
) TYPE=MyISAM COMMENT='Платы (заполняется через excel' AUTO_INCREMENT=2635 ;

-- --------------------------------------------------------

--
-- Структура таблицы `posintz`
--

CREATE TABLE IF NOT EXISTS `posintz` (
  `id` bigint(10) NOT NULL auto_increment,
  `tz_id` bigint(10) NOT NULL default '0',
  `posintz` int(11) NOT NULL default '0',
  `plate_id` bigint(10) NOT NULL default '0',
  `board_id` bigint(10) NOT NULL default '0',
  `block_id` bigint(10) NOT NULL default '0',
  `numbers` varchar(11) NOT NULL default '0',
  `first` tinyint(1) NOT NULL default '0',
  `srok` int(11) NOT NULL default '0',
  `priem` varchar(40) NOT NULL default 'ОТК',
  `constr` int(11) NOT NULL default '0',
  `template_check` int(11) NOT NULL default '0',
  `template_make` int(11) NOT NULL default '0',
  `mask` tinyint(1) default NULL,
  `mark` tinyint(1) default NULL,
  `eltest` tinyint(1) NOT NULL default '0',
  `numpl` int(11) NOT NULL default '0',
  `numbl` int(11) NOT NULL default '0',
  `pip` int(11) NOT NULL default '0',
  `ldate` date NOT NULL default '0000-00-00',
  `luser_id` bigint(10) NOT NULL default '0',
  `comment_id` bigint(10) NOT NULL default '0',
  `pitz_mater` varchar(100) default NULL,
  `pitz_psimat` varchar(100) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ldate` (`ldate`)
) TYPE=MyISAM COMMENT='Позиции в ТЗ' AUTO_INCREMENT=2717 ;

-- --------------------------------------------------------

--
-- Структура таблицы `relations`
--

CREATE TABLE IF NOT EXISTS `relations` (
  `id` bigint(20) NOT NULL default '0',
  `did1` bigint(20) NOT NULL default '0',
  `did2` bigint(20) NOT NULL default '0',
  `rtype` bigint(20) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `did1` (`did1`,`did2`,`rtype`)
) TYPE=MyISAM COMMENT='Таблица отношений между документами';

-- --------------------------------------------------------

--
-- Структура таблицы `reltype`
--

CREATE TABLE IF NOT EXISTS `reltype` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Типы отношений между документами' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` bigint(20) NOT NULL auto_increment,
  `u_id` bigint(20) NOT NULL default '0',
  `type_id` bigint(20) NOT NULL default '0',
  `rtype_id` bigint(20) NOT NULL default '0',
  `right` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `u_id` (`u_id`)
) TYPE=MyISAM COMMENT='Таблица прав доступа к управлению базой' AUTO_INCREMENT=555 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rrtypes`
--

CREATE TABLE IF NOT EXISTS `rrtypes` (
  `id` bigint(20) NOT NULL auto_increment,
  `rtype` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Типы доступа' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rtypes`
--

CREATE TABLE IF NOT EXISTS `rtypes` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Типы для доступа' AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session` varchar(255) NOT NULL default '',
  `u_id` bigint(12) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  PRIMARY KEY  (`session`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Структура таблицы `todo`
--

CREATE TABLE IF NOT EXISTS `todo` (
  `id` bigint(20) NOT NULL auto_increment,
  `what` text NOT NULL,
  `cts` timestamp(14) NOT NULL,
  `rts` timestamp(14) NOT NULL,
  `u_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Что сделать таблица' AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tz`
--

CREATE TABLE IF NOT EXISTS `tz` (
  `id` bigint(10) NOT NULL auto_increment,
  `order_id` bigint(10) NOT NULL default '0',
  `tz_date` date NOT NULL default '0000-00-00',
  `user_id` bigint(10) NOT NULL default '0',
  `pos_in_order` tinyint(4) NOT NULL default '1',
  `file_link_id` bigint(10) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Технические задания' AUTO_INCREMENT=860 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(10) unsigned NOT NULL auto_increment,
  `nik` varchar(15) NOT NULL default '',
  `fullname` varchar(50) NOT NULL default '',
  `position` varchar(50) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `password` (`password`)
) TYPE=MyISAM COMMENT='Пользватели базы данных' AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Структура таблицы `workers`
--

CREATE TABLE IF NOT EXISTS `workers` (
  `id` bigint(10) NOT NULL auto_increment,
  `tabn` varchar(10) default NULL,
  `stat` enum('stat','sovm1','sovm2') NOT NULL default 'stat',
  `fio` varchar(50) NOT NULL default '',
  `f` varchar(20) default NULL,
  `i` varchar(20) default NULL,
  `o` varchar(20) default NULL,
  `dolz` varchar(50) default NULL,
  `doli` varchar(50) default NULL,
  `dr` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`),
  KEY `fio` (`fio`,`f`,`i`,`o`,`dr`)
) TYPE=MyISAM COMMENT='Рабочие с днями рождения' AUTO_INCREMENT=86 ;

-- --------------------------------------------------------

--
-- Структура таблицы `zadel`
--

CREATE TABLE IF NOT EXISTS `zadel` (
  `id` bigint(10) NOT NULL auto_increment,
  `board_id` bigint(10) NOT NULL default '0',
  `ldate` date NOT NULL default '0000-00-00',
  `niz` varchar(10) NOT NULL default '',
  `number` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Задел в ОТК' AUTO_INCREMENT=1655 ;
