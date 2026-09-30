-- ============================================================
-- Base de datos: andi_cardenas
-- Proyecto: App de agendamiento de citas - Manicure Andi Cárdenas
-- Este archivo recrea toda la base de datos desde cero.
-- Si algo se daña, solo hay que abrir este archivo en MySQL
-- Workbench (conectada a "mysql server", la de XAMPP) y
-- ejecutarlo completo con Ctrl+Shift+Enter.
-- ============================================================

USE andi_cardenas;
DROP TABLE IF EXISTS citas;
DROP TABLE IF EXISTS servicios;
DROP TABLE IF EXISTS clientes;

CREATE DATABASE IF NOT EXISTS andi_cardenas;
USE andi_cardenas;

-- ---------- TABLAS ----------

CREATE TABLE IF NOT EXISTS clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    recomendado_para VARCHAR(150),
    tiempo_estimado_minutos INT NOT NULL,
    duracion_resultado VARCHAR(50),
    valor DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_servicio INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio)
);

-- ---------- DATOS: CLIENTES ----------

INSERT INTO clientes (nombre, apellido, telefono) VALUES ('María', 'Gómez', '3001234567');
INSERT INTO clientes (nombre, apellido, telefono) VALUES ('Laura', 'Pérez', '3109876543');
INSERT INTO clientes (nombre, apellido, telefono) VALUES ('lina', 'cardenas', '3188255052');
INSERT INTO clientes (nombre, apellido, telefono) VALUES ('melissa', 'morales', '3158403301');
INSERT INTO clientes (nombre, apellido, telefono) VALUES ('marta', 'carrillo', '3203014048');
INSERT INTO clientes (nombre, apellido, telefono) VALUES ('ligia', 'Gonzalez', '3157432301');
INSERT INTO clientes (nombre, apellido, telefono) VALUES ('gesenia', 'castañeda', '3175413082');
INSERT INTO clientes (nombre, apellido, telefono) VALUES ('Valentina', 'Suárez', '3112345678');

-- ---------- DATOS: SERVICIOS (incluye retiros, numerados 1-12) ----------

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, valor)
VALUES ('Retiro de semipermanente', 'Retiro cuidadoso del esmaltado semipermanente mediante un procedimiento especial.', 'Para retirar el servicio anterior antes de realizar un nuevo servicio.', 45, 12000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, valor)
VALUES ('Retiro de Soft Gel', 'Retiro cuidadoso del sistema Soft Gel, eliminando el producto de manera adecuada.', 'Para quienes desean retirar el sistema antes de realizar un nuevo servicio o dejar sus uñas naturales.', 60, 15000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, valor)
VALUES ('Retiro de Polygel', 'Retiro cuidadoso del sistema Polygel mediante el procedimiento correspondiente.', 'Para quienes desean retirar el sistema antes de realizar un nuevo servicio o dejar sus uñas naturales.', 60, 15000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, valor)
VALUES ('Retiro de acrílico', 'Retiro cuidadoso del sistema acrílico mediante el procedimiento correspondiente.', 'Para quienes desean retirar el sistema antes de realizar un nuevo servicio o realizar un nuevo procedimiento.', 80, 18000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Manicura convencional', 'Manicura realizada sobre la uña natural con esmaltado tradicional.', 'Para quienes buscan un servicio sencillo y un cambio de color sin utilizar sistemas de larga duración.', 80, 'Depende del cuidado de la clienta', 20000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Pedicura convencional', 'Pedicura realizada con esmaltado tradicional.', 'Para quienes buscan un servicio sencillo para mantener y embellecer las uñas de los pies.', 80, 'Depende del cuidado de la clienta', 20000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Manicure semipermanente', 'Esmaltado de larga duración con secado en cabina UV', 'Uñas débiles o quebradizas', 45, '3 semanas', 35000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Manicure clásica', 'Esmaltado tradicional', 'Cualquier tipo de uña', 30, '1 semana', 20000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Semipermanente', 'Esmaltado realizado sobre la uña natural.', 'Para quienes desean mantener sus uñas cuidadas por más tiempo.', 90, '20 dias', 40000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Sotf Gel', 'Extensión realizada con uñas de gel sobre la uña natural.', 'Para personas que desean tener las uñas más largas.', 150, '20 dias', 65000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Builder Gel', 'Se trabaja sobre el largo de la uña natural, aportando firmeza y grosor.', 'Para personas que desean mantener el largo de sus uñas naturales.', 150, '20 dias', 55000);

INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor)
VALUES ('Recubrimiento en Polygel', 'Se trabaja sobre la uña natural, aprovechando un largo aproximado de número 2.', 'Para personas que ya tienen un largo natural.', 150, '20 dias', 65000);

-- ---------- DATOS: CITAS ----------

INSERT INTO citas (id_cliente, id_servicio, fecha, hora, estado) VALUES (1, 1, '2026-09-05', '09:00:00', 'confirmada');
INSERT INTO citas (id_cliente, id_servicio, fecha, hora, estado) VALUES (2, 2, '2026-09-05', '11:30:00', 'pendiente');
INSERT INTO citas (id_cliente, id_servicio, fecha, hora, estado) VALUES (3, 7, '2026-09-06', '14:00:00', 'pendiente');
INSERT INTO citas (id_cliente, id_servicio, fecha, hora, estado) VALUES (4, 10, '2026-09-06', '16:00:00', 'confirmada');
INSERT INTO citas (id_cliente, id_servicio, fecha, hora, estado) VALUES (5, 1, '2026-09-07', '10:00:00', 'pendiente');
INSERT INTO citas (id_cliente, id_servicio, fecha, hora, estado) VALUES (6, 4, '2026-09-08', '15:00:00', 'confirmada');
