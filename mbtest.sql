-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 15 2020 г., 13:24
-- Версия сервера: 5.7.15
-- Версия PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mbtest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `app`
--

CREATE TABLE `app` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `developer` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `score_rank` varchar(10) NOT NULL,
  `owners` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `average`
--

CREATE TABLE `average` (
  `app_id` int(11) NOT NULL,
  `average_forever` int(11) NOT NULL DEFAULT '0',
  `average_2weeks` int(11) NOT NULL DEFAULT '0',
  `median_forever` int(11) NOT NULL DEFAULT '0',
  `median_2weeks` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `price`
--

CREATE TABLE `price` (
  `app_id` int(11) NOT NULL,
  `price` varchar(10) NOT NULL DEFAULT '0',
  `initialprice` varchar(10) NOT NULL DEFAULT '0',
  `discount` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rank`
--

CREATE TABLE `rank` (
  `app_id` int(11) NOT NULL,
  `positive` int(11) NOT NULL DEFAULT '0',
  `negative` int(11) NOT NULL DEFAULT '0',
  `userscore` int(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `app`
--
ALTER TABLE `app`
  ADD UNIQUE KEY `appid` (`id`);

--
-- Индексы таблицы `average`
--
ALTER TABLE `average`
  ADD UNIQUE KEY `app` (`app_id`);

--
-- Индексы таблицы `price`
--
ALTER TABLE `price`
  ADD UNIQUE KEY `app` (`app_id`);

--
-- Индексы таблицы `rank`
--
ALTER TABLE `rank`
  ADD UNIQUE KEY `appid` (`app_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
