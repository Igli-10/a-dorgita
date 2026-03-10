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

CREATE TABLE produtos (
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

