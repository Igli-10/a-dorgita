<?php
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $contrasinal;
    private $rol;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getContrasinal() { return $this->contrasinal; }
    public function getRol() { return $this->rol; }

    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setContrasinal($contrasinal) { $this->contrasinal = $contrasinal; }
    public function setRol($rol) { $this->rol = $rol; }
}
?>