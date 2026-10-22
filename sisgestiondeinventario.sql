-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 19-10-2026 a las 16:47:58
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sisgestiondeinventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-isegura@ucol.mx|127.0.0.1:timer', 'i:1791922876;', 1791922876),
('laravel-cache-isegura@ucol.mx|127.0.0.1', 'i:3;', 1791922876);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

DROP TABLE IF EXISTS `cajas`;
CREATE TABLE IF NOT EXISTS `cajas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cajas_sucursal_id_nombre_unique` (`sucursal_id`,`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `sucursal_id`, `nombre`, `descripcion`, `activa`, `created_at`, `updated_at`) VALUES
(1, 2, 'Caja 1', NULL, 1, '2026-09-22 09:45:59', '2026-09-22 09:45:59'),
(2, 2, 'Caja 2', NULL, 1, '2026-09-22 09:54:02', '2026-09-22 09:54:02'),
(3, 1, 'Caja1', NULL, 1, '2026-09-29 08:12:16', '2026-09-29 08:12:16'),
(4, 3, 'Caja1', NULL, 1, '2026-09-29 08:12:42', '2026-09-29 08:12:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=310 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(307, 'Novela', NULL, '2026-09-16 10:01:52', '2026-09-16 10:01:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_producto`
--

DROP TABLE IF EXISTS `categoria_producto`;
CREATE TABLE IF NOT EXISTS `categoria_producto` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `producto_id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoria_producto_producto_id_categoria_id_unique` (`producto_id`,`categoria_id`),
  KEY `categoria_producto_categoria_id_foreign` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `categoria_producto`
--

INSERT INTO `categoria_producto` (`id`, `producto_id`, `categoria_id`, `created_at`, `updated_at`) VALUES
(1, 8, 301, '2026-09-16 07:01:10', '2026-09-16 07:01:10'),
(2, 9, 302, '2026-09-16 07:01:10', '2026-09-16 07:01:10'),
(3, 10, 305, '2026-09-16 09:28:07', '2026-09-16 09:28:07'),
(4, 10, 303, '2026-09-16 09:28:07', '2026-09-16 09:28:07'),
(5, 10, 304, '2026-09-16 09:28:07', '2026-09-16 09:28:07'),
(6, 10, 306, '2026-09-16 09:28:07', '2026-09-16 09:28:07'),
(7, 11, 305, '2026-09-16 09:28:48', '2026-09-16 09:28:48'),
(8, 11, 301, '2026-09-16 09:28:48', '2026-09-16 09:28:48'),
(9, 11, 306, '2026-09-16 09:28:48', '2026-09-16 09:28:48'),
(10, 12, 305, '2026-09-16 09:30:21', '2026-09-16 09:30:21'),
(11, 12, 303, '2026-09-16 09:30:21', '2026-09-16 09:30:21'),
(12, 12, 304, '2026-09-16 09:30:21', '2026-09-16 09:30:21'),
(13, 12, 306, '2026-09-16 09:30:21', '2026-09-16 09:30:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

DROP TABLE IF EXISTS `detalle_ventas`;
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `venta_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int UNSIGNED NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_ventas_venta_id_foreign` (`venta_id`),
  KEY `detalle_ventas_producto_id_foreign` (`producto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`) VALUES
(34, 24, 22, 2, 436.00, 872.00, '2026-09-29 08:13:30', '2026-09-29 08:13:30'),
(36, 26, 33, 2, 398.00, 796.00, '2026-10-02 05:25:15', '2026-10-02 05:25:15'),
(35, 25, 38, 1, 283.00, 283.00, '2026-09-30 00:10:26', '2026-09-30 00:10:26'),
(37, 27, 27, 1, 398.00, 398.00, '2026-10-14 02:21:58', '2026-10-14 02:21:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

DROP TABLE IF EXISTS `generos`;
CREATE TABLE IF NOT EXISTS `generos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nombre_en` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `descripcion_en` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `generos_nombre_unique` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`, `nombre_en`, `descripcion`, `descripcion_en`, `created_at`, `updated_at`) VALUES
(1, 'Politíco', 'Political', NULL, NULL, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(2, 'Acción', 'Action', 'Mucha adrenalina', 'Lots of adrenaline', '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(3, 'Fantasías', 'Fantasy', 'Magia y Cosas sobrenaturales', 'Magic and supernatural things', '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(4, 'Comedia y Aventura', 'Comedy and Adventure', 'La trama gira en torno a las situaciones caóticas y humorísticas.', 'The plot revolves around chaotic and humorous situations.', '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(5, 'Romance', 'Romance', 'Novelas centradas en relaciones amorosas, a menudo subclasificadas (histórico, paranormal, contemporáneo).', 'Novels centered on romantic relationships, often divided into historical, paranormal, and contemporary subgenres.', '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(6, 'Misterio y Crimen', 'Mystery and Crime', 'Incluye novela negra, thriller, suspense y detectives.', 'Includes noir, thriller, suspense, and detective fiction.', '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(7, 'Desarrollo Personal y Autoayuda', 'Personal Development and Self-Help', NULL, NULL, '2026-09-29 07:48:40', '2026-09-29 07:48:40'),
(8, 'Historia y Biografías', 'History and Biographies', NULL, NULL, '2026-09-29 07:49:12', '2026-09-29 07:49:12'),
(9, 'Infantil', 'Childish', NULL, NULL, '2026-09-29 07:49:51', '2026-09-29 07:49:51'),
(10, 'Juvenil', 'Juvenile', NULL, NULL, '2026-09-29 07:50:17', '2026-09-29 07:50:17'),
(11, 'Dark Romance', 'Dark Romance', 'historias de toxity love, para adultos', NULL, '2026-09-29 23:46:08', '2026-09-29 23:46:08'),
(12, 'Adultos', 'Adults', 'mayores de 18', NULL, '2026-09-29 23:51:10', '2026-09-29 23:51:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero_producto`
--

DROP TABLE IF EXISTS `genero_producto`;
CREATE TABLE IF NOT EXISTS `genero_producto` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `producto_id` bigint UNSIGNED NOT NULL,
  `genero_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genero_producto_producto_id_genero_id_unique` (`producto_id`,`genero_id`),
  KEY `genero_producto_genero_id_foreign` (`genero_id`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `genero_producto`
--

INSERT INTO `genero_producto` (`id`, `producto_id`, `genero_id`, `created_at`, `updated_at`) VALUES
(1, 8, 6, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(2, 9, 5, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(3, 10, 4, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(4, 10, 3, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(5, 10, 2, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(6, 10, 1, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(7, 11, 6, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(8, 11, 2, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(9, 11, 1, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(10, 12, 4, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(11, 12, 3, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(12, 12, 2, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(13, 12, 1, '2026-09-16 09:59:57', '2026-09-16 09:59:57'),
(14, 13, 2, '2026-09-16 10:03:07', '2026-09-16 10:03:07'),
(15, 13, 6, '2026-09-16 10:03:07', '2026-09-16 10:03:07'),
(16, 14, 2, '2026-09-16 10:03:57', '2026-09-16 10:03:57'),
(17, 14, 4, '2026-09-16 10:03:57', '2026-09-16 10:03:57'),
(18, 14, 3, '2026-09-16 10:03:57', '2026-09-16 10:03:57'),
(19, 14, 1, '2026-09-16 10:03:57', '2026-09-16 10:03:57'),
(33, 20, 3, '2026-09-29 08:01:17', '2026-09-29 08:01:17'),
(32, 15, 10, '2026-09-29 07:57:24', '2026-09-29 07:57:24'),
(22, 15, 3, '2026-09-18 00:41:17', '2026-09-18 00:41:17'),
(31, 15, 9, '2026-09-29 07:57:24', '2026-09-29 07:57:24'),
(24, 16, 2, '2026-09-18 01:44:53', '2026-09-18 01:44:53'),
(25, 16, 4, '2026-09-18 01:44:53', '2026-09-18 01:44:53'),
(26, 17, 2, '2026-09-22 08:16:40', '2026-09-22 08:16:40'),
(27, 17, 3, '2026-09-22 08:16:40', '2026-09-22 08:16:40'),
(28, 18, 2, '2026-09-22 08:18:21', '2026-09-22 08:18:21'),
(29, 18, 3, '2026-09-22 08:18:21', '2026-09-22 08:18:21'),
(30, 19, 2, '2026-09-24 04:05:32', '2026-09-24 04:05:32'),
(34, 20, 9, '2026-09-29 08:01:17', '2026-09-29 08:01:17'),
(35, 20, 10, '2026-09-29 08:01:17', '2026-09-29 08:01:17'),
(36, 21, 3, '2026-09-29 08:03:36', '2026-09-29 08:03:36'),
(37, 21, 9, '2026-09-29 08:03:36', '2026-09-29 08:03:36'),
(38, 21, 10, '2026-09-29 08:03:36', '2026-09-29 08:03:36'),
(39, 22, 3, '2026-09-29 08:06:08', '2026-09-29 08:06:08'),
(40, 22, 9, '2026-09-29 08:06:08', '2026-09-29 08:06:08'),
(41, 22, 10, '2026-09-29 08:06:08', '2026-09-29 08:06:08'),
(42, 23, 3, '2026-09-29 08:07:33', '2026-09-29 08:07:33'),
(43, 23, 9, '2026-09-29 08:07:33', '2026-09-29 08:07:33'),
(44, 23, 10, '2026-09-29 08:07:33', '2026-09-29 08:07:33'),
(45, 24, 3, '2026-09-29 08:08:52', '2026-09-29 08:08:52'),
(46, 24, 9, '2026-09-29 08:08:52', '2026-09-29 08:08:52'),
(47, 24, 10, '2026-09-29 08:08:52', '2026-09-29 08:08:52'),
(48, 25, 3, '2026-09-29 08:09:57', '2026-09-29 08:09:57'),
(49, 25, 9, '2026-09-29 08:09:57', '2026-09-29 08:09:57'),
(50, 25, 10, '2026-09-29 08:09:57', '2026-09-29 08:09:57'),
(51, 26, 3, '2026-09-29 08:11:02', '2026-09-29 08:11:02'),
(52, 26, 9, '2026-09-29 08:11:02', '2026-09-29 08:11:02'),
(53, 26, 10, '2026-09-29 08:11:02', '2026-09-29 08:11:02'),
(54, 27, 3, '2026-09-29 23:20:51', '2026-09-29 23:20:51'),
(55, 27, 10, '2026-09-29 23:20:51', '2026-09-29 23:20:51'),
(56, 27, 5, '2026-09-29 23:20:51', '2026-09-29 23:20:51'),
(57, 28, 3, '2026-09-29 23:22:06', '2026-09-29 23:22:06'),
(58, 28, 10, '2026-09-29 23:22:06', '2026-09-29 23:22:06'),
(59, 28, 5, '2026-09-29 23:22:06', '2026-09-29 23:22:06'),
(60, 29, 3, '2026-09-29 23:23:14', '2026-09-29 23:23:14'),
(61, 29, 10, '2026-09-29 23:23:14', '2026-09-29 23:23:14'),
(62, 29, 5, '2026-09-29 23:23:14', '2026-09-29 23:23:14'),
(63, 30, 3, '2026-09-29 23:24:59', '2026-09-29 23:24:59'),
(64, 30, 10, '2026-09-29 23:24:59', '2026-09-29 23:24:59'),
(65, 30, 5, '2026-09-29 23:24:59', '2026-09-29 23:24:59'),
(66, 31, 3, '2026-09-29 23:25:56', '2026-09-29 23:25:56'),
(67, 31, 10, '2026-09-29 23:25:56', '2026-09-29 23:25:56'),
(68, 31, 5, '2026-09-29 23:25:56', '2026-09-29 23:25:56'),
(69, 32, 3, '2026-09-29 23:29:30', '2026-09-29 23:29:30'),
(70, 32, 10, '2026-09-29 23:29:30', '2026-09-29 23:29:30'),
(71, 32, 5, '2026-09-29 23:29:30', '2026-09-29 23:29:30'),
(72, 33, 3, '2026-09-29 23:31:00', '2026-09-29 23:31:00'),
(73, 33, 10, '2026-09-29 23:31:00', '2026-09-29 23:31:00'),
(74, 33, 5, '2026-09-29 23:31:00', '2026-09-29 23:31:00'),
(75, 34, 3, '2026-09-29 23:33:00', '2026-09-29 23:33:00'),
(76, 34, 10, '2026-09-29 23:33:00', '2026-09-29 23:33:00'),
(77, 34, 5, '2026-09-29 23:33:00', '2026-09-29 23:33:00'),
(78, 35, 3, '2026-09-29 23:34:43', '2026-09-29 23:34:43'),
(79, 35, 10, '2026-09-29 23:34:43', '2026-09-29 23:34:43'),
(80, 35, 5, '2026-09-29 23:34:43', '2026-09-29 23:34:43'),
(81, 36, 3, '2026-09-29 23:36:35', '2026-09-29 23:36:35'),
(82, 36, 9, '2026-09-29 23:36:35', '2026-09-29 23:36:35'),
(83, 36, 10, '2026-09-29 23:36:35', '2026-09-29 23:36:35'),
(84, 37, 3, '2026-09-29 23:38:47', '2026-09-29 23:38:47'),
(85, 37, 10, '2026-09-29 23:38:47', '2026-09-29 23:38:47'),
(86, 37, 5, '2026-09-29 23:38:47', '2026-09-29 23:38:47'),
(87, 38, 3, '2026-09-29 23:41:28', '2026-09-29 23:41:28'),
(88, 38, 10, '2026-09-29 23:41:28', '2026-09-29 23:41:28'),
(89, 38, 5, '2026-09-29 23:41:28', '2026-09-29 23:41:28'),
(90, 39, 3, '2026-09-29 23:43:55', '2026-09-29 23:43:55'),
(91, 39, 10, '2026-09-29 23:43:55', '2026-09-29 23:43:55'),
(92, 39, 5, '2026-09-29 23:43:55', '2026-09-29 23:43:55'),
(93, 40, 11, '2026-09-29 23:50:32', '2026-09-29 23:50:32'),
(94, 40, 5, '2026-09-29 23:50:32', '2026-09-29 23:50:32'),
(95, 40, 12, '2026-09-29 23:51:35', '2026-09-29 23:51:35'),
(96, 41, 12, '2026-09-29 23:53:37', '2026-09-29 23:53:37'),
(97, 41, 11, '2026-09-29 23:53:37', '2026-09-29 23:53:37'),
(98, 41, 5, '2026-09-29 23:53:37', '2026-09-29 23:53:37'),
(99, 42, 12, '2026-09-29 23:54:46', '2026-09-29 23:54:46'),
(100, 42, 11, '2026-09-29 23:54:46', '2026-09-29 23:54:46'),
(101, 42, 5, '2026-09-29 23:54:46', '2026-09-29 23:54:46'),
(102, 43, 12, '2026-09-29 23:56:29', '2026-09-29 23:56:29'),
(103, 43, 11, '2026-09-29 23:56:29', '2026-09-29 23:56:29'),
(104, 43, 5, '2026-09-29 23:56:29', '2026-09-29 23:56:29'),
(105, 44, 12, '2026-09-29 23:57:13', '2026-09-29 23:57:13'),
(106, 44, 11, '2026-09-29 23:57:13', '2026-09-29 23:57:13'),
(107, 44, 5, '2026-09-29 23:57:13', '2026-09-29 23:57:13'),
(108, 45, 12, '2026-09-30 00:02:19', '2026-09-30 00:02:19'),
(109, 45, 11, '2026-09-30 00:02:19', '2026-09-30 00:02:19'),
(110, 45, 5, '2026-09-30 00:02:19', '2026-09-30 00:02:19'),
(111, 46, 12, '2026-09-30 00:04:54', '2026-09-30 00:04:54'),
(112, 46, 11, '2026-09-30 00:04:54', '2026-09-30 00:04:54'),
(113, 46, 5, '2026-09-30 00:04:54', '2026-09-30 00:04:54'),
(114, 47, 12, '2026-09-30 00:05:50', '2026-09-30 00:05:50'),
(115, 47, 11, '2026-09-30 00:05:50', '2026-09-30 00:05:50'),
(116, 47, 5, '2026-09-30 00:05:50', '2026-09-30 00:05:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

DROP TABLE IF EXISTS `inventarios`;
CREATE TABLE IF NOT EXISTS `inventarios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int UNSIGNED NOT NULL DEFAULT '0',
  `stock_minimo` int UNSIGNED NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventarios_producto_id_unique` (`producto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `inventarios`
--

INSERT INTO `inventarios` (`id`, `producto_id`, `cantidad`, `stock_minimo`, `created_at`, `updated_at`) VALUES
(20, 20, 10, 5, '2026-09-29 08:01:17', '2026-09-29 08:01:17'),
(21, 21, 10, 5, '2026-09-29 08:03:36', '2026-09-29 08:03:36'),
(15, 15, 10, 5, '2026-09-18 00:41:17', '2026-09-29 07:50:46'),
(22, 22, 8, 5, '2026-09-29 08:06:08', '2026-09-29 08:13:30'),
(23, 23, 10, 5, '2026-09-29 08:07:33', '2026-09-29 08:07:33'),
(24, 24, 10, 5, '2026-09-29 08:08:52', '2026-09-29 08:08:52'),
(25, 25, 10, 5, '2026-09-29 08:09:57', '2026-09-29 08:09:57'),
(26, 26, 10, 5, '2026-09-29 08:11:02', '2026-09-29 08:11:02'),
(27, 27, 9, 5, '2026-09-29 23:20:51', '2026-10-14 02:21:58'),
(28, 28, 5, 2, '2026-09-29 23:22:06', '2026-09-29 23:22:06'),
(29, 29, 5, 2, '2026-09-29 23:23:14', '2026-09-29 23:23:14'),
(30, 30, 5, 2, '2026-09-29 23:24:59', '2026-09-29 23:24:59'),
(31, 31, 5, 2, '2026-09-29 23:25:56', '2026-09-29 23:25:56'),
(32, 32, 10, 2, '2026-09-29 23:29:30', '2026-09-29 23:29:30'),
(33, 33, 3, 2, '2026-09-29 23:31:00', '2026-10-02 05:25:15'),
(34, 34, 10, 5, '2026-09-29 23:33:00', '2026-09-29 23:33:00'),
(35, 35, 10, 5, '2026-09-29 23:34:43', '2026-09-29 23:34:43'),
(36, 36, 1, 5, '2026-09-29 23:36:35', '2026-09-29 23:36:52'),
(37, 37, 15, 5, '2026-09-29 23:38:47', '2026-09-29 23:38:47'),
(38, 38, 9, 5, '2026-09-29 23:41:28', '2026-09-30 00:10:26'),
(39, 39, 10, 5, '2026-09-29 23:43:55', '2026-09-29 23:43:55'),
(40, 40, 15, 5, '2026-09-29 23:50:32', '2026-09-29 23:50:32'),
(41, 41, 15, 5, '2026-09-29 23:53:37', '2026-09-29 23:53:37'),
(42, 42, 15, 5, '2026-09-29 23:54:46', '2026-09-29 23:54:46'),
(43, 43, 10, 5, '2026-09-29 23:56:29', '2026-09-29 23:56:29'),
(44, 44, 10, 5, '2026-09-29 23:57:13', '2026-09-29 23:57:13'),
(45, 45, 11, 5, '2026-09-30 00:02:19', '2026-09-30 00:02:19'),
(46, 46, 11, 5, '2026-09-30 00:04:54', '2026-09-30 00:04:54'),
(47, 47, 11, 5, '2026-09-30 00:05:50', '2026-09-30 00:05:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

DROP TABLE IF EXISTS `metodos_pago`;
CREATE TABLE IF NOT EXISTS `metodos_pago` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Efectivo', '2026-09-03 06:30:26', '2026-09-03 06:30:26'),
(2, 'Tarjeta', '2026-09-03 06:30:26', '2026-09-03 06:30:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(7, '2026_08_29_183220_create_categorias_table', 2),
(8, '2026_08_29_190000_create_roles_table', 3),
(9, '2026_08_29_190100_add_role_id_to_users_table', 3),
(10, '2026_08_30_100000_create_sucursales_table', 4),
(11, '2026_08_30_100100_add_sucursal_foreign_to_users_table', 4),
(12, '2026_08_30_100200_create_productos_table', 4),
(13, '2026_08_30_100300_create_inventarios_table', 4),
(14, '2026_08_30_100400_create_metodos_pago_table', 4),
(15, '2026_08_30_100500_create_ventas_table', 4),
(16, '2026_08_30_100600_create_detalle_ventas_table', 4),
(17, '2026_08_30_100700_add_pago_efectivo_to_ventas_table', 5),
(18, '2026_09_14_120000_add_iva_to_ventas_table', 6),
(19, '2026_09_15_190000_create_categoria_producto_table', 7),
(20, '2026_09_15_200000_create_password_reset_requests_table', 8),
(21, '2026_09_16_100000_add_generos_autor_editorial', 9),
(22, '2026_09_21_220000_create_cajas_table', 10),
(23, '2026_09_21_230000_add_caja_id_to_ventas_table', 11),
(24, '2026_09_22_120000_add_english_translations_to_generos_table', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_requests`
--

DROP TABLE IF EXISTS `password_reset_requests`;
CREATE TABLE IF NOT EXISTS `password_reset_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `attended_by` bigint UNSIGNED DEFAULT NULL,
  `attended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_requests_user_id_foreign` (`user_id`),
  KEY `password_reset_requests_attended_by_foreign` (`attended_by`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_requests`
--

INSERT INTO `password_reset_requests` (`id`, `user_id`, `status`, `attended_by`, `attended_at`, `created_at`, `updated_at`) VALUES
(1, 8, 'attended', 1, '2026-09-16 09:13:35', '2026-09-16 09:12:53', '2026-09-16 09:13:35'),
(2, 9, 'attended', 1, '2026-09-17 22:10:08', '2026-09-17 22:09:18', '2026-09-17 22:10:08'),
(3, 1, 'attended', 1, '2026-09-21 18:58:39', '2026-09-21 09:22:47', '2026-09-21 18:58:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('joshuagabrieldm@gmail.com', '$2y$12$hQQRWFV3er72eZvDAw3cOeD4132bg5EQFaZPYDZPw0Q8.73/txVLy', '2026-08-31 03:28:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED DEFAULT NULL,
  `nombre` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `codigo` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `autor` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `editorial` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productos_sucursal_id_foreign` (`sucursal_id`),
  KEY `productos_categoria_id_foreign` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `sucursal_id`, `categoria_id`, `nombre`, `descripcion`, `codigo`, `autor`, `editorial`, `precio`, `imagen`, `activo`, `created_at`, `updated_at`) VALUES
(15, 2, 307, 'Harry Potter y la piedra filosofal (Harry Potter 1)', 'Harry Potter y la piedra filosofal , tapa blanda', '01', 'J.k. Rowling', 'Planeta', 436.00, 'harry-potter-y-la-piedra-filosofal-harry-potter-1.jpg', 1, '2026-09-18 00:41:17', '2026-09-29 08:04:13'),
(20, 2, 307, 'Harry Potter y la cámara secreta (Harry Potter 2)', 'Harry Potter y la cámara secreta, tapa blanda', '02', 'J.k. Rowling', 'Planeta', 349.00, 'harry-potter-y-la-camara-secreta-harry-potter-2.webp', 1, '2026-09-29 08:01:17', '2026-09-29 08:01:17'),
(21, 2, 307, 'Harry Potter Y El Prisionero de Azkaban (Harry Potter 3)', 'Harry Potter Y El Prisionero de Azkaban, tapa blanda', '03', 'J.k. Rowling', 'Planeta', 385.00, 'harry-potter-y-el-prisionero-de-azkaban-harry-potter-3.jpg', 1, '2026-09-29 08:03:36', '2026-09-29 08:03:36'),
(22, 1, 307, 'Harry Potter y la piedra filosofal (Harry Potter 1)', 'Harry Potter y la piedra filosofal, tapa blanda', '01', 'J.k. Rowling', 'Planeta', 436.00, 'harry-potter-y-la-piedra-filosofal-harry-potter-1-2.jpg', 1, '2026-09-29 08:06:08', '2026-09-29 08:06:08'),
(23, 3, 307, 'Harry Potter y la cámara secreta (Harry Potter 2)', 'Harry Potter y la cámara secreta, tapa blanda', '02', 'J.k. Rowling', 'Planeta', 349.00, 'harry-potter-y-la-camara-secreta-harry-potter-2-2.webp', 1, '2026-09-29 08:07:33', '2026-09-29 08:07:33'),
(24, 3, 307, 'Harry Potter Y El Prisionero de Azkaban (Harry Potter 3)', 'Harry Potter Y El Prisionero de Azkaban, tapa blanda', '03', 'J.k. Rowling', 'Planeta', 385.00, 'harry-potter-y-el-prisionero-de-azkaban-harry-potter-3-2.jpg', 1, '2026-09-29 08:08:52', '2026-09-29 08:08:52'),
(25, 1, 307, 'Harry Potter Y El Prisionero de Azkaban (Harry Potter 3)', 'Harry Potter Y El Prisionero de Azkaban, tapa blanda', '03', 'J.k. Rowling', 'Planeta', 385.00, 'harry-potter-y-el-prisionero-de-azkaban-harry-potter-3-3.jpg', 1, '2026-09-29 08:09:57', '2026-09-29 08:09:57'),
(26, 1, 307, 'Harry Potter y la cámara secreta (Harry Potter 2)', 'Harry Potter y la cámara secreta, tapa blanda', '02', 'J.k. Rowling', 'Planeta', 349.00, 'harry-potter-y-la-camara-secreta-harry-potter-2-3.webp', 1, '2026-09-29 08:11:02', '2026-09-29 08:11:02'),
(27, 2, 307, 'Alas de sangre (Empíreo 1)', 'Saga Empíreo 1, pasta blanda', '04', 'Rebecca Yarros', 'Planeta', 398.00, 'alas-de-sangre-empireo-1.webp', 1, '2026-09-29 23:20:51', '2026-09-29 23:20:51'),
(28, 1, 307, 'Alas de sangre (Empíreo 1)', 'Saga, Empíreo 1, pasta blanda', '04', 'Rebecca Yarros', 'Planeta', 398.00, 'alas-de-sangre-empireo-1-2.webp', 1, '2026-09-29 23:22:06', '2026-09-29 23:22:06'),
(29, 3, 307, 'Alas de sangre (Empíreo 1)', 'Saga Empíreo 1, pasta blanda', '04', 'Rebecca Yarros', 'Planeta', 398.00, 'alas-de-sangre-empireo-1-3.webp', 1, '2026-09-29 23:23:14', '2026-09-29 23:23:14'),
(30, 2, 307, 'Alas de Hierro (Empíreo 2)', 'Saga Empíreo 2, pasta blanda', '04', 'Rebecca Yarros', 'Planeta', 398.00, 'alas-de-hierro-empireo-2.webp', 1, '2026-09-29 23:24:59', '2026-09-29 23:24:59'),
(31, 1, 307, 'Alas de Hierro (Empíreo 2)', 'Saga Empíreo 2, pasta blanda', '05', 'Rebecca Yarros', 'Planeta', 398.00, 'alas-de-hierro-empireo-2-2.webp', 1, '2026-09-29 23:25:56', '2026-09-29 23:25:56'),
(32, 3, 307, 'Alas de Hierro (Empíreo 2)', 'Saga Empíreo 2, pasta blanda', '05', 'Rebecca Yarros', 'Planeta', 398.00, 'alas-de-hierro-empireo-2-3.webp', 1, '2026-09-29 23:29:30', '2026-09-29 23:29:30'),
(33, 2, 307, 'Alas de Ónix (Empíreo 3)', 'Saga Empíreo 3, tapa blanda', '06', 'Rebecca Yarros', 'Planeta', 398.00, 'alas-de-onix-empireo-3.webp', 1, '2026-09-29 23:31:00', '2026-09-29 23:33:18'),
(34, 1, 307, 'Alas de Ónix (Empíreo 3)', 'Saga Empíreo 3, tapa blanda', '06', 'Rebecca Yarros', 'Planeta', 330.00, 'alas-de-onix-empireo-3-2.webp', 1, '2026-09-29 23:33:00', '2026-09-29 23:33:00'),
(35, 3, 307, 'Alas de Ónix (Empíreo 3)', 'Saga Empíreo 3, tapa blanda', '06', 'Rebecca Yarros', 'Planeta', 330.00, 'alas-de-onix-empireo-3-3.webp', 1, '2026-09-29 23:34:43', '2026-09-29 23:34:43'),
(36, 3, 307, 'Harry Potter y la piedra filosofal (Harry Potter 1)', 'harry potter 1, tapa blanda', '01', 'J.k. Rowling', 'Planeta', 436.00, 'harry-potter-y-la-piedra-filosofal-harry-potter-1-3.jpg', 1, '2026-09-29 23:36:35', '2026-09-29 23:36:35'),
(37, 2, 307, 'Diario oficial de Alas de sangre: Te necesito, Violet.', 'Diario oficial de Alas de sangre, pasta blanda', '07', 'Rebecca Yarros', 'Planeta', 283.00, 'diario-oficial-de-alas-de-sangre-te-necesito-violet.webp', 1, '2026-09-29 23:38:47', '2026-09-29 23:38:47'),
(38, 1, 307, 'Diario oficial de Alas de sangre: Te necesito, Violet.', 'Diario oficial de Alas de sangre, pasta blanda', '07', 'Rebecca Yarros', 'Planeta', 283.00, 'diario-oficial-de-alas-de-sangre-te-necesito-violet-2.webp', 1, '2026-09-29 23:41:28', '2026-09-29 23:43:01'),
(39, 3, 307, 'Diario oficial de Alas de sangre: Te necesito, Violet.', 'Diario oficial de Alas de sangre, pasta blanda', '07', 'Rebecca Yarros', 'Planeta', 283.00, 'diario-oficial-de-alas-de-sangre-te-necesito-violet-3.webp', 1, '2026-09-29 23:43:55', '2026-09-29 23:43:55'),
(40, 2, 307, 'Butcher & Blackbird', 'La trilogía del amor caótico(1), pasta blanda', '08', 'Brynne Weaver', 'contraluz', 481.74, 'butcher-blackbird.webp', 1, '2026-09-29 23:50:32', '2026-09-29 23:50:32'),
(41, 1, 307, 'Butcher & Blackbird', 'La trilogía del amor caótico(1)', '08', 'Brynne Weaver', 'contraluz', 481.74, 'butcher-blackbird-2.webp', 1, '2026-09-29 23:53:37', '2026-09-29 23:53:37'),
(42, 3, 307, 'Butcher & Blackbird', 'La trilogia del caos(1)', '08', 'Brynne Weaver', 'contraluz', 481.74, 'butcher-blackbird-3.webp', 1, '2026-09-29 23:54:45', '2026-09-29 23:54:45'),
(43, 2, 307, 'Leather & Lark', 'La Trilogía del Amor Caótico 2', '09', 'Brynne Weaver', 'contraluz', 481.74, 'leather-lark.webp', 1, '2026-09-29 23:56:29', '2026-09-29 23:56:29'),
(44, 1, 307, 'Leather & Lark', 'La Trilogía del Amor Caótico 2', '09', 'Brynne Weaver', 'contraluz', 481.74, 'leather-lark-2.webp', 1, '2026-09-29 23:57:13', '2026-09-29 23:57:13'),
(45, 2, 307, 'Scythe & Sparrow', 'La Trilogía del Amor Caótico 3', '11', 'Brynne Weaver', 'contraluz', 445.66, 'scythe-sparrow.webp', 1, '2026-09-30 00:02:19', '2026-09-30 00:02:19'),
(46, 1, 307, 'Scythe & Sparrow', 'La Trilogía del Amor Caótico 3', '11', 'Brynne Weaver', 'contraluz', 445.66, 'scythe-sparrow-2.webp', 1, '2026-09-30 00:04:54', '2026-09-30 00:04:54'),
(47, 3, 307, 'Scythe & Sparrow', 'La Trilogía del Amor Caótico 3', '11', 'Brynne Weaver', 'contraluz', 445.66, 'scythe-sparrow-3.webp', 1, '2026-09-30 00:05:50', '2026-09-30 00:05:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `slug`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador General', '2026-08-31 00:59:44', '2026-08-31 00:59:44'),
(2, 'gerente', 'Gerente de Sede', '2026-08-31 00:59:44', '2026-08-31 00:59:44'),
(3, 'cajero', 'Cajero', '2026-08-31 00:59:44', '2026-08-31 00:59:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('JqLzTDZr2sRNHGuEFgspoS0GlgM5gKTkD1P9DPJj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJjM0VCbzU2VXF0SFl2Wkd5WlhjNERNVmRVS2F5TGhlOWNmYXc5SXRaIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6bnVsbH0sInVybCI6eyJpbnRlbmRlZCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiJ9LCJsb2NhbGUiOiJlbiJ9', 1792428137);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

DROP TABLE IF EXISTS `sucursales`;
CREATE TABLE IF NOT EXISTS `sucursales` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `telefono` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `contacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `gerente_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sucursales_gerente_id_foreign` (`gerente_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`id`, `nombre`, `direccion`, `telefono`, `contacto`, `activa`, `gerente_id`, `created_at`, `updated_at`) VALUES
(1, 'Plaza del Sol GDL', 'Av. Adolfo López Mateos Sur 2375, Cd del Sol, 45050 Zapopan, Jal.', '3141039048', 'tempest@gmail.com', 1, NULL, '2026-09-03 06:09:44', '2026-09-29 23:16:13'),
(2, 'Manzanillo Centro', 'Av. Principal #123', '3121234567', 'centro@sispuntodeventa.com', 1, NULL, '2026-09-03 06:30:26', '2026-09-29 23:15:09'),
(3, 'Zentralia Colima', 'P.º Miguel de la Madrid Hurtado 301, 28018 Colima, Col.', '3123236006', 'rimuru@gmail.com', 1, NULL, '2026-09-14 02:50:40', '2026-09-29 23:15:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `sucursal_id` bigint UNSIGNED DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_sucursal_id_foreign` (`sucursal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `role_id`, `sucursal_id`, `activo`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'Johiel', 'joshuagabrieldm@gmail.com', NULL, '$2y$12$Twc5Vg6UixhrtcUr7mXK/.h.LiGOwy6sTNaW53SljPiOyFvPTe.5.', 'd4QJoxbOxviIsqIhsqgeNbPU8Afx0Zf9ZMhhBQef8CG9br9aH8lpmaTB6q0N', '2026-08-29 03:59:41', '2026-08-29 03:59:41'),
(9, 3, 2, 1, 'Rock', 'mega@gmail.com', NULL, '$2y$12$2AhDBPAWN3M0A9nT/h.J2Ok1ggdTrvxre2ZR4Ffbz4/jo.dF/8ycy', NULL, '2026-09-16 06:52:37', '2026-09-16 06:52:37'),
(26, 1, NULL, 1, 'Inuyasha', 'inuyashajuenime@gmail.com', NULL, '$2y$12$rvitYRyDQGoQMPMLaPuJceve6jl.aT2yrvOudWk7cFOR2aOVxe6Xy', NULL, '2026-09-22 08:50:09', '2026-09-22 08:50:09'),
(10, 1, NULL, 1, 'Shalomon', 'salmon@gmail.com', NULL, '$2y$12$DPBqfl2l9pgQcP2/jEruS.VQNzsp5VGhMHzFDS3EQ8Wzm/3f4cYgq', NULL, '2026-09-16 10:11:33', '2026-09-16 10:11:33'),
(8, 2, 2, 1, 'Aria', 'aria@gmail.com', NULL, '$2y$12$e.x2uHYa8SjXftT.SBfQF.Aiy0nNWc/P2G3seUA7FjOtUS50ZQTWG', NULL, '2026-09-16 06:52:05', '2026-09-16 06:52:05'),
(27, 1, NULL, 1, 'Isis', 'segurapaulinoisis@gmail.com', NULL, '$2y$12$LtJZNXa/mAhu5lTiX5yeIe6Zvc8JS.CUxnqyYQKi41herDj7zJ0W2', NULL, '2026-09-29 07:32:15', '2026-09-29 07:32:15'),
(28, 3, 1, 1, 'caja1', 'isegura@ucol.mx', NULL, '$2y$12$ggzspSVoi2uzdXljAAqEJOSylXUAsKv5sbdTXEEjUkk0PnNJPpj/S', NULL, '2026-09-29 07:33:29', '2026-09-29 07:37:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `caja_id` bigint UNSIGNED DEFAULT NULL,
  `metodo_pago_id` bigint UNSIGNED NOT NULL,
  `folio` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tasa_iva` decimal(5,2) NOT NULL DEFAULT '16.00',
  `total` decimal(10,2) NOT NULL,
  `monto_recibido` decimal(10,2) DEFAULT NULL,
  `cambio` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ventas_folio_unique` (`folio`),
  KEY `ventas_sucursal_id_foreign` (`sucursal_id`),
  KEY `ventas_user_id_foreign` (`user_id`),
  KEY `ventas_metodo_pago_id_foreign` (`metodo_pago_id`),
  KEY `ventas_caja_id_foreign` (`caja_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `sucursal_id`, `user_id`, `caja_id`, `metodo_pago_id`, `folio`, `subtotal`, `iva`, `tasa_iva`, `total`, `monto_recibido`, `cambio`, `created_at`, `updated_at`) VALUES
(24, 1, 28, 3, 1, 'V-000001', 872.00, 139.52, 16.00, 1011.52, 2000.00, 988.48, '2026-09-29 08:13:30', '2026-09-29 08:13:30'),
(25, 1, 28, 3, 1, 'V-000002', 283.00, 45.28, 16.00, 328.28, 200000.00, 199671.72, '2026-09-30 00:10:26', '2026-09-30 00:10:26'),
(26, 2, 27, 1, 1, 'V-000003', 796.00, 127.36, 16.00, 923.36, 1000.00, 76.64, '2026-10-02 05:25:15', '2026-10-02 05:25:15'),
(27, 2, 27, 1, 1, 'V-000004', 398.00, 63.68, 16.00, 461.68, 500.00, 38.32, '2026-10-14 02:21:58', '2026-10-14 02:21:58');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
