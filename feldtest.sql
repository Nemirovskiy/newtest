SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `answ` (
  `answ_id` int(11) NOT NULL,
  `quest_id` int(11) NOT NULL,
  `answ_order` int(11) NOT NULL,
  `answ_right` tinyint(1) NOT NULL,
  `answ_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `answ` (`answ_id`, `quest_id`, `answ_order`, `answ_right`, `answ_text`) VALUES
(1, 1, 1, 0, 'Правильное чередование +зубцов Р, нормальных QRS с ЧСС 40-60 в 1 мин'),
(2, 1, 2, 1, 'Отрицательный Р перед или за нормальными QRS с ЧСС 40-60, либо его отсутствие'),
(3, 1, 3, 0, 'Зубца Р нет, есть волны f, нормальный QRS c ЧСС 40-60');

CREATE TABLE `page` (
  `code` varchar(10) NOT NULL,
  `menu` tinyint(4) NOT NULL,
  `title` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `page` (`code`, `menu`, `title`) VALUES
('admin', 0, 'Администратор'),
('feld', 4, 'Фельдшеры'),
('help', 5, 'Инструкция'),
('index', 1, 'Главная'),
('ser', 2, 'СанЭпидРежим'),
('smp', 3, 'Скорая помощь');

CREATE TABLE `quest` (
  `quest_id` tinyint(4) NOT NULL,
  `theme_code` varchar(10) NOT NULL,
  `quest_number` int(11) NOT NULL,
  `quest_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `quest` (`quest_id`, `theme_code`, `quest_number`, `quest_text`) VALUES
(1, 'feld', 2, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК'),
(2, 'feld', 3, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК'),
(3, 'ser', 1, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК'),
(4, 'smp', 1, 'ИМПУЛЬС, ВЫШЕДШИЙ ИЗ А/В УЗЛА, ВЫГЛЯДИТ НА ЭКГ КАК');

CREATE TABLE `theme` (
  `theme_code` varchar(10) NOT NULL,
  `theme_text` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `theme` (`theme_code`, `theme_text`) VALUES
('feld', 'Фельдшеры'),
('ser', 'СанЭпидРежим'),
('smp', 'Скорая помощь');


ALTER TABLE `answ`
  ADD PRIMARY KEY (`answ_id`);

ALTER TABLE `page`
  ADD PRIMARY KEY (`code`);

ALTER TABLE `quest`
  ADD PRIMARY KEY (`quest_id`);

ALTER TABLE `theme`
  ADD PRIMARY KEY (`theme_code`),
  ADD UNIQUE KEY `theme_code` (`theme_code`);


ALTER TABLE `answ`
  MODIFY `answ_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `quest`
  MODIFY `quest_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
