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

    // Método para obter un usuario polo seu id
    public function obter($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bindParam(1, $id);
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

    public function contarUsuarios(){
        $stmt=$this->conexion->prepare("SELECT COUNT(*) AS total FROM usuarios");
        $stmt->execute();
        $resultado=$stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["total"];
    }

    // Gardo un token de recuperación de contrasinal para o usuario co email indicado
    public function gardarTokenRecuperacion($email, $tokenHash, $caducaEn)
    {
        try {
            $stmtUser = $this->conexion->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
            $stmtUser->execute([$email]);
            $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return false; // Usuario non existe, non hai token que gardar
            }

            $idUsuario = (int)$usuario['id'];

            // Marco como usados os tokens anteriores non consumidos
            $stmtInvalida = $this->conexion->prepare(
                "UPDATE recuperacion_contrasinal SET usado = 1 WHERE id_usuario = ? AND usado = 0"
            );
            $stmtInvalida->execute([$idUsuario]);

            $stmtToken = $this->conexion->prepare(
                "INSERT INTO recuperacion_contrasinal (id_usuario, token, caduca_en, usado) VALUES (?, ?, ?, 0)"
            );
            return $stmtToken->execute([$idUsuario, $tokenHash, $caducaEn]);
        } catch (PDOException $e) {
            error_log("Erro gardarTokenRecuperacion: " . $e->getMessage());
            return false;
        }
    }

    // Comprob se o token existe, non foi usado e non caducou
    public function validarTokenRecuperacion($tokenHash)
    {
        try {
            $sql = "SELECT id, id_usuario FROM recuperacion_contrasinal
                    WHERE token = ? AND usado = 0 AND caduca_en > datetime('now')
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$tokenHash]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro validarTokenRecuperacion: " . $e->getMessage());
            return false;
        }
    }

    // Actualizo o contrasinal dun usuario polo seu id
    public function actualizarContrasinalPorId($idUsuario, $novoHash)
    {
        try {
            $stmt = $this->conexion->prepare("UPDATE usuarios SET contrasinal = ? WHERE id = ?");
            return $stmt->execute([$novoHash, $idUsuario]);
        } catch (PDOException $e) {
            error_log("Erro actualizarContrasinalPorId: " . $e->getMessage());
            return false;
        }
    }

    // Marco o token como usado para que non poida reutilizarse
    public function marcarTokenComoUsado($idToken)
    {
        try {
            $stmt = $this->conexion->prepare("UPDATE recuperacion_contrasinal SET usado = 1 WHERE id = ?");
            return $stmt->execute([$idToken]);
        } catch (PDOException $e) {
            error_log("Erro marcarTokenComoUsado: " . $e->getMessage());
            return false;
        }
    }
}
