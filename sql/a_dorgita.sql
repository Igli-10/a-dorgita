CREATE DATABASE IF NOT EXISTS a_dorgita;
USE a_dorgita;

CREATE TABLE categorias (
    id int auto_increment primary key,
    nome varchar(50) not null,
    descripcion varchar(500)
);

CREATE TABLE usuarios(
    id int auto_increment primary key,
    nome varchar(50) not null,
    email varchar(100) not null,
    contrasinal varchar (250) not null,
    rol enum("admin","cliente")
    
);

CREATE TABLE productos (
    id int auto_increment primary key,
    nome varchar(100) not null,
    descripcion varchar(500),
    precio decimal(10,2) not null,
    stock int default 0, 
    imagen varchar (200),
    id_categoria int,
    foreign key (id_categoria) references categorias(id)
                                on delete set null
);

CREATE TABLE pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado TEXT DEFAULT 'pendente', -- pendente, enviado, entregado
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE detalles_pedido (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_pedido INTEGER,
    id_produto INTEGER,
    cantidade INTEGER NOT NULL,
    prezo_unitario DECIMAL(10,2) NOT NULL, 
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES productos(id)
);
