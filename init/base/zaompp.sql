-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- ����: localhost
-- ����� ��������: ��� 27 2010 �., 10:42
-- ������ �������: 4.0.15
-- ������ PHP: 4.3.3



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;

--
-- ���� ������: `zaompp`
--

-- --------------------------------------------------------

--
-- ��������� ������� `blockpos`
--
-- ��������: ��� 15 2009 �., 14:41
-- ��������� ����������: ��� 27 2010 �., 10:22
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
) TYPE=MyISAM COMMENT='������� � ������' AUTO_INCREMENT=427 ;

--
-- ����������� � ������� `blockpos`:
--   `block_id`
--       `������ � ����`
--   `board_id`
--       `������ �� �����`
--   `nib`
--       `���������� � �����`
--   `nx`
--       `���������� �� �`
--   `ny`
--       `���������� �� �`
--

-- --------------------------------------------------------

--
-- ��������� ������� `blocks`
--
-- ��������: ��� 15 2009 �., 12:23
-- ��������� ����������: ��� 27 2010 �., 10:39
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
) TYPE=MyISAM COMMENT='����� ����' AUTO_INCREMENT=490 ;

--
-- ����������� � ������� `blocks`:
--   `blockname`
--       `��� �����`
--   `customer_id`
--       `������ �� ���������`
--   `drlname`
--       `��� ���������`
--   `scomp`
--       `������� comp`
--   `sizex`
--       `������ �� �`
--   `sizey`
--       `������ �� y`
--   `ssolder`
--       `������� solder`
--   `thickness`
--       `�������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `boards`
--
-- ��������: ��� 06 2009 �., 14:21
-- ��������� ����������: ��� 27 2010 �., 10:22
-- ��������� ��������: ��� 06 2009 �., 14:21
--

CREATE TABLE IF NOT EXISTS `boards` (
  `id` bigint(10) NOT NULL auto_increment,
  `board_name` varchar(255) NOT NULL default '0',
  `customer_id` bigint(10) NOT NULL default '0',
  `sizex` float(5,3) NOT NULL default '0.000',
  `sizey` float(5,3) NOT NULL default '0.000',
  `thickness` varchar(10) NOT NULL default '0.00',
  `drawing_id` bigint(10) NOT NULL default '0',
  `tex�olite` varchar(50) NOT NULL default '0',
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
  KEY `board_id` (`board_name`,`tex�olite`)
) TYPE=MyISAM COMMENT='����� �� �����, ������ �� ��� ��������� � �����' AUTO_INCREMENT=427 ;

--
-- ����������� � ������� `boards`:
--   `aurum`
--       `�������� ���������`
--   `board_name`
--       `��� ����� ��� �� ��� ������`
--   `class`
--       `����� ��������`
--   `complexity_factor`
--       `���������� ���������`
--   `customer_id`
--       `������ �� ���������`
--   `drawing_id`
--       `������  ������`
--   `frezcorner`
--       `���������� �� �������`
--   `frez_factor`
--       `����������� ��������� ����������`
--   `glasscloth`
--       `�����������`
--   `immer`
--       `����������� ��������`
--   `layers`
--       `���������� �����`
--   `lsizex`
--       `������ ������ �`
--   `lsizey`
--       `������ ������ �`
--   `mark`
--       `���������`
--   `mask`
--       `�����`
--   `numlam`
--       `���������� �������`
--   `pallad`
--       `��������������`
--   `razr`
--       `�������� �����`
--   `rmark`
--       `������ ����������`
--   `sizex`
--       `������ �� �`
--   `sizey`
--       `������ �� �`
--   `textolitepsi`
--       `��������� � ���`
--   `tex�olite`
--       `��������� �� �������`
--   `thickness`
--       `�������`
--   `thick_tol`
--       `������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `coments`
--
-- ��������: ��� 15 2009 �., 14:41
-- ��������� ����������: ��� 17 2009 �., 11:50
-- ��������� ��������: ��� 15 2009 �., 14:41
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(10) NOT NULL auto_increment,
  `comment` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `comment` (`comment`)
) TYPE=MyISAM COMMENT='������ ����������� � ��������� ����������' AUTO_INCREMENT=2722 ;

-- --------------------------------------------------------

--
-- ��������� ������� `coppers`
--
-- ��������: ��� 13 2009 �., 16:26
-- ��������� ����������: ��� 27 2010 �., 10:39
-- ��������� ��������: ��� 13 2009 �., 16:26
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
) TYPE=MyISAM COMMENT='������� �������� ����' AUTO_INCREMENT=1394 ;

--
-- ����������� � ������� `coppers`:
--   `sizex`
--       `������ ����� �� ������ �������`
--   `sizey`
--       `������ ����� �� ������ �������`
--

--
-- ����� ������� `coppers`:
--   `customer_id`
--       `customers` -> `id`
--   `plate_id`
--       `plates` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `customers`
--
-- ��������: ��� 15 2009 �., 14:40
-- ��������� ����������: ��� 21 2009 �., 14:30
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) NOT NULL auto_increment,
  `customer` varchar(40) NOT NULL default '',
  `fullname` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `customer` (`customer`)
) TYPE=MyISAM COMMENT='���������' AUTO_INCREMENT=85 ;

--
-- ����������� � ������� `customers`:
--   `fullname`
--       `������ ������������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `docs`
--
-- ��������: ��� 15 2009 �., 12:44
-- ��������� ����������: ��� 15 2009 �., 12:44
--

CREATE TABLE IF NOT EXISTS `docs` (
  `id` bigint(11) NOT NULL auto_increment,
  `did` bigint(20) NOT NULL default '0',
  `table` varchar(50) NOT NULL default '',
  `user_id` bigint(20) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `did` (`did`,`table`,`user_id`,`ts`)
) TYPE=MyISAM COMMENT='������� ���� ���������� ����' AUTO_INCREMENT=1 ;

--
-- ����������� � ������� `docs`:
--   `did`
--       `������������� ���������`
--   `table`
--       `��� ������� ��� ���������`
--   `ts`
--       `����� ��������`
--   `user_id`
--       `������������� ������������ ����������� ��������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `eltest`
--
-- ��������: ��� 19 2009 �., 13:10
-- ��������� ����������: ��� 27 2010 �., 10:17
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
) TYPE=MyISAM COMMENT='��������������' AUTO_INCREMENT=72 ;

--
-- ����������� � ������� `eltest`:
--   `board_id`
--       `������ �� �����`
--   `factor`
--       `����������� ���������`
--   `numcomp`
--       `���������� ����������`
--   `numpl`
--       `���������� �������`
--   `pib`
--       `���������� ���� � �����`
--   `points`
--       `���������� ����� �� �����`
--   `pointsb`
--       `����� � �����`
--   `sizex`
--       `������ ��������`
--   `sizey`
--       `������ ��������`
--   `type`
--       `��� ��������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `filelinks`
--
-- ��������: ��� 15 2009 �., 14:40
-- ��������� ����������: ��� 27 2010 �., 10:40
--

CREATE TABLE IF NOT EXISTS `filelinks` (
  `id` bigint(10) NOT NULL auto_increment,
  `file_link` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='������ �� �����' AUTO_INCREMENT=1341 ;

-- --------------------------------------------------------

--
-- ��������� ������� `lanch`
--
-- ��������: ��� 15 2009 �., 14:40
-- ��������� ����������: ��� 27 2010 �., 10:40
-- ��������� ��������: ��� 24 2009 �., 16:28
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
) TYPE=MyISAM COMMENT='�������' AUTO_INCREMENT=2236 ;

--
-- ����������� � ������� `lanch`:
--   `pos_in_tz_id`
--       `������ �� ������� ��`
--

--
-- ����� ������� `lanch`:
--   `board_id`
--       `plates` -> `id`
--   `comment_id`
--       `coments` -> `id`
--   `file_link_id`
--       `filelinks` -> `id`
--   `tz_id`
--       `tz` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `lanched`
--
-- ��������: ��� 26 2010 �., 11:32
-- ��������� ����������: ��� 27 2010 �., 10:40
--

CREATE TABLE IF NOT EXISTS `lanched` (
  `board_id` bigint(10) NOT NULL default '0',
  `lastdate` date default NULL,
  PRIMARY KEY  (`board_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- ��������� ������� `logs`
--
-- ��������: ��� 19 2009 �., 15:21
-- ��������� ����������: ��� 27 2010 �., 10:40
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint(10) NOT NULL auto_increment,
  `logdate` timestamp(14) NOT NULL,
  `user_id` bigint(10) NOT NULL default '0',
  `sqltext` text NOT NULL,
  `action` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `logdate` (`logdate`,`user_id`)
) TYPE=MyISAM COMMENT='����������� ��������' AUTO_INCREMENT=1807 ;

--
-- ����������� � ������� `logs`:
--   `action`
--       `��� �������`
--   `sqltext`
--       `��� �����`
--   `user_id`
--       `��� �����`
--

-- --------------------------------------------------------

--
-- ��������� ������� `masterplate`
--
-- ��������: ��� 23 2009 �., 14:33
-- ��������� ����������: ��� 27 2010 �., 10:11
--

CREATE TABLE IF NOT EXISTS `masterplate` (
  `id` bigint(10) NOT NULL auto_increment,
  `tz_id` bigint(10) NOT NULL default '0',
  `posintz` int(11) NOT NULL default '0',
  `mpdate` date NOT NULL default '0000-00-00',
  `user_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='�����������' AUTO_INCREMENT=103 ;

--
-- ����� ������� `masterplate`:
--   `tz_id`
--       `tz` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `mppdop`
--
-- ��������: ��� 15 2009 �., 12:25
-- ��������� ����������: ��� 27 2010 �., 10:17
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
) TYPE=MyISAM COMMENT='������������� ��������� � ������������ ��� �����������������' AUTO_INCREMENT=72 ;

--
-- ����������� � ������� `mppdop`:
--   `block_id`
--       `������ �� ����`
--   `dfrez`
--       `������������� ����������`
--   `dop`
--       `���������: � ������, ���� ������������, �������� ��������, ������������,`
--   `m1`
--       `�������� 1`
--   `m2`
--       `�������� 2`
--   `m3`
--       `�������� 3`
--   `m4`
--       `�������� 4`
--   `m5`
--       `�������� 5`
--   `m6`
--       `�������� 6`
--   `m7`
--       `�������� 7`
--   `m8`
--       `�������� 8`
--   `mn1`
--       `��������� ������ 1`
--   `mn2`
--       `��������� ������ 2`
--   `mn3`
--       `��������� ������ 3`
--   `mn4`
--       `��������� ������ 4`
--   `mn5`
--       `��������� ������ 5`
--   `mn6`
--       `��������� ������ 6`
--   `mn7`
--       `��������� ������ 7`
--   `mn8`
--       `��������� ������ 8`
--   `ndraw`
--       `����� ������`
--   `nprokl`
--       `���������� ���������`
--   `nstek`
--       `���������� ������ �����������`
--   `osob`
--       `������ ������� 2`
--   `tprokl`
--       `������� ���������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `orders`
--
-- ��������: ��� 15 2009 �., 14:39
-- ��������� ����������: ��� 27 2010 �., 10:12
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(10) NOT NULL auto_increment,
  `orderdate` date NOT NULL default '0000-00-00',
  `number` varchar(50) NOT NULL default '',
  `customer_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='������' AUTO_INCREMENT=490 ;

--
-- ����� ������� `orders`:
--   `customer_id`
--       `customers` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `phototemplates`
--
-- ��������: ��� 15 2009 �., 14:39
-- ��������� ����������: ��� 26 2010 �., 15:45
--

CREATE TABLE IF NOT EXISTS `phototemplates` (
  `id` bigint(10) NOT NULL auto_increment,
  `ts` timestamp(14) NOT NULL,
  `user_id` bigint(10) NOT NULL default '0',
  `filenames` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='����������� ������������ �� ���������' AUTO_INCREMENT=5713 ;

--
-- ����� ������� `phototemplates`:
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `plates`
--
-- ��������: ��� 15 2009 �., 14:38
-- ��������� ����������: ��� 27 2010 �., 10:33
-- ��������� ��������: ��� 15 2009 �., 14:38
--

CREATE TABLE IF NOT EXISTS `plates` (
  `id` int(10) NOT NULL auto_increment,
  `plate` varchar(255) NOT NULL default '',
  `customer_id` int(10) NOT NULL default '0',
  `isblock` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `plate` (`plate`),
  KEY `customer_id` (`customer_id`)
) TYPE=MyISAM COMMENT='����� (����������� ����� excel' AUTO_INCREMENT=2476 ;

--
-- ����������� � ������� `plates`:
--   `isblock`
--       `���� ��� �����?`
--

--
-- ����� ������� `plates`:
--   `customer_id`
--       `customers` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `posintz`
--
-- ��������: ��� 26 2010 �., 12:01
-- ��������� ����������: ��� 27 2010 �., 10:40
-- ��������� ��������: ��� 26 2010 �., 12:01
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
  `priem` varchar(40) NOT NULL default '���',
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
) TYPE=MyISAM COMMENT='������� � ��' AUTO_INCREMENT=2406 ;

--
-- ����������� � ������� `posintz`:
--   `block_id`
--       `������ �� ����`
--   `board_id`
--       `������ �� ����� � �����`
--   `comment_id`
--       `������ �� ����������� �������`
--   `constr`
--       `����� ����������, �`
--   `eltest`
--       `���� ���������������?`
--   `first`
--       `��������� ������������`
--   `ldate`
--       `���� �������`
--   `luser_id`
--       `������ �� ������������ ������������`
--   `mark`
--       `C �����������?`
--   `mask`
--       `C ������?`
--   `numbl`
--       `���������� ����������� ������`
--   `numpl`
--       `���������� ����������� ����`
--   `pip`
--       `���� � ������, ��� ���������� ������ � �� ���� � �� �����`
--   `pitz_mater`
--       `�������� ��� ����� ��������� � ��`
--   `pitz_psimat`
--       `�������� � ��� ��� ����� ��������� � ��`
--   `priem`
--       `�������`
--   `srok`
--       `���� ������������`
--   `template_check`
--       `��������� ��������`
--   `template_make`
--       `���������� ��������`
--

--
-- ����� ������� `posintz`:
--   `plate_id`
--       `plates` -> `id`
--   `tz_id`
--       `tz` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `relations`
--
-- ��������: ��� 15 2009 �., 12:49
-- ��������� ����������: ��� 15 2009 �., 12:49
--

CREATE TABLE IF NOT EXISTS `relations` (
  `id` bigint(20) NOT NULL default '0',
  `did1` bigint(20) NOT NULL default '0',
  `did2` bigint(20) NOT NULL default '0',
  `rtype` bigint(20) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `did1` (`did1`,`did2`,`rtype`)
) TYPE=MyISAM COMMENT='������� ��������� ����� �����������';

--
-- ����������� � ������� `relations`:
--   `did1`
--       `������������� �������� ��������� �� ������� docs`
--   `did2`
--       `������������� �������� ��������� �� ������� docs`
--   `rtype`
--       `������������� �� ������� ���������`
--   `ts`
--       `����� �������� ���������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `reltype`
--
-- ��������: ��� 15 2009 �., 14:38
-- ��������� ����������: ��� 15 2009 �., 14:38
--

CREATE TABLE IF NOT EXISTS `reltype` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='���� ��������� ����� �����������' AUTO_INCREMENT=1 ;

--
-- ����������� � ������� `reltype`:
--   `type`
--       `�������� ���������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `rights`
--
-- ��������: ��� 21 2009 �., 14:01
-- ��������� ����������: ��� 19 2009 �., 12:36
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` bigint(20) NOT NULL auto_increment,
  `u_id` bigint(20) NOT NULL default '0',
  `type_id` bigint(20) NOT NULL default '0',
  `rtype_id` bigint(20) NOT NULL default '0',
  `right` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `u_id` (`u_id`)
) TYPE=MyISAM COMMENT='������� ���� ������� � ���������� �����' AUTO_INCREMENT=418 ;

--
-- ����������� � ������� `rights`:
--   `right`
--       `���������-�� ���������`
--   `rtype_id`
--       `��� ����`
--   `type_id`
--       `��� ������ � �������� �����`
--   `u_id`
--       `������������� ������������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `rrtypes`
--
-- ��������: ��� 21 2009 �., 14:04
-- ��������� ����������: ��� 19 2009 �., 13:12
-- ��������� ��������: ��� 19 2009 �., 13:12
--

CREATE TABLE IF NOT EXISTS `rrtypes` (
  `id` bigint(20) NOT NULL auto_increment,
  `rtype` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='���� �������' AUTO_INCREMENT=16 ;

--
-- ����������� � ������� `rrtypes`:
--   `rtype`
--       `���� �������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `rtypes`
--
-- ��������: ��� 21 2009 �., 14:02
-- ��������� ����������: ��� 22 2009 �., 13:03
--

CREATE TABLE IF NOT EXISTS `rtypes` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='���� ��� �������' AUTO_INCREMENT=18 ;

--
-- ����������� � ������� `rtypes`:
--   `type`
--       `Nbgf � �������� ����� ������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `session`
--
-- ��������: ��� 15 2010 �., 12:51
-- ��������� ����������: ��� 27 2010 �., 10:40
--

CREATE TABLE IF NOT EXISTS `session` (
  `session` varchar(255) NOT NULL default '',
  `u_id` bigint(12) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  PRIMARY KEY  (`session`)
) TYPE=MyISAM;

--
-- ����������� � ������� `session`:
--   `ts`
--       `����� �����������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `todo`
--
-- ��������: ��� 22 2009 �., 12:48
-- ��������� ����������: ��� 26 2010 �., 13:00
--

CREATE TABLE IF NOT EXISTS `todo` (
  `id` bigint(20) NOT NULL auto_increment,
  `what` text NOT NULL,
  `cts` timestamp(14) NOT NULL,
  `rts` timestamp(14) NOT NULL,
  `u_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='��� ������� �������' AUTO_INCREMENT=30 ;

--
-- ����������� � ������� `todo`:
--   `cts`
--       `���� ��������`
--   `rts`
--       `���� ����������`
--   `u_id`
--       `��� �������`
--   `what`
--       `��� ���������� �������`
--

-- --------------------------------------------------------

--
-- ��������� ������� `tz`
--
-- ��������: ��� 15 2009 �., 14:36
-- ��������� ����������: ��� 27 2010 �., 10:33
--

CREATE TABLE IF NOT EXISTS `tz` (
  `id` bigint(10) NOT NULL auto_increment,
  `order_id` bigint(10) NOT NULL default '0',
  `tz_date` date NOT NULL default '0000-00-00',
  `user_id` bigint(10) NOT NULL default '0',
  `pos_in_order` tinyint(4) NOT NULL default '1',
  `file_link_id` bigint(10) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='����������� �������' AUTO_INCREMENT=772 ;

--
-- ����� ������� `tz`:
--   `file_link_id`
--       `filelinks` -> `id`
--   `order_id`
--       `orders` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- ��������� ������� `users`
--
-- ��������: ��� 15 2010 �., 12:20
-- ��������� ����������: ��� 15 2010 �., 12:20
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
) TYPE=MyISAM COMMENT='����������� ���� ������' AUTO_INCREMENT=18 ;

--
-- ����������� � ������� `users`:
--   `password`
--       `������ � �������� ����`
--

-- --------------------------------------------------------

--
-- ��������� ������� `workers`
--
-- ��������: ��� 15 2009 �., 14:36
-- ��������� ����������: ��� 04 2009 �., 09:33
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
) TYPE=MyISAM COMMENT='������� � ����� ��������' AUTO_INCREMENT=86 ;

-- --------------------------------------------------------

--
-- ��������� ������� `zadel`
--
-- ��������: ��� 15 2009 �., 11:01
-- ��������� ����������: ��� 20 2010 �., 10:31
-- ��������� ��������: ��� 19 2009 �., 13:12
--

CREATE TABLE IF NOT EXISTS `zadel` (
  `id` bigint(10) NOT NULL auto_increment,
  `board_id` bigint(10) NOT NULL default '0',
  `ldate` date NOT NULL default '0000-00-00',
  `niz` varchar(10) NOT NULL default '',
  `number` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='����� � ���' AUTO_INCREMENT=1516 ;

--
-- ����������� � ������� `zadel`:
--   `ldate`
--       `���� �������`
--   `niz`
--       `����� ���������`
--   `number`
--       `����������`
--
