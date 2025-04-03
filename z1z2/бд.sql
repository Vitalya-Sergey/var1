-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 24 2025 г., 04:08
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
-- База данных: `hotel_management`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `room_number` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `floors`
--

CREATE TABLE `floors` (
  `floor_id` int(11) NOT NULL,
  `floor_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `floors`
--

INSERT INTO `floors` (`floor_id`, `floor_name`) VALUES
(1, '1 этаж'),
(2, '2 этаж'),
(3, '3 этаж');

-- --------------------------------------------------------

--
-- Структура таблицы `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `guests`
--

INSERT INTO `guests` (`guest_id`, `full_name`) VALUES
(1, 'Шевченко Ольга Викторовна'),
(2, 'Мазалова Ирина Львовна'),
(3, 'Семеняка Юрий Геннадьевич'),
(4, 'Савельев Олег Иванович'),
(5, 'Бунин Эдуард Михайлович'),
(6, 'Бакшиев Павел Иннокентьевич'),
(7, 'Тюренкова Наталья Сергеевна'),
(8, 'Любящева Галина Аркадьевна'),
(9, 'Александров Петр Константинович'),
(10, 'Мазалова Ольга Николаевна'),
(11, 'Лапшин Виктор Романович'),
(12, 'Гусев Семен Петрович'),
(13, 'Гладилина Вера Михайловна'),
(14, 'Масюк Динара Викторовна'),
(15, 'Лукин Илья Федорович'),
(16, 'Петров Станислав Игоревич'),
(17, 'Филь Марина Федоровна'),
(18, 'Михайлов Игорь Вадимович');

-- --------------------------------------------------------

--
-- Структура таблицы `roomcategories`
--

CREATE TABLE `roomcategories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `roomcategories`
--

INSERT INTO `roomcategories` (`category_id`, `category_name`) VALUES
(1, 'Одноместный стандарт'),
(2, 'Одноместный эконом'),
(3, 'Стандарт двухместный с 2 раздельными кроватями'),
(4, 'Эконом двухместный с 2 раздельными кроватями'),
(5, '3-местный бюджет'),
(6, 'Бизнес с 1 или 2 кроватями'),
(7, 'Двухкомнатный двухместный стандарт с 1 или 2 кроватями'),
(8, 'Студия'),
(9, 'Люкс с 2 двуспальными кроватями');

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `room_number` int(11) NOT NULL,
  `floor_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roomstatus`
--

CREATE TABLE `roomstatus` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `roomstatus`
--

INSERT INTO `roomstatus` (`status_id`, `status_name`) VALUES
(1, 'Занят'),
(2, 'Чистый'),
(3, 'Грязный'),
(4, 'Назначен к уборке');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Администратор','Пользователь') NOT NULL,
  `is_blocked` tinyint(1) DEFAULT 0,
  `login_attempts` int(11) DEFAULT 0,
  `first_login` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `role`, `is_blocked`, `login_attempts`, `first_login`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Администратор', 0, 0, 1, '2025-03-24 10:07:56', '2025-03-24 10:07:56');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `room_number` (`room_number`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Индексы таблицы `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`floor_id`);

--
-- Индексы таблицы `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`);

--
-- Индексы таблицы `roomcategories`
--
ALTER TABLE `roomcategories`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_number`),
  ADD KEY `floor_id` (`floor_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `roomstatus`
--
ALTER TABLE `roomstatus`
  ADD PRIMARY KEY (`status_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `roomcategories`
--
ALTER TABLE `roomcategories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `roomstatus`
--
ALTER TABLE `roomstatus`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_number`) REFERENCES `rooms` (`room_number`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`guest_id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `roomstatus` (`status_id`),
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`floor_id`),
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `roomcategories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
