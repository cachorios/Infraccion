-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2013 a las 11:58:27
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `infraccion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `automotor`
--

CREATE TABLE IF NOT EXISTS `automotor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dominio` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `marca` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `modelo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dni` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `cuit_cuil` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `domicilio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codigo_postal` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `localidad` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `automotorimportar`
--

CREATE TABLE IF NOT EXISTS `automotorimportar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dominio` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marca` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modelo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dni` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuit_cuil` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domicilio` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provincia` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localidad` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE IF NOT EXISTS `grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8C0E9BD33A909126` (`nombre`),
  UNIQUE KEY `UNIQ_8C0E9BD357698A6A` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `nombre`, `role`) VALUES
(1, 'Usuarios', 'ROLE_USUARIO'),
(2, 'Administradores', 'ROLE_ADMIN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `infraccion`
--

CREATE TABLE IF NOT EXISTS `infraccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `municipio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_infraccion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `foto1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `foto2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dominio` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `observacion` longtext COLLATE utf8_unicode_ci NOT NULL,
  `etapa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE IF NOT EXISTS `municipio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `localidad` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `codigo_postal` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `cont_1_nombre` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `cont_1_cargo` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `cont_1_celular` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `cont_1_telfijo` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `cont_1_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `cont_2_nombre` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cont_2_cargo` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cont_2_celular` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cont_2_telfijo` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cont_2_email` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`id`, `codigo`, `nombre`, `email`, `telefono`, `direccion`, `localidad`, `codigo_postal`, `cont_1_nombre`, `cont_1_cargo`, `cont_1_celular`, `cont_1_telfijo`, `cont_1_email`, `cont_2_nombre`, `cont_2_cargo`, `cont_2_celular`, `cont_2_telfijo`, `cont_2_email`) VALUES
(1, 14, 'Municipalidad de Candelaria', 'cande@yahoo.com', '54', 'sd', 'Candelaria', '3000', 'Antonio Escobar', 'Dir. Transporte', '54', '54', 'pulga@gmail.com', NULL, NULL, NULL, NULL, NULL),
(2, 25, 'Municipalidad de Garupa', 'garupa@garupa.com', '54', 'sd', 'Garupa', '3000', 'Hugo Goncalves', 'n/s', '54', '54', 'hugo@gmail.com', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoinfraccion`
--

CREATE TABLE IF NOT EXISTS `tipoinfraccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `observacion` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `tipoinfraccion`
--

INSERT INTO `tipoinfraccion` (`id`, `codigo`, `nombre`, `observacion`) VALUES
(1, 3, 'Semaforo Luz Roja', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE IF NOT EXISTS `ubicacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `municipio_id` int(11) DEFAULT NULL,
  `codigo` int(11) NOT NULL,
  `referencia` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `ubicacion` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EE40E1B58BC1BE0` (`municipio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id`, `municipio_id`, `codigo`, `referencia`, `ubicacion`) VALUES
(1, 2, 1, 'Semaforo 01', 'Ruta 12 km155');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(96) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` longtext COLLATE utf8_unicode_ci NOT NULL,
  `permite_email` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_nacimiento` datetime NOT NULL,
  `dni` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contenedor` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `ultimo_ingreso` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_EDD889C1E7927C74` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `email`, `password`, `salt`, `direccion`, `permite_email`, `is_active`, `is_deleted`, `fecha_alta`, `fecha_nacimiento`, `dni`, `foto`, `contenedor`, `ultimo_ingreso`) VALUES
(1, 'Luis Antonio', 'Rios', 'cachorios@gmail.com', 'jvy8xddHsrXNRxtyg+TOlEU9FUt/+IKOFymoIpDVfBcyKXEZtvX7Wpnswe4JNCsYDcS9Nj+TPGNiAXmZ/UXBmQ==', '943c68f9d9cea9a6a37cb78501122303', 'ch.113 Sec. 120 Casa 99', 1, 1, 0, '2013-05-15 20:29:42', '1968-11-05 00:00:00', '20489453', 'user-511a905a4d1de-foto1.jpg', 'N;', NULL),
(2, 'Alberto', 'Voefray', 'Alberto2000@hotmail.com', 'TbdVwBW/3MAkP5ZMWY+iU6KFWfVGb3hEyMxaUPCLYAQkPp7zc0FKAYiwXtDmAX7FloR4R3F+OoACDhB07dBBxA==', '943c68f9d9cea9a6a37cb78501122303', 'Av. Eva Peron y Centenario', 1, 1, 0, '2013-05-15 20:29:42', '1980-01-15 00:00:00', '26610243', 'user-511a8ef7b8377-foto1.jpg', 'N;', NULL),
(3, 'Ruben', 'Cardozo', 'ruben@hotmail.com', 'TbdVwBW/3MAkP5ZMWY+iU6KFWfVGb3hEyMxaUPCLYAQkPp7zc0FKAYiwXtDmAX7FloR4R3F+OoACDhB07dBBxA==', '943c68f9d9cea9a6a37cb78501122303', 'Av. 1', 1, 1, 0, '2013-05-15 20:29:42', '1951-01-15 00:00:00', '3', 'user-511970d001595-foto1.jpg', 'N;', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_grupo`
--

CREATE TABLE IF NOT EXISTS `usuario_grupo` (
  `usuario_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id`,`grupo_id`),
  KEY `IDX_91D0F1CDDB38439E` (`usuario_id`),
  KEY `IDX_91D0F1CD9C833003` (`grupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_grupo`
--

INSERT INTO `usuario_grupo` (`usuario_id`, `grupo_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD CONSTRAINT `FK_5EE40E1B58BC1BE0` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`);

--
-- Filtros para la tabla `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  ADD CONSTRAINT `FK_91D0F1CD9C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_91D0F1CDDB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
