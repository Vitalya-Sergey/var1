-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 09 2025 г., 04:44
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `module`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `surname` varchar(256) DEFAULT NULL,
  `type` enum('user','admin') DEFAULT NULL,
  `blocked` enum('0','1') DEFAULT NULL,
  `token` varchar(256) DEFAULT NULL,
  `login` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `latest` datetime DEFAULT NULL,
  `amountAttems` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `type`, `blocked`, `token`, `login`, `password`, `latest`, `amountAttems`) VALUES
(1, 'Пётр', 'Петрович', 'user', '0', NULL, 'pep', '123', '2025-04-09 09:14:26', 0),
(2, 'Кирилл', 'Пушкин', 'admin', '', '59c63b5970d8801bd0dbe44aea1590c8', 'ki', '123', '2025-04-09 09:35:10', 0),
(3, 'Матвей', 'Максимов', 'user', '0', NULL, 'mam', '$2y$10$Y50VKuBtqrZc4Cvo74YNcOOo2iZlj.hfysqDa7Pk2BR2M5Et5iqEy', NULL, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
