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

    //Filtrado con paxinación para a portada
    public function filtrarPaginadoCatalogo($id_cat = null, $max_prezo = null, $limite = 9, $offset = 0)
    {
        try {
            //Constrúo a consulta de forma dinámica para reutilizar os filtros
            $sql = "SELECT * FROM productos";
            $condicions = [];
            $params = [];

            if ($id_cat !== null && $id_cat !== '') {
                $condicions[] = "id_categoria = ?";
                $params[] = $id_cat;
            }

            if ($max_prezo !== null && $max_prezo !== '') {
                $condicions[] = "precio <= ?";
                $params[] = $max_prezo;
            }

            if (count($condicions) > 0) {
                $sql .= " WHERE " . implode(" AND ", $condicions);
            }

            $sql .= " ORDER BY id DESC LIMIT " . (int)$limite . " OFFSET " . (int)$offset;

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_CLASS, "Producto");
        } catch (Exception $e) {
            return [];
        }
    }

    //Conta produtos para calcular páxinas na portada
    public function contarFiltradosCatalogo($id_cat = null, $max_prezo = null)
    {
        try {
            //Esta conta úsase para calcular o número total de páxinas
            $sql = "SELECT COUNT(*) AS total FROM productos";
            $condicions = [];
            $params = [];

            if ($id_cat !== null && $id_cat !== '') {
                $condicions[] = "id_categoria = ?";
                $params[] = $id_cat;
            }

            if ($max_prezo !== null && $max_prezo !== '') {
                $condicions[] = "precio <= ?";
                $params[] = $max_prezo;
            }

            if (count($condicions) > 0) {
                $sql .= " WHERE " . implode(" AND ", $condicions);
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)($resultado['total'] ?? 0);
        } catch (Exception $e) {
            return 0;
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

    //Busca con paxinación para a portada
    public function buscarPaginadoCatalogo($mensaxe, $limite, $offset)
    {
        try {
            //Mesma lóxica da búsqueda normal pero devolvendo só un bloque de resultados
            $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE nome LIKE :mensaxe OR descripcion LIKE :mensaxe ORDER BY id DESC LIMIT " . (int)$limite . " OFFSET " . (int)$offset);
            $stmt->bindValue(':mensaxe', '%' . $mensaxe . '%', PDO::PARAM_STR);
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

    public function listarPaginado($limite, $offset)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM productos ORDER BY id DESC LIMIT " . (int)$limite . " OFFSET " . (int)$offset);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPaginado($mensaxe, $limite, $offset)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE nome LIKE :mensaxe OR descripcion LIKE :mensaxe ORDER BY id DESC LIMIT " . (int)$limite . " OFFSET " . (int)$offset);
        $stmt->bindValue(':mensaxe', '%' . $mensaxe . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarBusqueda($mensaxe)
    {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM productos WHERE nome LIKE :mensaxe OR descripcion LIKE :mensaxe");
        $stmt->bindValue(':mensaxe', '%' . $mensaxe . '%', PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }

    public function contarProductosAgotados()
    {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM productos WHERE stock <= 0");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["total"];
    }

    // Engado un produto á lista de favoritos dun usuario
    public function engadirFavorito($id_usuario, $id_producto)
    {
        try {
            $sql = "INSERT IGNORE INTO favoritos (id_usuario, id_producto) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_usuario, $id_producto]);
        } catch (PDOException $e) {
            error_log("Erro ao engadir favorito: " . $e->getMessage());
            return false;
        }
    }

    // Elimino un produto da lista de favoritos dun usuario
    public function quitarFavorito($id_usuario, $id_producto)
    {
        try {
            $sql = "DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_usuario, $id_producto]);
        } catch (PDOException $e) {
            error_log("Erro ao quitar favorito: " . $e->getMessage());
            return false;
        }
    }

    // Obteño a lista completa de produtos favoritos dun usuario
    public function obterFavoritos($id_usuario)
    {
        try {
            $sql = "SELECT p.* FROM productos p
                    JOIN favoritos f ON p.id = f.id_producto
                    WHERE f.id_usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id_usuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter favoritos: " . $e->getMessage());
            return [];
        }
    }

    // Obteño só os IDs dos produtos favoritos dun usuario para comparar na vista
    public function obterIdsFavoritos($id_usuario)
    {
        try {
            $sql = "SELECT id_producto FROM favoritos WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id_usuario]);
            return array_map('intval', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_producto'));
        } catch (PDOException $e) {
            error_log("Erro ao obter IDs favoritos: " . $e->getMessage());
            return [];
        }
    }
}
