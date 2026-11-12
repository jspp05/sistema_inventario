CREATE DATABASE IF NOT EXISTS inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE inventario;

DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias;

CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL
);

CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  precio DECIMAL(12,2) NOT NULL CHECK (precio > 0),
  estado ENUM('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  cantidad INT NOT NULL DEFAULT 0 CHECK (cantidad >= 0),
  categoria_id INT NOT NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

INSERT INTO categorias (nombre) VALUES ('Tecnología'), ('Hogar'), ('Moda');

INSERT INTO productos (nombre, precio, estado, cantidad, categoria_id) VALUES
('Laptop HP Pavillion', 3200000, 'Activo', 10, 1),
('Televisor Samsung 55', 2100000, 'Activo', 5, 1),
('Licuadora Oster', 180000, 'Inactivo', 0, 2),
('Camisa manga larga', 75000, 'Activo', 40, 3),
('Audífonos Bluetooth', 120000, 'Activo', 4, 1);
