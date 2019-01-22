-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 27 sep 2018 om 21:45
-- Serverversie: 5.7.22
-- PHP-versie: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pivoSysteemV2.0`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `messages`
--

CREATE TABLE `messages` (
  `messageId` int(11) NOT NULL,
  `receiverUserId` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT '0',
  `messageMoment` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `OrderHistory`
--

CREATE TABLE `OrderHistory` (
  `orderId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL,
  `orderMoment` datetime NOT NULL,
  `during` tinyint(1) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `orderedBy` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Products`
--

CREATE TABLE `Products` (
  `productId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `priceDuring` decimal(5,2) NOT NULL,
  `priceOutside` decimal(5,2) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ToPay`
--

CREATE TABLE `ToPay` (
  `userId` int(11) NOT NULL,
  `totalPay` decimal(5,2) DEFAULT '0.00',
  `payed` decimal(5,2) DEFAULT '0.00',
  `toPay` decimal(5,2) DEFAULT '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `userRights`
--

CREATE TABLE `userRights` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Users`
--

CREATE TABLE `Users` (
  `userId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `passwordHashed` varchar(255) NOT NULL,
  `actief` tinyint(1) NOT NULL,
  `seriesIdentifier` varchar(60) DEFAULT NULL,
  `ipAdress` varchar(255) DEFAULT NULL,
  `rights` int(2) NOT NULL,
  `allowOthersToBuy` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageId`);

--
-- Indexen voor tabel `OrderHistory`
--
ALTER TABLE `OrderHistory`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `OrderHistory_fk0` (`productId`),
  ADD KEY `OrderHistory_fk1` (`userId`);

--
-- Indexen voor tabel `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`productId`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexen voor tabel `ToPay`
--
ALTER TABLE `ToPay`
  ADD PRIMARY KEY (`userId`);

--
-- Indexen voor tabel `userRights`
--
ALTER TABLE `userRights`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `messageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `OrderHistory`
--
ALTER TABLE `OrderHistory`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `Products`
--
ALTER TABLE `Products`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ToPay`
--
ALTER TABLE `ToPay`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `Users`
--
ALTER TABLE `Users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
