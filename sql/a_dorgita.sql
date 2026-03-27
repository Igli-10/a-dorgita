CREATE DATABASE IF NOT EXISTS a_dorgita;
USE a_dorgita;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descripcion VARCHAR(500)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasinal VARCHAR(250) NOT NULL,
    rol ENUM('admin', 'cliente')
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descripcion VARCHAR(500),
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    imagen VARCHAR(200),
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'pendente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE detalles_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    cantidade INT NOT NULL,
    prezo_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

CREATE TABLE IF NOT EXISTS recuperacion_contrasinal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    caduca_en DATETIME NOT NULL,
    usado TINYINT(1) NOT NULL DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_usuario (id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS favoritos (
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);
