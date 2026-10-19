-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 02-09-2026 a las 19:24:21
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.4.15

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
  `key` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'ipsam', 'Fuga aperiam et molestias voluptatem nihil.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(2, 'et', 'Tempora facere modi laborum eum eos nisi ut.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(3, 'voluptatem', 'Laudantium nulla aut et est ut cum commodi.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(4, 'numquam', 'Sit dolorum dolor dolore rerum minima placeat.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(5, 'aut', 'Beatae nesciunt non aut magni vel aut rerum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(6, 'soluta', 'Quis dolor sint alias non.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(7, 'sit', 'Dolores natus rerum ut vel ullam alias.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(8, 'id', 'Voluptatem iste vel facilis similique quam eos vel.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(9, 'est', 'Ducimus eum dolor sunt excepturi deserunt aut facilis.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(10, 'est', 'Delectus molestiae cupiditate eum beatae.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(11, 'doloribus', 'Reiciendis qui deserunt et esse necessitatibus ut optio.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(12, 'sint', 'Eveniet repudiandae nesciunt labore eaque et qui.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(13, 'officia', 'Sint enim laborum corrupti facere sunt.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(14, 'quia', 'Ab ducimus sequi perspiciatis rerum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(15, 'consequatur', 'Et error blanditiis aperiam tempore perspiciatis delectus.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(16, 'neque', 'Nisi animi vel aut delectus fugiat dignissimos.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(17, 'hic', 'Consequuntur facere sed consequatur.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(18, 'et', 'Dolorum ut qui voluptatibus odio non consequuntur non.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(19, 'aut', 'Et fugit laudantium qui aperiam sit ut ut.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(20, 'quod', 'Eius consequatur ut odio omnis.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(21, 'unde', 'Expedita corrupti maxime consequuntur porro nam enim.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(22, 'id', 'Explicabo libero qui alias et ut.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(23, 'modi', 'Dicta qui ea et nulla inventore id magnam.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(24, 'consequuntur', 'Enim dolorem explicabo est.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(25, 'hic', 'Ducimus ullam et temporibus consequatur ad veniam.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(26, 'assumenda', 'Maxime cupiditate vel nemo voluptatum et voluptas dolor.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(27, 'placeat', 'Alias dolor error consequuntur neque quasi.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(28, 'consectetur', 'Voluptates et dolore quis ea doloribus repellendus omnis error.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(29, 'et', 'Est et autem voluptatem magnam dolore harum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(30, 'quia', 'Vitae illum qui accusantium architecto aut.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(31, 'voluptatem', 'Ipsum doloremque nisi recusandae est eaque beatae eum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(32, 'placeat', 'Possimus velit ipsum dolores sequi.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(33, 'natus', 'Ut sed est in velit voluptas.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(34, 'ut', 'Quo voluptas quaerat et.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(35, 'tempora', 'Ab maiores adipisci distinctio.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(36, 'fugit', 'Laudantium ut ut sunt dolores delectus iste.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(37, 'omnis', 'Praesentium repellendus et sed ut in nam.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(38, 'corrupti', 'Aut illo et quaerat consectetur molestiae.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(39, 'est', 'Quos quia cupiditate amet atque voluptatibus harum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(40, 'iure', 'Sed incidunt saepe repudiandae.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(41, 'corporis', 'Deserunt similique dignissimos nisi molestias.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(42, 'velit', 'Commodi animi magnam et enim maxime nulla cumque.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(43, 'error', 'Enim amet reiciendis aliquid sed dolores.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(44, 'voluptatem', 'Maxime placeat laudantium aut dolor quod rem.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(45, 'tempore', 'Deserunt omnis exercitationem ipsam consequatur nulla pariatur sunt.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(46, 'molestiae', 'Sunt maxime magni fugiat.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(47, 'laborum', 'Et neque minus non qui.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(48, 'eligendi', 'Unde quasi nostrum esse ut.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(49, 'quia', 'Illo aut rerum totam qui ut.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(50, 'ratione', 'Iusto eos eligendi vel ea fugiat vel quisquam.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(51, 'placeat', 'Repudiandae sed ipsum aut voluptatum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(52, 'voluptatem', 'Omnis eaque est nostrum occaecati optio est sunt optio.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(53, 'harum', 'Voluptates molestias enim ducimus quidem aut cumque.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(54, 'magni', 'Adipisci nobis placeat vel ut animi corporis sunt.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(55, 'et', 'Laudantium rerum debitis est iusto esse cumque vitae.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(56, 'laboriosam', 'Eum nostrum cupiditate autem nobis.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(57, 'velit', 'Minima veritatis libero aut ullam.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(58, 'nemo', 'Iure error perferendis similique consequuntur deleniti.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(59, 'iusto', 'Quis et et porro incidunt cumque recusandae unde.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(60, 'voluptate', 'Dolores quibusdam reiciendis aspernatur veritatis ut similique.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(61, 'nihil', 'Voluptas illo qui maxime sit cum aspernatur perferendis.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(62, 'quidem', 'Consequuntur quia voluptate officiis.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(63, 'est', 'Praesentium impedit dolorum modi soluta temporibus adipisci.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(64, 'totam', 'Ea ullam autem libero tenetur et minima.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(65, 'sit', 'Et aperiam iste et rerum temporibus hic reiciendis.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(66, 'sit', 'Provident veniam accusamus possimus libero impedit ex consequatur.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(67, 'ut', 'Id rerum excepturi error provident voluptas.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(68, 'est', 'Quibusdam doloremque facere veniam velit aut et et.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(69, 'assumenda', 'Ipsum ea deleniti repudiandae autem modi.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(70, 'ut', 'Accusantium earum quisquam id repellat ut.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(71, 'qui', 'Rerum ad a qui ducimus expedita saepe eveniet.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(72, 'ex', 'Et magnam omnis quisquam ea.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(73, 'molestiae', 'Vel molestiae et est architecto temporibus hic et.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(74, 'dignissimos', 'Odit libero suscipit consequatur officiis consectetur quia numquam qui.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(75, 'adipisci', 'Odit eum ut dolor illum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(76, 'dolores', 'Commodi laudantium qui qui.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(77, 'totam', 'Assumenda rem quasi porro.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(78, 'veniam', 'Recusandae iure vero delectus exercitationem vel enim inventore.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(79, 'dolores', 'A voluptas sunt aut molestiae optio voluptate qui.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(80, 'autem', 'Dolorem qui velit cumque incidunt necessitatibus.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(81, 'et', 'Quam quis dolorem quo est.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(82, 'unde', 'Illum ipsam occaecati aut molestiae atque.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(83, 'fugit', 'Nulla laboriosam ipsam eius eligendi.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(84, 'ut', 'Ea magni corrupti repellendus veniam unde.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(85, 'rerum', 'Sed dolorem rerum non totam dicta nihil.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(86, 'aliquam', 'Debitis dolore suscipit eaque est dolore illum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(87, 'vero', 'Ab architecto fuga aut eos assumenda.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(88, 'debitis', 'Harum ex quidem sit.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(89, 'libero', 'Et mollitia velit quis officiis quia aut provident.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(90, 'aperiam', 'Laboriosam earum alias ipsam quia est.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(91, 'animi', 'Sit odio ut tenetur illum.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(92, 'mollitia', 'Sunt non quidem sed hic et voluptatem autem.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(93, 'vel', 'Quia ratione est ipsam et.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(94, 'dolorem', 'Consequuntur voluptas fuga qui sed numquam nesciunt molestiae.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(95, 'esse', 'Ipsam inventore tenetur velit illum ut rerum inventore sed.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(96, 'fugit', 'Ut voluptatum beatae a officia.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(97, 'voluptatem', 'Laborum dolore molestiae dolorem ad quia ad.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(98, 'necessitatibus', 'Commodi voluptatem voluptatem consequatur in nam voluptatem dolores.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(99, 'ratione', 'Labore nesciunt tempore minus excepturi facere.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(100, 'expedita', 'Illo adipisci deserunt voluptas et.', '2026-08-30 03:12:02', '2026-08-30 03:12:02'),
(101, 'dolore', 'Est aut asperiores exercitationem qui cum nobis minima.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(102, 'soluta', 'Repudiandae ea id corporis eum quidem.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(103, 'debitis', 'Iste a impedit dignissimos quos quo.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(104, 'quis', 'Illum voluptatem officia dolores.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(105, 'dolorem', 'Facilis nam illum illum molestiae.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(106, 'tempore', 'Odit numquam aut at facilis.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(107, 'vel', 'Perferendis velit laborum maxime est quibusdam.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(108, 'aliquam', 'Consequuntur laudantium repellat laboriosam quod similique adipisci autem consectetur.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(109, 'consequatur', 'Qui hic omnis sequi natus expedita.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(110, 'et', 'Eius eum aut occaecati modi illo id et.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(111, 'delectus', 'Cum perferendis quia ut perspiciatis necessitatibus.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(112, 'velit', 'Dolorem architecto est omnis veritatis animi repellendus ut.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(113, 'et', 'Consequatur at quam et nihil.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(114, 'nihil', 'Ratione sed ut ad magnam.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(115, 'similique', 'Itaque consectetur saepe occaecati quis qui quis.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(116, 'qui', 'Sit totam sed ea sed qui sunt quos.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(117, 'dolore', 'Nihil qui aliquam voluptatibus autem nam pariatur totam.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(118, 'aut', 'Repellat placeat magnam quia officiis ducimus.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(119, 'quia', 'Dolor et ipsam facilis quaerat aperiam quo.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(120, 'porro', 'Ex rerum eaque qui pariatur qui.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(121, 'hic', 'Consequuntur error veritatis magni in.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(122, 'tempore', 'Nihil modi sit ipsam voluptas laudantium.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(123, 'quibusdam', 'Qui sit ut tempore modi nulla vel.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(124, 'quisquam', 'Voluptatibus iure velit omnis optio nemo voluptates.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(125, 'sed', 'Ducimus maxime qui non distinctio eos iusto est fugit.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(126, 'consequatur', 'Corrupti dolores aliquam alias voluptate nulla rerum et eum.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(127, 'sint', 'Accusamus voluptatum quis adipisci et accusantium voluptatibus omnis.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(128, 'rem', 'Asperiores eos et quos eveniet et.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(129, 'saepe', 'Autem qui quas quis qui dignissimos eos.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(130, 'aut', 'Dolor nam ducimus ullam incidunt ut quos reprehenderit.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(131, 'accusantium', 'Deleniti amet est voluptate maiores illum dolor alias.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(132, 'velit', 'Sit magnam tenetur repudiandae aut nesciunt quod iure.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(133, 'ut', 'Delectus autem quo doloribus voluptates.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(134, 'aut', 'Atque voluptatem et neque et.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(135, 'nesciunt', 'Commodi iure incidunt eveniet.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(136, 'eum', 'Sed et ut excepturi dolorem explicabo amet ut.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(137, 'nisi', 'Occaecati quo qui sit ipsa.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(138, 'temporibus', 'Voluptas pariatur ex qui totam.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(139, 'nihil', 'Occaecati sint officia iure mollitia.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(140, 'dolor', 'Eligendi dolor error et qui.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(141, 'unde', 'Repudiandae eveniet voluptatum ratione quae ut.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(142, 'et', 'Ex voluptatem quae porro quidem id ut.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(143, 'quam', 'Modi aut quam soluta voluptas nulla.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(144, 'voluptatibus', 'Alias saepe dolorum eos rem.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(145, 'sunt', 'Rem quo sunt aut nam.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(146, 'eius', 'Consectetur quidem est et.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(147, 'quia', 'Ea quo est corporis tenetur accusamus sed excepturi in.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(148, 'quas', 'Consequatur esse error cupiditate perspiciatis ut.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(149, 'quaerat', 'Atque ipsum aliquam quos tenetur.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(150, 'minima', 'Dicta id neque sed aut alias.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(151, 'voluptatem', 'Itaque enim ut rerum qui distinctio.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(152, 'dolores', 'Mollitia eligendi impedit ab nobis quas recusandae.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(153, 'similique', 'In id nam error quas hic.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(154, 'sed', 'Molestiae vel nisi consequatur ut aspernatur nihil.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(155, 'rerum', 'Architecto voluptate et suscipit facere recusandae quas.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(156, 'repudiandae', 'Molestiae inventore ut magnam et.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(157, 'blanditiis', 'Atque sapiente at eos maiores enim.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(158, 'cupiditate', 'Rerum ex velit magni impedit.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(159, 'magni', 'Numquam enim qui quia rerum ratione aspernatur.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(160, 'nesciunt', 'Rerum quam doloribus sunt rerum voluptatem velit saepe.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(161, 'sunt', 'Ut soluta vel nulla voluptate exercitationem.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(162, 'culpa', 'Dolores asperiores voluptas dolorem porro tempora incidunt.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(163, 'quo', 'Non est itaque quis et odio laudantium nihil.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(164, 'sed', 'Inventore similique et unde ipsum fugiat error.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(165, 'qui', 'Sequi nostrum vel dolores harum eius distinctio sed.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(166, 'architecto', 'Deleniti ut voluptas corrupti suscipit eum voluptas ratione.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(167, 'ut', 'Consequatur facilis est animi autem libero veniam iste.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(168, 'exercitationem', 'Ut error sit eos rerum excepturi consequuntur officiis.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(169, 'maiores', 'Deserunt odio sit optio rerum.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(170, 'quis', 'Officiis vel et sed officiis sequi fugiat perferendis.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(171, 'minus', 'Aliquam libero at id possimus est.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(172, 'molestias', 'Ipsum omnis repellat iste.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(173, 'praesentium', 'A et autem eum cum vel.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(174, 'alias', 'Excepturi repellat aspernatur illum occaecati.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(175, 'ullam', 'Dolorem voluptas nostrum deserunt omnis rerum accusamus quam animi.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(176, 'fuga', 'Repellendus minus sit ut qui qui explicabo.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(177, 'iure', 'Distinctio cumque ut sint beatae voluptatibus.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(178, 'laborum', 'Soluta laborum doloribus nam et quaerat quis.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(179, 'dignissimos', 'Voluptatem deserunt minus sed eos voluptatibus est at.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(180, 'velit', 'Doloremque alias et labore quae quas eum repudiandae.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(181, 'reprehenderit', 'Explicabo distinctio labore iusto.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(182, 'laboriosam', 'Repellat ut qui blanditiis dignissimos molestiae ullam deleniti.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(183, 'eum', 'Repellat fugit consectetur nulla quidem.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(184, 'asperiores', 'Repudiandae quia maiores molestiae nemo et.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(185, 'aut', 'Nisi pariatur voluptas quod quam unde aut.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(186, 'et', 'Ut iure ab unde voluptatem nobis quis.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(187, 'et', 'Iusto maxime consectetur voluptate velit.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(188, 'ipsa', 'Nobis aliquam aspernatur qui pariatur.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(189, 'eveniet', 'In sint amet quia est vero.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(190, 'hic', 'Voluptatibus dolor et soluta nam veniam omnis qui.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(191, 'et', 'Ex incidunt qui corrupti nisi dolor libero ea.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(192, 'harum', 'Sed aut omnis odio ut.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(193, 'veniam', 'Repellendus quasi hic expedita ipsam.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(194, 'et', 'Illo quae alias porro voluptatem nesciunt iusto vitae.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(195, 'id', 'Architecto qui enim ipsa possimus.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(196, 'vel', 'Accusamus hic vel quisquam error quis voluptatibus.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(197, 'qui', 'Magnam id quasi voluptates temporibus rem.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(198, 'nisi', 'Sunt placeat perferendis eum cumque deserunt.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(199, 'quia', 'Unde similique necessitatibus voluptates a.', '2026-08-31 00:59:46', '2026-08-31 00:59:46'),
(200, 'aut', 'Quia ut soluta et in magni et nostrum.', '2026-08-31 00:59:46', '2026-08-31 00:59:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `id` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb3_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(7, '2026_08_29_183220_create_categorias_table', 2),
(8, '2026_08_29_190000_create_roles_table', 3),
(9, '2026_08_29_190100_add_role_id_to_users_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
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
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `id` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9mgOraug5iJLCevIlKwwpO7YeXx4l8EncKZbpaqp', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJuem5MWGdOVGxoYnNXRThpbjQzTloyMTlBWFV2S2hSNmJoeDVCcGVhIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9QSVwvcHVibGljXC9hZG1pblwvdXN1YXJpb3MiLCJyb3V0ZSI6ImFkbWluLnVzdWFyaW9zLmluZGV4In0sInVybCI6W10sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJhdXRoIjp7InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI6MTc4ODMwNjY0MH19', 1788306642);

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
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `role_id`, `sucursal_id`, `activo`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'Johiel', 'joshuagabrieldm@gmail.com', NULL, '$2y$12$Twc5Vg6UixhrtcUr7mXK/.h.LiGOwy6sTNaW53SljPiOyFvPTe.5.', NULL, '2026-08-29 03:59:41', '2026-08-29 03:59:41'),
(2, 1, NULL, 1, 'Administrador General', 'admin@pi.com', '2026-08-31 00:59:45', '$2y$12$TXVXhuiewnjhsJP3BE6F5eGFzq9ZACMiEtuEJjaGM9ERHR1sA5J32', NULL, '2026-08-31 00:59:45', '2026-08-31 00:59:45'),
(3, 2, NULL, 1, 'Ariel', 'ari@gamil.com', NULL, '$2y$12$KaI5KLX0zo7Ijh0/rB1e/OsBjlTjstbABU/DA.vTtabxRVs4UvYDq', NULL, '2026-08-31 01:21:11', '2026-08-31 01:21:11');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
