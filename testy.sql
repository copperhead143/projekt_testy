-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sty 11, 2024 at 09:56 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `odpowiedzi`
--

CREATE TABLE `odpowiedzi` (
  `nazwa_testu` varchar(20) NOT NULL,
  `id_osoby` int(3) NOT NULL,
  `pytanie` varchar(50) NOT NULL,
  `odpowiedz` varchar(20) NOT NULL,
  `czy_poprawna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `osoby`
--

CREATE TABLE `osoby` (
  `id` int(3) NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `imie` varchar(20) NOT NULL,
  `nazwisko` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `osoby`
--

INSERT INTO `osoby` (`id`, `login`, `password`, `imie`, `nazwisko`, `role`) VALUES
(1, 'admin', 'zaq1', 'admin', 'admin', 'prowadzacy'),
(2, 'uczen', 'zaq1', 'uczen', 'uczen', 'uczen');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pytania`
--

CREATE TABLE `pytania` (
  `test_nazwa` varchar(20) NOT NULL,
  `pytanie` varchar(250) NOT NULL,
  `popr_odp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `test`
--

CREATE TABLE `test` (
  `nazwa` varchar(40) NOT NULL,
  `id_osoby` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `osoby`
--
ALTER TABLE `osoby`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `pytania`
--
ALTER TABLE `pytania`
  ADD KEY `id_test` (`test_nazwa`);

--
-- Indeksy dla tabeli `test`
--
ALTER TABLE `test`
  ADD KEY `id_osoby` (`id_osoby`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `osoby`
--
ALTER TABLE `osoby`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
