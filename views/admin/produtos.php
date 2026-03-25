<?php
$produtoValor = function ($produto, $campo, $metodo) {
    if (is_array($produto)) {
        return $produto[$campo] ?? null;
    }

    return method_exists($produto, $metodo) ? $produto->$metodo() : null;
};
?>

<div class="container py-5 mt-5">

    <!-- Teño os botóns para cambiar de sección -->
     <div class="d-flex gap-3 mb-4">
        <a href="index.php?c=admin&a=panelControl" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-speedometer2 me-2"></i>Panel de control
        </a>
        <a href="index.php?c=admin&a=pedidos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-box-seam me-2"></i>Xestión de Pedidos
        </a>
        <a href="index.php?c=admin&a=productos" class="btn btn-engadir-carro shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-grid me-2"></i>Xestión de Produtos
        </a>
        <a href="index.php?c=admin&a=categorias" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-tags me-2"></i>Xestión de Categorías
        </a>
        <a href="index.php?c=admin&a=usuarios" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-people me-2"></i>Xestión de Usuarios
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold texto-verde mb-0">
            <i class="bi bi-box-seam me-2"></i>Xestión de Produtos
        </h2>
        <button type="button" class="btn btn-engadir-carro shadow-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEngadirProducto">
            <i class="bi bi-plus-lg me-2"></i>Engadir Produto
        </button>
    </div>

    <!-- Podo buscar produtos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="index.php" method="GET" class="d-flex gap-2">
                <input type="hidden" name="c" value="admin">
                <input type="hidden" name="a" value="productos">

                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0 texto-verde">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="busca" class="form-control border-start-0 ps-0"
                           placeholder="Buscar por nome ou descrición..."
                           value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
                    <button type="submit" class="btn btn-engadir-carro px-4">Buscar</button>
                </div>

                <?php if (!empty($_GET['busca'])): ?>
                    <a href="index.php?c=admin&a=productos" class="btn btn-outline-secondary d-flex align-items-center rounded-pill px-3 shadow-sm">
                        <i class="bi bi-x-lg me-1"></i>Limpar
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Vexo os produtos e podo editalos ou borralos -->
    <div class="table-responsive shadow-sm rounded border bg-white p-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="borde-superior">
                <tr class="texto-verde">
                    <th class="py-3">ID</th>
                    <th class="py-3">Imaxe</th>
                    <th class="py-3">Nome</th>
                    <th class="py-3">Prezo</th>
                    <th class="py-3 text-center">Stock</th>
                    <th class="py-3 text-center">Accións</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Non hai produtos rexistrados no sistema.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                        <!-- Amoso cada produto e a súa edición -->
                        <tr>
                            <td class="fw-bold texto-verde">#<?php echo $produtoValor($p, 'id', 'getId'); ?></td>
                            <td>
                                <img src="public/img/<?php echo htmlspecialchars($produtoValor($p, 'imagen', 'getImagen')); ?>" 
                                     alt="<?php echo htmlspecialchars($produtoValor($p, 'nome', 'getNome')); ?>" 
                                     class="imaxe-miniatura-pedido">
                            </td>
                            <td class="fw-bold"><?php echo htmlspecialchars($produtoValor($p, 'nome', 'getNome')); ?></td>
                            <td class="fw-bold"><?php echo number_format($produtoValor($p, 'precio', 'getPrecio'), 2); ?> €</td>
                            <td class="text-center">
                                <?php if ($produtoValor($p, 'stock', 'getStock') <= 0): ?>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">Esgotado</span>
                                <?php elseif ($produtoValor($p, 'stock', 'getStock') <= 5): ?>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><?php echo $produtoValor($p, 'stock', 'getStock'); ?> en stock</span>
                                <?php else: ?>
                                    <span class="badge bg-success px-3 py-2 rounded-pill"><?php echo $produtoValor($p, 'stock', 'getStock'); ?> en stock</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditarProducto<?php echo $produtoValor($p, 'id', 'getId'); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="index.php?c=admin&a=borrarProducto" method="POST" class="d-inline" onsubmit="return confirm('Estás seguro de que desexas borrar este produto de xeito definitivo?');">
                                        <input type="hidden" name="id" value="<?php echo $produtoValor($p, 'id', 'getId'); ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditarProducto<?php echo $produtoValor($p, 'id', 'getId'); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header fondo-verde text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-pencil-square me-2"></i>Editar Produto #<?php echo $produtoValor($p, 'id', 'getId'); ?>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="index.php?c=admin&a=actualizarProducto" method="POST">
                                        <div class="modal-body bg-white">
                                            <input type="hidden" name="id" value="<?php echo $produtoValor($p, 'id', 'getId'); ?>">
                                            
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold texto-verde">Nome</label>
                                                    <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($produtoValor($p, 'nome', 'getNome')); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold texto-verde">Categoría</label>
                                                    <select name="id_categoria" class="form-select" required>
                                                        <?php foreach ($categorias as $c): ?>
                                                            <option value="<?php echo $c["id"]; ?>" <?php if($produtoValor($p, 'id_categoria', 'getIdCategoria') == $c["id"]) echo "selected"; ?>>
                                                                <?php echo htmlspecialchars($c["nome"]); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold texto-verde">Prezo (€)</label>
                                                    <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $produtoValor($p, 'precio', 'getPrecio'); ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold texto-verde">Stock</label>
                                                    <input type="number" name="stock" class="form-control" value="<?php echo $produtoValor($p, 'stock', 'getStock'); ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold texto-verde">Nome Imaxe</label>
                                                    <input type="text" name="imagen" class="form-control" value="<?php echo htmlspecialchars($produtoValor($p, 'imagen', 'getImagen')); ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold texto-verde">Descrición</label>
                                                    <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($produtoValor($p, 'descripcion', 'getDescripcion')); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn seguir-comprando rounded-pill px-4">Gardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($totalPaginas) && $totalPaginas > 1): ?>
        <nav aria-label="Paxinación de produtos" class="mt-4">
            <ul class="pagination justify-content-center">
                
                <li class="page-item <?php echo $pagina <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link text-success shadow-sm rounded-start-pill px-3" href="index.php?c=admin&a=productos&pagina=<?php echo $pagina - 1; ?><?php echo $mensaxe ? '&busca=' . urlencode($mensaxe) : ''; ?>">Anterior</a>
                </li>

                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?php echo $pagina == $i ? 'active' : ''; ?>">
                        <a class="page-link shadow-sm <?php echo $pagina == $i ? 'bg-success border-success text-white' : 'text-success'; ?>" href="index.php?c=admin&a=productos&pagina=<?php echo $i; ?><?php echo $mensaxe ? '&busca=' . urlencode($mensaxe) : ''; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?php echo $pagina >= $totalPaginas ? 'disabled' : ''; ?>">
                    <a class="page-link text-success shadow-sm rounded-end-pill px-3" href="index.php?c=admin&a=productos&pagina=<?php echo $pagina + 1; ?><?php echo $mensaxe ? '&busca=' . urlencode($mensaxe) : ''; ?>">Seguinte</a>
                </li>
                
            </ul>
        </nav>
    <?php endif; ?>

    <!-- Este botón saca do panel -->
    <div class="mt-4 text-center">
        <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda px-4 rounded-pill">
            <i class="bi bi-arrow-left me-2"></i>Saír do panel
        </a>
    </div>
</div>

<!-- Esta ventá serve para crear un produto novo -->
<div class="modal fade" id="modalEngadirProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header fondo-verde text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Engadir Novo Produto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?c=admin&a=gardarProducto" method="POST">
                <div class="modal-body bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold texto-verde">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold texto-verde">Categoría</label>
                            <select name="id_categoria" class="form-select" required>
                                <option value="">Selecciona unha categoría...</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?php echo $c["id"]; ?>"><?php echo htmlspecialchars($c["nome"]); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold texto-verde">Prezo (€)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold texto-verde">Stock</label>
                            <input type="number" name="stock" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold texto-verde">Nome Imaxe</label>
                            <input type="text" name="imagen" class="form-control" placeholder="exemplo.jpg" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold texto-verde">Descrición</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-engadir-carro rounded-pill px-4">Engadir Produto</button>
                </div>
            </form>
        </div>
    </div>
</div>