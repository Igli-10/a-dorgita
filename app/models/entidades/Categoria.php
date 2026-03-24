<?php
class Categoria{
    private $id;
    private $nome;
    private $descripcion;

    public function getId(){ return $this->id;}
    public function getNome(){ return $this->nome;}
    public function getDescripcion() { return $this->descripcion; }

    public function setId($id){ return $this->id=$id;}
    public function setNome($nome) { return $this->nome=$nome;}
    public function setDescripcion($descripcion){ return $this->descripcion=$descripcion;}
}


?>