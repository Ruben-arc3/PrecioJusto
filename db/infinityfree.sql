-- PrecioJusto: estructura completa y datos de demostración para InfinityFree.
-- En phpMyAdmin, selecciona primero la base de datos creada en InfinityFree y luego importa este archivo.
-- Incluye URLs genéricas de imágenes. No contiene credenciales ni cuentas de usuario precreadas.
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-06-2025 a las 22:38:47
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
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
(3, 'Despensa'),
(4, 'Lacteos'),
(5, 'Bebidas'),
(6, 'FrutasVerduras'),
(7, 'organicos'),
(8, 'panaderia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_productos`
--

CREATE TABLE `categoria_productos` (
  `id_relacion` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta`
--

CREATE TABLE `oferta` (
  `id` int(11) NOT NULL,
  `id_tienda` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(100) DEFAULT NULL,
  `Foto` varchar(255) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos` (CON FOTOS ACTUALIZADAS)
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `Foto`, `marca`, `id_categoria`) VALUES
(44, 'Arroz Diana x500G', 'https://picsum.photos/seed/arroz/600/600', 'Diana', 3),
(45, 'Aceite Gourmet x900ml', 'https://picsum.photos/seed/aceite/600/600', 'Gourmet', 3),
(46, 'Leche Entera Alpina x1L', 'https://picsum.photos/seed/leche/600/600', 'Alpina', 4),
(47, 'Queso Campesino x250g', 'https://picsum.photos/seed/queso/600/600', 'Colanta', 4),
(48, 'Gaseosa Coca-Cola x2.5L', 'https://picsum.photos/seed/gaseosa/600/600', 'Coca-Cola', 5),
(49, 'Harina PAN (500 gr)', 'https://picsum.photos/seed/harina/600/600', 'Pan', 8),
(50, 'Esparcible C/Sal Multiusos RAMA 220 gr', 'https://picsum.photos/seed/esparcible/600/600', 'Rama', 4),
(51, 'Salchicha Ranchera 230 G X7 Unds', 'https://picsum.photos/seed/salchicha/600/600', 'Ranchera', 3),
(52, 'Spaghetti Doria x250g', 'https://picsum.photos/seed/spaghetti/600/600', 'Doria', 3),
(53, 'Huevos AA x30un', 'https://picsum.photos/seed/huevos/600/600', 'Kikes', 4),
(60, 'Sal REFISAL alta pureza (1000 gr)', 'https://picsum.photos/seed/sal/600/600', 'Refisal', 3),
(61, 'Gaseosa SCHWEPPES Ginger Ale (1500 ml)', 'https://picsum.photos/seed/schweppes/600/600', 'Schweppes', 5),
(62, 'Aguardiente ANTIOQUENO azul sin azúcar botella (750 ml)', 'https://picsum.photos/seed/aguardiente/600/600', 'Antioqueño', 5),
(63, 'Aguacate Und', 'https://picsum.photos/seed/aguacate/600/600', 'Natural', 6),
(64, 'Manzana 1000 gr', 'https://picsum.photos/seed/manzana/600/600', 'Natural', 6),
(65, 'Banano 1 und', 'https://picsum.photos/seed/banano/600/600', 'Natural', 6),
(66, 'Cerveza Águila Lata 330 Ml X6 Unds', 'https://picsum.photos/seed/cerveza/600/600', 'Águila', 5),
(67, 'Jamon PIETRAN de Cerdo (230 gr)', 'https://picsum.photos/seed/jamon/600/600', 'Pietrán', 4),
(68, 'Mayonesa FRUCO (380 gr)', 'https://picsum.photos/seed/mayonesa/600/600', 'Fruco', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_tienda`
--

CREATE TABLE `producto_tienda` (
  `id_producto` int(11) NOT NULL,
  `id_tienda` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `url` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_tienda`
--

INSERT INTO `producto_tienda` (`id_producto`, `id_tienda`, `precio`, `url`) VALUES
(44, 1, 2250.00, 'https://www.exito.com/arroz-diana-500-gr-479512/p'),
(44, 91, 2200.00, 'https://www.olimpica.com/arroz-diana-500-gr-7702511000014-518231/p'),
(44, 94, 2100.00, 'https://domicilios.tiendasd1.com/p/arroz-diana-500-g-12002073'),
(45, 1, 21150.00, 'https://www.exito.com/aceite-familia-multiusos-gourmet-900ml/phttps://www.exito.com/aceite-familia-multiusos-gourmet-900-ml-3069123/p'),
(45, 91, 14992.00, 'https://www.olimpica.com/aceite-familia-multiusos-gourmet-900ml/phttps://www.olimpica.com/aceite-gourmet-familia-multiusos-900ml/p'),
(46, 1, 7730.00, 'https://www.exito.com/leche-entera-en-caja-x-1-litro-227925/p'),
(46, 91, 7650.00, 'https://www.olimpica.com/leche-uht-alpina-ent-cj-1-l/p'),
(47, 1, 7690.00, 'https://www.exito.com/queso-campesino-empacado-al-vacio-x-250-gr-433118/p'),
(47, 91, 7270.00, 'https://www.olimpica.com/qso-campes-colanta-250-g/p'),
(48, 1, 12160.00, 'https://www.exito.com/of-duopack-gaseosa-original-coca-cola-5000-ml-3122148/p'),
(48, 91, 15500.00, 'https://www.olimpica.com/gaseosa-coca-cola-2-5l-x2/p'),
(48, 94, 14450.00, 'https://domicilios.tiendasd1.com/p/combo-gaseosa-coca-cola-x2-und-25l-12005528'),
(49, 1, 2600.00, 'https://www.exito.com/pan-harina-maiz-blanca-x-500-gr-654936/p'),
(49, 91, 2550.00, 'https://www.olimpica.com/harina-de-maiz-blanco-pan-500-g-7702084127330--567652/p'),
(50, 1, 5210.00, 'https://www.exito.com/esparcible-csal-multiusos-rama-220-gr-3083420/p'),
(50, 91, 3444.00, 'https://www.olimpica.com/esparcible%20c%2fsal%20multiusos%20rama%20220%20gr?_q=Esparcible%20C/Sal%20Multiusos%20RAMA%20220%20gr&map=ft'),
(50, 94, 4800.00, 'https://domicilios.tiendasd1.com/search?name=Esparcible++Multiusos+RAMA'),
(51, 1, 14350.00, 'https://www.exito.com/salchicha-ranchera-x-230-749936/p'),
(51, 91, 12500.00, 'https://www.olimpica.com/salchicha-ranchera-x7un-230-g-7701101247273-1362716/p'),
(51, 94, 12300.00, 'https://domicilios.tiendasd1.com/p/salchicha-ranchera-x-230-g-x-7-ud-12005929'),
(52, 1, 2050.00, 'https://www.exito.com/pasta-clasica-spaghetti-x-250-gr-65008/p'),
(52, 91, 2100.00, 'https://www.olimpica.com/pasta-espaguetti-doria-clasico-250-g/p'),
(52, 94, 2150.00, 'https://domicilios.tiendasd1.com/p/spaghetti-doria-250-g-12003256'),
(53, 1, 23900.00, 'https://www.exito.com/huevo-aa-rojo-30und-pet-744836/p'),
(53, 91, 24500.00, 'https://www.olimpica.com/huevo-aa-rojo-pet-kikes-30un-7707304621100-1353948/p'),
(60, 1, 2600.00, 'https://www.exito.com/sal-refinada-1000-gr-345053/p'),
(60, 91, 2600.00, 'https://www.olimpica.com/sal-refisal-1-kg/p'),
(60, 94, 2550.00, 'https://domicilios.tiendasd1.com/p/sal-refisal-1000-g-12002874'),
(61, 1, 3152.00, 'https://www.exito.com/soda-ginger-594179/p'),
(61, 91, 4810.00, 'https://www.olimpica.com/gaseosa-schweppes-ginger-ale-1-5l/p'),
(61, 94, 4600.00, 'https://domicilios.tiendasd1.com/p/gaseosa-schweppes-ginger-ale-pet-1500ml-12004749'),
(62, 1, 52900.00, 'https://www.exito.com/aguardiente-antioqueno-sin-azucar-en-botella-x-750ml-949390/p'),
(62, 91, 47900.00, 'https://www.olimpica.com/aguardiente-antioqueno-sin-azucar-750-ml-7702049000531-1520459/p'),
(62, 94, 49950.00, 'https://domicilios.tiendasd1.com/p/aguardiente-antioqueno-sin-azucar-750-ml-12002708'),
(63, 1, 4740.00, 'https://www.exito.com/aguacate-lorena-unidad-305408/p'),
(63, 91, 3714.00, 'https://www.olimpica.com/aguacate-economico-por-unidad-7701008659087/p?skuId=813'),
(63, 94, 5990.00, 'https://domicilios.tiendasd1.com/p/aguacate-unidad-12002754'),
(64, 1, 9900.00, 'https://www.exito.com/manzana-pink-lady-bolsa-1000-gr-3185261/p'),
(64, 91, 8388.00, 'https://www.olimpica.com/manzana-roj-bolsa-por-1-kg-7701008509429/p?skuId=4951'),
(64, 94, 10900.00, 'https://domicilios.tiendasd1.com/p/manzana-royal-gala-1000-grs-12000109'),
(65, 1, 820.00, 'https://www.exito.com/banano-unidad-937109/p'),
(65, 94, 450.00, 'https://domicilios.tiendasd1.com/p/banano-unidad-12005468'),
(66, 1, 18900.00, 'https://www.exito.com/cerveza-lata-sixpack-aguila-1980-ml-446655/p'),
(66, 91, 19800.00, 'https://www.olimpica.com/cerveza-aguila-lata-330-ml--x-6/p'),
(67, 1, 12900.00, 'https://www.exito.com/jamon-estandar-libre-de-grasa-reducido-en-sodio-x-230g-711974/p'),
(67, 91, 12600.00, 'https://www.olimpica.com/jamon-pietran-estandar-sin-conservantes-230g-7701101356098-1521487/p'),
(67, 94, 12900.00, 'https://domicilios.tiendasd1.com/p/jamon-pietran-estandar-230g-12006001'),
(68, 1, 11600.00, 'https://www.exito.com/mayonesa-doy-pack-606330/p'),
(68, 91, 8700.00, 'https://www.olimpica.com/mayonesa-fruco-doy-pack-380-g-7702047038772--2038606/p'),
(68, 94, 8990.00, 'https://domicilios.tiendasd1.com/p/mayonesa-fruco-400-grs-12006976');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiendas`
--

CREATE TABLE `tiendas` (
  `id_tienda` int(11) NOT NULL,
  `nombre_tienda` varchar(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiendas`
--

INSERT INTO `tiendas` (`id_tienda`, `nombre_tienda`, `url`) VALUES
(1, 'Exito', 'https://www.exito.com'),
(91, 'Olimpica', 'https://www.olimpica.com/?gclsrc=aw.ds&&kb=ga_sb_14495418882_129671119129&gad_source=1&gad_campaignid=14495418882&gclid=EAIaIQobChMI5amlzZSujQMVlp9aBR33nwicEAAYASAAEgKg4PD_BwE'),
(94, 'D1', 'https://www.tiendasd1.com'),
(95, 'Ara', 'https://www.ara.com.co');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contrasena` varchar(100) DEFAULT NULL,
  `tipo` enum('admin','cliente') NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

-- Los usuarios se registran desde la aplicación. No se incluyen cuentas precreadas.
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

CREATE TABLE `valoraciones` (
  `id_valoracion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `puntuacion` tinyint(4) NOT NULL CHECK (`puntuacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `categoria_productos`
--
ALTER TABLE `categoria_productos`
  ADD PRIMARY KEY (`id_relacion`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tienda` (`id_tienda`),
  ADD KEY `fk_oferta_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_productos_categoria` (`id_categoria`);

--
-- Indices de la tabla `producto_tienda`
--
ALTER TABLE `producto_tienda`
  ADD PRIMARY KEY (`id_producto`,`id_tienda`),
  ADD KEY `id_tienda` (`id_tienda`),
  ADD KEY `idx_precio` (`precio`);

--
-- Indices de la tabla `tiendas`
--
ALTER TABLE `tiendas`
  ADD PRIMARY KEY (`id_tienda`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`correo`);

--
-- Indices de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`id_valoracion`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT de la tabla `oferta`
--
ALTER TABLE `oferta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT de la tabla `tiendas`
--
ALTER TABLE `tiendas`
  MODIFY `id_tienda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  MODIFY `id_valoracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categoria_productos`
--
ALTER TABLE `categoria_productos`
  ADD CONSTRAINT `categoria_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `categoria_productos_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD CONSTRAINT `fk_oferta_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `oferta_ibfk_1` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_tienda`
--
ALTER TABLE `producto_tienda`
  ADD CONSTRAINT `producto_tienda_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_tienda_ibfk_2` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE;

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `valoraciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Productos, precios, tiendas y ofertas de ejemplo.
-- Datos de demostración para PrecioJusto.
-- Requiere haber importado db/proyecto.sql primero.
-- Todos los nombres, precios y enlaces a tiendas son ficticios.
-- Las imágenes son placeholders servidos por Picsum.

START TRANSACTION;

INSERT INTO tiendas (id_tienda, nombre_tienda, url) VALUES
(9001, 'Mercado Central', 'https://example.com/mercado-central'),
(9002, 'Ahorro Familiar', 'https://example.com/ahorro-familiar'),
(9003, 'Tienda del Barrio', 'https://example.com/tienda-del-barrio')
ON DUPLICATE KEY UPDATE
 nombre_tienda = VALUES(nombre_tienda),
 url = VALUES(url);

INSERT INTO productos (id_producto, nombre_producto, Foto, marca, id_categoria) VALUES
(9001, 'Arroz blanco 1 kg', 'https://picsum.photos/seed/pj-arroz/600/600', 'Buen Campo', 3),
(9002, 'Pasta tipo espagueti 500 g', 'https://picsum.photos/seed/pj-pasta/600/600', 'Del Molino', 3),
(9003, 'Leche entera 1 L', 'https://picsum.photos/seed/pj-leche/600/600', 'La Vaquita', 4),
(9004, 'Queso campesino 250 g', 'https://picsum.photos/seed/pj-queso/600/600', 'La Pradera', 4),
(9005, 'Yogur natural 1 L', 'https://picsum.photos/seed/pj-yogur/600/600', 'Día Fresco', 4),
(9006, 'Agua sin gas 1.5 L', 'https://picsum.photos/seed/pj-agua/600/600', 'Manantial', 5),
(9007, 'Café molido 250 g', 'https://picsum.photos/seed/pj-cafe/600/600', 'Montaña', 3),
(9008, 'Manzana roja 1 kg', 'https://picsum.photos/seed/pj-manzana/600/600', 'Finca Verde', 6),
(9009, 'Banano 1 kg', 'https://picsum.photos/seed/pj-banano/600/600', 'Finca Verde', 6),
(9010, 'Pan tajado 500 g', 'https://picsum.photos/seed/pj-pan/600/600', 'Horno Casa', 8),
(9011, 'Aceite vegetal 900 ml', 'https://picsum.photos/seed/pj-aceite/600/600', 'Cocina Feliz', 3),
(9012, 'Huevos tipo AA x 12', 'https://picsum.photos/seed/pj-huevos/600/600', 'Granja Clara', 4)
ON DUPLICATE KEY UPDATE
 nombre_producto = VALUES(nombre_producto),
 Foto = VALUES(Foto),
 marca = VALUES(marca),
 id_categoria = VALUES(id_categoria);

INSERT INTO producto_tienda (id_producto, id_tienda, precio, url) VALUES
(9001,9001,4500.00,'https://example.com/mercado-central/arroz-1kg'),
(9001,9002,4290.00,'https://example.com/ahorro-familiar/arroz-1kg'),
(9001,9003,4600.00,'https://example.com/tienda-del-barrio/arroz-1kg'),
(9002,9001,3200.00,'https://example.com/mercado-central/pasta-500g'),
(9002,9002,3100.00,'https://example.com/ahorro-familiar/pasta-500g'),
(9002,9003,3350.00,'https://example.com/tienda-del-barrio/pasta-500g'),
(9003,9001,4200.00,'https://example.com/mercado-central/leche-1l'),
(9003,9002,4380.00,'https://example.com/ahorro-familiar/leche-1l'),
(9003,9003,4100.00,'https://example.com/tienda-del-barrio/leche-1l'),
(9004,9001,9800.00,'https://example.com/mercado-central/queso-250g'),
(9004,9002,9500.00,'https://example.com/ahorro-familiar/queso-250g'),
(9004,9003,10100.00,'https://example.com/tienda-del-barrio/queso-250g'),
(9005,9001,2500.00,'https://example.com/mercado-central/yogur-1l'),
(9005,9002,2700.00,'https://example.com/ahorro-familiar/yogur-1l'),
(9005,9003,2400.00,'https://example.com/tienda-del-barrio/yogur-1l'),
(9006,9001,1800.00,'https://example.com/mercado-central/agua-15l'),
(9006,9002,1700.00,'https://example.com/ahorro-familiar/agua-15l'),
(9006,9003,1900.00,'https://example.com/tienda-del-barrio/agua-15l'),
(9007,9001,12500.00,'https://example.com/mercado-central/cafe-250g'),
(9007,9002,11900.00,'https://example.com/ahorro-familiar/cafe-250g'),
(9007,9003,13200.00,'https://example.com/tienda-del-barrio/cafe-250g'),
(9008,9001,8500.00,'https://example.com/mercado-central/manzana-1kg'),
(9008,9002,9000.00,'https://example.com/ahorro-familiar/manzana-1kg'),
(9008,9003,7800.00,'https://example.com/tienda-del-barrio/manzana-1kg'),
(9009,9001,4500.00,'https://example.com/mercado-central/banano-1kg'),
(9009,9002,4300.00,'https://example.com/ahorro-familiar/banano-1kg'),
(9009,9003,4700.00,'https://example.com/tienda-del-barrio/banano-1kg'),
(9010,9001,5600.00,'https://example.com/mercado-central/pan-500g'),
(9010,9002,5200.00,'https://example.com/ahorro-familiar/pan-500g'),
(9010,9003,5750.00,'https://example.com/tienda-del-barrio/pan-500g'),
(9011,9001,12900.00,'https://example.com/mercado-central/aceite-900ml'),
(9011,9002,12700.00,'https://example.com/ahorro-familiar/aceite-900ml'),
(9011,9003,13100.00,'https://example.com/tienda-del-barrio/aceite-900ml'),
(9012,9001,11500.00,'https://example.com/mercado-central/huevos-12'),
(9012,9002,11200.00,'https://example.com/ahorro-familiar/huevos-12'),
(9012,9003,11900.00,'https://example.com/tienda-del-barrio/huevos-12')
ON DUPLICATE KEY UPDATE
 precio = VALUES(precio),
 url = VALUES(url);

INSERT INTO oferta (id, id_tienda, descripcion, fecha, id_producto) VALUES
(9001,9002,'Descuento de demostración en arroz',CURRENT_DATE,9001),
(9002,9002,'Precio especial de demostración en pasta',CURRENT_DATE,9002),
(9003,9003,'Oferta de demostración en leche',CURRENT_DATE,9003),
(9004,9002,'Precio especial de demostración en queso',CURRENT_DATE,9004),
(9005,9003,'Descuento de demostración en yogur',CURRENT_DATE,9005),
(9006,9002,'Oferta de demostración en agua',CURRENT_DATE,9006),
(9007,9002,'Precio especial de demostración en café',CURRENT_DATE,9007),
(9008,9003,'Oferta de demostración en manzanas',CURRENT_DATE,9008)
ON DUPLICATE KEY UPDATE
 id_tienda = VALUES(id_tienda),
 descripcion = VALUES(descripcion),
 fecha = VALUES(fecha),
 id_producto = VALUES(id_producto);

COMMIT;
