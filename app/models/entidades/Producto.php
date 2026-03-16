<?php
class Producto {
    private $id;
    private $nome;
    private $descripcion;
    private $precio;
    private $stock;
    private $imagen;
    private $id_categoria;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getStock() { return $this->stock; }
    public function getImagen() { return $this->imagen; }
    public function getIdCategoria() { return $this->id_categoria; }

    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setStock($stock) { $this->stock = $stock; }
    public function setImagen($imagen) { $this->imagen = $imagen; }
    public function setIdCategoria($id_categoria) { $this->id_categoria = $id_categoria; }
}