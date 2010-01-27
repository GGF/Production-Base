-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 27 2010 г., 10:42
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
-- Создание: Окт 15 2009 г., 14:41
-- Последнее обновление: Янв 27 2010 г., 10:22
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
) TYPE=MyISAM COMMENT='Позиции в блоках' AUTO_INCREMENT=427 ;

--
-- Комментарии к таблице `blockpos`:
--   `block_id`
--       `Ссылка а блок`
--   `board_id`
--       `Ссылка на плату`
--   `nib`
--       `Количество в блоке`
--   `nx`
--       `Количество по х`
--   `ny`
--       `Количество по у`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blocks`
--
-- Создание: Окт 15 2009 г., 12:23
-- Последнее обновление: Янв 27 2010 г., 10:39
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
) TYPE=MyISAM COMMENT='Блоки плат' AUTO_INCREMENT=490 ;

--
-- Комментарии к таблице `blocks`:
--   `blockname`
--       `Имя блока`
--   `customer_id`
--       `ссылка на заказчика`
--   `drlname`
--       `Имя сверловки`
--   `scomp`
--       `Площадь comp`
--   `sizex`
--       `размер по х`
--   `sizey`
--       `размер по y`
--   `ssolder`
--       `Площадь solder`
--   `thickness`
--       `толщина`
--

-- --------------------------------------------------------

--
-- Структура таблицы `boards`
--
-- Создание: Ноя 06 2009 г., 14:21
-- Последнее обновление: Янв 27 2010 г., 10:22
-- Последняя проверка: Ноя 06 2009 г., 14:21
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
) TYPE=MyISAM COMMENT='Даные по плате, только те что относятся к плате' AUTO_INCREMENT=427 ;

--
-- Комментарии к таблице `boards`:
--   `aurum`
--       `Золчение контактов`
--   `board_name`
--       `Имя платы для ТЗ без экселя`
--   `class`
--       `класс точности`
--   `complexity_factor`
--       `Коэфициент сложности`
--   `customer_id`
--       `Ссылка на заказчика`
--   `drawing_id`
--       `ссылка  чертеж`
--   `frezcorner`
--       `Фрезеровка по контуру`
--   `frez_factor`
--       `Коэффициент сложности фрезеровки`
--   `glasscloth`
--       `Стеклоткань`
--   `immer`
--       `Имерсионное покрытие`
--   `layers`
--       `количество слоев`
--   `lsizex`
--       `размер ламели х`
--   `lsizey`
--       `размер ламели у`
--   `mark`
--       `Марировка`
--   `mask`
--       `Маска`
--   `numlam`
--       `Количество ламелей`
--   `pallad`
--       `Палладирование`
--   `razr`
--       `Разрубка блока`
--   `rmark`
--       `ручная маркировка`
--   `sizex`
--       `размер по х`
--   `sizey`
--       `размер по у`
--   `textolitepsi`
--       `Текстолит в ПСИ`
--   `texеolite`
--       `Текстолит по чертежу`
--   `thickness`
--       `толщина`
--   `thick_tol`
--       `допуск`
--

-- --------------------------------------------------------

--
-- Структура таблицы `coments`
--
-- Создание: Окт 15 2009 г., 14:41
-- Последнее обновление: Ноя 17 2009 г., 11:50
-- Последняя проверка: Окт 15 2009 г., 14:41
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(10) NOT NULL auto_increment,
  `comment` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `comment` (`comment`)
) TYPE=MyISAM COMMENT='Свалка коментариев к различным документам' AUTO_INCREMENT=2722 ;

-- --------------------------------------------------------

--
-- Структура таблицы `coppers`
--
-- Создание: Май 13 2009 г., 16:26
-- Последнее обновление: Янв 27 2010 г., 10:39
-- Последняя проверка: Май 13 2009 г., 16:26
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
) TYPE=MyISAM COMMENT='Таблица площадей плат' AUTO_INCREMENT=1394 ;

--
-- Комментарии к таблице `coppers`:
--   `sizex`
--       `Размер блока по первой стороне`
--   `sizey`
--       `Размер блока по второй стороне`
--

--
-- Связи таблицы `coppers`:
--   `customer_id`
--       `customers` -> `id`
--   `plate_id`
--       `plates` -> `id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--
-- Создание: Окт 15 2009 г., 14:40
-- Последнее обновление: Дек 21 2009 г., 14:30
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) NOT NULL auto_increment,
  `customer` varchar(40) NOT NULL default '',
  `fullname` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `customer` (`customer`)
) TYPE=MyISAM COMMENT='Заказчики' AUTO_INCREMENT=85 ;

--
-- Комментарии к таблице `customers`:
--   `fullname`
--       `Полное наименование`
--

-- --------------------------------------------------------

--
-- Структура таблицы `docs`
--
-- Создание: Окт 15 2009 г., 12:44
-- Последнее обновление: Окт 15 2009 г., 12:44
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

--
-- Комментарии к таблице `docs`:
--   `did`
--       `Идентификатор документа`
--   `table`
--       `Имя таблицы для документа`
--   `ts`
--       `Время создания`
--   `user_id`
--       `Идентификатор пользователя добавившего документ`
--

-- --------------------------------------------------------

--
-- Структура таблицы `eltest`
--
-- Создание: Ноя 19 2009 г., 13:10
-- Последнее обновление: Янв 27 2010 г., 10:17
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
) TYPE=MyISAM COMMENT='Электроконторь' AUTO_INCREMENT=72 ;

--
-- Комментарии к таблице `eltest`:
--   `board_id`
--       `Ссылка на плату`
--   `factor`
--       `Коэффициент сложности`
--   `numcomp`
--       `Количество комплектов`
--   `numpl`
--       `Количество пластин`
--   `pib`
--       `Количество плат в блоке`
--   `points`
--       `Количество точек на плате`
--   `pointsb`
--       `Точек в блоке`
--   `sizex`
--       `Размер пластины`
--   `sizey`
--       `Размер пластины`
--   `type`
--       `Тип контроля`
--

-- --------------------------------------------------------

--
-- Структура таблицы `filelinks`
--
-- Создание: Окт 15 2009 г., 14:40
-- Последнее обновление: Янв 27 2010 г., 10:40
--

CREATE TABLE IF NOT EXISTS `filelinks` (
  `id` bigint(10) NOT NULL auto_increment,
  `file_link` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Ссылки на файлы' AUTO_INCREMENT=1341 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lanch`
--
-- Создание: Окт 15 2009 г., 14:40
-- Последнее обновление: Янв 27 2010 г., 10:40
-- Последняя проверка: Ноя 24 2009 г., 16:28
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
) TYPE=MyISAM COMMENT='Заупски' AUTO_INCREMENT=2236 ;

--
-- Комментарии к таблице `lanch`:
--   `pos_in_tz_id`
--       `Ссылка на позицию ТЗ`
--

--
-- Связи таблицы `lanch`:
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
-- Структура таблицы `lanched`
--
-- Создание: Янв 26 2010 г., 11:32
-- Последнее обновление: Янв 27 2010 г., 10:40
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
-- Создание: Ноя 19 2009 г., 15:21
-- Последнее обновление: Янв 27 2010 г., 10:40
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint(10) NOT NULL auto_increment,
  `logdate` timestamp(14) NOT NULL,
  `user_id` bigint(10) NOT NULL default '0',
  `sqltext` text NOT NULL,
  `action` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `logdate` (`logdate`,`user_id`)
) TYPE=MyISAM COMMENT='Логирование удалений' AUTO_INCREMENT=1807 ;

--
-- Комментарии к таблице `logs`:
--   `action`
--       `Тип дествия`
--   `sqltext`
--       `Что делал`
--   `user_id`
--       `Кто делал`
--

-- --------------------------------------------------------

--
-- Структура таблицы `masterplate`
--
-- Создание: Окт 23 2009 г., 14:33
-- Последнее обновление: Янв 27 2010 г., 10:11
--

CREATE TABLE IF NOT EXISTS `masterplate` (
  `id` bigint(10) NOT NULL auto_increment,
  `tz_id` bigint(10) NOT NULL default '0',
  `posintz` int(11) NOT NULL default '0',
  `mpdate` date NOT NULL default '0000-00-00',
  `user_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Мастерплаты' AUTO_INCREMENT=103 ;

--
-- Связи таблицы `masterplate`:
--   `tz_id`
--       `tz` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `mppdop`
--
-- Создание: Окт 15 2009 г., 12:25
-- Последнее обновление: Янв 27 2010 г., 10:17
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
) TYPE=MyISAM COMMENT='Доплнительные материалы к многослойкам для сопроводительного' AUTO_INCREMENT=72 ;

--
-- Комментарии к таблице `mppdop`:
--   `block_id`
--       `Ссылка на блок`
--   `dfrez`
--       `Доплнительная фрезеровка`
--   `dop`
--       `Материалы: № партии, дата изготовления, входного контроля, перепроверки,`
--   `m1`
--       `Матриалы 1`
--   `m2`
--       `Матриалы 2`
--   `m3`
--       `Матриалы 3`
--   `m4`
--       `Матриалы 4`
--   `m5`
--       `Матриалы 5`
--   `m6`
--       `Матриалы 6`
--   `m7`
--       `Матриалы 7`
--   `m8`
--       `Матриалы 8`
--   `mn1`
--       `Материалы номера 1`
--   `mn2`
--       `Материалы номера 2`
--   `mn3`
--       `Материалы номера 3`
--   `mn4`
--       `Материалы номера 4`
--   `mn5`
--       `Материалы номера 5`
--   `mn6`
--       `Материалы номера 6`
--   `mn7`
--       `Материалы номера 7`
--   `mn8`
--       `Материалы номера 8`
--   `ndraw`
--       `Номер ертежа`
--   `nprokl`
--       `Количество прокладок`
--   `nstek`
--       `Количество листов стеклоткани`
--   `osob`
--       `Особое казание 2`
--   `tprokl`
--       `Толщина прокладок`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--
-- Создание: Окт 15 2009 г., 14:39
-- Последнее обновление: Янв 27 2010 г., 10:12
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(10) NOT NULL auto_increment,
  `orderdate` date NOT NULL default '0000-00-00',
  `number` varchar(50) NOT NULL default '',
  `customer_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Заказы' AUTO_INCREMENT=490 ;

--
-- Связи таблицы `orders`:
--   `customer_id`
--       `customers` -> `id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `phototemplates`
--
-- Создание: Окт 15 2009 г., 14:39
-- Последнее обновление: Янв 26 2010 г., 15:45
--

CREATE TABLE IF NOT EXISTS `phototemplates` (
  `id` bigint(10) NOT NULL auto_increment,
  `ts` timestamp(14) NOT NULL,
  `user_id` bigint(10) NOT NULL default '0',
  `filenames` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Фотошаблоны отправленные на рисование' AUTO_INCREMENT=5713 ;

--
-- Связи таблицы `phototemplates`:
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `plates`
--
-- Создание: Окт 15 2009 г., 14:38
-- Последнее обновление: Янв 27 2010 г., 10:33
-- Последняя проверка: Окт 15 2009 г., 14:38
--

CREATE TABLE IF NOT EXISTS `plates` (
  `id` int(10) NOT NULL auto_increment,
  `plate` varchar(255) NOT NULL default '',
  `customer_id` int(10) NOT NULL default '0',
  `isblock` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `plate` (`plate`),
  KEY `customer_id` (`customer_id`)
) TYPE=MyISAM COMMENT='Платы (заполняется через excel' AUTO_INCREMENT=2476 ;

--
-- Комментарии к таблице `plates`:
--   `isblock`
--       `Блок или плата?`
--

--
-- Связи таблицы `plates`:
--   `customer_id`
--       `customers` -> `id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `posintz`
--
-- Создание: Янв 26 2010 г., 12:01
-- Последнее обновление: Янв 27 2010 г., 10:40
-- Последняя проверка: Янв 26 2010 г., 12:01
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
) TYPE=MyISAM COMMENT='Позиции в ТЗ' AUTO_INCREMENT=2406 ;

--
-- Комментарии к таблице `posintz`:
--   `block_id`
--       `ссылка на блок`
--   `board_id`
--       `Ссылка на плату в блоке`
--   `comment_id`
--       `Ссылка на комментарий запуска`
--   `constr`
--       `Время подготовки, ч`
--   `eltest`
--       `Есть электроконтроль?`
--   `first`
--       `Первичное изготовление`
--   `ldate`
--       `Дата запуска`
--   `luser_id`
--       `ссылка на пользователя запускающего`
--   `mark`
--       `C маркировкой?`
--   `mask`
--       `C маской?`
--   `numbl`
--       `Количесвто тестируемых блоков`
--   `numpl`
--       `Количество тестируемых плат`
--   `pip`
--       `Плат в партии, для многослоек бывает и по пять и по одной`
--   `pitz_mater`
--       `Материал для платы указанный в ТЗ`
--   `pitz_psimat`
--       `Материал в ПСИ для платы указанный в ТЗ`
--   `priem`
--       `Приемка`
--   `srok`
--       `Срок изготовления`
--   `template_check`
--       `Проверить шаблонов`
--   `template_make`
--       `Изготовить Шаблонов`
--

--
-- Связи таблицы `posintz`:
--   `plate_id`
--       `plates` -> `id`
--   `tz_id`
--       `tz` -> `id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `relations`
--
-- Создание: Окт 15 2009 г., 12:49
-- Последнее обновление: Окт 15 2009 г., 12:49
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

--
-- Комментарии к таблице `relations`:
--   `did1`
--       `Идентификатор старшего документа из таблицы docs`
--   `did2`
--       `Идентификатор младшего документа из таблицы docs`
--   `rtype`
--       `Идентификатор из таблицы отношений`
--   `ts`
--       `время создания отношения`
--

-- --------------------------------------------------------

--
-- Структура таблицы `reltype`
--
-- Создание: Окт 15 2009 г., 14:38
-- Последнее обновление: Окт 15 2009 г., 14:38
--

CREATE TABLE IF NOT EXISTS `reltype` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Типы отношений между документами' AUTO_INCREMENT=1 ;

--
-- Комментарии к таблице `reltype`:
--   `type`
--       `Название отношения`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--
-- Создание: Окт 21 2009 г., 14:01
-- Последнее обновление: Ноя 19 2009 г., 12:36
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` bigint(20) NOT NULL auto_increment,
  `u_id` bigint(20) NOT NULL default '0',
  `type_id` bigint(20) NOT NULL default '0',
  `rtype_id` bigint(20) NOT NULL default '0',
  `right` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `u_id` (`u_id`)
) TYPE=MyISAM COMMENT='Таблица прав доступа к управлению базой' AUTO_INCREMENT=418 ;

--
-- Комментарии к таблице `rights`:
--   `right`
--       `разрешено-не ращрешено`
--   `rtype_id`
--       `Тип прав`
--   `type_id`
--       `Тип данных к которому права`
--   `u_id`
--       `Идентификатор пользователя`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rrtypes`
--
-- Создание: Окт 21 2009 г., 14:04
-- Последнее обновление: Ноя 19 2009 г., 13:12
-- Последняя проверка: Ноя 19 2009 г., 13:12
--

CREATE TABLE IF NOT EXISTS `rrtypes` (
  `id` bigint(20) NOT NULL auto_increment,
  `rtype` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Типы доступа' AUTO_INCREMENT=16 ;

--
-- Комментарии к таблице `rrtypes`:
--   `rtype`
--       `Типы доступа`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rtypes`
--
-- Создание: Окт 21 2009 г., 14:02
-- Последнее обновление: Окт 22 2009 г., 13:03
--

CREATE TABLE IF NOT EXISTS `rtypes` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Типы для доступа' AUTO_INCREMENT=18 ;

--
-- Комментарии к таблице `rtypes`:
--   `type`
--       `Nbgf к которому будет доступ`
--

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--
-- Создание: Янв 15 2010 г., 12:51
-- Последнее обновление: Янв 27 2010 г., 10:40
--

CREATE TABLE IF NOT EXISTS `session` (
  `session` varchar(255) NOT NULL default '',
  `u_id` bigint(12) NOT NULL default '0',
  `ts` timestamp(14) NOT NULL,
  PRIMARY KEY  (`session`)
) TYPE=MyISAM;

--
-- Комментарии к таблице `session`:
--   `ts`
--       `Время авторизации`
--

-- --------------------------------------------------------

--
-- Структура таблицы `todo`
--
-- Создание: Окт 22 2009 г., 12:48
-- Последнее обновление: Янв 26 2010 г., 13:00
--

CREATE TABLE IF NOT EXISTS `todo` (
  `id` bigint(20) NOT NULL auto_increment,
  `what` text NOT NULL,
  `cts` timestamp(14) NOT NULL,
  `rts` timestamp(14) NOT NULL,
  `u_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Что сделать таблица' AUTO_INCREMENT=30 ;

--
-- Комментарии к таблице `todo`:
--   `cts`
--       `Дата создания`
--   `rts`
--       `Дата завершения`
--   `u_id`
--       `Кто добавил`
--   `what`
--       `Что собственно сделать`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tz`
--
-- Создание: Окт 15 2009 г., 14:36
-- Последнее обновление: Янв 27 2010 г., 10:33
--

CREATE TABLE IF NOT EXISTS `tz` (
  `id` bigint(10) NOT NULL auto_increment,
  `order_id` bigint(10) NOT NULL default '0',
  `tz_date` date NOT NULL default '0000-00-00',
  `user_id` bigint(10) NOT NULL default '0',
  `pos_in_order` tinyint(4) NOT NULL default '1',
  `file_link_id` bigint(10) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Технические задания' AUTO_INCREMENT=772 ;

--
-- Связи таблицы `tz`:
--   `file_link_id`
--       `filelinks` -> `id`
--   `order_id`
--       `orders` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--
-- Создание: Янв 15 2010 г., 12:20
-- Последнее обновление: Янв 15 2010 г., 12:20
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

--
-- Комментарии к таблице `users`:
--   `password`
--       `Пароль в открытом виде`
--

-- --------------------------------------------------------

--
-- Структура таблицы `workers`
--
-- Создание: Окт 15 2009 г., 14:36
-- Последнее обновление: Дек 04 2009 г., 09:33
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
-- Создание: Сен 15 2009 г., 11:01
-- Последнее обновление: Янв 20 2010 г., 10:31
-- Последняя проверка: Ноя 19 2009 г., 13:12
--

CREATE TABLE IF NOT EXISTS `zadel` (
  `id` bigint(10) NOT NULL auto_increment,
  `board_id` bigint(10) NOT NULL default '0',
  `ldate` date NOT NULL default '0000-00-00',
  `niz` varchar(10) NOT NULL default '',
  `number` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Задел в ОТК' AUTO_INCREMENT=1516 ;

--
-- Комментарии к таблице `zadel`:
--   `ldate`
--       `Дата запуска`
--   `niz`
--       `Номер извещения`
--   `number`
--       `Количество`
--
