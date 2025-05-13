-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2025 a las 13:55:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `playa2.0`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pitta100productosMasVendidos` ()   BEGIN

SELECT p.id, 
       p.producto, 
       SUM(v.cantidad) AS cantidad, 
       SUM(ROUND(v.total, 2)) AS total_venta
FROM ventas v
INNER JOIN productos p ON v.id_producto = p.id
GROUP BY p.id, p.producto
ORDER BY total_venta DESC
LIMIT 10;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_ObtenerDatosDashboard` ()   BEGIN
    DECLARE totalProductos INT;
    DECLARE totalCompras FLOAT;
    DECLARE totalVentas FLOAT;
    DECLARE ganancias FLOAT;
    DECLARE productoPocoStock INT; -- Corregido el tipo de datos
    DECLARE ventasHoy FLOAT;

    -- Asignación de valores a las variables
    SET totalProductos = (SELECT COUNT(*) FROM productos p);
    SET totalCompras = (SELECT SUM(p.precio_costo * p.stock) FROM productos p);
    SET totalVentas = (SELECT SUM(v.total) FROM ventas v);
    SET ganancias = (SELECT SUM(v.total) - SUM(p.precio_costo * v.cantidad) 
                     FROM ventas v 
                     INNER JOIN productos p ON v.id_producto = p.id);
    SET productoPocoStock = (SELECT COUNT(1) FROM productos p WHERE p.stock <= p.stock_minimo); -- Corregido la lógica de "producto poco stock"
    SET ventasHoy = (SELECT SUM(v.total) FROM ventas v WHERE v.fecha_venta = CURDATE());

    -- Selección de los resultados
    SELECT 
        IFNULL(totalProductos, 0) AS totalProductos,
        IFNULL(ROUND(totalCompras, 2), 0) AS totalCompras,
        IFNULL(ROUND(totalVentas, 2), 0) AS totalVentas,
        IFNULL(ROUND(ganancias, 2), 0) AS ganancias,
        IFNULL(productoPocoStock, 0) AS productoPocoStock,
        IFNULL(ROUND(ventasHoy, 2), 0) AS ventasHoy;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_ObtenerVentasMesActual` ()   BEGIN
SELECT DATE(v.fecha_venta) AS fecha_venta,
       SUM(ROUND(v.total, 3)) AS total_venta
FROM ventas v
WHERE DATE(v.fecha_venta) >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')  -- Primer día del mes actual
  AND DATE(v.fecha_venta) <= last_day(CURRENT_DATE)  -- Último día del mes actual
GROUP BY DATE(v.fecha_venta);





END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acreedores`
--

CREATE TABLE `acreedores` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `concepto` text NOT NULL,
  `monto` int(11) NOT NULL,
  `saldo` int(11) NOT NULL,
  `sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `caja` varchar(50) NOT NULL,
  `monto` float NOT NULL,
  `comprobante` text NOT NULL,
  `anulado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `id_padre` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `id_padre`, `categoria`) VALUES
(72, 0, 'Rodados (Vehículos terrestres)'),
(73, 0, 'Lanchas (Vehículos acuáticos)'),
(74, 0, 'Aéreos'),
(75, 0, 'Otros   Vehículos eléctrico, Equipo pesado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierres`
--

CREATE TABLE `cierres` (
  `id` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `monto_apertura` double NOT NULL,
  `monto_cierre` double DEFAULT NULL,
  `cot_real` int(11) NOT NULL,
  `cot_dolar` int(11) NOT NULL,
  `sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cierres`
--

INSERT INTO `cierres` (`id`, `fecha_apertura`, `fecha_cierre`, `id_usuario`, `id_caja`, `monto_apertura`, `monto_cierre`, `cot_real`, `cot_dolar`, `sucursal`) VALUES
(299, '2025-05-11 09:44:00', '2025-05-12 11:23:00', 14, 3, 0, 0, 1350, 7870, 0),
(300, '2025-05-12 11:23:00', NULL, 14, 3, 0, NULL, 1350, 7870, 0),
(301, '2025-05-12 20:05:00', NULL, 3, 3, 0, NULL, 1350, 7870, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_inventario`
--

CREATE TABLE `cierre_inventario` (
  `id` int(11) NOT NULL,
  `fecha_apertura` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `usuario_inicial` int(11) DEFAULT NULL,
  `usuario_final` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cierre_inventario`
--

INSERT INTO `cierre_inventario` (`id`, `fecha_apertura`, `fecha_cierre`, `usuario_inicial`, `usuario_final`, `motivo`) VALUES
(1, '2022-04-21 10:37:00', NULL, 22, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `nick` varchar(30) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `pass` varchar(15) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cumple` date DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `foto_perfil` text DEFAULT NULL,
  `user_cod` varchar(50) DEFAULT NULL,
  `sucursal` int(11) DEFAULT NULL,
  `cliente` int(11) DEFAULT 0,
  `proveedor` int(11) DEFAULT 0,
  `puntos` int(11) DEFAULT NULL,
  `gastado` int(11) DEFAULT NULL,
  `mayorista` varchar(3) NOT NULL,
  `adressWork` varchar(255) DEFAULT NULL,
  `residencia_url` text DEFAULT NULL,
  `phoneWork` varchar(50) DEFAULT NULL,
  `comprobanteIngreso` varchar(255) DEFAULT NULL,
  `cedulaTributaria` varchar(255) DEFAULT NULL,
  `facturasLegalesEmitidas` varchar(255) DEFAULT NULL,
  `cedulaIdentidad` varchar(255) DEFAULT NULL,
  `estructuraJuridica` varchar(255) DEFAULT NULL,
  `beneficiarioFinal` varchar(255) DEFAULT NULL,
  `varios` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `ruc`, `nombre`, `nick`, `correo`, `pass`, `telefono`, `cumple`, `direccion`, `fecha_registro`, `foto_perfil`, `user_cod`, `sucursal`, `cliente`, `proveedor`, `puntos`, `gastado`, `mayorista`, `adressWork`, `residencia_url`, `phoneWork`, `comprobanteIngreso`, `cedulaTributaria`, `facturasLegalesEmitidas`, `cedulaIdentidad`, `estructuraJuridica`, `beneficiarioFinal`, `varios`) VALUES
(186, '3629078 ', 'rosa echague ', ' ', 'pitta100@gmail.com ', ' ', '0976810854', '0000-00-00', 'calle angaite ', '2025-04-22', NULL, NULL, 0, 1, 0, 0, 0, 'NO', 'super carretera avda san blas ', NULL, '061255366', NULL, NULL, NULL, NULL, NULL, '6818a69c00e44_CURRICULUM_PITTA (3).pdf', '6818a69c01f66_CURRICULUM_PITTA (2).pdf'),
(188, '3198595', 'victor Pitta', ' ', 'pitta100@gmail.com', ' ', '0983455074', '0000-00-00', 'calle angasite', '2025-04-28', NULL, NULL, 0, 0, 0, 0, 0, 'NO', 'angaite', 'https://maps.app.goo.gl/S6PPB69GLdqC5PNz8', '0983455074', '6818a94e86bdf_CURRICULUM_PITTA (3).pdf', '6818a94e88155_CURRICULUM_PITTA (2).pdf', '6818a94e8828a_index.pdf', '6818a94e883ce_permiso.pdf', '6818a94e884e5_Calendario_30_Dias_Emprendimiento.pdf', '6818a94e88b24_555.pdf', '6818a94e88c2c_01-25-BI-INFORME-MAPAS.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_producto` varchar(30) NOT NULL,
  `precio_compra` float NOT NULL,
  `precio_min` float NOT NULL,
  `precio_may` float NOT NULL,
  `subtotal` float NOT NULL,
  `descuento` int(11) NOT NULL,
  `iva` int(11) NOT NULL,
  `total` float NOT NULL,
  `comprobante` varchar(20) NOT NULL,
  `nro_comprobante` varchar(40) DEFAULT NULL,
  `cantidad` float NOT NULL,
  `margen_ganancia` varchar(45) NOT NULL,
  `fecha_compra` datetime NOT NULL,
  `metodo` varchar(40) NOT NULL,
  `banco` varchar(45) NOT NULL,
  `contado` varchar(30) NOT NULL,
  `anulado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras_tmp`
--

CREATE TABLE `compras_tmp` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_producto` varchar(25) NOT NULL,
  `precio_compra` int(11) NOT NULL,
  `precio_min` float NOT NULL,
  `precio_may` float NOT NULL,
  `cantidad` float NOT NULL,
  `fecha_compra` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_emitida` date NOT NULL,
  `fecha_pagada` date NOT NULL,
  `comprobante` varchar(20) NOT NULL,
  `nro_comprobante` varchar(40) NOT NULL,
  `monto` int(11) NOT NULL,
  `saldo` int(11) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deudas`
--

CREATE TABLE `deudas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `vencimiento` date DEFAULT NULL,
  `concepto` text NOT NULL,
  `monto` int(11) NOT NULL,
  `saldo` int(11) NOT NULL,
  `sucursal` int(11) NOT NULL,
  `cuotas` int(11) NOT NULL,
  `intereses` int(20) DEFAULT 0,
  `montoRefuerzo` decimal(10,2) DEFAULT NULL,
  `cantidadRefuerzo` int(11) DEFAULT NULL,
  `fecha_refuerzo` date DEFAULT NULL,
  `fecha_pago_cuota` date DEFAULT NULL,
  `tipo_entrega` varchar(20) DEFAULT NULL,
  `entrega_inicial` decimal(10,2) DEFAULT 0.00,
  `entregas_restantes` int(11) DEFAULT 0,
  `monto_estimado` decimal(10,2) DEFAULT 0.00,
  `venci_entrega_restante` datetime DEFAULT NULL,
  `totalEntrega` decimal(10,2) DEFAULT 0.00,
  `frecuencia_pagos` enum('trimestral','semestral','novenal','Anual') NOT NULL DEFAULT 'Anual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `deudas`
--

INSERT INTO `deudas` (`id`, `id_cliente`, `id_venta`, `fecha`, `vencimiento`, `concepto`, `monto`, `saldo`, `sucursal`, `cuotas`, `intereses`, `montoRefuerzo`, `cantidadRefuerzo`, `fecha_refuerzo`, `fecha_pago_cuota`, `tipo_entrega`, `entrega_inicial`, `entregas_restantes`, `monto_estimado`, `venci_entrega_restante`, `totalEntrega`, `frecuencia_pagos`) VALUES
(43, 1, 1, '2025-05-12 15:50:00', '2025-12-12', 'Venta a crédito', 135000000, 74999998, 1, 18, 0, 0.00, 0, '2026-02-12', '2025-05-12', 'parcial', 15000000.00, 1, 20000000.00, '2025-10-12 15:49:00', 35000000.00, 'trimestral'),
(44, 186, 2, '2025-05-12 21:19:00', '2025-05-12', 'Venta a crédito', 135000000, 100000000, 1, 24, 0, 5000000.00, 2, '2025-06-12', '2025-05-12', 'parcial', 15000000.00, 1, 20000000.00, '2025-09-12 21:18:00', 35000000.00, 'semestral'),
(45, 188, 3, '2025-05-12 22:28:00', '2025-06-12', 'Venta a crédito', 135000000, 100000000, 1, 20, 0, 5000000.00, 2, '2025-09-12', '2025-06-12', 'parcial', 15000000.00, 1, 20000000.00, '2025-07-12 22:26:00', 35000000.00, 'semestral'),
(46, 186, 4, '2025-05-12 23:37:00', '2025-07-12', 'Venta a crédito', 200, 73, 1, 11, 0, 10.00, 1, '2026-03-12', '2025-05-12', 'total', 0.00, 0, 0.00, '2025-05-12 23:02:00', 120.00, 'trimestral');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `vendedor_salon` int(11) DEFAULT NULL,
  `id_producto` varchar(30) NOT NULL,
  `precio_costo` float NOT NULL,
  `precio_venta` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  `descuento` varchar(80) NOT NULL,
  `iva` int(11) NOT NULL,
  `total` float NOT NULL,
  `comprobante` varchar(20) NOT NULL,
  `nro_comprobante` varchar(40) DEFAULT NULL,
  `cantidad` float NOT NULL,
  `margen_ganancia` varchar(45) NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `metodo` varchar(40) NOT NULL,
  `banco` varchar(45) NOT NULL,
  `contado` varchar(30) NOT NULL,
  `anulado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `devoluciones`
--

INSERT INTO `devoluciones` (`id`, `id_venta`, `id_cliente`, `id_vendedor`, `vendedor_salon`, `id_producto`, `precio_costo`, `precio_venta`, `subtotal`, `descuento`, `iva`, `total`, `comprobante`, `nro_comprobante`, `cantidad`, `margen_ganancia`, `fecha_venta`, `metodo`, `banco`, `contado`, `anulado`) VALUES
(1, 1, 0, 15, 0, '121', 23500, 35000, -35000, 'Ajuste', 0, -35000, '0', '0', -1, '0', '2022-02-14 17:14:00', '0', '0', 'Efectivo', 0),
(2, 1, 0, 15, 0, '192', 13500, 20000, -20000, 'Ajuste', 0, -20000, '0', '0', -1, '0', '2022-02-14 17:14:00', '0', '0', 'Efectivo', 0),
(3, 2, 0, 15, 0, '367', 23500, 35000, -35000, 'Ajuste', 0, -35000, '0', '0', -1, '0', '2022-02-17 18:11:00', '0', '0', 'Efectivo', 0),
(4, 3, 0, 15, 0, '116', 40000, 60000, -60000, 'Ajuste', 0, -60000, '0', '0', -1, '0', '2022-02-18 17:25:00', '0', '0', 'Efectivo', 0),
(5, 3, 0, 15, 0, '104', 73500, 110000, -110000, 'Ajuste', 0, -110000, '0', '0', -1, '0', '2022-02-18 17:25:00', '0', '0', 'Efectivo', 0),
(6, 4, 0, 15, 0, '257', 80000, 120000, -120000, 'Ajuste', 0, -120000, '0', '0', -1, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(7, 4, 0, 15, 0, '251', 73500, 110000, -110000, 'Ajuste', 0, -110000, '0', '0', -1, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(8, 4, 0, 15, 0, '266', 90000, 135000, -135000, 'Ajuste', 0, -135000, '0', '0', -1, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(9, 4, 0, 15, 0, '264', 83500, 125000, -125000, 'Ajuste', 0, -125000, '0', '0', -1, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(10, 4, 0, 15, 0, '263', 67000, 100000, -600000, 'Ajuste', 0, -600000, '0', '0', -6, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(11, 4, 0, 15, 0, '262', 60000, 90000, -270000, 'Ajuste', 0, -270000, '0', '0', -3, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(12, 4, 0, 15, 0, '242', 110000, 165000, -165000, 'Ajuste', 0, -165000, '0', '0', -1, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(13, 4, 0, 15, 0, '202', 7000, 10000, -10000, 'Ajuste', 0, -10000, '0', '0', -1, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(14, 4, 0, 15, 0, '167', 7000, 10000, -10000, 'Ajuste', 0, -10000, '0', '0', -1, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(15, 4, 0, 15, 0, '192', 13500, 20000, -180000, 'Ajuste', 0, -180000, '0', '0', -9, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(16, 4, 0, 15, 0, '198', 10000, 15000, -120000, 'Ajuste', 0, -120000, '0', '0', -8, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(17, 4, 0, 15, 0, '197', 7000, 10000, -30000, 'Ajuste', 0, -30000, '0', '0', -3, '0', '2022-02-21 18:00:00', '0', '0', 'Efectivo', 0),
(18, 5, 0, 15, 0, '94', 17000, 25000, -25000, 'Ajuste', 0, -25000, '0', '0', -1, '0', '2022-02-21 18:06:00', '0', '0', 'Efectivo', 0),
(19, 5, 0, 15, 0, '181', 13500, 20000, -80000, 'Ajuste', 0, -80000, '0', '0', -4, '0', '2022-02-21 18:06:00', '0', '0', 'Efectivo', 0),
(20, 5, 0, 15, 0, '182', 10000, 15000, -60000, 'Ajuste', 0, -60000, '0', '0', -4, '0', '2022-02-21 18:06:00', '0', '0', 'Efectivo', 0),
(21, 6, 0, 15, 0, '93', 23500, 35000, -1540000, 'Ajuste', 0, -1540000, '0', '0', -44, '0', '2022-02-22 11:59:00', '0', '0', 'Efectivo', 0),
(22, 7, 0, 15, 0, '290', 33500, 50000, -150000, 'Ajuste', 0, -150000, '0', '0', -3, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(23, 7, 0, 15, 0, '288', 27000, 40000, -160000, 'Ajuste', 0, -160000, '0', '0', -4, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(24, 7, 0, 15, 0, '286', 20000, 30000, -30000, 'Ajuste', 0, -30000, '0', '0', -1, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(25, 7, 0, 15, 0, '285', 13500, 20000, -20000, 'Ajuste', 0, -20000, '0', '0', -1, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(26, 7, 0, 15, 0, '284', 10000, 15000, -45000, 'Ajuste', 0, -45000, '0', '0', -3, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(27, 7, 0, 15, 0, '224', 50000, 75000, -75000, 'Ajuste', 0, -75000, '0', '0', -1, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(28, 7, 0, 15, 0, '228', 37000, 55000, -55000, 'Ajuste', 0, -55000, '0', '0', -1, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(29, 7, 0, 15, 0, '220', 27000, 40000, -40000, 'Ajuste', 0, -40000, '0', '0', -1, '0', '2022-02-22 14:46:00', '0', '0', 'Efectivo', 0),
(30, 8, 0, 15, 0, '365', 20000, 30000, -1200000, 'Ajuste', 0, -1200000, '0', '0', -40, '0', '2022-02-23 09:22:00', '0', '0', 'Efectivo', 0),
(31, 9, 0, 15, 0, '56', 40000, 60000, -960000, 'Ajuste', 0, -960000, '0', '0', -16, '0', '2022-02-23 15:38:00', '0', '0', 'Efectivo', 0),
(32, 9, 0, 15, 0, '57', 33500, 50000, -450000, 'Ajuste', 0, -450000, '0', '0', -9, '0', '2022-02-23 15:38:00', '0', '0', 'Efectivo', 0),
(33, 9, 0, 15, 0, '42', 20000, 30000, -720000, 'Ajuste', 0, -720000, '0', '0', -24, '0', '2022-02-23 15:38:00', '0', '0', 'Efectivo', 0),
(34, 9, 0, 15, 0, '401', 47000, 70000, -280000, 'Ajuste', 0, -280000, '0', '0', -4, '0', '2022-02-23 15:38:00', '0', '0', 'Efectivo', 0),
(35, 9, 0, 15, 0, '213', 113500, 170000, -340000, 'Ajuste', 0, -340000, '0', '0', -2, '0', '2022-02-23 15:38:00', '0', '0', 'Efectivo', 0),
(36, 9, 0, 15, 0, '227', 80000, 120000, -600000, 'Ajuste', 0, -600000, '0', '0', -5, '0', '2022-02-23 15:38:00', '0', '0', 'Efectivo', 0),
(37, 10, 0, 15, 0, '138', 23500, 35000, -35000, 'Ajuste', 0, -35000, '0', '0', -1, '0', '2022-03-04 08:07:00', '0', '0', 'Efectivo', 0),
(38, 11, 0, 15, 0, '192', 13500, 20000, -3380000, 'Ajuste', 0, -3380000, '0', '0', -169, '0', '2022-03-04 18:00:00', '0', '0', 'Efectivo', 0),
(39, 12, 0, 15, 0, '120', 27000, 40000, -40000, 'Ajuste', 0, -40000, '0', '0', -1, '0', '2022-03-05 17:21:00', '0', '0', 'Efectivo', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones_tmp`
--

CREATE TABLE `devoluciones_tmp` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_producto` varchar(25) NOT NULL,
  `precio_venta` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `descuento` varchar(80) NOT NULL,
  `fecha_venta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `devoluciones_tmp`
--

INSERT INTO `devoluciones_tmp` (`id`, `id_venta`, `id_vendedor`, `id_producto`, `precio_venta`, `cantidad`, `descuento`, `fecha_venta`) VALUES
(43, 1, 6, '17', 150000, -1, 'Ajuste', '2022-04-22 10:05:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `id_acreedor` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `categoria` text NOT NULL,
  `concepto` text NOT NULL,
  `comprobante` text DEFAULT NULL,
  `monto` int(11) NOT NULL,
  `forma_pago` varchar(50) DEFAULT NULL,
  `sucursal` int(11) NOT NULL,
  `anulado` int(11) DEFAULT NULL,
  `nro_cheque` text DEFAULT NULL,
  `plazo` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `egresos`
--

INSERT INTO `egresos` (`id`, `id_cliente`, `id_usuario`, `id_caja`, `id_compra`, `id_acreedor`, `fecha`, `categoria`, `concepto`, `comprobante`, `monto`, `forma_pago`, `sucursal`, `anulado`, `nro_cheque`, `plazo`) VALUES
(434, 186, 14, 1, 1, NULL, '2025-04-24 13:47:00', 'compra', 'compra al contado', 'Ticket N° 23454444', 45000000, 'Efectivo ', 0, NULL, '', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gift_card`
--

CREATE TABLE `gift_card` (
  `id` int(11) NOT NULL,
  `id_funcionario` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nro_tarjeta` varchar(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `anulado` int(11) DEFAULT NULL,
  `retirado` text DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `forma_pago` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gift_card`
--

INSERT INTO `gift_card` (`id`, `id_funcionario`, `id_cliente`, `nro_tarjeta`, `monto`, `anulado`, `retirado`, `fecha`, `forma_pago`) VALUES
(10, 22, 0, '0', 150000, NULL, 'RETIRADO', '2022-04-08', 'Efectivo'),
(13, 14, 0, '03', 350000, NULL, 'RETIRADO', '2022-04-22', 'Efectivo'),
(14, 14, 0, '04', 200000, NULL, 'RETIRADO', '2022-04-23', 'Efectivo'),
(15, 25, 0, '23', 100000, NULL, 'RETIRADO', '2022-04-23', 'Efectivo'),
(16, 14, 0, '05', 400000, NULL, 'RETIRADO', '2022-04-26', 'Efectivo'),
(17, 14, 0, '06', 245000, NULL, 'RETIRADO', '2022-04-30', 'Tarjeta'),
(18, 14, 0, '07', 200000, NULL, 'RETIRADO', '2022-04-30', 'Efectivo'),
(20, 25, 0, '9', 200000, NULL, 'RETIRADO', '2022-05-07', 'Efectivo'),
(21, 25, 0, '10', 300000, NULL, NULL, '2022-05-07', 'Efectivo'),
(22, 25, 0, '11', 100000, 1, NULL, '2022-05-07', 'Efectivo'),
(27, 14, 0, '12', 100000, NULL, NULL, '2022-05-09', 'Efectivo'),
(28, 14, 0, '16', 300000, NULL, 'RETIRADO', '2022-05-11', 'Efectivo'),
(29, 25, 0, '17', 300000, NULL, NULL, '2022-05-13', 'Efectivo'),
(30, 14, 0, '14', 100000, NULL, NULL, '2022-05-13', 'Transferencia'),
(31, 14, 0, '15', 100000, NULL, NULL, '2022-05-13', 'Transferencia'),
(32, 14, 0, '8', 100000, NULL, NULL, '2022-05-13', 'Transferencia'),
(33, 14, 0, '13', 100000, NULL, NULL, '2022-05-13', 'Transferencia'),
(34, 14, 0, '28', 200000, NULL, NULL, '2022-05-13', 'Efectivo'),
(35, 14, 0, '29', 100000, NULL, NULL, '2022-05-13', 'Efectivo'),
(36, 25, 0, '50', 100000, NULL, NULL, '2022-05-13', 'Efectivo'),
(37, 25, 0, '49', 200000, NULL, NULL, '2022-05-13', 'Efectivo'),
(38, 25, 0, '31', 200000, NULL, NULL, '2022-05-14', 'Efectivo'),
(39, 14, 0, '32', 250000, NULL, 'RETIRADO', '2022-05-14', 'Tarjeta'),
(40, 14, 0, '43', 400000, NULL, NULL, '2022-05-14', 'Efectivo'),
(41, 25, 0, '45', 200000, NULL, NULL, '2022-05-14', 'Tarjeta'),
(42, 14, 0, '47', 200000, NULL, NULL, '2022-05-14', 'Efectivo'),
(43, 14, 0, '46', 100000, NULL, NULL, '2022-05-14', 'Efectivo'),
(44, 14, 0, '30', 500000, NULL, 'RETIRADO', '2022-05-14', 'Tarjeta'),
(54, 14, 0, '44', 170000, NULL, NULL, '2022-05-16', 'Transferencia'),
(56, 14, 0, '33', 200000, NULL, 'RETIRADO', '2022-05-16', 'Efectivo'),
(57, 25, 0, '48', 100000, NULL, NULL, '2022-05-17', 'Tarjeta'),
(58, 25, 0, '42', 300000, NULL, NULL, '2022-05-19', 'Efectivo'),
(59, 14, 0, '41', 150000, NULL, NULL, '2022-05-19', 'Efectivo'),
(60, 14, 0, '40', 200000, NULL, NULL, '2022-05-19', 'Efectivo'),
(61, 25, 0, '39', 150000, NULL, 'RETIRADO', '2022-05-19', 'Tarjeta'),
(62, 14, 0, '38', 500000, NULL, NULL, '2022-05-23', 'Transferencia'),
(63, 14, 0, '37', 250000, NULL, 'RETIRADO', '2022-05-24', 'Efectivo'),
(64, 14, 0, '35', 200000, 1, NULL, '2022-05-25', 'Efectivo'),
(65, 14, 0, '36', 200000, NULL, NULL, '2022-05-25', 'Efectivo'),
(67, 14, 0, '34', 200000, NULL, NULL, '2022-05-25', 'Efectivo'),
(68, 25, 0, '051', 200000, NULL, NULL, '2022-05-26', 'Efectivo'),
(69, 14, 0, '052', 150000, NULL, NULL, '2022-05-26', 'Transferencia'),
(70, 14, 0, '053', 200000, NULL, NULL, '2022-05-30', 'Efectivo'),
(71, 25, 0, '054', 100000, NULL, NULL, '2022-06-01', 'Efectivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `imagen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_deuda` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `categoria` text NOT NULL,
  `concepto` text NOT NULL,
  `comprobante` text DEFAULT NULL,
  `monto` int(11) NOT NULL,
  `forma_pago` varchar(50) DEFAULT NULL,
  `sucursal` int(11) NOT NULL,
  `anulado` int(11) DEFAULT NULL,
  `id_gift` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `id_cliente`, `id_usuario`, `id_caja`, `id_venta`, `id_deuda`, `fecha`, `categoria`, `concepto`, `comprobante`, `monto`, `forma_pago`, `sucursal`, `anulado`, `id_gift`) VALUES
(16131, 1, 14, 2, 1, 43, '2025-05-12 15:50:00', 'Entrega', 'Venta a crédito a ', 'Ticket', 35000000, '', 0, NULL, NULL),
(16132, 1, 14, 3, 1, 43, '2025-05-12 16:03:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 2500000, 'Efectivo', 1, NULL, NULL),
(16133, 1, 14, 3, 1, 43, '2025-05-12 16:04:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 2500000, 'Efectivo', 1, NULL, NULL),
(16134, 1, 14, 3, 1, 43, '2025-05-12 16:12:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 4166667, 'Efectivo', 1, NULL, NULL),
(16135, 1, 14, 3, 1, 43, '2025-05-12 16:14:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 4166667, 'Efectivo', 1, NULL, NULL),
(16136, 1, 14, 3, 1, 43, '2025-05-12 16:44:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 4166667, 'Efectivo', 1, NULL, NULL),
(16137, 1, 14, 3, 1, 43, '2025-05-12 16:50:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 4166667, 'Efectivo', 1, NULL, NULL),
(16138, 1, 14, 3, 1, 43, '2025-05-12 16:59:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 4166667, 'Efectivo', 1, NULL, NULL),
(16139, 1, 14, 3, 1, 43, '2025-05-12 17:00:00', 'Cobro de deuda', 'Cobro de deuda a ', 'Recibo Nº ', 4166667, 'Efectivo', 1, NULL, NULL),
(16140, 186, 3, 2, 2, 44, '2025-05-12 21:19:00', 'Entrega', 'Venta a crédito a rosa echague ', 'Ticket', 35000000, '', 0, NULL, NULL),
(16141, 188, 3, 2, 3, 45, '2025-05-12 22:28:00', 'Entrega', 'Venta a crédito a victor Pitta', 'Ticket', 35000000, '', 0, NULL, NULL),
(16142, 186, 3, 2, 4, 46, '2025-05-12 23:37:00', 'Entrega', 'Venta a crédito a rosa echague ', 'Ticket', 120, '', 0, NULL, NULL),
(16143, 186, 3, 2, 4, 46, '2025-05-12 23:47:00', 'Cobro de deuda', 'Cobro de deuda a rosa echague ', 'Recibo Nº 23515', 10, 'Cheque', 1, NULL, NULL),
(16144, 186, 3, 2, 4, 46, '2025-05-12 23:48:00', 'Cobro de deuda', 'Cobro de deuda a rosa echague ', 'Recibo Nº 21651', 7, 'Tarjeta', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` varchar(50) DEFAULT NULL,
  `stock_actual` int(11) DEFAULT 0,
  `stock_real` int(11) DEFAULT 0,
  `faltante` int(11) DEFAULT 0,
  `anulado` int(11) NOT NULL DEFAULT 0,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `id_producto`, `id_usuario`, `stock_actual`, `stock_real`, `faltante`, `anulado`, `fecha`) VALUES
(1261, 502, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1262, 15, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1263, 16, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1264, 17, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1265, 18, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1266, 19, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1267, 20, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1268, 21, '22', 32, NULL, NULL, 0, '2022-04-21'),
(1269, 22, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1270, 23, '22', 20, NULL, NULL, 0, '2022-04-21'),
(1271, 24, '22', 187, NULL, NULL, 0, '2022-04-21'),
(1272, 25, '22', 28, NULL, NULL, 0, '2022-04-21'),
(1273, 26, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1274, 27, '22', 94, NULL, NULL, 0, '2022-04-21'),
(1275, 28, '22', 24, NULL, NULL, 0, '2022-04-21'),
(1276, 29, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1277, 30, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1278, 31, '22', 130, NULL, NULL, 0, '2022-04-21'),
(1279, 32, '22', 17, NULL, NULL, 0, '2022-04-21'),
(1280, 33, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1281, 38, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1282, 39, '22', 23, NULL, NULL, 0, '2022-04-21'),
(1283, 40, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1284, 41, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1285, 42, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1286, 43, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1287, 46, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1288, 47, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1289, 48, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1290, 49, '22', 121, NULL, NULL, 0, '2022-04-21'),
(1291, 56, '22', 44, NULL, NULL, 0, '2022-04-21'),
(1292, 57, '22', 166, NULL, NULL, 0, '2022-04-21'),
(1293, 58, '22', 51, NULL, NULL, 0, '2022-04-21'),
(1294, 60, '22', 22, NULL, NULL, 0, '2022-04-21'),
(1295, 61, '22', 37, NULL, NULL, 0, '2022-04-21'),
(1296, 62, '22', 18, NULL, NULL, 0, '2022-04-21'),
(1297, 64, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1298, 65, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1299, 66, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1300, 67, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1301, 68, '22', 106, NULL, NULL, 0, '2022-04-21'),
(1302, 69, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1303, 70, '22', 56, NULL, NULL, 0, '2022-04-21'),
(1304, 71, '22', 205, NULL, NULL, 0, '2022-04-21'),
(1305, 73, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1306, 76, '22', 46, NULL, NULL, 0, '2022-04-21'),
(1307, 81, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1308, 92, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1309, 93, '22', 328, NULL, NULL, 0, '2022-04-21'),
(1310, 94, '22', 12, NULL, NULL, 0, '2022-04-21'),
(1311, 95, '22', 36, NULL, NULL, 0, '2022-04-21'),
(1312, 98, '22', 29, NULL, NULL, 0, '2022-04-21'),
(1313, 99, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1314, 101, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1315, 102, '22', -1, NULL, NULL, 0, '2022-04-21'),
(1316, 103, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1317, 104, '22', 40, NULL, NULL, 0, '2022-04-21'),
(1318, 105, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1319, 106, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1320, 107, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1321, 108, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1322, 109, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1323, 110, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1324, 111, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1325, 112, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1326, 114, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1327, 115, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1328, 116, '22', 135, NULL, NULL, 0, '2022-04-21'),
(1329, 117, '22', 50, NULL, NULL, 0, '2022-04-21'),
(1330, 118, '22', 362, NULL, NULL, 0, '2022-04-21'),
(1331, 119, '22', 99, NULL, NULL, 0, '2022-04-21'),
(1332, 120, '22', 260, NULL, NULL, 0, '2022-04-21'),
(1333, 121, '22', 386, NULL, NULL, 0, '2022-04-21'),
(1334, 129, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1335, 130, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1336, 131, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1337, 132, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1338, 133, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1339, 134, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1340, 135, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1341, 136, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1342, 137, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1343, 138, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1344, 139, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1345, 140, '22', 436, NULL, NULL, 0, '2022-04-21'),
(1346, 145, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1347, 152, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1348, 153, '22', 247, NULL, NULL, 0, '2022-04-21'),
(1349, 154, '22', 240, NULL, NULL, 0, '2022-04-21'),
(1350, 155, '22', 145, NULL, NULL, 0, '2022-04-21'),
(1351, 156, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1352, 157, '22', -4, NULL, NULL, 0, '2022-04-21'),
(1353, 158, '22', 16, NULL, NULL, 0, '2022-04-21'),
(1354, 159, '22', 168, NULL, NULL, 0, '2022-04-21'),
(1355, 160, '22', 56, NULL, NULL, 0, '2022-04-21'),
(1356, 161, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1357, 162, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1358, 163, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1359, 164, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1360, 165, '22', 95, NULL, NULL, 0, '2022-04-21'),
(1361, 166, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1362, 167, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1363, 173, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1364, 174, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1365, 175, '22', 216, NULL, NULL, 0, '2022-04-21'),
(1366, 176, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1367, 177, '22', 152, NULL, NULL, 0, '2022-04-21'),
(1368, 178, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1369, 179, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1370, 180, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1371, 181, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1372, 182, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1373, 183, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1374, 184, '22', 65, NULL, NULL, 0, '2022-04-21'),
(1375, 185, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1376, 186, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1377, 187, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1378, 197, '22', 35, NULL, NULL, 0, '2022-04-21'),
(1379, 198, '22', 100, NULL, NULL, 0, '2022-04-21'),
(1380, 213, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1381, 214, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1382, 215, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1383, 216, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1384, 217, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1385, 218, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1386, 219, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1387, 220, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1388, 221, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1389, 222, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1390, 223, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1391, 224, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1392, 225, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1393, 226, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1394, 227, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1395, 228, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1396, 229, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1397, 230, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1398, 231, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1399, 232, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1400, 233, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1401, 234, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1402, 235, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1403, 236, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1404, 237, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1405, 238, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1406, 239, '22', 27, NULL, NULL, 0, '2022-04-21'),
(1407, 240, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1408, 241, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1409, 242, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1410, 243, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1411, 244, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1412, 246, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1413, 247, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1414, 248, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1415, 249, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1416, 250, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1417, 251, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1418, 252, '22', 43, NULL, NULL, 0, '2022-04-21'),
(1419, 257, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1420, 258, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1421, 259, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1422, 260, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1423, 261, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1424, 262, '22', 14, NULL, NULL, 0, '2022-04-21'),
(1425, 263, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1426, 264, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1427, 266, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1428, 267, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1429, 268, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1430, 269, '22', 19, NULL, NULL, 0, '2022-04-21'),
(1431, 270, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1432, 271, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1433, 272, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1434, 273, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1435, 274, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1436, 275, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1437, 276, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1438, 277, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1439, 278, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1440, 279, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1441, 280, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1442, 281, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1443, 282, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1444, 283, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1445, 284, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1446, 285, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1447, 286, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1448, 287, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1449, 288, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1450, 289, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1451, 290, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1452, 291, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1453, 292, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1454, 293, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1455, 294, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1456, 295, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1457, 296, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1458, 297, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1459, 361, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1460, 362, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1461, 365, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1462, 367, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1463, 372, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1464, 373, '22', 80, NULL, NULL, 0, '2022-04-21'),
(1465, 376, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1466, 377, '22', 110, NULL, NULL, 0, '2022-04-21'),
(1467, 379, '22', 81, NULL, NULL, 0, '2022-04-21'),
(1468, 381, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1469, 396, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1470, 400, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1471, 401, '22', 57, NULL, NULL, 0, '2022-04-21'),
(1472, 402, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1473, 405, '22', 21, NULL, NULL, 0, '2022-04-21'),
(1474, 408, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1475, 409, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1476, 411, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1477, 413, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1478, 414, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1479, 415, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1480, 417, '22', 55, NULL, NULL, 0, '2022-04-21'),
(1481, 418, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1482, 419, '22', 26, NULL, NULL, 0, '2022-04-21'),
(1483, 420, '22', 26, NULL, NULL, 0, '2022-04-21'),
(1484, 421, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1485, 422, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1486, 423, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1487, 426, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1488, 427, '22', 71, NULL, NULL, 0, '2022-04-21'),
(1489, 428, '22', 43, NULL, NULL, 0, '2022-04-21'),
(1490, 429, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1491, 430, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1492, 431, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1493, 433, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1494, 434, '22', -1, NULL, NULL, 0, '2022-04-21'),
(1495, 435, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1496, 438, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1497, 439, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1498, 440, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1499, 441, '22', 12, NULL, NULL, 0, '2022-04-21'),
(1500, 442, '22', 60, NULL, NULL, 0, '2022-04-21'),
(1501, 443, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1502, 444, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1503, 445, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1504, 446, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1505, 447, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1506, 448, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1507, 449, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1508, 451, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1509, 452, '22', 31, NULL, NULL, 0, '2022-04-21'),
(1510, 454, '22', -4, NULL, NULL, 0, '2022-04-21'),
(1511, 455, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1512, 456, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1513, 457, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1514, 458, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1515, 466, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1516, 467, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1517, 468, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1518, 469, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1519, 470, '22', 84, NULL, NULL, 0, '2022-04-21'),
(1520, 471, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1521, 472, '22', 16, NULL, NULL, 0, '2022-04-21'),
(1522, 473, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1523, 474, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1524, 475, '22', 22, NULL, NULL, 0, '2022-04-21'),
(1525, 476, '22', 29, NULL, NULL, 0, '2022-04-21'),
(1526, 477, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1527, 478, '22', 31, NULL, NULL, 0, '2022-04-21'),
(1528, 479, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1529, 480, '22', 21, NULL, NULL, 0, '2022-04-21'),
(1530, 481, '22', 17, NULL, NULL, 0, '2022-04-21'),
(1531, 482, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1532, 483, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1533, 484, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1534, 485, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1535, 487, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1536, 488, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1537, 490, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1538, 491, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1539, 492, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1540, 493, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1541, 494, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1542, 495, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1543, 496, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1544, 497, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1545, 499, '22', 271, NULL, NULL, 0, '2022-04-21'),
(1546, 500, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1547, 501, '22', 20, NULL, NULL, 0, '2022-04-21'),
(1548, 503, '22', 100, NULL, NULL, 0, '2022-04-21'),
(1549, 504, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1550, 505, '22', 20, NULL, NULL, 0, '2022-04-21'),
(1551, 506, '22', 27, NULL, NULL, 0, '2022-04-21'),
(1552, 507, '22', -2, NULL, NULL, 0, '2022-04-21'),
(1553, 508, '22', -1, NULL, NULL, 0, '2022-04-21'),
(1554, 509, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1555, 510, '22', 83, NULL, NULL, 0, '2022-04-21'),
(1556, 199, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1557, 200, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1558, 201, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1559, 202, '22', 226, NULL, NULL, 0, '2022-04-21'),
(1560, 203, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1561, 204, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1562, 205, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1563, 206, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1564, 207, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1565, 208, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1566, 209, '22', 54, NULL, NULL, 0, '2022-04-21'),
(1567, 210, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1568, 211, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1569, 212, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1570, 366, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1571, 370, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1572, 374, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1573, 380, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1574, 394, '22', 20, NULL, NULL, 0, '2022-04-21'),
(1575, 397, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1576, 398, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1577, 412, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1578, 424, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1579, 425, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1580, 436, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1581, 498, '22', 43, NULL, NULL, 0, '2022-04-21'),
(1582, 393, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1583, 188, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1584, 189, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1585, 190, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1586, 191, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1587, 192, '22', 91, NULL, NULL, 0, '2022-04-21'),
(1588, 193, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1589, 194, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1590, 195, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1591, 196, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1592, 364, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1593, 459, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1594, 460, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1595, 461, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1596, 462, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1597, 463, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1598, 464, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1599, 437, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1600, 502, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1601, 15, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1602, 16, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1603, 17, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1604, 18, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1605, 19, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1606, 20, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1607, 21, '22', 32, NULL, NULL, 0, '2022-04-21'),
(1608, 22, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1609, 23, '22', 20, NULL, NULL, 0, '2022-04-21'),
(1610, 24, '22', 186, NULL, NULL, 0, '2022-04-21'),
(1611, 25, '22', 28, NULL, NULL, 0, '2022-04-21'),
(1612, 26, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1613, 27, '22', 94, NULL, NULL, 0, '2022-04-21'),
(1614, 28, '22', 24, NULL, NULL, 0, '2022-04-21'),
(1615, 29, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1616, 30, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1617, 31, '22', 130, NULL, NULL, 0, '2022-04-21'),
(1618, 32, '22', 17, NULL, NULL, 0, '2022-04-21'),
(1619, 33, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1620, 38, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1621, 39, '22', 23, NULL, NULL, 0, '2022-04-21'),
(1622, 40, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1623, 41, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1624, 42, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1625, 43, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1626, 46, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1627, 47, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1628, 48, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1629, 49, '22', 121, NULL, NULL, 0, '2022-04-21'),
(1630, 56, '22', 44, NULL, NULL, 0, '2022-04-21'),
(1631, 57, '22', 166, NULL, NULL, 0, '2022-04-21'),
(1632, 58, '22', 51, NULL, NULL, 0, '2022-04-21'),
(1633, 60, '22', 22, NULL, NULL, 0, '2022-04-21'),
(1634, 61, '22', 37, NULL, NULL, 0, '2022-04-21'),
(1635, 62, '22', 18, NULL, NULL, 0, '2022-04-21'),
(1636, 64, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1637, 65, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1638, 66, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1639, 67, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1640, 68, '22', 106, NULL, NULL, 0, '2022-04-21'),
(1641, 69, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1642, 70, '22', 56, NULL, NULL, 0, '2022-04-21'),
(1643, 71, '22', 205, NULL, NULL, 0, '2022-04-21'),
(1644, 73, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1645, 76, '22', 46, NULL, NULL, 0, '2022-04-21'),
(1646, 81, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1647, 92, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1648, 93, '22', 328, NULL, NULL, 0, '2022-04-21'),
(1649, 94, '22', 12, NULL, NULL, 0, '2022-04-21'),
(1650, 95, '22', 36, NULL, NULL, 0, '2022-04-21'),
(1651, 98, '22', 29, NULL, NULL, 0, '2022-04-21'),
(1652, 99, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1653, 101, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1654, 102, '22', -1, NULL, NULL, 0, '2022-04-21'),
(1655, 103, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1656, 104, '22', 40, NULL, NULL, 0, '2022-04-21'),
(1657, 105, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1658, 106, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1659, 107, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1660, 108, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1661, 109, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1662, 110, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1663, 111, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1664, 112, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1665, 114, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1666, 115, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1667, 116, '22', 135, NULL, NULL, 0, '2022-04-21'),
(1668, 117, '22', 50, NULL, NULL, 0, '2022-04-21'),
(1669, 118, '22', 362, NULL, NULL, 0, '2022-04-21'),
(1670, 119, '22', 99, NULL, NULL, 0, '2022-04-21'),
(1671, 120, '22', 260, NULL, NULL, 0, '2022-04-21'),
(1672, 121, '22', 385, NULL, NULL, 0, '2022-04-21'),
(1673, 129, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1674, 130, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1675, 131, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1676, 132, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1677, 133, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1678, 134, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1679, 135, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1680, 136, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1681, 137, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1682, 138, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1683, 139, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1684, 140, '22', 436, NULL, NULL, 0, '2022-04-21'),
(1685, 145, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1686, 152, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1687, 153, '22', 247, NULL, NULL, 0, '2022-04-21'),
(1688, 154, '22', 240, NULL, NULL, 0, '2022-04-21'),
(1689, 155, '22', 145, NULL, NULL, 0, '2022-04-21'),
(1690, 156, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1691, 157, '22', -4, NULL, NULL, 0, '2022-04-21'),
(1692, 158, '22', 16, NULL, NULL, 0, '2022-04-21'),
(1693, 159, '22', 168, NULL, NULL, 0, '2022-04-21'),
(1694, 160, '22', 56, NULL, NULL, 0, '2022-04-21'),
(1695, 161, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1696, 162, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1697, 163, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1698, 164, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1699, 165, '22', 95, NULL, NULL, 0, '2022-04-21'),
(1700, 166, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1701, 167, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1702, 173, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1703, 174, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1704, 175, '22', 216, NULL, NULL, 0, '2022-04-21'),
(1705, 176, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1706, 177, '22', 152, NULL, NULL, 0, '2022-04-21'),
(1707, 178, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1708, 179, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1709, 180, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1710, 181, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1711, 182, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1712, 183, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1713, 184, '22', 65, NULL, NULL, 0, '2022-04-21'),
(1714, 185, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1715, 186, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1716, 187, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1717, 197, '22', 35, NULL, NULL, 0, '2022-04-21'),
(1718, 198, '22', 98, NULL, NULL, 0, '2022-04-21'),
(1719, 213, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1720, 214, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1721, 215, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1722, 216, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1723, 217, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1724, 218, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1725, 219, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1726, 220, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1727, 221, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1728, 222, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1729, 223, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1730, 224, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1731, 225, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1732, 226, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1733, 227, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1734, 228, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1735, 229, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1736, 230, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1737, 231, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1738, 232, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1739, 233, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1740, 234, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1741, 235, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1742, 236, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1743, 237, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1744, 238, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1745, 239, '22', 27, NULL, NULL, 0, '2022-04-21'),
(1746, 240, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1747, 241, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1748, 242, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1749, 243, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1750, 244, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1751, 246, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1752, 247, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1753, 248, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1754, 249, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1755, 250, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1756, 251, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1757, 252, '22', 43, NULL, NULL, 0, '2022-04-21'),
(1758, 257, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1759, 258, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1760, 259, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1761, 260, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1762, 261, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1763, 262, '22', 14, NULL, NULL, 0, '2022-04-21'),
(1764, 263, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1765, 264, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1766, 266, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1767, 267, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1768, 268, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1769, 269, '22', 19, NULL, NULL, 0, '2022-04-21'),
(1770, 270, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1771, 271, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1772, 272, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1773, 273, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1774, 274, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1775, 275, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1776, 276, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1777, 277, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1778, 278, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1779, 279, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1780, 280, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1781, 281, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1782, 282, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1783, 283, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1784, 284, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1785, 285, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1786, 286, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1787, 287, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1788, 288, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1789, 289, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1790, 290, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1791, 291, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1792, 292, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1793, 293, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1794, 294, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1795, 295, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1796, 296, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1797, 297, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1798, 361, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1799, 362, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1800, 365, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1801, 367, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1802, 372, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1803, 373, '22', 80, NULL, NULL, 0, '2022-04-21'),
(1804, 376, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1805, 377, '22', 110, NULL, NULL, 0, '2022-04-21'),
(1806, 379, '22', 81, NULL, NULL, 0, '2022-04-21'),
(1807, 381, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1808, 396, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1809, 400, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1810, 401, '22', 57, NULL, NULL, 0, '2022-04-21'),
(1811, 402, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1812, 405, '22', 21, NULL, NULL, 0, '2022-04-21'),
(1813, 408, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1814, 409, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1815, 411, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1816, 413, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1817, 414, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1818, 415, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1819, 417, '22', 55, NULL, NULL, 0, '2022-04-21'),
(1820, 418, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1821, 419, '22', 26, NULL, NULL, 0, '2022-04-21'),
(1822, 420, '22', 26, NULL, NULL, 0, '2022-04-21'),
(1823, 421, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1824, 422, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1825, 423, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1826, 426, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1827, 427, '22', 71, NULL, NULL, 0, '2022-04-21'),
(1828, 428, '22', 42, NULL, NULL, 0, '2022-04-21'),
(1829, 429, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1830, 430, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1831, 431, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1832, 433, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1833, 434, '22', -1, NULL, NULL, 0, '2022-04-21'),
(1834, 435, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1835, 438, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1836, 439, '22', 9, NULL, NULL, 0, '2022-04-21'),
(1837, 440, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1838, 441, '22', 12, NULL, NULL, 0, '2022-04-21'),
(1839, 442, '22', 60, NULL, NULL, 0, '2022-04-21'),
(1840, 443, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1841, 444, '22', 11, NULL, NULL, 0, '2022-04-21'),
(1842, 445, '22', -1, NULL, NULL, 0, '2022-04-21'),
(1843, 446, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1844, 447, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1845, 448, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1846, 449, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1847, 451, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1848, 452, '22', 31, NULL, NULL, 0, '2022-04-21'),
(1849, 454, '22', -4, NULL, NULL, 0, '2022-04-21'),
(1850, 455, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1851, 456, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1852, 457, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1853, 458, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1854, 466, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1855, 467, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1856, 468, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1857, 469, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1858, 470, '22', 84, NULL, NULL, 0, '2022-04-21'),
(1859, 471, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1860, 472, '22', 16, NULL, NULL, 0, '2022-04-21'),
(1861, 473, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1862, 474, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1863, 475, '22', 22, NULL, NULL, 0, '2022-04-21'),
(1864, 476, '22', 29, NULL, NULL, 0, '2022-04-21'),
(1865, 477, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1866, 478, '22', 31, NULL, NULL, 0, '2022-04-21'),
(1867, 479, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1868, 480, '22', 21, NULL, NULL, 0, '2022-04-21'),
(1869, 481, '22', 16, NULL, NULL, 0, '2022-04-21'),
(1870, 482, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1871, 483, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1872, 484, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1873, 485, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1874, 487, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1875, 488, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1876, 490, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1877, 491, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1878, 492, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1879, 493, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1880, 494, '22', 6, NULL, NULL, 0, '2022-04-21'),
(1881, 495, '22', 4, NULL, NULL, 0, '2022-04-21'),
(1882, 496, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1883, 497, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1884, 499, '22', 269, NULL, NULL, 0, '2022-04-21'),
(1885, 500, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1886, 501, '22', 20, NULL, NULL, 0, '2022-04-21'),
(1887, 503, '22', 100, NULL, NULL, 0, '2022-04-21'),
(1888, 504, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1889, 505, '22', 20, NULL, NULL, 0, '2022-04-21'),
(1890, 506, '22', 27, NULL, NULL, 0, '2022-04-21'),
(1891, 507, '22', -2, NULL, NULL, 0, '2022-04-21'),
(1892, 508, '22', -1, NULL, NULL, 0, '2022-04-21'),
(1893, 509, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1894, 510, '22', 83, NULL, NULL, 0, '2022-04-21'),
(1895, 199, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1896, 200, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1897, 201, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1898, 202, '22', 226, NULL, NULL, 0, '2022-04-21'),
(1899, 203, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1900, 204, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1901, 205, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1902, 206, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1903, 207, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1904, 208, '22', 10, NULL, NULL, 0, '2022-04-21'),
(1905, 209, '22', 54, NULL, NULL, 0, '2022-04-21'),
(1906, 210, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1907, 211, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1908, 212, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1909, 366, '22', 7, NULL, NULL, 0, '2022-04-21'),
(1910, 370, '22', 13, NULL, NULL, 0, '2022-04-21'),
(1911, 374, '22', 38, NULL, NULL, 0, '2022-04-21'),
(1912, 380, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1913, 394, '22', 19, NULL, NULL, 0, '2022-04-21'),
(1914, 397, '22', 8, NULL, NULL, 0, '2022-04-21'),
(1915, 398, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1916, 412, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1917, 424, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1918, 425, '22', 3, NULL, NULL, 0, '2022-04-21'),
(1919, 436, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1920, 498, '22', 43, NULL, NULL, 0, '2022-04-21'),
(1921, 393, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1922, 188, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1923, 189, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1924, 190, '22', 1, NULL, NULL, 0, '2022-04-21'),
(1925, 191, '22', 2, NULL, NULL, 0, '2022-04-21'),
(1926, 192, '22', 89, NULL, NULL, 0, '2022-04-21'),
(1927, 193, '22', 0, NULL, NULL, 0, '2022-04-21'),
(1928, 194, '22', 5, NULL, NULL, 0, '2022-04-21'),
(1929, 195, '22', 0, 0, 0, 0, '2022-04-21'),
(1930, 196, '22', 3, 3, 0, 0, '2022-04-21'),
(1931, 364, '22', 0, 0, 0, 0, '2022-04-21'),
(1932, 459, '22', 0, 0, 0, 0, '2022-04-21'),
(1933, 460, '22', 0, 0, 0, 0, '2022-04-21'),
(1934, 461, '22', 0, 0, 0, 0, '2022-04-21'),
(1935, 462, '22', 0, 0, 0, 0, '2022-04-21'),
(1936, 463, '22', 0, 0, 0, 0, '2022-04-21'),
(1937, 464, '22', 0, 0, 0, 0, '2022-04-21'),
(1938, 437, '22', 0, 0, 0, 0, '2022-04-21'),
(1939, 502, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1940, 15, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1941, 16, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1942, 17, '22', 2, NULL, NULL, 0, '2022-04-22'),
(1943, 18, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1944, 19, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1945, 20, '22', 2, NULL, NULL, 0, '2022-04-22'),
(1946, 21, '22', 32, NULL, NULL, 0, '2022-04-22'),
(1947, 22, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1948, 23, '22', 20, NULL, NULL, 0, '2022-04-22'),
(1949, 24, '22', 186, NULL, NULL, 0, '2022-04-22'),
(1950, 25, '22', 28, NULL, NULL, 0, '2022-04-22'),
(1951, 26, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1952, 27, '22', 94, NULL, NULL, 0, '2022-04-22'),
(1953, 28, '22', 24, NULL, NULL, 0, '2022-04-22'),
(1954, 29, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1955, 30, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1956, 31, '22', 130, NULL, NULL, 0, '2022-04-22'),
(1957, 32, '22', 17, NULL, NULL, 0, '2022-04-22'),
(1958, 33, '22', 9, NULL, NULL, 0, '2022-04-22'),
(1959, 38, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1960, 39, '22', 23, NULL, NULL, 0, '2022-04-22'),
(1961, 40, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1962, 41, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1963, 42, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1964, 43, '22', 2, NULL, NULL, 0, '2022-04-22'),
(1965, 46, '22', 8, NULL, NULL, 0, '2022-04-22'),
(1966, 47, '22', 10, NULL, NULL, 0, '2022-04-22'),
(1967, 48, '22', 5, NULL, NULL, 0, '2022-04-22'),
(1968, 49, '22', 121, NULL, NULL, 0, '2022-04-22'),
(1969, 56, '22', 44, NULL, NULL, 0, '2022-04-22'),
(1970, 57, '22', 165, NULL, NULL, 0, '2022-04-22'),
(1971, 58, '22', 51, NULL, NULL, 0, '2022-04-22'),
(1972, 60, '22', 22, NULL, NULL, 0, '2022-04-22'),
(1973, 61, '22', 37, NULL, NULL, 0, '2022-04-22'),
(1974, 62, '22', 17, NULL, NULL, 0, '2022-04-22'),
(1975, 64, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1976, 65, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1977, 66, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1978, 67, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1979, 68, '22', 106, NULL, NULL, 0, '2022-04-22'),
(1980, 69, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1981, 70, '22', 56, NULL, NULL, 0, '2022-04-22'),
(1982, 71, '22', 205, NULL, NULL, 0, '2022-04-22'),
(1983, 73, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1984, 76, '22', 46, NULL, NULL, 0, '2022-04-22'),
(1985, 81, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1986, 92, '22', 9, NULL, NULL, 0, '2022-04-22'),
(1987, 93, '22', 328, NULL, NULL, 0, '2022-04-22'),
(1988, 94, '22', 12, NULL, NULL, 0, '2022-04-22'),
(1989, 95, '22', 35, NULL, NULL, 0, '2022-04-22'),
(1990, 98, '22', 29, NULL, NULL, 0, '2022-04-22'),
(1991, 99, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1992, 101, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1993, 102, '22', -1, NULL, NULL, 0, '2022-04-22'),
(1994, 103, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1995, 104, '22', 40, NULL, NULL, 0, '2022-04-22'),
(1996, 105, '22', 3, NULL, NULL, 0, '2022-04-22'),
(1997, 106, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1998, 107, '22', 0, NULL, NULL, 0, '2022-04-22'),
(1999, 108, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2000, 109, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2001, 110, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2002, 111, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2003, 112, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2004, 114, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2005, 115, '22', 10, NULL, NULL, 0, '2022-04-22'),
(2006, 116, '22', 135, NULL, NULL, 0, '2022-04-22'),
(2007, 117, '22', 50, NULL, NULL, 0, '2022-04-22'),
(2008, 118, '22', 360, NULL, NULL, 0, '2022-04-22'),
(2009, 119, '22', 99, NULL, NULL, 0, '2022-04-22'),
(2010, 120, '22', 260, NULL, NULL, 0, '2022-04-22'),
(2011, 121, '22', 385, NULL, NULL, 0, '2022-04-22'),
(2012, 129, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2013, 130, '22', 8, NULL, NULL, 0, '2022-04-22'),
(2014, 131, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2015, 132, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2016, 133, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2017, 134, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2018, 135, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2019, 136, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2020, 137, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2021, 138, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2022, 139, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2023, 140, '22', 436, NULL, NULL, 0, '2022-04-22'),
(2024, 145, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2025, 152, '22', 11, NULL, NULL, 0, '2022-04-22'),
(2026, 153, '22', 247, NULL, NULL, 0, '2022-04-22'),
(2027, 154, '22', 240, NULL, NULL, 0, '2022-04-22'),
(2028, 155, '22', 145, NULL, NULL, 0, '2022-04-22'),
(2029, 156, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2030, 157, '22', -4, NULL, NULL, 0, '2022-04-22'),
(2031, 158, '22', 16, NULL, NULL, 0, '2022-04-22'),
(2032, 159, '22', 167, NULL, NULL, 0, '2022-04-22'),
(2033, 160, '22', 56, NULL, NULL, 0, '2022-04-22'),
(2034, 161, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2035, 162, '22', 13, NULL, NULL, 0, '2022-04-22'),
(2036, 163, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2037, 164, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2038, 165, '22', 95, NULL, NULL, 0, '2022-04-22'),
(2039, 166, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2040, 167, '22', 11, NULL, NULL, 0, '2022-04-22'),
(2041, 173, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2042, 174, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2043, 175, '22', 216, NULL, NULL, 0, '2022-04-22'),
(2044, 176, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2045, 177, '22', 152, NULL, NULL, 0, '2022-04-22'),
(2046, 178, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2047, 179, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2048, 180, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2049, 181, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2050, 182, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2051, 183, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2052, 184, '22', 65, NULL, NULL, 0, '2022-04-22'),
(2053, 185, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2054, 186, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2055, 187, '22', 13, NULL, NULL, 0, '2022-04-22'),
(2056, 197, '22', 35, NULL, NULL, 0, '2022-04-22'),
(2057, 198, '22', 98, NULL, NULL, 0, '2022-04-22'),
(2058, 213, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2059, 214, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2060, 215, '22', 7, NULL, NULL, 0, '2022-04-22'),
(2061, 216, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2062, 217, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2063, 218, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2064, 219, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2065, 220, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2066, 221, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2067, 222, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2068, 223, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2069, 224, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2070, 225, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2071, 226, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2072, 227, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2073, 228, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2074, 229, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2075, 230, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2076, 231, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2077, 232, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2078, 233, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2079, 234, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2080, 235, '22', 7, NULL, NULL, 0, '2022-04-22'),
(2081, 236, '22', 11, NULL, NULL, 0, '2022-04-22'),
(2082, 237, '22', 13, NULL, NULL, 0, '2022-04-22'),
(2083, 238, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2084, 239, '22', 27, NULL, NULL, 0, '2022-04-22'),
(2085, 240, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2086, 241, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2087, 242, '22', 10, NULL, NULL, 0, '2022-04-22'),
(2088, 243, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2089, 244, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2090, 246, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2091, 247, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2092, 248, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2093, 249, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2094, 250, '22', 11, NULL, NULL, 0, '2022-04-22'),
(2095, 251, '22', 11, NULL, NULL, 0, '2022-04-22'),
(2096, 252, '22', 43, NULL, NULL, 0, '2022-04-22'),
(2097, 257, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2098, 258, '22', 8, NULL, NULL, 0, '2022-04-22'),
(2099, 259, '22', 7, NULL, NULL, 0, '2022-04-22'),
(2100, 260, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2101, 261, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2102, 262, '22', 14, NULL, NULL, 0, '2022-04-22'),
(2103, 263, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2104, 264, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2105, 266, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2106, 267, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2107, 268, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2108, 269, '22', 19, NULL, NULL, 0, '2022-04-22'),
(2109, 270, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2110, 271, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2111, 272, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2112, 273, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2113, 274, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2114, 275, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2115, 276, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2116, 277, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2117, 278, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2118, 279, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2119, 280, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2120, 281, '22', 13, NULL, NULL, 0, '2022-04-22'),
(2121, 282, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2122, 283, '22', 8, NULL, NULL, 0, '2022-04-22'),
(2123, 284, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2124, 285, '22', 38, NULL, NULL, 0, '2022-04-22'),
(2125, 286, '22', 10, NULL, NULL, 0, '2022-04-22'),
(2126, 287, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2127, 288, '22', 38, NULL, NULL, 0, '2022-04-22'),
(2128, 289, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2129, 290, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2130, 291, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2131, 292, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2132, 293, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2133, 294, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2134, 295, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2135, 296, '22', 11, NULL, NULL, 0, '2022-04-22'),
(2136, 297, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2137, 361, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2138, 362, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2139, 365, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2140, 367, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2141, 372, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2142, 373, '22', 80, NULL, NULL, 0, '2022-04-22'),
(2143, 376, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2144, 377, '22', 110, NULL, NULL, 0, '2022-04-22'),
(2145, 379, '22', 81, NULL, NULL, 0, '2022-04-22'),
(2146, 381, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2147, 396, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2148, 400, '22', 13, NULL, NULL, 0, '2022-04-22'),
(2149, 401, '22', 57, NULL, NULL, 0, '2022-04-22'),
(2150, 402, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2151, 405, '22', 21, NULL, NULL, 0, '2022-04-22'),
(2152, 408, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2153, 409, '22', 7, NULL, NULL, 0, '2022-04-22'),
(2154, 411, '22', 7, NULL, NULL, 0, '2022-04-22'),
(2155, 413, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2156, 414, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2157, 415, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2158, 417, '22', 55, NULL, NULL, 0, '2022-04-22'),
(2159, 418, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2160, 419, '22', 26, NULL, NULL, 0, '2022-04-22'),
(2161, 420, '22', 26, NULL, NULL, 0, '2022-04-22'),
(2162, 421, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2163, 422, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2164, 423, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2165, 426, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2166, 427, '22', 71, NULL, NULL, 0, '2022-04-22'),
(2167, 428, '22', 42, NULL, NULL, 0, '2022-04-22'),
(2168, 429, '22', 13, NULL, NULL, 0, '2022-04-22'),
(2169, 430, '22', 10, NULL, NULL, 0, '2022-04-22'),
(2170, 431, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2171, 433, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2172, 434, '22', -1, NULL, NULL, 0, '2022-04-22'),
(2173, 435, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2174, 438, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2175, 439, '22', 9, NULL, NULL, 0, '2022-04-22'),
(2176, 440, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2177, 441, '22', 12, NULL, NULL, 0, '2022-04-22'),
(2178, 442, '22', 60, NULL, NULL, 0, '2022-04-22'),
(2179, 443, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2180, 444, '22', 11, NULL, NULL, 0, '2022-04-22'),
(2181, 445, '22', -1, NULL, NULL, 0, '2022-04-22'),
(2182, 446, '22', 7, NULL, NULL, 0, '2022-04-22'),
(2183, 447, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2184, 448, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2185, 449, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2186, 451, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2187, 452, '22', 31, NULL, NULL, 0, '2022-04-22'),
(2188, 454, '22', -4, NULL, NULL, 0, '2022-04-22'),
(2189, 455, '22', 10, NULL, NULL, 0, '2022-04-22'),
(2190, 456, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2191, 457, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2192, 458, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2193, 466, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2194, 467, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2195, 468, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2196, 469, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2197, 470, '22', 84, NULL, NULL, 0, '2022-04-22'),
(2198, 471, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2199, 472, '22', 16, NULL, NULL, 0, '2022-04-22'),
(2200, 473, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2201, 474, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2202, 475, '22', 22, NULL, NULL, 0, '2022-04-22'),
(2203, 476, '22', 29, NULL, NULL, 0, '2022-04-22'),
(2204, 477, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2205, 478, '22', 31, NULL, NULL, 0, '2022-04-22'),
(2206, 479, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2207, 480, '22', 21, NULL, NULL, 0, '2022-04-22'),
(2208, 481, '22', 16, NULL, NULL, 0, '2022-04-22'),
(2209, 482, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2210, 483, '22', 8, NULL, NULL, 0, '2022-04-22'),
(2211, 484, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2212, 485, '22', 8, NULL, NULL, 0, '2022-04-22'),
(2213, 487, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2214, 488, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2215, 490, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2216, 491, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2217, 492, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2218, 493, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2219, 494, '22', 6, NULL, NULL, 0, '2022-04-22'),
(2220, 495, '22', 4, NULL, NULL, 0, '2022-04-22'),
(2221, 496, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2222, 497, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2223, 499, '22', 268, NULL, NULL, 0, '2022-04-22'),
(2224, 500, '22', 38, NULL, NULL, 0, '2022-04-22'),
(2225, 501, '22', 20, NULL, NULL, 0, '2022-04-22'),
(2226, 503, '22', 99, NULL, NULL, 0, '2022-04-22'),
(2227, 504, '22', 8, NULL, NULL, 0, '2022-04-22'),
(2228, 505, '22', 20, NULL, NULL, 0, '2022-04-22'),
(2229, 506, '22', 27, NULL, NULL, 0, '2022-04-22'),
(2230, 507, '22', -2, NULL, NULL, 0, '2022-04-22'),
(2231, 508, '22', -1, NULL, NULL, 0, '2022-04-22'),
(2232, 509, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2233, 510, '22', 83, NULL, NULL, 0, '2022-04-22'),
(2234, 199, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2235, 200, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2236, 201, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2237, 202, '22', 226, NULL, NULL, 0, '2022-04-22'),
(2238, 203, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2239, 204, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2240, 205, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2241, 206, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2242, 207, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2243, 208, '22', 10, NULL, NULL, 0, '2022-04-22'),
(2244, 209, '22', 54, NULL, NULL, 0, '2022-04-22'),
(2245, 210, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2246, 211, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2247, 212, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2248, 366, '22', 7, NULL, NULL, 0, '2022-04-22'),
(2249, 370, '22', 13, NULL, NULL, 0, '2022-04-22'),
(2250, 374, '22', 38, NULL, NULL, 0, '2022-04-22'),
(2251, 380, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2252, 394, '22', 19, NULL, NULL, 0, '2022-04-22'),
(2253, 397, '22', 8, NULL, NULL, 0, '2022-04-22'),
(2254, 398, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2255, 412, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2256, 424, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2257, 425, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2258, 436, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2259, 498, '22', 43, NULL, NULL, 0, '2022-04-22'),
(2260, 393, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2261, 188, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2262, 189, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2263, 190, '22', 1, NULL, NULL, 0, '2022-04-22'),
(2264, 191, '22', 2, NULL, NULL, 0, '2022-04-22'),
(2265, 192, '22', 89, NULL, NULL, 0, '2022-04-22'),
(2266, 193, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2267, 194, '22', 5, NULL, NULL, 0, '2022-04-22'),
(2268, 195, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2269, 196, '22', 3, NULL, NULL, 0, '2022-04-22'),
(2270, 364, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2271, 459, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2272, 460, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2273, 461, '22', 0, NULL, NULL, 0, '2022-04-22'),
(2274, 462, '22', 0, 0, 0, 0, '2022-04-22');
INSERT INTO `inventario` (`id`, `id_producto`, `id_usuario`, `stock_actual`, `stock_real`, `faltante`, `anulado`, `fecha`) VALUES
(2275, 463, '22', 0, 0, 0, 0, '2022-04-22'),
(2276, 464, '22', 0, 1, -1, 0, '2022-04-22'),
(2277, 437, '22', 0, 0, 0, 0, '2022-04-22'),
(2617, 502, '22', -3, NULL, NULL, 0, '2022-05-17'),
(2618, 15, '22', 11, NULL, NULL, 0, '2022-05-17'),
(2619, 16, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2620, 17, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2621, 18, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2622, 19, '22', 23, NULL, NULL, 0, '2022-05-17'),
(2623, 20, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2624, 21, '22', 28, NULL, NULL, 0, '2022-05-17'),
(2625, 22, '22', 51, NULL, NULL, 0, '2022-05-17'),
(2626, 23, '22', 20, NULL, NULL, 0, '2022-05-17'),
(2627, 24, '22', 160, NULL, NULL, 0, '2022-05-17'),
(2628, 25, '22', 32, NULL, NULL, 0, '2022-05-17'),
(2629, 26, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2630, 27, '22', 61, NULL, NULL, 0, '2022-05-17'),
(2631, 28, '22', 15, NULL, NULL, 0, '2022-05-17'),
(2632, 29, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2633, 30, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2634, 31, '22', 124, NULL, NULL, 0, '2022-05-17'),
(2635, 32, '22', 17, NULL, NULL, 0, '2022-05-17'),
(2636, 33, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2637, 38, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2638, 39, '22', 11, NULL, NULL, 0, '2022-05-17'),
(2639, 40, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2640, 41, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2641, 42, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2642, 43, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2643, 46, '22', -5, NULL, NULL, 0, '2022-05-17'),
(2644, 47, '22', 14, NULL, NULL, 0, '2022-05-17'),
(2645, 48, '22', 25, NULL, NULL, 0, '2022-05-17'),
(2646, 49, '22', 116, NULL, NULL, 0, '2022-05-17'),
(2647, 56, '22', 32, NULL, NULL, 0, '2022-05-17'),
(2648, 57, '22', 172, NULL, NULL, 0, '2022-05-17'),
(2649, 58, '22', 122, NULL, NULL, 0, '2022-05-17'),
(2650, 60, '22', 16, NULL, NULL, 0, '2022-05-17'),
(2651, 61, '22', 51, NULL, NULL, 0, '2022-05-17'),
(2652, 62, '22', 84, NULL, NULL, 0, '2022-05-17'),
(2653, 64, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2654, 65, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2655, 66, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2656, 67, '22', 25, NULL, NULL, 0, '2022-05-17'),
(2657, 68, '22', 74, NULL, NULL, 0, '2022-05-17'),
(2658, 69, '22', 24, NULL, NULL, 0, '2022-05-17'),
(2659, 70, '22', 87, NULL, NULL, 0, '2022-05-17'),
(2660, 71, '22', 189, NULL, NULL, 0, '2022-05-17'),
(2661, 73, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2662, 76, '22', 42, NULL, NULL, 0, '2022-05-17'),
(2663, 81, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2664, 92, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2665, 93, '22', 318, NULL, NULL, 0, '2022-05-17'),
(2666, 94, '22', 11, NULL, NULL, 0, '2022-05-17'),
(2667, 95, '22', 15, NULL, NULL, 0, '2022-05-17'),
(2668, 98, '22', 15, NULL, NULL, 0, '2022-05-17'),
(2669, 99, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2670, 101, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2671, 102, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2672, 103, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2673, 104, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2674, 105, '22', 33, NULL, NULL, 0, '2022-05-17'),
(2675, 106, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2676, 107, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2677, 108, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2678, 109, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2679, 110, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2680, 111, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2681, 112, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2682, 114, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2683, 115, '22', 99, NULL, NULL, 0, '2022-05-17'),
(2684, 116, '22', 219, NULL, NULL, 0, '2022-05-17'),
(2685, 117, '22', 49, NULL, NULL, 0, '2022-05-17'),
(2686, 118, '22', 320, NULL, NULL, 0, '2022-05-17'),
(2687, 119, '22', 167, NULL, NULL, 0, '2022-05-17'),
(2688, 120, '22', 195, NULL, NULL, 0, '2022-05-17'),
(2689, 121, '22', 422, NULL, NULL, 0, '2022-05-17'),
(2690, 129, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2691, 130, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2692, 131, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2693, 132, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2694, 133, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2695, 134, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2696, 135, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2697, 136, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2698, 137, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2699, 138, '22', 14, NULL, NULL, 0, '2022-05-17'),
(2700, 139, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2701, 140, '22', 841, NULL, NULL, 0, '2022-05-17'),
(2702, 145, '22', 39, NULL, NULL, 0, '2022-05-17'),
(2703, 152, '22', 134, NULL, NULL, 0, '2022-05-17'),
(2704, 153, '22', 216, NULL, NULL, 0, '2022-05-17'),
(2705, 154, '22', 187, NULL, NULL, 0, '2022-05-17'),
(2706, 155, '22', 110, NULL, NULL, 0, '2022-05-17'),
(2707, 156, '22', 16, NULL, NULL, 0, '2022-05-17'),
(2708, 157, '22', 14, NULL, NULL, 0, '2022-05-17'),
(2709, 158, '22', 12, NULL, NULL, 0, '2022-05-17'),
(2710, 159, '22', 102, NULL, NULL, 0, '2022-05-17'),
(2711, 160, '22', 72, NULL, NULL, 0, '2022-05-17'),
(2712, 161, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2713, 162, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2714, 163, '22', -16, NULL, NULL, 0, '2022-05-17'),
(2715, 164, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2716, 165, '22', 335, NULL, NULL, 0, '2022-05-17'),
(2717, 166, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2718, 167, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2719, 173, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2720, 174, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2721, 175, '22', 153, NULL, NULL, 0, '2022-05-17'),
(2722, 176, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2723, 177, '22', 143, NULL, NULL, 0, '2022-05-17'),
(2724, 178, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2725, 179, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2726, 180, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2727, 181, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2728, 182, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2729, 183, '22', 36, NULL, NULL, 0, '2022-05-17'),
(2730, 184, '22', 51, NULL, NULL, 0, '2022-05-17'),
(2731, 185, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2732, 186, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2733, 187, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2734, 197, '22', 263, NULL, NULL, 0, '2022-05-17'),
(2735, 198, '22', 170, NULL, NULL, 0, '2022-05-17'),
(2736, 213, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2737, 214, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2738, 215, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2739, 216, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2740, 217, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2741, 218, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2742, 219, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2743, 220, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2744, 221, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2745, 222, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2746, 223, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2747, 224, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2748, 225, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2749, 226, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2750, 227, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2751, 228, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2752, 229, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2753, 230, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2754, 231, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2755, 232, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2756, 233, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2757, 234, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2758, 235, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2759, 236, '22', 10, NULL, NULL, 0, '2022-05-17'),
(2760, 237, '22', 12, NULL, NULL, 0, '2022-05-17'),
(2761, 238, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2762, 239, '22', 24, NULL, NULL, 0, '2022-05-17'),
(2763, 240, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2764, 241, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2765, 242, '22', 10, NULL, NULL, 0, '2022-05-17'),
(2766, 243, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2767, 244, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2768, 246, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2769, 247, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2770, 248, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2771, 249, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2772, 250, '22', 11, NULL, NULL, 0, '2022-05-17'),
(2773, 251, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2774, 252, '22', 42, NULL, NULL, 0, '2022-05-17'),
(2775, 257, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2776, 258, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2777, 259, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2778, 260, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2779, 261, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2780, 262, '22', 13, NULL, NULL, 0, '2022-05-17'),
(2781, 263, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2782, 264, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2783, 266, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2784, 267, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2785, 268, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2786, 269, '22', 17, NULL, NULL, 0, '2022-05-17'),
(2787, 270, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2788, 271, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2789, 272, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2790, 273, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2791, 274, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2792, 275, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2793, 276, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2794, 277, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2795, 278, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2796, 279, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2797, 280, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2798, 281, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2799, 282, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2800, 283, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2801, 284, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2802, 285, '22', 29, NULL, NULL, 0, '2022-05-17'),
(2803, 286, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2804, 287, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2805, 288, '22', 29, NULL, NULL, 0, '2022-05-17'),
(2806, 289, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2807, 290, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2808, 291, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2809, 292, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2810, 293, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2811, 294, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2812, 295, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2813, 296, '22', 10, NULL, NULL, 0, '2022-05-17'),
(2814, 297, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2815, 361, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2816, 362, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2817, 365, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2818, 367, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2819, 372, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2820, 373, '22', 34, NULL, NULL, 0, '2022-05-17'),
(2821, 376, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2822, 377, '22', 100, NULL, NULL, 0, '2022-05-17'),
(2823, 379, '22', 76, NULL, NULL, 0, '2022-05-17'),
(2824, 381, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2825, 396, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2826, 400, '22', 14, NULL, NULL, 0, '2022-05-17'),
(2827, 401, '22', 44, NULL, NULL, 0, '2022-05-17'),
(2828, 402, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2829, 405, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2830, 408, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2831, 409, '22', 140, NULL, NULL, 0, '2022-05-17'),
(2832, 411, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2833, 413, '22', 27, NULL, NULL, 0, '2022-05-17'),
(2834, 414, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2835, 415, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2836, 417, '22', 50, NULL, NULL, 0, '2022-05-17'),
(2837, 418, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2838, 419, '22', 25, NULL, NULL, 0, '2022-05-17'),
(2839, 420, '22', 23, NULL, NULL, 0, '2022-05-17'),
(2840, 421, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2841, 422, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2842, 423, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2843, 426, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2844, 427, '22', 70, NULL, NULL, 0, '2022-05-17'),
(2845, 428, '22', 103, NULL, NULL, 0, '2022-05-17'),
(2846, 429, '22', 10, NULL, NULL, 0, '2022-05-17'),
(2847, 430, '22', 10, NULL, NULL, 0, '2022-05-17'),
(2848, 431, '22', 12, NULL, NULL, 0, '2022-05-17'),
(2849, 433, '22', 25, NULL, NULL, 0, '2022-05-17'),
(2850, 434, '22', -3, NULL, NULL, 0, '2022-05-17'),
(2851, 435, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2852, 438, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2853, 439, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2854, 440, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2855, 441, '22', 19, NULL, NULL, 0, '2022-05-17'),
(2856, 442, '22', 63, NULL, NULL, 0, '2022-05-17'),
(2857, 443, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2858, 444, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2859, 445, '22', 13, NULL, NULL, 0, '2022-05-17'),
(2860, 446, '22', 24, NULL, NULL, 0, '2022-05-17'),
(2861, 447, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2862, 448, '22', -4, NULL, NULL, 0, '2022-05-17'),
(2863, 449, '22', -2, NULL, NULL, 0, '2022-05-17'),
(2864, 451, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2865, 452, '22', 23, NULL, NULL, 0, '2022-05-17'),
(2866, 454, '22', -14, NULL, NULL, 0, '2022-05-17'),
(2867, 455, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2868, 456, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2869, 457, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2870, 458, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2871, 466, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2872, 467, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2873, 468, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2874, 469, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2875, 470, '22', 286, NULL, NULL, 0, '2022-05-17'),
(2876, 471, '22', 18, NULL, NULL, 0, '2022-05-17'),
(2877, 472, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2878, 473, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2879, 474, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2880, 475, '22', 23, NULL, NULL, 0, '2022-05-17'),
(2881, 476, '22', 18, NULL, NULL, 0, '2022-05-17'),
(2882, 477, '22', 42, NULL, NULL, 0, '2022-05-17'),
(2883, 478, '22', -3, NULL, NULL, 0, '2022-05-17'),
(2884, 479, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2885, 480, '22', 14, NULL, NULL, 0, '2022-05-17'),
(2886, 481, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2887, 482, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2888, 483, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2889, 484, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2890, 485, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2891, 487, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2892, 488, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2893, 490, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2894, 491, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2895, 492, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2896, 493, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2897, 494, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2898, 495, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2899, 496, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2900, 497, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2901, 499, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2902, 500, '22', 85, NULL, NULL, 0, '2022-05-17'),
(2903, 501, '22', 11, NULL, NULL, 0, '2022-05-17'),
(2904, 503, '22', 81, NULL, NULL, 0, '2022-05-17'),
(2905, 504, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2906, 505, '22', 78, NULL, NULL, 0, '2022-05-17'),
(2907, 506, '22', -12, NULL, NULL, 0, '2022-05-17'),
(2908, 507, '22', -100, NULL, NULL, 0, '2022-05-17'),
(2909, 508, '22', -5, NULL, NULL, 0, '2022-05-17'),
(2910, 509, '22', -12, NULL, NULL, 0, '2022-05-17'),
(2911, 510, '22', 92, NULL, NULL, 0, '2022-05-17'),
(2912, 511, '22', 71, NULL, NULL, 0, '2022-05-17'),
(2913, 512, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2914, 513, '22', -14, NULL, NULL, 0, '2022-05-17'),
(2915, 514, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2916, 515, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2917, 516, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2918, 517, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2919, 518, '22', 30, NULL, NULL, 0, '2022-05-17'),
(2920, 519, '22', 81, NULL, NULL, 0, '2022-05-17'),
(2921, 520, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2922, 521, '22', -5, NULL, NULL, 0, '2022-05-17'),
(2923, 522, '22', -1, NULL, NULL, 0, '2022-05-17'),
(2924, 526, '22', 21, NULL, NULL, 0, '2022-05-17'),
(2925, 527, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2926, 528, '22', -6, NULL, NULL, 0, '2022-05-17'),
(2927, 529, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2928, 530, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2929, 531, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2930, 532, '22', -24, NULL, NULL, 0, '2022-05-17'),
(2931, 533, '22', 30, NULL, NULL, 0, '2022-05-17'),
(2932, 534, '22', 63, NULL, NULL, 0, '2022-05-17'),
(2933, 535, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2934, 536, '22', 52, NULL, NULL, 0, '2022-05-17'),
(2935, 537, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2936, 538, '22', 22, NULL, NULL, 0, '2022-05-17'),
(2937, 539, '22', 12, NULL, NULL, 0, '2022-05-17'),
(2938, 540, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2939, 541, '22', 11, NULL, NULL, 0, '2022-05-17'),
(2940, 542, '22', 10, NULL, NULL, 0, '2022-05-17'),
(2941, 543, '22', 65, NULL, NULL, 0, '2022-05-17'),
(2942, 544, '22', -4, NULL, NULL, 0, '2022-05-17'),
(2943, 545, '22', 35, NULL, NULL, 0, '2022-05-17'),
(2944, 546, '22', 17, NULL, NULL, 0, '2022-05-17'),
(2945, 547, '22', 13, NULL, NULL, 0, '2022-05-17'),
(2946, 548, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2947, 549, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2948, 550, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2949, 551, '22', 9, NULL, NULL, 0, '2022-05-17'),
(2950, 552, '22', 7, NULL, NULL, 0, '2022-05-17'),
(2951, 553, '22', 6, NULL, NULL, 0, '2022-05-17'),
(2952, 199, '22', -4, NULL, NULL, 0, '2022-05-17'),
(2953, 200, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2954, 201, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2955, 202, '22', 191, NULL, NULL, 0, '2022-05-17'),
(2956, 203, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2957, 204, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2958, 205, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2959, 206, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2960, 207, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2961, 208, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2962, 209, '22', 45, NULL, NULL, 0, '2022-05-17'),
(2963, 210, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2964, 211, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2965, 212, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2966, 366, '22', 50, NULL, NULL, 0, '2022-05-17'),
(2967, 370, '22', 13, NULL, NULL, 0, '2022-05-17'),
(2968, 374, '22', 86, NULL, NULL, 0, '2022-05-17'),
(2969, 380, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2970, 394, '22', -3, NULL, NULL, 0, '2022-05-17'),
(2971, 397, '22', 8, NULL, NULL, 0, '2022-05-17'),
(2972, 398, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2973, 412, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2974, 424, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2975, 425, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2976, 436, '22', 88, NULL, NULL, 0, '2022-05-17'),
(2977, 498, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2978, 393, '22', 5, NULL, NULL, 0, '2022-05-17'),
(2979, 188, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2980, 189, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2981, 190, '22', 1, NULL, NULL, 0, '2022-05-17'),
(2982, 191, '22', 2, NULL, NULL, 0, '2022-05-17'),
(2983, 192, '22', 250, NULL, NULL, 0, '2022-05-17'),
(2984, 193, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2985, 194, '22', 4, NULL, NULL, 0, '2022-05-17'),
(2986, 195, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2987, 196, '22', 3, NULL, NULL, 0, '2022-05-17'),
(2988, 364, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2989, 523, '22', 163, NULL, NULL, 0, '2022-05-17'),
(2990, 524, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2991, 525, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2992, 459, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2993, 460, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2994, 461, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2995, 462, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2996, 463, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2997, 464, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2998, 437, '22', 0, NULL, NULL, 0, '2022-05-17'),
(2999, 502, '22', -3, NULL, NULL, 0, '2022-05-17'),
(3000, 15, '22', 11, NULL, NULL, 0, '2022-05-17'),
(3001, 16, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3002, 17, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3003, 18, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3004, 19, '22', 23, NULL, NULL, 0, '2022-05-17'),
(3005, 20, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3006, 21, '22', 28, NULL, NULL, 0, '2022-05-17'),
(3007, 22, '22', 51, NULL, NULL, 0, '2022-05-17'),
(3008, 23, '22', 19, NULL, NULL, 0, '2022-05-17'),
(3009, 24, '22', 160, NULL, NULL, 0, '2022-05-17'),
(3010, 25, '22', 32, NULL, NULL, 0, '2022-05-17'),
(3011, 26, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3012, 27, '22', 61, NULL, NULL, 0, '2022-05-17'),
(3013, 28, '22', 15, NULL, NULL, 0, '2022-05-17'),
(3014, 29, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3015, 30, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3016, 31, '22', 121, NULL, NULL, 0, '2022-05-17'),
(3017, 32, '22', 16, NULL, NULL, 0, '2022-05-17'),
(3018, 33, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3019, 38, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3020, 39, '22', 10, NULL, NULL, 0, '2022-05-17'),
(3021, 40, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3022, 41, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3023, 42, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3024, 43, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3025, 46, '22', -5, NULL, NULL, 0, '2022-05-17'),
(3026, 47, '22', 13, NULL, NULL, 0, '2022-05-17'),
(3027, 48, '22', 25, NULL, NULL, 0, '2022-05-17'),
(3028, 49, '22', 116, NULL, NULL, 0, '2022-05-17'),
(3029, 56, '22', 32, NULL, NULL, 0, '2022-05-17'),
(3030, 57, '22', 172, NULL, NULL, 0, '2022-05-17'),
(3031, 58, '22', 121, NULL, NULL, 0, '2022-05-17'),
(3032, 60, '22', 16, NULL, NULL, 0, '2022-05-17'),
(3033, 61, '22', 50, NULL, NULL, 0, '2022-05-17'),
(3034, 62, '22', 82, NULL, NULL, 0, '2022-05-17'),
(3035, 64, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3036, 65, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3037, 66, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3038, 67, '22', 25, NULL, NULL, 0, '2022-05-17'),
(3039, 68, '22', 74, NULL, NULL, 0, '2022-05-17'),
(3040, 69, '22', 24, NULL, NULL, 0, '2022-05-17'),
(3041, 70, '22', 87, NULL, NULL, 0, '2022-05-17'),
(3042, 71, '22', 189, NULL, NULL, 0, '2022-05-17'),
(3043, 73, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3044, 76, '22', 42, NULL, NULL, 0, '2022-05-17'),
(3045, 81, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3046, 92, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3047, 93, '22', 314, NULL, NULL, 0, '2022-05-17'),
(3048, 94, '22', 11, NULL, NULL, 0, '2022-05-17'),
(3049, 95, '22', 15, NULL, NULL, 0, '2022-05-17'),
(3050, 98, '22', 15, NULL, NULL, 0, '2022-05-17'),
(3051, 99, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3052, 101, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3053, 102, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3054, 103, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3055, 104, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3056, 105, '22', 33, NULL, NULL, 0, '2022-05-17'),
(3057, 106, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3058, 107, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3059, 108, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3060, 109, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3061, 110, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3062, 111, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3063, 112, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3064, 114, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3065, 115, '22', 99, NULL, NULL, 0, '2022-05-17'),
(3066, 116, '22', 218, NULL, NULL, 0, '2022-05-17'),
(3067, 117, '22', 48, NULL, NULL, 0, '2022-05-17'),
(3068, 118, '22', 315, NULL, NULL, 0, '2022-05-17'),
(3069, 119, '22', 167, NULL, NULL, 0, '2022-05-17'),
(3070, 120, '22', 195, NULL, NULL, 0, '2022-05-17'),
(3071, 121, '22', 421, NULL, NULL, 0, '2022-05-17'),
(3072, 129, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3073, 130, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3074, 131, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3075, 132, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3076, 133, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3077, 134, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3078, 135, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3079, 136, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3080, 137, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3081, 138, '22', 14, NULL, NULL, 0, '2022-05-17'),
(3082, 139, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3083, 140, '22', 837, NULL, NULL, 0, '2022-05-17'),
(3084, 145, '22', 39, NULL, NULL, 0, '2022-05-17'),
(3085, 152, '22', 131, NULL, NULL, 0, '2022-05-17'),
(3086, 153, '22', 215, NULL, NULL, 0, '2022-05-17'),
(3087, 154, '22', 187, NULL, NULL, 0, '2022-05-17'),
(3088, 155, '22', 110, NULL, NULL, 0, '2022-05-17'),
(3089, 156, '22', 16, NULL, NULL, 0, '2022-05-17'),
(3090, 157, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3091, 158, '22', 12, NULL, NULL, 0, '2022-05-17'),
(3092, 159, '22', 101, NULL, NULL, 0, '2022-05-17'),
(3093, 160, '22', 72, NULL, NULL, 0, '2022-05-17'),
(3094, 161, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3095, 162, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3096, 163, '22', -16, NULL, NULL, 0, '2022-05-17'),
(3097, 164, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3098, 165, '22', 334, NULL, NULL, 0, '2022-05-17'),
(3099, 166, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3100, 167, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3101, 173, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3102, 174, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3103, 175, '22', 153, NULL, NULL, 0, '2022-05-17'),
(3104, 176, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3105, 177, '22', 143, NULL, NULL, 0, '2022-05-17'),
(3106, 178, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3107, 179, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3108, 180, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3109, 181, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3110, 182, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3111, 183, '22', 36, NULL, NULL, 0, '2022-05-17'),
(3112, 184, '22', 51, NULL, NULL, 0, '2022-05-17'),
(3113, 185, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3114, 186, '22', 4, NULL, NULL, 0, '2022-05-17'),
(3115, 187, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3116, 197, '22', 260, NULL, NULL, 0, '2022-05-17'),
(3117, 198, '22', 170, NULL, NULL, 0, '2022-05-17'),
(3118, 213, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3119, 214, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3120, 215, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3121, 216, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3122, 217, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3123, 218, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3124, 219, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3125, 220, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3126, 221, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3127, 222, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3128, 223, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3129, 224, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3130, 225, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3131, 226, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3132, 227, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3133, 228, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3134, 229, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3135, 230, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3136, 231, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3137, 232, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3138, 233, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3139, 234, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3140, 235, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3141, 236, '22', 10, NULL, NULL, 0, '2022-05-17'),
(3142, 237, '22', 12, NULL, NULL, 0, '2022-05-17'),
(3143, 238, '22', 4, NULL, NULL, 0, '2022-05-17'),
(3144, 239, '22', 24, NULL, NULL, 0, '2022-05-17'),
(3145, 240, '22', 4, NULL, NULL, 0, '2022-05-17'),
(3146, 241, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3147, 242, '22', 10, NULL, NULL, 0, '2022-05-17'),
(3148, 243, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3149, 244, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3150, 246, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3151, 247, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3152, 248, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3153, 249, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3154, 250, '22', 11, NULL, NULL, 0, '2022-05-17'),
(3155, 251, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3156, 252, '22', 42, NULL, NULL, 0, '2022-05-17'),
(3157, 257, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3158, 258, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3159, 259, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3160, 260, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3161, 261, '22', 4, NULL, NULL, 0, '2022-05-17'),
(3162, 262, '22', 13, NULL, NULL, 0, '2022-05-17'),
(3163, 263, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3164, 264, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3165, 266, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3166, 267, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3167, 268, '22', 4, NULL, NULL, 0, '2022-05-17'),
(3168, 269, '22', 17, NULL, NULL, 0, '2022-05-17'),
(3169, 270, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3170, 271, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3171, 272, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3172, 273, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3173, 274, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3174, 275, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3175, 276, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3176, 277, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3177, 278, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3178, 279, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3179, 280, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3180, 281, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3181, 282, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3182, 283, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3183, 284, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3184, 285, '22', 29, NULL, NULL, 0, '2022-05-17'),
(3185, 286, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3186, 287, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3187, 288, '22', 29, NULL, NULL, 0, '2022-05-17'),
(3188, 289, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3189, 290, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3190, 291, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3191, 292, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3192, 293, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3193, 294, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3194, 295, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3195, 296, '22', 10, NULL, NULL, 0, '2022-05-17'),
(3196, 297, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3197, 361, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3198, 362, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3199, 365, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3200, 367, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3201, 372, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3202, 373, '22', 34, NULL, NULL, 0, '2022-05-17'),
(3203, 376, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3204, 377, '22', 100, NULL, NULL, 0, '2022-05-17'),
(3205, 379, '22', 76, NULL, NULL, 0, '2022-05-17'),
(3206, 381, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3207, 396, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3208, 400, '22', 14, NULL, NULL, 0, '2022-05-17'),
(3209, 401, '22', 44, NULL, NULL, 0, '2022-05-17'),
(3210, 402, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3211, 405, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3212, 408, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3213, 409, '22', 131, NULL, NULL, 0, '2022-05-17'),
(3214, 411, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3215, 413, '22', 27, NULL, NULL, 0, '2022-05-17'),
(3216, 414, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3217, 415, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3218, 417, '22', 50, NULL, NULL, 0, '2022-05-17'),
(3219, 418, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3220, 419, '22', 25, NULL, NULL, 0, '2022-05-17'),
(3221, 420, '22', 23, NULL, NULL, 0, '2022-05-17'),
(3222, 421, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3223, 422, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3224, 423, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3225, 426, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3226, 427, '22', 70, NULL, NULL, 0, '2022-05-17'),
(3227, 428, '22', 100, NULL, NULL, 0, '2022-05-17'),
(3228, 429, '22', 10, NULL, NULL, 0, '2022-05-17'),
(3229, 430, '22', 10, NULL, NULL, 0, '2022-05-17'),
(3230, 431, '22', 12, NULL, NULL, 0, '2022-05-17'),
(3231, 433, '22', 25, NULL, NULL, 0, '2022-05-17'),
(3232, 434, '22', -3, NULL, NULL, 0, '2022-05-17'),
(3233, 435, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3234, 438, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3235, 439, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3236, 440, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3237, 441, '22', 19, NULL, NULL, 0, '2022-05-17'),
(3238, 442, '22', 63, NULL, NULL, 0, '2022-05-17'),
(3239, 443, '22', 4, NULL, NULL, 0, '2022-05-17'),
(3240, 444, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3241, 445, '22', 13, NULL, NULL, 0, '2022-05-17'),
(3242, 446, '22', 24, NULL, NULL, 0, '2022-05-17'),
(3243, 447, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3244, 448, '22', -4, NULL, NULL, 0, '2022-05-17'),
(3245, 449, '22', -2, NULL, NULL, 0, '2022-05-17'),
(3246, 451, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3247, 452, '22', 23, NULL, NULL, 0, '2022-05-17'),
(3248, 454, '22', -14, NULL, NULL, 0, '2022-05-17'),
(3249, 455, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3250, 456, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3251, 457, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3252, 458, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3253, 466, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3254, 467, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3255, 468, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3256, 469, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3257, 470, '22', 281, NULL, NULL, 0, '2022-05-17'),
(3258, 471, '22', 18, NULL, NULL, 0, '2022-05-17'),
(3259, 472, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3260, 473, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3261, 474, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3262, 475, '22', 22, NULL, NULL, 0, '2022-05-17'),
(3263, 476, '22', 18, NULL, NULL, 0, '2022-05-17'),
(3264, 477, '22', 41, NULL, NULL, 0, '2022-05-17'),
(3265, 478, '22', -7, NULL, NULL, 0, '2022-05-17'),
(3266, 479, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3267, 480, '22', 14, NULL, NULL, 0, '2022-05-17'),
(3268, 481, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3269, 482, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3270, 483, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3271, 484, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3272, 485, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3273, 487, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3274, 488, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3275, 490, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3276, 491, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3277, 492, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3278, 493, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3279, 494, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3280, 495, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3281, 496, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3282, 497, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3283, 499, '22', -5, NULL, NULL, 0, '2022-05-17'),
(3284, 500, '22', 82, NULL, NULL, 0, '2022-05-17'),
(3285, 501, '22', 11, NULL, NULL, 0, '2022-05-17'),
(3286, 503, '22', 81, NULL, NULL, 0, '2022-05-17'),
(3287, 504, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3288, 505, '22', 78, NULL, NULL, 0, '2022-05-17'),
(3289, 506, '22', -12, NULL, NULL, 0, '2022-05-17'),
(3290, 507, '22', -101, NULL, NULL, 0, '2022-05-17'),
(3291, 508, '22', -5, NULL, NULL, 0, '2022-05-17'),
(3292, 509, '22', -12, NULL, NULL, 0, '2022-05-17'),
(3293, 510, '22', 90, NULL, NULL, 0, '2022-05-17'),
(3294, 511, '22', 63, NULL, NULL, 0, '2022-05-17'),
(3295, 512, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3296, 513, '22', -14, NULL, NULL, 0, '2022-05-17'),
(3297, 514, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3298, 515, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3299, 516, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3300, 517, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3301, 518, '22', 30, NULL, NULL, 0, '2022-05-17'),
(3302, 519, '22', 81, NULL, NULL, 0, '2022-05-17'),
(3303, 520, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3304, 521, '22', -5, NULL, NULL, 0, '2022-05-17'),
(3305, 522, '22', -1, NULL, NULL, 0, '2022-05-17'),
(3306, 526, '22', 20, NULL, NULL, 0, '2022-05-17'),
(3307, 527, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3308, 528, '22', -6, NULL, NULL, 0, '2022-05-17'),
(3309, 529, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3310, 530, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3311, 531, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3312, 532, '22', -24, NULL, NULL, 0, '2022-05-17'),
(3313, 533, '22', 30, NULL, NULL, 0, '2022-05-17'),
(3314, 534, '22', 62, NULL, NULL, 0, '2022-05-17'),
(3315, 535, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3316, 536, '22', 42, NULL, NULL, 0, '2022-05-17'),
(3317, 537, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3318, 538, '22', 21, NULL, NULL, 0, '2022-05-17'),
(3319, 539, '22', 12, NULL, NULL, 0, '2022-05-17'),
(3320, 540, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3321, 541, '22', 11, NULL, NULL, 0, '2022-05-17'),
(3322, 542, '22', 10, NULL, NULL, 0, '2022-05-17'),
(3323, 543, '22', 64, NULL, NULL, 0, '2022-05-17'),
(3324, 544, '22', -4, NULL, NULL, 0, '2022-05-17'),
(3325, 545, '22', 33, NULL, NULL, 0, '2022-05-17'),
(3326, 546, '22', 16, NULL, NULL, 0, '2022-05-17'),
(3327, 547, '22', 13, NULL, NULL, 0, '2022-05-17'),
(3328, 548, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3329, 549, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3330, 550, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3331, 551, '22', 9, NULL, NULL, 0, '2022-05-17'),
(3332, 552, '22', 7, NULL, NULL, 0, '2022-05-17'),
(3333, 553, '22', 6, NULL, NULL, 0, '2022-05-17'),
(3334, 199, '22', -6, NULL, NULL, 0, '2022-05-17'),
(3335, 200, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3336, 201, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3337, 202, '22', 191, NULL, NULL, 0, '2022-05-17'),
(3338, 203, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3339, 204, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3340, 205, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3341, 206, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3342, 207, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3343, 208, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3344, 209, '22', 45, NULL, NULL, 0, '2022-05-17'),
(3345, 210, '22', 3, NULL, NULL, 0, '2022-05-17'),
(3346, 211, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3347, 212, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3348, 366, '22', 50, NULL, NULL, 0, '2022-05-17'),
(3349, 370, '22', 13, NULL, NULL, 0, '2022-05-17'),
(3350, 374, '22', 85, NULL, NULL, 0, '2022-05-17'),
(3351, 380, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3352, 394, '22', -3, NULL, NULL, 0, '2022-05-17'),
(3353, 397, '22', 8, NULL, NULL, 0, '2022-05-17'),
(3354, 398, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3355, 412, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3356, 424, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3357, 425, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3358, 436, '22', 88, NULL, NULL, 0, '2022-05-17'),
(3359, 498, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3360, 393, '22', 5, NULL, NULL, 0, '2022-05-17'),
(3361, 188, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3362, 189, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3363, 190, '22', 1, NULL, NULL, 0, '2022-05-17'),
(3364, 191, '22', 2, NULL, NULL, 0, '2022-05-17'),
(3365, 192, '22', 249, NULL, NULL, 0, '2022-05-17'),
(3366, 193, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3367, 194, '22', 4, NULL, NULL, 0, '2022-05-17'),
(3368, 195, '22', 0, 0, 0, 0, '2022-05-17'),
(3369, 196, '22', 3, 3, 0, 0, '2022-05-17'),
(3370, 364, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3371, 523, '22', 163, NULL, NULL, 0, '2022-05-17'),
(3372, 524, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3373, 525, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3374, 459, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3375, 460, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3376, 461, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3377, 462, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3378, 463, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3379, 464, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3380, 437, '22', 0, NULL, NULL, 0, '2022-05-17'),
(3381, 502, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3382, 15, '22', 11, NULL, NULL, 0, '2022-05-19'),
(3383, 16, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3384, 17, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3385, 18, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3386, 19, '22', 23, NULL, NULL, 0, '2022-05-19'),
(3387, 20, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3388, 21, '22', 28, NULL, NULL, 0, '2022-05-19'),
(3389, 22, '22', 50, NULL, NULL, 0, '2022-05-19'),
(3390, 23, '22', 19, NULL, NULL, 0, '2022-05-19'),
(3391, 24, '22', 160, NULL, NULL, 0, '2022-05-19'),
(3392, 25, '22', 32, NULL, NULL, 0, '2022-05-19'),
(3393, 26, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3394, 27, '22', 60, NULL, NULL, 0, '2022-05-19'),
(3395, 28, '22', 15, NULL, NULL, 0, '2022-05-19'),
(3396, 29, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3397, 30, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3398, 31, '22', 121, NULL, NULL, 0, '2022-05-19'),
(3399, 32, '22', 16, NULL, NULL, 0, '2022-05-19'),
(3400, 33, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3401, 38, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3402, 39, '22', 10, NULL, NULL, 0, '2022-05-19'),
(3403, 40, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3404, 41, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3405, 42, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3406, 43, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3407, 46, '22', -5, NULL, NULL, 0, '2022-05-19'),
(3408, 47, '22', 13, NULL, NULL, 0, '2022-05-19'),
(3409, 48, '22', 24, NULL, NULL, 0, '2022-05-19'),
(3410, 49, '22', 116, NULL, NULL, 0, '2022-05-19'),
(3411, 56, '22', 31, NULL, NULL, 0, '2022-05-19'),
(3412, 57, '22', 169, NULL, NULL, 0, '2022-05-19'),
(3413, 58, '22', 114, NULL, NULL, 0, '2022-05-19'),
(3414, 60, '22', 16, NULL, NULL, 0, '2022-05-19'),
(3415, 61, '22', 49, NULL, NULL, 0, '2022-05-19'),
(3416, 62, '22', 81, NULL, NULL, 0, '2022-05-19'),
(3417, 64, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3418, 65, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3419, 66, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3420, 67, '22', 24, NULL, NULL, 0, '2022-05-19'),
(3421, 68, '22', 72, NULL, NULL, 0, '2022-05-19'),
(3422, 69, '22', 24, NULL, NULL, 0, '2022-05-19'),
(3423, 70, '22', 86, NULL, NULL, 0, '2022-05-19'),
(3424, 71, '22', 189, NULL, NULL, 0, '2022-05-19'),
(3425, 73, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3426, 76, '22', 41, NULL, NULL, 0, '2022-05-19'),
(3427, 81, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3428, 92, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3429, 93, '22', 307, NULL, NULL, 0, '2022-05-19'),
(3430, 94, '22', 11, NULL, NULL, 0, '2022-05-19'),
(3431, 95, '22', 15, NULL, NULL, 0, '2022-05-19'),
(3432, 98, '22', 14, NULL, NULL, 0, '2022-05-19'),
(3433, 99, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3434, 101, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3435, 102, '22', -1, NULL, NULL, 0, '2022-05-19'),
(3436, 103, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3437, 104, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3438, 105, '22', 33, NULL, NULL, 0, '2022-05-19'),
(3439, 106, '22', -1, NULL, NULL, 0, '2022-05-19'),
(3440, 107, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3441, 108, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3442, 109, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3443, 110, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3444, 111, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3445, 112, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3446, 114, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3447, 115, '22', 98, NULL, NULL, 0, '2022-05-19'),
(3448, 116, '22', 214, NULL, NULL, 0, '2022-05-19'),
(3449, 117, '22', 45, NULL, NULL, 0, '2022-05-19'),
(3450, 118, '22', 307, NULL, NULL, 0, '2022-05-19'),
(3451, 119, '22', 164, NULL, NULL, 0, '2022-05-19'),
(3452, 120, '22', 189, NULL, NULL, 0, '2022-05-19'),
(3453, 121, '22', 397, NULL, NULL, 0, '2022-05-19'),
(3454, 129, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3455, 130, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3456, 131, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3457, 132, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3458, 133, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3459, 134, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3460, 135, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3461, 136, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3462, 137, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3463, 138, '22', 13, NULL, NULL, 0, '2022-05-19'),
(3464, 139, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3465, 140, '22', 785, NULL, NULL, 0, '2022-05-19'),
(3466, 145, '22', 30, NULL, NULL, 0, '2022-05-19'),
(3467, 152, '22', 78, NULL, NULL, 0, '2022-05-19'),
(3468, 153, '22', 60, NULL, NULL, 0, '2022-05-19'),
(3469, 154, '22', 187, NULL, NULL, 0, '2022-05-19'),
(3470, 155, '22', 190, NULL, NULL, 0, '2022-05-19'),
(3471, 156, '22', 16, NULL, NULL, 0, '2022-05-19'),
(3472, 157, '22', -3, NULL, NULL, 0, '2022-05-19'),
(3473, 158, '22', 12, NULL, NULL, 0, '2022-05-19'),
(3474, 159, '22', 96, NULL, NULL, 0, '2022-05-19'),
(3475, 160, '22', 70, NULL, NULL, 0, '2022-05-19'),
(3476, 161, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3477, 162, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3478, 164, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3479, 165, '22', 349, NULL, NULL, 0, '2022-05-19'),
(3480, 166, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3481, 167, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3482, 173, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3483, 174, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3484, 175, '22', 153, NULL, NULL, 0, '2022-05-19'),
(3485, 176, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3486, 177, '22', 143, NULL, NULL, 0, '2022-05-19'),
(3487, 178, '22', -1, NULL, NULL, 0, '2022-05-19'),
(3488, 179, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3489, 180, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3490, 181, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3491, 182, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3492, 183, '22', 35, NULL, NULL, 0, '2022-05-19'),
(3493, 184, '22', 50, NULL, NULL, 0, '2022-05-19'),
(3494, 185, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3495, 186, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3496, 187, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3497, 197, '22', 258, NULL, NULL, 0, '2022-05-19'),
(3498, 198, '22', 147, NULL, NULL, 0, '2022-05-19'),
(3499, 213, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3500, 214, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3501, 215, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3502, 216, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3503, 217, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3504, 218, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3505, 219, '22', -1, NULL, NULL, 0, '2022-05-19'),
(3506, 220, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3507, 221, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3508, 222, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3509, 223, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3510, 224, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3511, 225, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3512, 226, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3513, 227, '22', -1, NULL, NULL, 0, '2022-05-19'),
(3514, 228, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3515, 229, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3516, 230, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3517, 231, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3518, 232, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3519, 233, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3520, 234, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3521, 235, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3522, 236, '22', 10, NULL, NULL, 0, '2022-05-19'),
(3523, 237, '22', 12, NULL, NULL, 0, '2022-05-19'),
(3524, 238, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3525, 239, '22', 24, NULL, NULL, 0, '2022-05-19'),
(3526, 240, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3527, 241, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3528, 242, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3529, 243, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3530, 244, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3531, 246, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3532, 247, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3533, 248, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3534, 249, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3535, 250, '22', 11, NULL, NULL, 0, '2022-05-19'),
(3536, 251, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3537, 252, '22', 40, NULL, NULL, 0, '2022-05-19'),
(3538, 257, '22', 14, NULL, NULL, 0, '2022-05-19'),
(3539, 258, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3540, 259, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3541, 260, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3542, 261, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3543, 262, '22', 13, NULL, NULL, 0, '2022-05-19'),
(3544, 263, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3545, 264, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3546, 266, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3547, 267, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3548, 268, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3549, 269, '22', 18, NULL, NULL, 0, '2022-05-19'),
(3550, 270, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3551, 271, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3552, 272, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3553, 273, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3554, 274, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3555, 275, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3556, 276, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3557, 277, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3558, 278, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3559, 279, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3560, 280, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3561, 281, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3562, 282, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3563, 283, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3564, 284, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3565, 285, '22', 28, NULL, NULL, 0, '2022-05-19'),
(3566, 286, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3567, 287, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3568, 288, '22', 29, NULL, NULL, 0, '2022-05-19'),
(3569, 289, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3570, 290, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3571, 291, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3572, 292, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3573, 293, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3574, 294, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3575, 295, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3576, 296, '22', 11, NULL, NULL, 0, '2022-05-19'),
(3577, 297, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3578, 361, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3579, 362, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3580, 365, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3581, 367, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3582, 372, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3583, 373, '22', 33, NULL, NULL, 0, '2022-05-19'),
(3584, 376, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3585, 377, '22', 122, NULL, NULL, 0, '2022-05-19'),
(3586, 379, '22', 76, NULL, NULL, 0, '2022-05-19'),
(3587, 381, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3588, 396, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3589, 400, '22', 14, NULL, NULL, 0, '2022-05-19'),
(3590, 401, '22', 44, NULL, NULL, 0, '2022-05-19'),
(3591, 402, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3592, 405, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3593, 408, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3594, 409, '22', 124, NULL, NULL, 0, '2022-05-19'),
(3595, 411, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3596, 413, '22', 27, NULL, NULL, 0, '2022-05-19'),
(3597, 414, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3598, 415, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3599, 417, '22', 50, NULL, NULL, 0, '2022-05-19'),
(3600, 418, '22', -1, NULL, NULL, 0, '2022-05-19'),
(3601, 419, '22', 25, NULL, NULL, 0, '2022-05-19'),
(3602, 420, '22', 23, NULL, NULL, 0, '2022-05-19'),
(3603, 421, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3604, 422, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3605, 423, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3606, 426, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3607, 427, '22', 70, NULL, NULL, 0, '2022-05-19'),
(3608, 428, '22', 100, NULL, NULL, 0, '2022-05-19'),
(3609, 429, '22', 10, NULL, NULL, 0, '2022-05-19'),
(3610, 430, '22', 10, NULL, NULL, 0, '2022-05-19'),
(3611, 431, '22', 12, NULL, NULL, 0, '2022-05-19'),
(3612, 433, '22', 24, NULL, NULL, 0, '2022-05-19'),
(3613, 434, '22', -4, NULL, NULL, 0, '2022-05-19'),
(3614, 435, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3615, 438, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3616, 439, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3617, 440, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3618, 441, '22', 19, NULL, NULL, 0, '2022-05-19'),
(3619, 442, '22', 62, NULL, NULL, 0, '2022-05-19'),
(3620, 443, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3621, 444, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3622, 445, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3623, 446, '22', 24, NULL, NULL, 0, '2022-05-19'),
(3624, 447, '22', -1, NULL, NULL, 0, '2022-05-19');
INSERT INTO `inventario` (`id`, `id_producto`, `id_usuario`, `stock_actual`, `stock_real`, `faltante`, `anulado`, `fecha`) VALUES
(3625, 448, '22', -8, NULL, NULL, 0, '2022-05-19'),
(3626, 449, '22', 17, NULL, NULL, 0, '2022-05-19'),
(3627, 451, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3628, 452, '22', 23, NULL, NULL, 0, '2022-05-19'),
(3629, 454, '22', -14, NULL, NULL, 0, '2022-05-19'),
(3630, 455, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3631, 456, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3632, 457, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3633, 458, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3634, 466, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3635, 467, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3636, 468, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3637, 469, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3638, 470, '22', 267, NULL, NULL, 0, '2022-05-19'),
(3639, 471, '22', 17, NULL, NULL, 0, '2022-05-19'),
(3640, 472, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3641, 473, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3642, 474, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3643, 475, '22', 21, NULL, NULL, 0, '2022-05-19'),
(3644, 476, '22', 17, NULL, NULL, 0, '2022-05-19'),
(3645, 477, '22', 37, NULL, NULL, 0, '2022-05-19'),
(3646, 478, '22', -13, NULL, NULL, 0, '2022-05-19'),
(3647, 479, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3648, 480, '22', 14, NULL, NULL, 0, '2022-05-19'),
(3649, 481, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3650, 482, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3651, 483, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3652, 484, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3653, 485, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3654, 487, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3655, 488, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3656, 490, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3657, 491, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3658, 492, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3659, 493, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3660, 494, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3661, 495, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3662, 496, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3663, 497, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3664, 499, '22', 170, NULL, NULL, 0, '2022-05-19'),
(3665, 500, '22', 81, NULL, NULL, 0, '2022-05-19'),
(3666, 501, '22', 11, NULL, NULL, 0, '2022-05-19'),
(3667, 503, '22', 80, NULL, NULL, 0, '2022-05-19'),
(3668, 504, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3669, 505, '22', 78, NULL, NULL, 0, '2022-05-19'),
(3670, 506, '22', 18, NULL, NULL, 0, '2022-05-19'),
(3671, 507, '22', -36, NULL, NULL, 0, '2022-05-19'),
(3672, 508, '22', -5, NULL, NULL, 0, '2022-05-19'),
(3673, 509, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3674, 510, '22', 85, NULL, NULL, 0, '2022-05-19'),
(3675, 511, '22', 69, NULL, NULL, 0, '2022-05-19'),
(3676, 512, '22', 38, NULL, NULL, 0, '2022-05-19'),
(3677, 514, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3678, 515, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3679, 516, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3680, 517, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3681, 518, '22', 30, NULL, NULL, 0, '2022-05-19'),
(3682, 519, '22', 80, NULL, NULL, 0, '2022-05-19'),
(3683, 520, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3684, 521, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3685, 522, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3686, 526, '22', 18, NULL, NULL, 0, '2022-05-19'),
(3687, 527, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3688, 528, '22', -6, NULL, NULL, 0, '2022-05-19'),
(3689, 529, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3690, 530, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3691, 531, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3692, 532, '22', -26, NULL, NULL, 0, '2022-05-19'),
(3693, 533, '22', 30, NULL, NULL, 0, '2022-05-19'),
(3694, 534, '22', 61, NULL, NULL, 0, '2022-05-19'),
(3695, 535, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3696, 536, '22', 121, NULL, NULL, 0, '2022-05-19'),
(3697, 537, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3698, 538, '22', 21, NULL, NULL, 0, '2022-05-19'),
(3699, 539, '22', 12, NULL, NULL, 0, '2022-05-19'),
(3700, 540, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3701, 541, '22', 10, NULL, NULL, 0, '2022-05-19'),
(3702, 542, '22', 10, NULL, NULL, 0, '2022-05-19'),
(3703, 543, '22', 62, NULL, NULL, 0, '2022-05-19'),
(3704, 544, '22', -4, NULL, NULL, 0, '2022-05-19'),
(3705, 545, '22', 29, NULL, NULL, 0, '2022-05-19'),
(3706, 546, '22', 16, NULL, NULL, 0, '2022-05-19'),
(3707, 547, '22', 12, NULL, NULL, 0, '2022-05-19'),
(3708, 548, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3709, 549, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3710, 550, '22', 7, NULL, NULL, 0, '2022-05-19'),
(3711, 551, '22', 9, NULL, NULL, 0, '2022-05-19'),
(3712, 552, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3713, 553, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3714, 554, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3715, 555, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3716, 556, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3717, 557, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3718, 558, '22', 6, NULL, NULL, 0, '2022-05-19'),
(3719, 559, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3720, 560, '22', 16, NULL, NULL, 0, '2022-05-19'),
(3721, 561, '22', 12, NULL, NULL, 0, '2022-05-19'),
(3722, 562, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3723, 200, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3724, 201, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3725, 202, '22', 190, NULL, NULL, 0, '2022-05-19'),
(3726, 203, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3727, 204, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3728, 205, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3729, 206, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3730, 207, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3731, 208, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3732, 209, '22', 45, NULL, NULL, 0, '2022-05-19'),
(3733, 210, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3734, 211, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3735, 212, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3736, 366, '22', 50, NULL, NULL, 0, '2022-05-19'),
(3737, 370, '22', 13, NULL, NULL, 0, '2022-05-19'),
(3738, 374, '22', 82, NULL, NULL, 0, '2022-05-19'),
(3739, 380, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3740, 394, '22', 46, NULL, NULL, 0, '2022-05-19'),
(3741, 397, '22', 8, NULL, NULL, 0, '2022-05-19'),
(3742, 398, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3743, 412, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3744, 424, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3745, 425, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3746, 436, '22', 86, NULL, NULL, 0, '2022-05-19'),
(3747, 498, '22', 57, NULL, NULL, 0, '2022-05-19'),
(3748, 393, '22', 5, NULL, NULL, 0, '2022-05-19'),
(3749, 188, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3750, 189, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3751, 190, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3752, 191, '22', 2, NULL, NULL, 0, '2022-05-19'),
(3753, 192, '22', 222, NULL, NULL, 0, '2022-05-19'),
(3754, 193, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3755, 194, '22', 4, NULL, NULL, 0, '2022-05-19'),
(3756, 195, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3757, 196, '22', 3, NULL, NULL, 0, '2022-05-19'),
(3758, 364, '22', 1, NULL, NULL, 0, '2022-05-19'),
(3759, 523, '22', 162, NULL, NULL, 0, '2022-05-19'),
(3760, 524, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3761, 525, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3762, 459, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3763, 460, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3764, 461, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3765, 462, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3766, 463, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3767, 464, '22', 0, NULL, NULL, 0, '2022-05-19'),
(3768, 437, '22', 0, NULL, NULL, 0, '2022-05-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `marca`) VALUES
(1, 'sin marca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos`
--

CREATE TABLE `metodos` (
  `id` int(11) NOT NULL,
  `metodo` varchar(50) NOT NULL,
  `anulado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `metodos`
--

INSERT INTO `metodos` (`id`, `metodo`, `anulado`) VALUES
(2, 'Efectivo', 0),
(3, 'Tarjeta', 0),
(4, 'Transferencia', 0),
(5, 'Giro', 0),
(6, 'Gift Card', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monedas`
--

CREATE TABLE `monedas` (
  `id` int(11) NOT NULL,
  `reales` int(11) NOT NULL,
  `dolares` int(11) NOT NULL,
  `monto_inicial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `nota` text NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id`, `fecha`, `nota`, `usuario_id`, `created_at`) VALUES
(1, '2025-03-05', 'Esta es una nota de ejemplo.', 1, '2025-03-06 17:27:15'),
(2, '2025-03-06', 'Otra nota de ejemplo para la base de datos.', 2, '2025-03-06 17:27:15'),
(3, '2025-03-07', 'pasar por la municipalidad ', NULL, '2025-03-07 02:45:45'),
(4, '2025-03-07', 'pasar por la municipalidad ', NULL, '2025-03-07 02:47:18'),
(5, '2025-03-07', 'pasar por la muni ', NULL, '2025-03-07 02:50:01'),
(6, '2025-03-07', 'pasar por la muni', NULL, '2025-03-07 02:52:05'),
(7, '2025-03-07', 'pasar por la muni', NULL, '2025-03-07 02:52:20'),
(8, '2025-03-07', 'pasar por la muni', NULL, '2025-03-07 02:52:23'),
(9, '2025-03-13', 'asdasda', NULL, '2025-03-07 02:52:35'),
(10, '2025-03-12', 'pasar por', NULL, '2025-03-07 02:57:47'),
(11, '2025-03-27', 'wefwerfwer', NULL, '2025-03-07 02:58:09'),
(12, '2025-03-20', 'sdcsd', NULL, '2025-03-07 02:59:09'),
(13, '2025-04-02', 'sefwse', NULL, '2025-03-07 02:59:24'),
(14, '2025-04-02', 'sefwse', NULL, '2025-03-07 02:59:39'),
(15, '2025-03-19', 'frtghr', NULL, '2025-03-07 02:59:56'),
(16, '2025-03-26', 'dfgds', NULL, '2025-03-07 03:01:06'),
(17, '2025-03-12', 'sdfcvsadc', NULL, '2025-03-07 03:03:31'),
(18, '2025-03-14', 'szdcvsd', NULL, '2025-03-07 03:06:10'),
(19, '2025-03-05', 'lllkk', NULL, '2025-03-07 12:58:59'),
(20, '2025-03-19', 'onjn', NULL, '2025-03-07 13:18:28'),
(21, '2025-03-20', 'bjhbjhb', NULL, '2025-03-07 13:27:27'),
(22, '2025-03-04', 'uilug', NULL, '2025-03-07 13:38:36'),
(23, '2025-04-07', 'cobrar a mama', NULL, '2025-03-07 13:40:52'),
(24, '2025-03-08', 'ya casi esta ', NULL, '2025-03-07 13:42:29'),
(25, '2025-03-26', 'pepito me quiere pegar ', NULL, '2025-03-07 13:44:17'),
(26, '2025-03-07', 'es el dia para avanzar ', NULL, '2025-03-07 19:54:27'),
(27, '2025-04-07', 'COBRARLE A ROSA EN EL HOSPITAL REGIONAL', NULL, '2025-03-07 22:09:26'),
(28, '2025-05-14', 'cumple de nelson Feliu ', NULL, '2025-03-08 15:17:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_cuotas`
--

CREATE TABLE `pagos_cuotas` (
  `id` int(11) NOT NULL,
  `id_deuda` int(11) NOT NULL,
  `nro_cuota` int(11) NOT NULL,
  `monto_cuota` decimal(10,2) NOT NULL,
  `monto_pagado` decimal(10,2) DEFAULT 0.00,
  `pagado` tinyint(1) DEFAULT 0,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_tmp`
--

CREATE TABLE `pagos_tmp` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `pago` varchar(45) NOT NULL,
  `monto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(25) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `producto` varchar(60) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `marcaVehiculo` text NOT NULL,
  `descripcion` text NOT NULL,
  `precio_costo` int(11) NOT NULL,
  `precio_minorista` int(11) NOT NULL DEFAULT 0,
  `precio_mayorista` int(11) DEFAULT 0,
  `stock` float NOT NULL DEFAULT 0,
  `stock_minimo` float DEFAULT 5,
  `descuento_max` int(11) DEFAULT 5,
  `importado` varchar(10) DEFAULT NULL,
  `iva` int(11) NOT NULL DEFAULT 10,
  `sucursal` int(11) NOT NULL,
  `anulado` int(11) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `anio` int(4) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `puertas` int(1) DEFAULT NULL,
  `combustible` varchar(20) DEFAULT NULL,
  `transmision` varchar(20) DEFAULT NULL,
  `traccion` varchar(10) DEFAULT NULL,
  `placa` varchar(20) DEFAULT NULL,
  `tipo_vehiculo` varchar(30) DEFAULT NULL,
  `vin` varchar(50) DEFAULT NULL,
  `motor` varchar(50) DEFAULT NULL,
  `kilometraje` int(11) DEFAULT NULL,
  `pais_origen` varchar(50) DEFAULT NULL,
  `fecha_importacion` date DEFAULT NULL,
  `usado` varchar(5) DEFAULT NULL,
  `dueno_anterior` varchar(100) DEFAULT NULL,
  `cedula_rif` varchar(20) DEFAULT NULL,
  `titulo_propiedad` varchar(255) DEFAULT NULL,
  `factura_original` varchar(255) DEFAULT NULL,
  `revision_tecnica` varchar(255) DEFAULT NULL,
  `permiso_circulacion` varchar(255) DEFAULT NULL,
  `precio_financiado` decimal(15,2) DEFAULT 0.00,
  `entrega_minima` decimal(15,2) DEFAULT 0.00,
  `cuotas_minimas` decimal(15,2) DEFAULT 0.00,
  `cant_refuerzo` int(11) DEFAULT 0,
  `monto_minimo_refuerzo` bigint(20) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `id_categoria`, `producto`, `marca`, `marcaVehiculo`, `descripcion`, `precio_costo`, `precio_minorista`, `precio_mayorista`, `stock`, `stock_minimo`, `descuento_max`, `importado`, `iva`, `sucursal`, `anulado`, `modelo`, `anio`, `version`, `color`, `puertas`, `combustible`, `transmision`, `traccion`, `placa`, `tipo_vehiculo`, `vin`, `motor`, `kilometraje`, `pais_origen`, `fecha_importacion`, `usado`, `dueno_anterior`, `cedula_rif`, `titulo_propiedad`, `factura_original`, `revision_tecnica`, `permiso_circulacion`, `precio_financiado`, `entrega_minima`, `cuotas_minimas`, `cant_refuerzo`, `monto_minimo_refuerzo`) VALUES
(599, '10001 ', 72, 'CAMIONERA   ', '1', 'TOYOTA ', 'UNA CAMIONETA EN  ESTADO MAS O MENOS ACEPTABLE ', 120, 150, 0, 2, NULL, 0, 'NO', 10, 1, NULL, 'prado ', 1997, 'LTZ ', 'BLANCO ', 2, 'Diésel', 'Manual', '4x4', 'ABC-222 ', 'Camioneta', '332KK56656+5KKK', '22355555', 349998, 'JAPON', '2025-04-17', 'SI', 'CARLOS ANTONIO LOPEZ', '3198595-0983455074', '6805467b51580_permiso.pdf', '6805467b51c4b_Calendario_30_Dias_Emprendimiento.pdf', '6805467b51e9b_555.pdf', '6805467b5219e_2233.pdf', 200.00, 15.00, 24.00, 2, 50),
(600, '10002', 72, 'auto', '1', 'TOYOTA ', 'corrolla excelente estado', 100000000, 250000000, 0, 0, NULL, 0, 'SI', 10, 1, NULL, 'corrolla', 1999, 'LTZ', 'BLANCO', 4, 'Diésel', 'Manual', '4x2', 'ABC-111', 'Sedán', '332KK56656+5ooo', '223555553232', 84999, 'JAPON', '2025-04-16', 'NO', '', '', '680a47e565926_permiso.pdf', '680a47e5668f6_instituto (3).sql', '680a47e566db9_2233.pdf', '680a47e566f48_Ubicacion.pdf', 130000000.00, 45000000.00, 22.00, 2, 5000000),
(601, '101131', 75, 'lancha ', '1', 'jaryi', 'lancha a babor ', 45000000, 100000000, 0, 0, NULL, 0, 'SI', 10, 1, NULL, 'pando', 2020, 'full', 'blanco', 2, 'Gasolina', 'Manual', '4x2', 'AB22-222 ', 'SUV', '332KK56jjjkkhj+5KKK', '223', 1199, 'Paraguay', '2025-01-08', 'NO', 'CARLOS ANTONIO LOPEZ', '0983455074', NULL, NULL, NULL, NULL, 135000000.00, 35000000.00, 24.00, 2, 5000000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productoss`
--

CREATE TABLE `productoss` (
  `id` int(11) NOT NULL,
  `codigo` varchar(25) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `producto` varchar(60) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `precio_costo` int(11) NOT NULL,
  `precio_minorista` int(11) NOT NULL DEFAULT 0,
  `precio_mayorista` varchar(50) DEFAULT '0',
  `stock` float NOT NULL DEFAULT 0,
  `stock_minimo` float DEFAULT 5,
  `descuento_max` int(11) DEFAULT 5,
  `importado` varchar(10) DEFAULT NULL,
  `iva` int(11) NOT NULL DEFAULT 10,
  `sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `id` int(11) NOT NULL,
  `sucursal` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`id`, `sucursal`) VALUES
(1, 'Central');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencias`
--

CREATE TABLE `transferencias` (
  `id` int(11) NOT NULL,
  `usuario_emisor` int(11) NOT NULL,
  `usuario_receptor` int(11) DEFAULT NULL,
  `local_emisor` int(11) NOT NULL,
  `local_receptor` int(11) DEFAULT NULL,
  `id_producto` varchar(50) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `fecha_aceptada` date DEFAULT NULL,
  `fecha_solicitada` date NOT NULL,
  `observacion` text DEFAULT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `nivel` int(11) NOT NULL,
  `sucursal` int(11) DEFAULT NULL,
  `comision` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `user`, `pass`, `nivel`, `sucursal`, `comision`) VALUES
(14, 'pitta', '100', 1, 1, 0),
(15, 'Moises Villalba', 'MOISES100788', 1, 1, 0),
(16, 'Candy', '5528791', 1, 1, 0),
(17, 'Rebeca', '123456', 1, 1, 0),
(18, 'betania', '123456', 2, 1, 0),
(19, 'Natalia', '123456', 2, 1, 0),
(21, 'ORLANDOJ', 'Semeniuk1c', 1, 1, 5),
(22, 'cynthia ', 'camila07', 1, 1, 5),
(23, 'Tech', '12345', 1, 1, 0),
(24, 'Josias', '0710', 2, 1, 0),
(25, 'Leticia', '1997', 2, 1, 0),
(26, 'Ever', '123', 1, 1, 10),
(27, 'jn-sa', '123456', 1, 1, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `vendedor_salon` int(11) DEFAULT NULL,
  `id_producto` varchar(30) NOT NULL,
  `precio_costo` float NOT NULL,
  `precio_venta` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  `descuento` int(11) NOT NULL,
  `iva` int(11) NOT NULL,
  `total` float NOT NULL,
  `comprobante` varchar(20) NOT NULL,
  `nro_comprobante` varchar(40) DEFAULT NULL,
  `cantidad` float NOT NULL,
  `margen_ganancia` varchar(45) NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `metodo` varchar(40) NOT NULL,
  `banco` varchar(45) NOT NULL,
  `contado` varchar(30) NOT NULL,
  `anulado` int(11) NOT NULL DEFAULT 0,
  `id_gift` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_venta`, `id_cliente`, `id_vendedor`, `vendedor_salon`, `id_producto`, `precio_costo`, `precio_venta`, `subtotal`, `descuento`, `iva`, `total`, `comprobante`, `nro_comprobante`, `cantidad`, `margen_ganancia`, `fecha_venta`, `metodo`, `banco`, `contado`, `anulado`, `id_gift`) VALUES
(41150, 1, 1, 14, 0, '601', 45000000, 135000000, 135000000, 0, 0, 135000000, 'Ticket', '', 1, '200', '2025-05-12 15:50:00', '', '', 'Credito', 0, NULL),
(41151, 2, 186, 3, 0, '601', 45000000, 135000000, 135000000, 0, 0, 135000000, 'Ticket', '', 1, '200', '2025-05-12 21:19:00', '', '', 'Credito', 0, NULL),
(41152, 3, 188, 3, 0, '601', 45000000, 135000000, 135000000, 0, 0, 135000000, 'Ticket', '001-008-000000100', 1, '200', '2025-05-12 22:28:00', '', '', 'Credito', 0, NULL),
(41153, 4, 186, 3, 0, '599', 120, 200, 200, 0, 0, 200, 'Ticket', '001-008-000000100', 1, '66.666666666667', '2025-05-12 23:37:00', '', '', 'Credito', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_tmp`
--

CREATE TABLE `ventas_tmp` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_producto` varchar(25) NOT NULL,
  `precio_venta` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `descuento` float NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `entrega` decimal(10,2) DEFAULT 0.00,
  `cuota_vehiculo` int(11) DEFAULT 0,
  `monto_refuerzo` decimal(10,2) DEFAULT 0.00,
  `cantidad_refuerzo` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ventas_tmp`
--

INSERT INTO `ventas_tmp` (`id`, `id_venta`, `id_vendedor`, `id_producto`, `precio_venta`, `cantidad`, `descuento`, `fecha_venta`, `entrega`, `cuota_vehiculo`, `monto_refuerzo`, `cantidad_refuerzo`) VALUES
(42328, 1, 3, '599', 150, 1, 0, '2025-05-12 23:55:00', 15.00, 24, 50.00, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acreedores`
--
ALTER TABLE `acreedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cierres`
--
ALTER TABLE `cierres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cierre_inventario`
--
ALTER TABLE `cierre_inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras_tmp`
--
ALTER TABLE `compras_tmp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deudas`
--
ALTER TABLE `deudas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `devoluciones_tmp`
--
ALTER TABLE `devoluciones_tmp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gift_card`
--
ALTER TABLE `gift_card`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nro_tarjeta` (`nro_tarjeta`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `metodos`
--
ALTER TABLE `metodos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `monedas`
--
ALTER TABLE `monedas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos_cuotas`
--
ALTER TABLE `pagos_cuotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_deuda` (`id_deuda`);

--
-- Indices de la tabla `pagos_tmp`
--
ALTER TABLE `pagos_tmp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_2` (`codigo`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `productoss`
--
ALTER TABLE `productoss`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_2` (`codigo`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas_tmp`
--
ALTER TABLE `ventas_tmp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acreedores`
--
ALTER TABLE `acreedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `cierres`
--
ALTER TABLE `cierres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT de la tabla `cierre_inventario`
--
ALTER TABLE `cierre_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1117;

--
-- AUTO_INCREMENT de la tabla `compras_tmp`
--
ALTER TABLE `compras_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1165;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deudas`
--
ALTER TABLE `deudas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `devoluciones_tmp`
--
ALTER TABLE `devoluciones_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=435;

--
-- AUTO_INCREMENT de la tabla `gift_card`
--
ALTER TABLE `gift_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16145;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5008;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `metodos`
--
ALTER TABLE `metodos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `monedas`
--
ALTER TABLE `monedas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `pagos_cuotas`
--
ALTER TABLE `pagos_cuotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos_tmp`
--
ALTER TABLE `pagos_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10857;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=602;

--
-- AUTO_INCREMENT de la tabla `productoss`
--
ALTER TABLE `productoss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41154;

--
-- AUTO_INCREMENT de la tabla `ventas_tmp`
--
ALTER TABLE `ventas_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42329;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos_cuotas`
--
ALTER TABLE `pagos_cuotas`
  ADD CONSTRAINT `pagos_cuotas_ibfk_1` FOREIGN KEY (`id_deuda`) REFERENCES `deudas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
