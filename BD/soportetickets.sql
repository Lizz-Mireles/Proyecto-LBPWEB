-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 03:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `soportetickets`
--
CREATE DATABASE IF NOT EXISTS `soportetickets` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `soportetickets`;

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `leido` tinyint(4) DEFAULT 0,
  `leido_admin` tinyint(1) DEFAULT 0,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `folio` varchar(20) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `prioridad` enum('baja','media','alta','critica') DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `asignado_a` int(11) DEFAULT NULL,
  `estado` enum('abierto','proceso','espera','cerrado') DEFAULT 'abierto',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_asignacion` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `solucion` text DEFAULT NULL,
  `tipo_reporte` varchar(100) DEFAULT NULL,
  `inventario` varchar(100) DEFAULT NULL,
  `anydesk` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `quien_reporta` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `folio`, `titulo`, `descripcion`, `prioridad`, `creado_por`, `asignado_a`, `estado`, `fecha_creacion`, `fecha_asignacion`, `fecha_cierre`, `solucion`, `tipo_reporte`, `inventario`, `anydesk`, `departamento`, `ubicacion`, `telefono`, `extension`, `quien_reporta`) VALUES
(1, 'TK-000001', 'xxxx', NULL, 'media', 3, 2, 'abierto', '2026-08-08 12:32:43', NULL, NULL, NULL, 'Software', ' jjhui', 'fgcty', 'jnhikho', 'nbjk', '', 'vhjyu', 'vhjgui'),
(2, 'TK-000002', NULL, NULL, 'baja', 3, 2, 'abierto', '2026-08-08 12:49:24', NULL, NULL, NULL, 'Impresora', 'm', 'n', 'nhj', 'nm', '', 'ff', 'ee'),
(3, 'TK-000003', NULL, NULL, 'media', 1, 2, 'abierto', '2026-08-08 12:54:44', NULL, NULL, NULL, 'Software', 'nvhj', 'vhj', 'nm', 'njj', '', 'jkku', 'nmbj'),
(4, 'TK-000004', NULL, NULL, 'critica', 1, 2, 'proceso', '2026-08-15 09:29:24', NULL, NULL, NULL, 'Software', 'dmld', 'fregfer', 'snmek', 'ddek', '', 'njhiwudleldh', 'dhddoed'),
(5, 'TK-000005', 'Sobrecalentamiento', 'Acumulacion de polvo', 'critica', 1, 2, 'proceso', '2026-11-05 12:13:41', NULL, NULL, NULL, 'hardware', 'xxx', 'xxxx', 'TI', 'xxx', '0000000000', 'xxxx', 'xxx'),
(6, 'TK-000006', 'xxxx', 'xxxxx', '', 1, 2, 'cerrado', '2026-11-05 18:12:56', NULL, NULL, NULL, 'Software', 'xx', 'xxxx', 'xxxx', 'xxxx', '4455667788', 'xxxx', 'xxxx');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','tecnico','cliente') DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `activo` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `username`, `password`, `rol`, `hora_salida`, `activo`, `created_at`) VALUES
(1, 'Administrador', 'admin', '$2y$10$xgc4Jg/uuUnZL6DdZHKMZuafDu7pHVR9SPLuz0Pm65oQAHUZOXvBO', 'admin', '17:04:27', 1, '2026-07-31 15:58:48'),
(2, 'Sebastian', 'Tecnico 1', '$2y$10$vUmfC/v66gsDSAaK2AzHQOmxp1oySJUOg5j8yR1rP3AfGzGOde0F.', 'tecnico', '15:18:00', 1, '2026-11-05 15:18:54'),
(3, 'Alois', 'Cliente 1', '$2y$10$oFcbyKcgvyZFJ5uIxAcIQeg7rG/qy.tGKi4.3MEaxhndvg7uccgy.', 'cliente', '19:43:00', 1, '2026-11-05 19:43:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_folio` (`folio`),
  ADD KEY `idx_creado_por` (`creado_por`),
  ADD KEY `idx_asignado_a` (`asignado_a`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_notificaciones_usuario` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_tickets_asignado_a` FOREIGN KEY (`asignado_a`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tickets_creado_por` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
