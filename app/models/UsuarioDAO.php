<?php
require_once __DIR__ . "/entidades/Usuario.php";
require_once __DIR__ . "/../../config/database.php";

class UsuarioDAO
{
    private $conexion;

    //Establecese a conexión a base de datos
    public function __construct()
    {
        try {
            $this->conexion = Database::connect();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Método para buscar un usuario polo seu correo electrónico 
    public function obterPorEmail($email)
    {

        $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE email = ?");

        // Vinculamos o valor da variable $email ao primeiro marcador (?) de forma segura
        $stmt->bindParam(1, $email);

        // Indicámoslle a PDO que transforme os resultados directamente nun obxecto da clase 'Usuario'
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Usuario');

        $stmt->execute();

        return $stmt->fetch() ?: null;
    }

    // Método para rexistrar un novo usuario na base de datos
    public function crear($nome, $email, $contrasinal, $rol)
    {
        try {

            $stmt = $this->conexion->prepare("INSERT INTO usuarios (nome, email, contrasinal, rol) VALUES (?, ?, ?, ?)");

            // Vinculamos cada variable ao seu respectivo marcador pola súa posición de esquerda a dereita
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $contrasinal);
            $stmt->bindParam(4, $rol);


            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro a hora de rexistrar un usuario " . $e->getMessage());
            return false;
        }
    }

    public function actualizarRol($id, $rol)
    {
        try {
            $stmt = $this->conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
            return $stmt->execute(array($rol, $id));
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listar()
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM usuarios ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, "Usuario");
        } catch (Exception $e) {
            return [];
        }
    }

    public function borrar($id)
    {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM usuarios WHERE id=?");
            return $stmt->execute(array($id));
        } catch (PDOException $e) {
            return false;
        }
    }

    // Busco usuarios polo seu nome ou email
    public function buscar($termo)
    {
        try {
            $like = "%" . $termo . "%";
            $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE nome LIKE ? OR email LIKE ? ORDER BY id DESC");
            $stmt->execute([$like, $like]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, "Usuario");
        } catch (Exception $e) {
            return [];
        }
    }
}
