-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2019 at 10:25 PM
-- Server version: 10.1.39-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u429721638_isp`
--

-- --------------------------------------------------------

--
-- Table structure for table `galerijos_nuotraukos`
--

CREATE TABLE `galerijos_nuotraukos` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nuotraukos_kelias` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `formatas` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galerijos_nuotraukos_etikete`
--

CREATE TABLE `galerijos_nuotraukos_etikete` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galerijos_nuotraukos_pamegimai`
--

CREATE TABLE `galerijos_nuotraukos_pamegimai` (
  `id` int(11) NOT NULL,
  `sukurimo_data` date NOT NULL,
  `nuotraukos_pamegimas` tinyint(1) NOT NULL,
  `fk_komentaras` int(11) DEFAULT NULL,
  `fk_nuotrauka` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galerijos_nuotrauku_etiketes`
--

CREATE TABLE `galerijos_nuotrauku_etiketes` (
  `id` int(11) NOT NULL,
  `fk_nuotrauka` int(11) NOT NULL,
  `fk_etikete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galerijos_nuotrauku_komentarai`
--

CREATE TABLE `galerijos_nuotrauku_komentarai` (
  `id` int(11) NOT NULL,
  `tekstas` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int(11) NOT NULL,
  `fk_galerijos_nuotrauka` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `katalogai`
--

CREATE TABLE `katalogai` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `naudotojai`
--

CREATE TABLE `naudotojai` (
  `id` int(11) NOT NULL,
  `slapyvardis` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slaptazodis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registracijos_data` datetime NOT NULL,
  `avataro_kelias` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uzblokuotas` int(1) NOT NULL DEFAULT '0',
  `uztildytas` int(1) NOT NULL DEFAULT '0',
  `paskutini_karta_prisijunges` datetime NOT NULL,
  `role` int(2) NOT NULL,
  `salis` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_nr` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vardas` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pavarde` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gimimo_data` date DEFAULT NULL,
  `miestas` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `megstamiausias_zaidimas` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biografine_zinute` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discord` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parasas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snapchat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tinklalapis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mokykla` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aukstasis_issilavinimas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `naudotoju_ipai`
--

CREATE TABLE `naudotoju_ipai` (
  `id` int(11) NOT NULL,
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paskutinis_prisijungimas` date NOT NULL,
  `fk_naudotojas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `pavadinimas`) VALUES
(1, 'Naudotojas'),
(2, 'Moderatorius'),
(3, 'Administratorius');

-- --------------------------------------------------------

--
-- Table structure for table `slaptazodziu_priminikliai`
--

CREATE TABLE `slaptazodziu_priminikliai` (
  `id` int(11) NOT NULL,
  `tokenas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `pabaigos_data` datetime NOT NULL,
  `fk_naudotojas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temos`
--

CREATE TABLE `temos` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int(11) NOT NULL,
  `fk_katalogas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temu_atsakymai`
--

CREATE TABLE `temu_atsakymai` (
  `id` int(11) NOT NULL,
  `tekstas` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int(11) NOT NULL,
  `fk_tema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temu_pamegimai`
--

CREATE TABLE `temu_pamegimai` (
  `id` int(11) NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int(11) NOT NULL,
  `fk_temos_atsakymas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zurnalo_irasai`
--

CREATE TABLE `zurnalo_irasai` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `tekstas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fk_naudotojas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `galerijos_nuotraukos`
--
ALTER TABLE `galerijos_nuotraukos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galerijos_nuotraukos_fk0` (`fk_naudotojas`);

--
-- Indexes for table `galerijos_nuotraukos_etikete`
--
ALTER TABLE `galerijos_nuotraukos_etikete`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galerijos_nuotraukos_pamegimai`
--
ALTER TABLE `galerijos_nuotraukos_pamegimai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galerijos_nuotraukos_pamegimai_fk0` (`fk_komentaras`),
  ADD KEY `galerijos_nuotraukos_pamegimai_fk1` (`fk_nuotrauka`);

--
-- Indexes for table `galerijos_nuotrauku_etiketes`
--
ALTER TABLE `galerijos_nuotrauku_etiketes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galerijos_nuotrauku_etiketes_fk0` (`fk_nuotrauka`),
  ADD KEY `galerijos_nuotrauku_etiketes_fk1` (`fk_etikete`);

--
-- Indexes for table `galerijos_nuotrauku_komentarai`
--
ALTER TABLE `galerijos_nuotrauku_komentarai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galerijos_nuotrauku_komentarai_fk0` (`fk_naudotojas`),
  ADD KEY `galerijos_nuotrauku_komentarai_fk1` (`fk_galerijos_nuotrauka`);

--
-- Indexes for table `katalogai`
--
ALTER TABLE `katalogai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `naudotojai`
--
ALTER TABLE `naudotojai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_roles0` (`role`);

--
-- Indexes for table `naudotoju_ipai`
--
ALTER TABLE `naudotoju_ipai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_naudotojas00` (`fk_naudotojas`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slaptazodziu_priminikliai`
--
ALTER TABLE `slaptazodziu_priminikliai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_naudotojas1` (`fk_naudotojas`);

--
-- Indexes for table `temos`
--
ALTER TABLE `temos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temos_fk0` (`fk_naudotojas`),
  ADD KEY `temos_fk1` (`fk_katalogas`);

--
-- Indexes for table `temu_atsakymai`
--
ALTER TABLE `temu_atsakymai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temu_atsakymai_fk0` (`fk_naudotojas`),
  ADD KEY `temu_atsakymai_fk1` (`fk_tema`);

--
-- Indexes for table `temu_pamegimai`
--
ALTER TABLE `temu_pamegimai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temu_pamegimai_fk0` (`fk_naudotojas`),
  ADD KEY `temu_pamegimai_fk1` (`fk_temos_atsakymas`);

--
-- Indexes for table `zurnalo_irasai`
--
ALTER TABLE `zurnalo_irasai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_naudotojas0` (`fk_naudotojas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `galerijos_nuotraukos`
--
ALTER TABLE `galerijos_nuotraukos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galerijos_nuotraukos_etikete`
--
ALTER TABLE `galerijos_nuotraukos_etikete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galerijos_nuotraukos_pamegimai`
--
ALTER TABLE `galerijos_nuotraukos_pamegimai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galerijos_nuotrauku_etiketes`
--
ALTER TABLE `galerijos_nuotrauku_etiketes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galerijos_nuotrauku_komentarai`
--
ALTER TABLE `galerijos_nuotrauku_komentarai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `katalogai`
--
ALTER TABLE `katalogai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `naudotojai`
--
ALTER TABLE `naudotojai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `naudotoju_ipai`
--
ALTER TABLE `naudotoju_ipai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `slaptazodziu_priminikliai`
--
ALTER TABLE `slaptazodziu_priminikliai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temos`
--
ALTER TABLE `temos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temu_atsakymai`
--
ALTER TABLE `temu_atsakymai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temu_pamegimai`
--
ALTER TABLE `temu_pamegimai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zurnalo_irasai`
--
ALTER TABLE `zurnalo_irasai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `galerijos_nuotraukos`
--
ALTER TABLE `galerijos_nuotraukos`
  ADD CONSTRAINT `galerijos_nuotraukos_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galerijos_nuotraukos_pamegimai`
--
ALTER TABLE `galerijos_nuotraukos_pamegimai`
  ADD CONSTRAINT `galerijos_nuotraukos_pamegimai_fk0` FOREIGN KEY (`fk_komentaras`) REFERENCES `galerijos_nuotrauku_komentarai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `galerijos_nuotraukos_pamegimai_fk1` FOREIGN KEY (`fk_nuotrauka`) REFERENCES `galerijos_nuotraukos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galerijos_nuotrauku_etiketes`
--
ALTER TABLE `galerijos_nuotrauku_etiketes`
  ADD CONSTRAINT `galerijos_nuotrauku_etiketes_fk0` FOREIGN KEY (`fk_nuotrauka`) REFERENCES `galerijos_nuotraukos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `galerijos_nuotrauku_etiketes_fk1` FOREIGN KEY (`fk_etikete`) REFERENCES `galerijos_nuotraukos_etikete` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galerijos_nuotrauku_komentarai`
--
ALTER TABLE `galerijos_nuotrauku_komentarai`
  ADD CONSTRAINT `galerijos_nuotrauku_komentarai_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `galerijos_nuotrauku_komentarai_fk1` FOREIGN KEY (`fk_galerijos_nuotrauka`) REFERENCES `galerijos_nuotraukos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `naudotojai`
--
ALTER TABLE `naudotojai`
  ADD CONSTRAINT `fk_roles0` FOREIGN KEY (`role`) REFERENCES `roles` (`id`);

--
-- Constraints for table `naudotoju_ipai`
--
ALTER TABLE `naudotoju_ipai`
  ADD CONSTRAINT `fk_naudotojas00` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `slaptazodziu_priminikliai`
--
ALTER TABLE `slaptazodziu_priminikliai`
  ADD CONSTRAINT `fk_naudotojas1` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temos`
--
ALTER TABLE `temos`
  ADD CONSTRAINT `temos_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temos_fk1` FOREIGN KEY (`fk_katalogas`) REFERENCES `katalogai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temu_atsakymai`
--
ALTER TABLE `temu_atsakymai`
  ADD CONSTRAINT `temu_atsakymai_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temu_atsakymai_fk1` FOREIGN KEY (`fk_tema`) REFERENCES `temos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temu_pamegimai`
--
ALTER TABLE `temu_pamegimai`
  ADD CONSTRAINT `temu_pamegimai_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temu_pamegimai_fk1` FOREIGN KEY (`fk_temos_atsakymas`) REFERENCES `temu_atsakymai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zurnalo_irasai`
--
ALTER TABLE `zurnalo_irasai`
  ADD CONSTRAINT `fk_naudotojas0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
