-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 26 jul 2022 om 15:30
-- Serverversie: 10.4.24-MariaDB
-- PHP-versie: 8.1.6


DROP DATABASE IF EXISTS `nlc`;

CREATE DATABASE `nlc`;

USE `nlc`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nlc`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `algemeen`
--

CREATE TABLE `algemeen` (
  `usrid` mediumint(9) NOT NULL,
    `gebruiker` text NOT NULL,
    `wachtwoord` text NOT NULL,
  `normalegroep` text NOT NULL,
    `aantalcredits` int not NULL,
   `maandagles1status` int NOT NULL,
   `maandagles2status` int NOT NULL,
   `woensdagles1status` int NOT NULL,
   `woensdagles2status` int NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `algemeen`
--

INSERT INTO `algemeen` (`usrid`, `gebruiker`, `wachtwoord`, `normalegroep`, `aantalcredits`, `maandagles1status`, `maandagles2status`,
  `woensdagles1status`, `woensdagles2status`) VALUES
(1, 'daria', 'student001', 'woensdag avond', 0, 0, 0, 1, 1),
(2, 'mirjam', 'student002', 'woensdag avond', 0, 0, 0, 1, 1);

-- --------------------------------------------------------

--


CREATE TABLE `datums` (
    `lesnumber` INT NOT NULL,
  `lessenmaandag` DATETIME NOT NULL,
    `lessenwoensdag` DATETIME NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `algemeen`
--

INSERT INTO `datums` (`lesnumber`, `lessenmaandag`, `lessenwoensdag`) VALUES
(1, '2022-08-01 18:00:00', '2022-08-03 18:00:00'),
(2, '2022-12-22 18:00:00', '2022-12-24 18:00:00');









-- Indexen voor geëxporteerde tabellen
--

--

--
ALTER TABLE `algemeen`
  ADD PRIMARY KEY (`usrid`);

--

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--


--
ALTER TABLE `algemeen`
  MODIFY `usrid` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
