<?php
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $contrasinal;
    private $rol;
    private $foto_perfil; 

    // Getters
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getContrasinal() { return $this->contrasinal; }
    public function getRol() { return $this->rol; }
    
    
    public function getFotoPerfil() { 
        return $this->foto_perfil ?? 'default.png'; 
    }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setContrasinal($contrasinal) { $this->contrasinal = $contrasinal; }
    public function setRol($rol) { $this->rol = $rol; }
    
    public function setFotoPerfil($foto_perfil) { 
        $this->foto_perfil = $foto_perfil; 
    }
}
?>