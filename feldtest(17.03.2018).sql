-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 17 2018 г., 08:39
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

-- --------------------------------------------------------

--
-- Структура таблицы `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `answ` (
  `answ_id` int(11) NOT NULL,
  `quest_id` int(11) NOT NULL,
  `answ_order` int(11) NOT NULL,
  `answ_right` tinyint(1) NOT NULL DEFAULT '0',
  `answ_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `page` (
  `code` varchar(20) NOT NULL,
  `menu` tinyint(1) DEFAULT NULL,
  `admin_menu` tinyint(1) DEFAULT NULL,
  `title` varchar(20) NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '1'
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

CREATE TABLE `quest` (
  `quest_id` tinyint(4) NOT NULL,
  `theme_code` varchar(10) NOT NULL,
  `quest_number` int(11) NOT NULL,
  `quest_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `theme` (
  `theme_code` varchar(10) NOT NULL,
  `theme_text` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `theme`
--

INSERT INTO `theme` (`theme_code`, `theme_text`) VALUES
('feld', 'Фельдшеры'),
('ser', 'СанЭпидРежим'),
('smp', 'Скорая помощь');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `answ`
--
ALTER TABLE `answ`
  ADD PRIMARY KEY (`answ_id`);

--
-- Индексы таблицы `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`code`);

--
-- Индексы таблицы `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`quest_id`);

--
-- Индексы таблицы `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`theme_code`),
  ADD UNIQUE KEY `theme_code` (`theme_code`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `answ`
--
ALTER TABLE `answ`
  MODIFY `answ_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `quest`
--
ALTER TABLE `quest`
  MODIFY `quest_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
