-- Tabla de Sucursales
CREATE TABLE sucursales (
    id_sucursal INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    correo VARCHAR(100) NOT NULL
);

--Inserción tabla sucursal
INSERT INTO sucursales (id_sucursal, nombre, codigo, correo) VALUES
(1, 'Central', '001', 'central@librepraesto.com'),
(2, 'Este', '002', 'este@librepraesto.com');

-- Tabla de Usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    sucursal_id INT,
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id_sucursal)
);

--inserción tabla usuarios
INSERT INTO usuarios (id_usuario, nombre_usuario, correo, contraseña, estado, sucursal_id) VALUES
(1, 'Leonor', 'leonor@gmail.com', '$2y$10$tCbmtTH1Gl8uXJHaycXzd.2tRG89fY.XlucPmsvZRqc0nj10dymxK', 'activo', 2),
(2, 'Ana', 'ana@gmail.com', '$2y$10$DevD9z.0y7xEnYKSGomrGOIyOpBUIp/fMVk5mcOkHp61zLj1iyHxa', 'activo', 1);

-- Tabla de Clientes
CREATE TABLE clientes (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `cip` varchar(20) NOT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sucursal_id` int DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `cip` (`cip`),
  KEY `sucursal_id` (`sucursal_id`)
);

--inserción tabla clientes
INSERT INTO clientes (id_cliente, cip, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento, sucursal_id, usuario, correo, contraseña) VALUES
(1, '8-1004-2420', 'Leonor', 'Alicia', 'Pérez', 'Mendoza', '2004-01-29', 1, 'LeonorP', 'leonor@gmail.com', '$2y$10$a8pOO.jMwugKrUKWHLgc9ugknGgLJoam88JW4zyzBNzlwZMENNV9K'),
(2, '8-789-856', 'Ana', 'Michelle', 'Pérez', 'Mendoza', '2002-08-08', 1, 'AnaP', 'ana@gmail.com', '$2y$10$2vqfnCxddGhQamk5QbbdOO.5Fw9hlGeY3982na3mFoyohozsVAU.m'),
(3, '8-7849-8781', 'María', 'Michelle', 'Sanchez', 'Mendoza', '2004-08-10', 2, 'MaraS', 'ana@gmail.com', '$2y$10$WpliZCL7nJTOR3Ykp77mT.sVoxby439OhpaU4cLqPsxtQRiKMcVAm'),
(4, '8-9745-8975', 'Pedro', 'Pablo', 'Sanchez', 'Gomez', '1998-10-24', 2, 'PedroS', 'pedro@gmail.com', '$2y$10$NgebD3Lc4qqjRMcw2szgI.GQS3kENp1yTKPIjgy1Vg5b6MNnoEJhG'),
(5, '8-7899-8754', 'Alma', 'Marta', 'Ramirez', 'Lomelí', '2009-01-08', 1, 'AlmaR', 'alma@gmail.com', '$2y$10$6wVTtPqDfSCjdmWfUx//puG7Liy3Xo39XrRFPT6zfkquqp1gBbdE.');



-- Tabla de Libros
CREATE TABLE libros (
    id_libro INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    autor VARCHAR(255) NOT NULL,
    categoria ENUM('fantasia', 'terror', 'novela', 'no-ficcion') NOT NULL,
    cantidad_disponible INT DEFAULT 0,
    precio DECIMAL(10,2) NOT NULL,
    imagen_thumbnail VARCHAR(255),
    imagen_original VARCHAR(255),
    imagen_formato VARCHAR(50),
    imagen_ubicacion VARCHAR(255) 
);

--insercion tabla libros
INSERT INTO libros (id_libro, codigo, nombre, descripcion, autor, categoria, cantidad_disponible, precio, imagen_thumbnail, imagen_original, imagen_formato, imagen_ubicacion)
VALUES
-- Gabriel García Márquez (Colombia) - Categoría: novela
(1, 'GM01', 'Cien años de soledad', 'Historia épica de la familia Buendía en Macondo', 'Gabriel García Márquez', 'novela', 20, 15.99, 'cien anos de soledad.jpg', 'cien anos de soledad.jpg', 'jpg', '../../Public/Imagenes/Gabriel/'),
(2, 'GM02', 'El amor en los tiempos del cólera', 'Historia de un amor que desafía el tiempo y las circunstancias', 'Gabriel García Márquez', 'novela', 18, 14.99, 'el amor en tiempos de colera.jpeg', 'el amor en tiempos de colera.jpeg', 'jpeg', '../../Public/Imagenes/Gabriel/'),
(3, 'GM03', 'El coronel no tiene quien le escriba', 'Relato sobre la pobreza y la dignidad en tiempos de guerra', 'Gabriel García Márquez', 'novela', 15, 12.49, 'el coronel no tiene.jpg', 'el coronel no tiene.jpg', 'jpg', '../../Public/Imagenes/Gabriel/'),
(4, 'GM04', 'Crónica de una muerte anunciada', 'Relato de un asesinato anunciado y sus consecuencias en la comunidad', 'Gabriel García Márquez', 'novela', 17, 11.99, 'cronicas de una muerte.jpeg', 'cronicas de una muerte.jpeg', 'jpeg', '../../Public/Imagenes/Gabriel/'),
(5, 'GM05', 'Los funerales de la Mamá Grande', 'Colección de cuentos sobre el realismo mágico en Latinoamérica', 'Gabriel García Márquez', 'novela', 12, 9.99, 'los funerales de la mama.jpeg', 'los funerales de la mama.jpeg', 'jpeg', '../../Public/Imagenes/Gabriel/'),
(6, 'GM06', 'Ojos de perro azul', 'Colección de cuentos que exploran el amor y la soledad', 'Gabriel García Márquez', 'novela', 14, 8.99, 'ojos de perro azul.jpg', 'ojos de perro azul.jpg', 'jpg', '../../Public/Imagenes/Gabriel/'),
(7, 'GM07', 'La hojarasca', 'Historia sobre el conflicto generacional y la sociedad', 'Gabriel García Márquez', 'novela', 10, 10.49, 'la hojarsca.jpg', 'la hojarsca.jpg', 'jpg', '../../Public/Imagenes/Gabriel/'),
(8, 'GM08', 'Relatos sobrenaturales', 'Historias que exploran lo fantástico y lo mágico', 'Gabriel García Márquez', 'novela', 9, 12.49, 'relatos sobrenaturales.jpeg', 'relatos sobrenaturales.jpeg', 'jpeg', '../../Public/Imagenes/Gabriel/'),
(9, 'GM09', 'El otoño del patriarca', 'Reflexión sobre el poder absoluto y la soledad', 'Gabriel García Márquez', 'novela', 11, 14.99, 'el otono del patrarca.jpeg', 'el otono del patrarca.jpeg', 'jpeg', '../../Public/Imagenes/Gabriel/'),
(10, 'GM10', 'Memoria de mis putas tristes', 'Relato sobre el amor y la soledad en la vejez', 'Gabriel García Márquez', 'novela', 13, 13.49, 'memorias de mi.jpg', 'memorias de mi.jpg', 'jpg', '../../Public/Imagenes/Gabriel/');




INSERT INTO libros (id_libro, codigo, nombre, descripcion, autor, categoria, cantidad_disponible, precio, imagen_thumbnail, imagen_original, imagen_formato, imagen_ubicacion)
VALUES
(11, 'IA01', 'La casa de los espíritus', 'Relato que combina realismo mágico y la historia de una familia chilena', 'Isabel Allende', 'novela', 25, 16.99, 'casa_de_espiritus_thumbnail.jpg', 'casa de los espiritus.jpg', 'jpg', '../../Public/Imagenes/Isabel/'),
(12, 'IA02', 'De amor y de sombra', 'Historia de amor ambientada en el contexto de la represión política en Chile', 'Isabel Allende', 'novela', 20, 14.99, 'de_amor_sombra_thumbnail.jpg', 'de amor y de sombra.jpg', 'jpg', '../../Public/Imagenes/Isabel/'),
(13, 'IA03', 'Eva Luna', 'Relato de una joven que cuenta historias y vive la transformación social de su país', 'Isabel Allende', 'novela', 18, 13.99, 'eva_luna_thumbnail.jpg', 'eva luna.jpg', 'jpg', '../../Public/Imagenes/Isabel/'),
(14, 'IA04', 'Paula', 'Memorias personales narradas como una carta a su hija', 'Isabel Allende', 'no-ficcion', 22, 15.49, 'paula_thumbnail.jpg', 'paula.jpg', 'jpg', '../../Public/Imagenes/Isabel/'),
(15, 'IA05', 'Afrodita', 'Una mezcla de recetas y relatos sobre el amor y la comida', 'Isabel Allende', 'no-ficcion', 15, 12.49, 'afrodita_thumbnail.jpg', 'afrodita.jpg', 'jpg', '../../Public/Imagenes/Isabel/');



INSERT INTO libros (id_libro, codigo, nombre, descripcion, autor, categoria, cantidad_disponible, precio, imagen_thumbnail, imagen_original, imagen_formato, imagen_ubicacion)
VALUES
(16, 'MV01', 'La ciudad y los perros', 'Relato sobre la vida de unos cadetes en una escuela militar, explorando la violencia y la amistad', 'Mario Vargas Llosa', 'novela', 30, 17.99, 'la_ciudad_y_los_perros_thumbnail.jpg', 'la ciudad y los perros.jpeg', 'jpeg', '../../Public/Imagenes/Mario/'),
(17, 'MV02', 'La casa verde', 'Historia que mezcla el realismo social y lo mítico en la selva peruana', 'Mario Vargas Llosa', 'novela', 28, 16.49, 'la_casa_verde_thumbnail.jpg', 'la casa verde.jpeg', 'jpeg', '../../Public/Imagenes/Mario/'),
(18, 'MV03', 'Conversación en la catedral', 'Reflexión sobre la corrupción y la situación política de Perú en los años 50', 'Mario Vargas Llosa', 'novela', 22, 15.49, 'conversacion_en_la_catedral_thumbnail.jpg', 'conversacion en la catedral.jpg', 'jpg', '../../Public/Imagenes/Mario/'),
(19, 'MV04', 'Pantaleón y las visitadoras', 'Historia humorística sobre la corrupción y la moral en el ejército peruano', 'Mario Vargas Llosa', 'novela', 25, 14.99, 'pantaleon_y_las_visitadoras_thumbnail.jpg', 'pantaleon y las visitadoras.jpg', 'jpg', '../../Public/Imagenes/Mario/'),
(20, 'MV05', 'La tía Tula', 'Relato sobre una mujer que se convierte en figura materna para su familia', 'Mario Vargas Llosa', 'novela', 18, 13.99, 'la_tia_tula_thumbnail.jpg', 'la tia Julia y el escribidor.jpg', 'jpg', '../../Public/Imagenes/Mario/'),
(21, 'MV06', 'La guerra del fin del mundo', 'Narración sobre la guerra en Canudos en Brasil y su relación con las ideologías', 'Mario Vargas Llosa', 'novela', 20, 18.49, 'la_guerra_del_fin_del_mundo_thumbnail.jpg', 'la guerra del fin del mundo.jpg', 'jpg', '../../Public/Imagenes/Mario/'),
(22, 'MV07', 'El pez en el agua', 'Memorias de Vargas Llosa sobre su vida, su carrera política y su compromiso con la democracia', 'Mario Vargas Llosa', 'no-ficcion', 15, 12.99, 'el_pez_en_el_agua_thumbnail.jpg', 'El pez en el agua.jpg', 'jpg', '../../Public/Imagenes/Mario/'),
(23, 'MV08', 'Lituma en los Andes', 'Historia sobre la vida de un soldado peruano en la época de terrorismo en los Andes', 'Mario Vargas Llosa', 'novela', 17, 14.99, 'lituma_en_los_andes_thumbnail.jpg', 'Lituma en los andes.jpg', 'jpg', '../../Public/Imagenes/Mario/'),
(24, 'MV09', 'La fiesta del chivo', 'Relato sobre la dictadura de Rafael Trujillo en la República Dominicana', 'Mario Vargas Llosa', 'novela', 16, 13.49, 'la_fiesta_del_chivo_thumbnail.jpg', 'La_fiesta_del_chivo.jpg', 'jpg', '../../Public/Imagenes/Mario/'),
(25, 'MV10', 'El sueño del celta', 'Relato histórico sobre la vida del aventurero Roger Casement en el Congo y la Amazonía', 'Mario Vargas Llosa', 'novela', 14, 15.99, 'el_sueno_del_celta_thumbnail.jpg', 'El sueno del celta.jpg', 'jpg', '../../Public/Imagenes/Mario/');




INSERT INTO libros (id_libro, codigo, nombre, descripcion, autor, categoria, cantidad_disponible, precio, imagen_thumbnail, imagen_original, imagen_formato, imagen_ubicacion)
VALUES
(26, 'CRZ01', 'La sombra del viento', 'Un joven descubre un libro en un lugar misterioso y se ve envuelto en una trama literaria fascinante', 'Carlos Ruiz Zafón', 'novela', 20, 18.99, 'la_sombra_del_viento_thumbnail.jpg', 'La sombra del viento.jpg', 'jpg', '../../Public/Imagenes/Carlos/'),
(27, 'CRZ02', 'El juego del ángel', 'Relato que sigue a un escritor que se ve envuelto en oscuros secretos literarios en la Barcelona de los años 1920', 'Carlos Ruiz Zafón', 'novela', 18, 16.99, 'el_juego_del_angel_thumbnail.jpg', 'El juego del angel.jpg', 'jpg', '../../Public/Imagenes/Carlos/'),
(28, 'CRZ03', 'El prisionero del cielo', 'Secuela de "La sombra del viento", que profundiza en la historia de los personajes de la saga', 'Carlos Ruiz Zafón', 'novela', 22, 17.49, 'el_prisionero_del_cielo_thumbnail.jpg', 'El prisionero del cielo.jpg', 'jpg', '../../Public/Imagenes/Carlos/'),
(29, 'CRZ04', 'El laberinto de los espíritus', 'La cuarta entrega de la saga de Zafón que cierra la historia de la familia Sempere', 'Carlos Ruiz Zafón', 'novela', 25, 19.99, 'el_laberinto_de_los_espiritus_thumbnail.jpg', 'El laberinto de los espiritus.jpg', 'jpg', '../../Public/Imagenes/Carlos/'),
(30, 'CRZ06', 'Marina', 'Narrativa de un romance misterioso y trágico en la Barcelona de los años 1980', 'Carlos Ruiz Zafón', 'novela', 20, 15.99, 'marina_thumbnail.jpg', 'Marina.jpg', 'jpg', '../../Public/Imagenes/Carlos/');



INSERT INTO libros (id_libro, codigo, nombre, descripcion, autor, categoria, cantidad_disponible, precio, imagen_thumbnail, imagen_original, imagen_formato, imagen_ubicacion)
VALUES
(31, 'PC01', 'El alquimista', 'Historia de un joven pastor que busca un tesoro en el desierto, siguiendo su leyenda personal', 'Paulo Coelho', 'novela', 30, 12.99, 'el_alquimista_thumbnail.jpg', 'El alquimista.jpg', 'jpg', '../../Public/Imagenes/Paulo/'),
(32, 'PC02', 'El peregrino de Compostela', 'Relato sobre el camino hacia Santiago y las lecciones de vida que se aprenden en el viaje', 'Paulo Coelho', 'novela', 25, 14.99, 'el_peregrino_de_compostela_thumbnail.jpg', 'El peregrino de Compostela.jpg', 'jpg', '../../Public/Imagenes/Paulo/'),
(33, 'PC03', 'Brida', 'Narrativa de una joven que busca su destino y su conexión con la magia y la espiritualidad', 'Paulo Coelho', 'novela', 18, 13.99, 'brida_thumbnail.jpg', 'Brida.jpg', 'jpg', '../../Public/Imagenes/Paulo/'),
(34, 'PC04', 'Verónika decide morir', 'Relato sobre una joven que decide suicidarse pero acaba encontrando una nueva perspectiva de vida', 'Paulo Coelho', 'novela', 20, 15.49, 'veronika_decide_morir_thumbnail.jpg', 'Veronika decide morir.jpg', 'jpg', '../../Public/Imagenes/Paulo/'),
(35, 'PC05', 'El Maktub', 'Un conjunto de reflexiones y enseñanzas para vivir de manera plena y espiritual', 'Paulo Coelho', 'novela', 22, 11.99, 'el_maktub_thumbnail.jpg', 'El Maktub.jpg', 'jpg', '../../Public/Imagenes/Paulo/');

-- Tabla de Inventario
CREATE TABLE inventario (
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    id_libro INT NOT NULL,
    sucursal_id INT NOT NULL,
    cantidad_disponible INT DEFAULT 0,
    estado ENUM('Normal', 'Bajo'), 
    FOREIGN KEY (id_libro) REFERENCES libros(id_libro),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id_sucursal)
);

--insercion tabla inventario
INSERT INTO inventario (id_libro, sucursal_id, cantidad_disponible) VALUES
(1, 1, 50),
(2, 1, 30),
(3, 2, 20),
(4, 2, 10),
(5, 1, 100);

--trigger de  actualizar estado
DELIMITER $$

CREATE TRIGGER actualizar_estado_inventario
BEFORE UPDATE ON inventario
FOR EACH ROW
BEGIN
    IF NEW.cantidad_disponible < 10 THEN
        SET NEW.estado = 'Bajo';
    ELSE
        SET NEW.estado = 'Normal';
    END IF;
END$$

DELIMITER ;

--trigger de insercion estado
DELIMITER $$

CREATE TRIGGER actualizar_estado_inventario_insert
BEFORE INSERT ON inventario
FOR EACH ROW
BEGIN
    IF NEW.cantidad_disponible < 10 THEN
        SET NEW.estado = 'Bajo';
    ELSE
        SET NEW.estado = 'Normal';
    END IF;
END$$

DELIMITER ;

-- Tabla de Ventas
CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    libro_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
    cantidad INT NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (libro_id) REFERENCES libros(id_libro),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id_sucursal)
);

--Insercion tabla ventas
INSERT INTO ventas (cliente_id, libro_id, sucursal_id, fecha_venta, cantidad) VALUES
(1, 1, 1, '2024-11-01 10:00:00', 2),
(2, 3, 2, '2024-11-02 15:30:00', 1),
(3, 4, 2, '2024-11-03 18:45:00', 3),
(1, 2, 1, '2024-11-04 12:00:00', 1),
(4, 5, 1, '2024-11-05 14:20:00', 5);


-- tabla de compras
CREATE TABLE compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    libro_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    cantidad INT NOT NULL,
    precio_total DECIMAL(10, 2) NOT NULL, 
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (libro_id) REFERENCES libros(id_libro),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id_sucursal)
);

--Insercion tabla compras
INSERT INTO compras (cliente_id, libro_id, sucursal_id, fecha_compra, cantidad, precio_total) VALUES
(1, 1, 1, '2024-11-01 10:00:00', 2, 39.98),
(2, 3, 2, '2024-11-02 15:30:00', 1, 19.99),
(3, 4, 2, '2024-11-03 18:45:00', 3, 59.97),
(1, 2, 1, '2024-11-04 12:00:00', 1, 14.99),
(4, 5, 1, '2024-11-05 14:20:00', 5, 124.95);


-- Tabla de Solicitudes
CREATE TABLE solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre_libro VARCHAR(255) NOT NULL,
    autor VARCHAR(255),
    categoria ENUM('fantasia', 'terror', 'novela', 'no-ficcion'),
    estado_solicitud ENUM('pendiente', 'procesada') DEFAULT 'pendiente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente)
);

--Insercion tabla solicitudes
INSERT INTO solicitudes (cliente_id, nombre_libro, autor, categoria, estado_solicitud) VALUES
(1, 'Cien años de soledad', 'Gabriel García Márquez', 'novela', 'pendiente'),
(2, 'El amor en los tiempos del cólera', 'Gabriel García Márquez', 'fantasia', 'procesada'),
(3, 'El coronel no tiene quien le escriba', 'Gabriel García Márquez', 'terror', 'pendiente'),
(4, 'Crónica de una muerte anunciada', 'Gabriel García Márquez', 'no-ficcion', 'pendiente'),
(5, 'Los funerales de la Mamá Grande', 'Gabriel García Márquez', 'novela', 'procesada');

--tabla de autores
CREATE TABLE autor(
    cod_autor INT PRIMARY KEY NOT NULL, 
    au_nombre VARCHAR(25), 
    au_apellido VARCHAR(25),
    imagen_thumbnail VARCHAR(255),
    imagen_original VARCHAR(255),
    imagen_formato VARCHAR(50),
    imagen_ubicacion VARCHAR(255)
);

-- Insertar datos en la tabla autor
INSERT INTO autor (cod_autor, au_nombre, au_apellido, imagen_thumbnail, imagen_original, imagen_formato, imagen_ubicacion)
VALUES
(1, 'Gabriel', 'García Márquez', 'Gabriel_Garcia.jpeg', 'Gabriel_Garcia.jpeg', 'jpeg', '../../Public/Imagenes/Gabriel/'),
(2, 'Isabel', 'Allende', 'Isabel-Allende.webp', 'Isabel-Allende.webp', 'webp', '../../Public/Imagenes/Isabel/'),
(3, 'Mario', 'Vargas Llosa', 'Mario-Vargas-Llosa.webp', 'Mario-Vargas-Llosa.webp', 'webp', '../../Public/Imagenes/Mario/'),
(4, 'Carlos', 'Ruiz Zafón', 'Carlos_Ruiz_Zafon.jpeg', 'Carlos_Ruiz_Zafon.jpeg', 'jpeg', '../../Public/Imagenes/Carlos/'),
(5, 'Paulo', 'Coelho', 'Paulo-Coelho.jpg', 'Paulo-Coelho.jpg', 'jpg', '../../Public/Imagenes/Paulo/');