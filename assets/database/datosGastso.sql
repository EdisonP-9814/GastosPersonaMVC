-- 1. ROLES DE USUARIO
-- Necesarios para el registro y login
INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Usuario General');

-- 2. CATEGORÍAS DE TRANSACCIONES
-- Necesarias para el formulario de gastos/ingresos
INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `tipo_categoria`) VALUES
(1, 'Alimentación', 'GASTO'),
(2, 'Vivienda', 'GASTO'),
(3, 'Transporte', 'GASTO'),
(4, 'Entretenimiento', 'GASTO'),
(5, 'Salud', 'GASTO'),
(6, 'Educación', 'GASTO'),
(7, 'Ropa y Accesorios', 'GASTO'),
(8, 'Cuidado Personal', 'GASTO'),
(9, 'Deudas y Préstamos', 'GASTO'),
(10, 'Regalos y Donaciones', 'GASTO'),
(11, 'Otros Gastos', 'GASTO'),
(12, 'Salario', 'INGRESO'),
(13, 'Ingresos Extra', 'INGRESO'),
(14, 'Inversiones', 'INGRESO'),
(15, 'Otros Ingresos', 'INGRESO');

-- 3. SUBCATEGORÍAS
-- Dependen de las categorías
INSERT INTO `subcategorias` (`id_subcategoria`, `nombre_subcategoria`, `id_categoria_subcategoria`) VALUES
(1, 'Supermercado', 1),
(2, 'Restaurantes y Cafés', 1),
(3, 'Comida a Domicilio', 1),
(4, 'Hipoteca o Alquiler', 2),
(5, 'Servicios (Agua, Luz, Gas, Internet)', 2),
(6, 'Reparaciones y Mantenimiento', 2),
(7, 'Muebles y Decoración', 2),
(8, 'Combustible', 3),
(9, 'Transporte Público (Bus, Metro)', 3),
(10, 'Taxis y Apps (Uber, Didi)', 3),
(11, 'Mantenimiento Vehículo', 3),
(12, 'Cine, Conciertos, Teatro', 4),
(13, 'Suscripciones (Netflix, Spotify)', 4),
(14, 'Hobbies y Deportes', 4),
(15, 'Viajes y Vacaciones', 4),
(16, 'Seguro Médico', 5),
(17, 'Farmacia y Medicamentos', 5),
(18, 'Consultas Médicas', 5),
(19, 'Matrícula y Pensiones', 6),
(20, 'Libros y Materiales', 6),
(21, 'Nómina', 12),
(22, 'Trabajos Freelance', 13),
(23, 'Venta de Artículos', 13),
(24, 'Rendimientos (Acciones, Fondos)', 14);

-- 4. MÉTODOS DE PAGO
-- Necesarios para el formulario de gastos/ingresos
INSERT INTO `metodos_pago` (`id_metodo`, `nombre_metodo`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta de Débito'),
(3, 'Tarjeta de Crédito'),
(4, 'Transferencia Bancaria'),
(5, 'Billetera Digital (Nequi, Daviplata)');

-- 5. ETIQUETAS (Opcional pero recomendado)
-- Para clasificar mejor las transacciones
INSERT INTO `etiquetas` (`id_etiqueta`, `nombre_etiqueta`) VALUES
(1, 'Urgente'),
(2, 'Importante'),
(3, 'Viaje'),
(4, 'Impuestos'),
(5, 'Trabajo'),
(6, 'Personal');