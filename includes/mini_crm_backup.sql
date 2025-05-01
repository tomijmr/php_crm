
-- Backup de Base de Datos para Mini CRM
-- Fecha: 2025-05-01

CREATE DATABASE IF NOT EXISTS mini_crm;
USE mini_crm;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL
);

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL
);

-- Detalles de ventas
CREATE TABLE IF NOT EXISTS ventas_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Insertar usuario por defecto (clave: admin123)
INSERT INTO usuarios (nombre, email, password) VALUES
('Administrador', 'admin@crm.com', '$2y$10$EXAMPLEHASHFORPASSWORDadmin123');

-- Insertar productos de ejemplo
INSERT INTO productos (codigo, nombre, precio, stock) VALUES
('P001', 'Producto A', 100.00, 50),
('P002', 'Producto B', 200.00, 30),
('P003', 'Producto C', 150.00, 20);
