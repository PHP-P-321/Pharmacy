-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 24 2024 г., 12:57
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pharmacy`
--

-- --------------------------------------------------------

--
-- Структура таблицы `deleted_medications`
--

CREATE TABLE `deleted_medications` (
  `id` int(11) NOT NULL,
  `id_medication` int(11) NOT NULL,
  `id_reason` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `deleted_medications`
--

INSERT INTO `deleted_medications` (`id`, `id_medication`, `id_reason`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `medications`
--

CREATE TABLE `medications` (
  `id` int(11) NOT NULL,
  `id_warehouse` text NOT NULL,
  `name_medication` varchar(100) NOT NULL,
  `quantity_medication` text NOT NULL,
  `expiration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `medications`
--

INSERT INTO `medications` (`id`, `id_warehouse`, `name_medication`, `quantity_medication`, `expiration_date`) VALUES
(1, '2, 4', 'Препарат 1', '250, 10', '2024-09-30'),
(2, '1, 3', 'Препарат 2', '100, 50', '2024-04-27'),
(3, '1, 3', 'Препарат 3', '100, 80', '2024-04-28');

-- --------------------------------------------------------

--
-- Структура таблицы `reasons`
--

CREATE TABLE `reasons` (
  `id` int(11) NOT NULL,
  `name_reason` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `reasons`
--

INSERT INTO `reasons` (`id`, `name_reason`) VALUES
(1, 'Причина списания 1'),
(2, 'Причина списания 2'),
(3, 'Причина списания 3'),
(4, 'Причина списания 4');

-- --------------------------------------------------------

--
-- Структура таблицы `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `ids_medical` text NOT NULL,
  `quantityes` text NOT NULL,
  `urgency` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `requests`
--

INSERT INTO `requests` (`id`, `ids_medical`, `quantityes`, `urgency`, `status`) VALUES
(1, '2, 3', '10, 14', 0, 0),
(2, '2', '3', 0, 0),
(3, '3', '5', 1, 0),
(4, '2', '100', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `login` text NOT NULL,
  `email` text NOT NULL,
  `fullname` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role`, `login`, `email`, `fullname`, `password`) VALUES
(1, 1, 'asd', 'asd@asd', 'Иван Иванович Иванов', '$2y$10$DeWt1RUERlQixUVoUItJN.tMtK5Ml3uIfDdD2xr6ifaHfa/3gcdi2'),
(2, 2, 'zxc', 'zxc@zxc.ru', 'Артем Артемович Артемов', '$2y$10$bCSqM3awO2405qfAvDgOpu0Nd4Ub2ig14XvG7LgYN6KEKaOb4N//S');

-- --------------------------------------------------------

--
-- Структура таблицы `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL,
  `name_warehouse` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `warehouses`
--

INSERT INTO `warehouses` (`id`, `name_warehouse`, `address`) VALUES
(1, 'Склад 1', 'г. Город 1, ул. Улица 1. д. 1'),
(2, 'Склад 2', 'г. Город 2, ул. Улица 2. д. 2'),
(3, 'Склад 3', 'г. Город 3, ул. Улица 3. д. 3'),
(4, 'Склад 4', 'г. Город 4, ул. Улица 4. д. 4');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `deleted_medications`
--
ALTER TABLE `deleted_medications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reasons`
--
ALTER TABLE `reasons`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `deleted_medications`
--
ALTER TABLE `deleted_medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `medications`
--
ALTER TABLE `medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `reasons`
--
ALTER TABLE `reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
