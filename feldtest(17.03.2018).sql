-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 17 2018 г., 08:39
-- Версия сервера: 5.7.20
-- Версия PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `feldtest`
--
CREATE DATABASE IF NOT EXISTS `feldtest` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `feldtest`;

-- --------------------------------------------------------

--
-- Структура таблицы `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `access`
--

INSERT INTO `access` (`id`, `name`) VALUES
(1, 'all'),
(2, 'login'),
(3, 'edit'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `answ`
--

CREATE TABLE IF NOT EXISTS `answ` (
  `answ_id` int(11) NOT NULL AUTO_INCREMENT,
  `quest_id` int(11) NOT NULL,
  `answ_order` int(11) NOT NULL,
  `answ_right` tinyint(1) NOT NULL DEFAULT '0',
  `answ_text` text NOT NULL,
  PRIMARY KEY (`answ_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answ`
--

INSERT INTO `answ` (`answ_id`, `quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES
(1, 1, 1, 0, 'Правильное чередование +зубцов Р, нормальных QRS с ЧСС 40-60 в 1 мин'),
(2, 1, 2, 1, 'Отрицательный Р перед или за нормальными QRS с ЧСС 40-60, либо его отсутствие'),
(3, 1, 3, 0, 'Зубца Р нет, есть волны f, нормальный QRS c ЧСС 40-60');

-- --------------------------------------------------------

--
-- Структура таблицы `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `code` varchar(20) NOT NULL,
  `menu` tinyint(1) DEFAULT NULL,
  `admin_menu` tinyint(1) DEFAULT NULL,
  `title` varchar(20) NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `page`
--

INSERT INTO `page` (`code`, `menu`, `admin_menu`, `title`, `access`) VALUES
('addtest', NULL, 11, 'Добавление тестов', 4),
('admin', NULL, 10, 'Администратор', 4),
('feld', 4, NULL, 'Фельдшеры', 1),
('help', 5, NULL, 'Инструкция', 1),
('index', 1, 1, 'Главная', 1),
('login', NULL, 5, 'Вход на сайт', 1),
('ser', 2, NULL, 'СанЭпидРежим', 1),
('smp', 3, NULL, 'Скорая помощь', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `quest`
--

CREATE TABLE IF NOT EXISTS `quest` (
  `quest_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `theme_code` varchar(10) NOT NULL,
  `quest_number` int(11) NOT NULL,
  `quest_text` text NOT NULL,
  PRIMARY KEY (`quest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `quest`
--

INSERT INTO `quest` (`quest_id`, `theme_code`, `quest_number`, `quest_text`) VALUES
(1, 'feld', 2, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК'),
(2, 'feld', 3, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК'),
(3, 'ser', 1, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК'),
(4, 'smp', 1, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК');

-- --------------------------------------------------------

--
-- Структура таблицы `theme`
--

CREATE TABLE IF NOT EXISTS `theme` (
  `theme_code` varchar(10) NOT NULL,
  `theme_text` varchar(20) NOT NULL,
  PRIMARY KEY (`theme_code`),
  UNIQUE KEY `theme_code` (`theme_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `theme`
--

INSERT INTO `theme` (`theme_code`, `theme_text`) VALUES
('feld', 'Фельдшеры'),
('ser', 'СанЭпидРежим'),
('smp', 'Скорая помощь');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
