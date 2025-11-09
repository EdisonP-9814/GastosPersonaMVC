-- By Edison - 20250926
-- Database: `gastos_personales`

CREATE DATABASE IF NOT EXISTS `gastos_personales` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `gastos_personales`;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------
-- PARAMETRIC TABLES --
-- --------------------

-- 01
-- Table structure for table `roles`
--
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 02
-- Table structure for table `usuarios`
--
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `cedula_usuario` varchar(20) COLLATE utf8_unicode_ci NOT NULL UNIQUE,
  `nombre_usuario` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email_usuario` varchar(150) COLLATE utf8_unicode_ci NOT NULL UNIQUE,
  `telefono_usuario` varchar(20) COLLATE utf8_unicode_ci,
  `direccion_usuario` varchar(255) COLLATE utf8_unicode_ci,
  `clave_usuario` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_registro_usuario` timestamp DEFAULT CURRENT_TIMESTAMP,
  `id_rol_usuario` int NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 03
-- Table structure for table `cuentas`
--
CREATE TABLE IF NOT EXISTS `cuentas` (
  `id_cuenta` int NOT NULL AUTO_INCREMENT,
  `nombre_cuenta` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_cuenta` enum('EFECTIVO','BANCO','TARJETA_CREDITO','TARJETA_DEBITO','DIGITAL') NOT NULL,
  `saldo_inicial_cuenta` decimal(14,2) DEFAULT 0,
  `id_usuario_cuenta` int NOT NULL,
  PRIMARY KEY (`id_cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 04
-- Table structure for table `categorias`
--
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_categoria` enum('INGRESO','GASTO') NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 05
-- Table structure for table `subcategorias`
--
CREATE TABLE IF NOT EXISTS `subcategorias` (
  `id_subcategoria` int NOT NULL AUTO_INCREMENT,
  `nombre_subcategoria` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_categoria_subcategoria` int NOT NULL,
  PRIMARY KEY (`id_subcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 06
-- Table structure for table `metodos_pago`
--
CREATE TABLE IF NOT EXISTS `metodos_pago` (
  `id_metodo` int NOT NULL AUTO_INCREMENT,
  `nombre_metodo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_metodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 07
-- Table structure for table `transacciones`
--
CREATE TABLE IF NOT EXISTS `transacciones` (
  `id_transaccion` int NOT NULL AUTO_INCREMENT,
  `monto_transaccion` decimal(14,2) NOT NULL,
  `tipo_transaccion` enum('INGRESO','GASTO') NOT NULL,
  `descripcion_transaccion` varchar(255) COLLATE utf8_unicode_ci,
  `fecha_transaccion` date NOT NULL,
  `id_usuario_transaccion` int NOT NULL,
  `id_cuenta_transaccion` int NOT NULL,
  `id_subcategoria_transaccion` int NOT NULL,
  `id_metodo_transaccion` int NOT NULL,
  PRIMARY KEY (`id_transaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 08
-- Table structure for table `etiquetas`
--
CREATE TABLE IF NOT EXISTS `etiquetas` (
  `id_etiqueta` int NOT NULL AUTO_INCREMENT,
  `nombre_etiqueta` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_etiqueta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 09
-- Table structure for table `transacciones_etiquetas`
--
CREATE TABLE IF NOT EXISTS `transacciones_etiquetas` (
  `id_transaccion_te` int NOT NULL,
  `id_etiqueta_te` int NOT NULL,
  PRIMARY KEY (`id_transaccion_te`, `id_etiqueta_te`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 10
-- Table structure for table `presupuestos`
--
CREATE TABLE IF NOT EXISTS `presupuestos` (
  `id_presupuesto` int NOT NULL AUTO_INCREMENT,
  `monto_limite_presupuesto` decimal(14,2) NOT NULL,
  `periodo_presupuesto` date NOT NULL,
  `id_usuario_presupuesto` int NOT NULL,
  `id_categoria_presupuesto` int NOT NULL,
  PRIMARY KEY (`id_presupuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 11
-- Table structure for table `objetivos`
--
CREATE TABLE IF NOT EXISTS `objetivos` (
  `id_objetivo` int NOT NULL AUTO_INCREMENT,
  `nombre_objetivo` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `monto_objetivo` decimal(14,2) NOT NULL,
  `fecha_limite_objetivo` date NOT NULL,
  `id_usuario_objetivo` int NOT NULL,
  PRIMARY KEY (`id_objetivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 12
-- Table structure for table `prestamos`
--
CREATE TABLE IF NOT EXISTS `prestamos` (
  `id_prestamo` int NOT NULL AUTO_INCREMENT,
  `nombre_deudor_prestamo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `monto_prestamo` decimal(14,2) NOT NULL,
  `tipo_prestamo` enum('A_PAGAR','A_COBRAR') NOT NULL,
  `fecha_inicio_prestamo` date NOT NULL,
  `fecha_fin_prestamo` date,
  `id_usuario_prestamo` int NOT NULL,
  PRIMARY KEY (`id_prestamo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 13
-- Table structure for table `notificaciones`
--
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` int NOT NULL AUTO_INCREMENT,
  `mensaje_notificacion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `leido_notificacion` boolean DEFAULT FALSE,
  `fecha_notificacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `id_usuario_notificacion` int NOT NULL,
  PRIMARY KEY (`id_notificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- ---------------------------
-- Definicon de foreing key --
-- ---------------------------

ALTER TABLE `usuarios`
  ADD KEY (`id_rol_usuario`),
  ADD CONSTRAINT `fk_rol_usuario` FOREIGN KEY (`id_rol_usuario`) REFERENCES `roles` (`id_rol`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `cuentas`
  ADD KEY (`id_usuario_cuenta`),
  ADD CONSTRAINT `fk_usuario_cuenta` FOREIGN KEY (`id_usuario_cuenta`) REFERENCES `usuarios` (`id_usuario`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `subcategorias`
  ADD KEY (`id_categoria_subcategoria`),
  ADD CONSTRAINT `fk_categoria_subcategoria` FOREIGN KEY (`id_categoria_subcategoria`) REFERENCES `categorias` (`id_categoria`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `transacciones`
  ADD KEY (`id_usuario_transaccion`),
  ADD CONSTRAINT `fk_usuario_transaccion` FOREIGN KEY (`id_usuario_transaccion`) REFERENCES `usuarios` (`id_usuario`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  ADD KEY (`id_cuenta_transaccion`),
  ADD CONSTRAINT `fk_cuenta_transaccion` FOREIGN KEY (`id_cuenta_transaccion`) REFERENCES `cuentas` (`id_cuenta`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  ADD KEY (`id_subcategoria_transaccion`),
  ADD CONSTRAINT `fk_subcategoria_transaccion` FOREIGN KEY (`id_subcategoria_transaccion`) REFERENCES `subcategorias` (`id_subcategoria`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  ADD KEY (`id_metodo_transaccion`),
  ADD CONSTRAINT `fk_metodo_transaccion` FOREIGN KEY (`id_metodo_transaccion`) REFERENCES `metodos_pago` (`id_metodo`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `transacciones_etiquetas`
  ADD KEY (`id_transaccion_te`),
  ADD CONSTRAINT `fk_transaccion_te` FOREIGN KEY (`id_transaccion_te`) REFERENCES `transacciones` (`id_transaccion`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  ADD KEY (`id_etiqueta_te`),
  ADD CONSTRAINT `fk_etiqueta_te` FOREIGN KEY (`id_etiqueta_te`) REFERENCES `etiquetas` (`id_etiqueta`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `presupuestos`
  ADD KEY (`id_usuario_presupuesto`),
  ADD CONSTRAINT `fk_usuario_presupuesto` FOREIGN KEY (`id_usuario_presupuesto`) REFERENCES `usuarios` (`id_usuario`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  ADD KEY (`id_categoria_presupuesto`),
  ADD CONSTRAINT `fk_categoria_presupuesto` FOREIGN KEY (`id_categoria_presupuesto`) REFERENCES `categorias` (`id_categoria`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `objetivos`
  ADD KEY (`id_usuario_objetivo`),
  ADD CONSTRAINT `fk_usuario_objetivo` FOREIGN KEY (`id_usuario_objetivo`) REFERENCES `usuarios` (`id_usuario`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `prestamos`
  ADD KEY (`id_usuario_prestamo`),
  ADD CONSTRAINT `fk_usuario_prestamo` FOREIGN KEY (`id_usuario_prestamo`) REFERENCES `usuarios` (`id_usuario`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `notificaciones`
  ADD KEY (`id_usuario_notificacion`),
  ADD CONSTRAINT `fk_usuario_notificacion` FOREIGN KEY (`id_usuario_notificacion`) REFERENCES `usuarios` (`id_usuario`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;
