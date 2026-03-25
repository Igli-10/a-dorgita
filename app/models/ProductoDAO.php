<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/entidades/Producto.php';

class ProductoDAO
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

    //Metodo para obter os productos da táboa 
    public function listar()
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM productos");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Producto');
        } catch (Exception $e) {
            die("Erro o listar os produtos" . $e->getMessage());
        }
    }

    //Método para obter un producto polo seu id
    public function obter($id)
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute(array($id));
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Producto'); // Recupera unha fila da base de datos e convírtea en obxecto produto
            return $stmt->fetch();
        } catch (Exception $e) {
            die("Erro o ver detalles do produto" . $e->getMessage());
        }
    }

    //Método para filtrar un producto por categoría e prezo
    public function filtrar($id_cat = null, $max_prezo = null)
    {
        try {
            $sql = "SELECT * FROM productos";
            $condicions = []; // Aquí gardo os anacos do WHERE 
            $params = [];     // Aquí gardo os valores para o execute

            // 1. Comprobo se hai categoría
            if ($id_cat !== null && $id_cat !== '') {
                $condicions[] = "id_categoria = ?";
                $params[] = $id_cat;
            }

            // 2. Comprobo se hai prezo
            if ($max_prezo !== null && $max_prezo !== '') {
                $condicions[] = "precio <= ?";
                $params[] = $max_prezo;
            }

            // 3. Só engado o WHERE se realmente hai algunha condición
            if (count($condicions) > 0) {
                // Implode xunta o array cun " AND " entre cada elemento
                $sql .= " WHERE " . implode(" AND ", $condicions);
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_CLASS, "Producto");
        } catch (Exception $e) {
            die("Erro ao filtrar: " . $e->getMessage());
        }
    }

    public function consultarStock($id)
    {
        try {
            $stmt = $this->conexion->prepare("SELECT stock FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? (int)$resultado['stock'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function descontarStock($id, $cantidade)
    {
        try {
            $stmt = $this->conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");
            return $stmt->execute([$cantidade, $id, $cantidade]);
        } catch (PDOException $e) {
            return false;
        }
    }

    //Busca produtos na base de datos que coincidan co mensaxe proporcionado.
    //Mira tanto no nome como na descripción do produto.
    public function buscar($mensaxe)
    {
        try {

            $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE nome LIKE :mensaxe OR descripcion LIKE :mensaxe");

            // Engado os comodíns % para buscar coincidencias parciais
            $mensaxe_busca = "%" . $mensaxe . "%";
            $stmt->bindParam(":mensaxe", $mensaxe_busca);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS, "Producto");
        } catch (PDOException $e) {
            return [];
        }
    }

    // Busca coincidencias rápidas para o autocompletado 
    public function suxerir($mensaxe)
    {
        try {
            // Busco por nome, descrición e imaxe, e limito a 5 resultados
            $stmt = $this->conexion->prepare("SELECT id, nome, descripcion, imagen FROM productos WHERE nome LIKE :mensaxe OR descripcion LIKE :mensaxe LIMIT 5");

            $mensaxe_busca = "%" . $mensaxe . "%";

            $stmt->bindParam(':mensaxe', $mensaxe_busca);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    //Borrar un produto
    public function borrar($id)
    {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM productos WHERE id=?");
            return $stmt->execute(array($id));
        } catch (PDOException $e) {
            return false;
        }
    }


    // Gardar un novo produto (incluíndo a id_categoria)
    public function gardar($nome, $descripcion, $precio, $stock, $imagen, $id_categoria)
    {
        try {
            $stmt = $this->conexion->prepare("INSERT INTO productos (nome, descripcion, precio, stock, imagen, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute(array($nome, $descripcion, $precio, $stock, $imagen, $id_categoria));
        } catch (PDOException $e) {
            return false;
        }
    }

    // Actualizar produto existente
    public function actualizar($id, $nome, $descripcion, $precio, $stock, $imagen, $id_categoria)
    {
        try {
            $stmt = $this->conexion->prepare("UPDATE productos SET nome = ?, descripcion = ?, precio = ?, stock = ?, imagen = ?, id_categoria = ? WHERE id = ?");
            return $stmt->execute(array($nome, $descripcion, $precio, $stock, $imagen, $id_categoria, $id));
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método extra para listar categorías (necesario para o formulario de engadir/editar)
    public function obterCategorias()
    {
        try {
            $stmt = $this->conexion->query("SELECT * FROM categorias ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }

    public function contarProductos()
    {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM productos");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["total"];
    }

    public function contarProductosAgotados()
    {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM productos WHERE stock <= 0");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["total"];
    }
}
