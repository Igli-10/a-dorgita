<div class="container py-5 mt-5">
    
    <div class="d-flex gap-3 mb-4">
        <a href="index.php?c=admin&a=pedidos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-box-seam me-2"></i>Xestión de Pedidos
        </a>
        <a href="index.php?c=admin&a=productos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-grid me-2"></i>Xestión de Produtos
        </a>
        <a href="index.php?c=admin&a=categorias" class="btn btn-engadir-carro shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-tags me-2"></i>Xestión de Categorías
        </a>
        <a href="index.php?c=admin&a=usuarios" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-people me-2"></i>Xestión de Usuarios
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold texto-verde mb-0">
            <i class="bi bi-tags-fill me-2"></i>Xestión de Categorías
        </h2>
        <button type="button" class="btn btn-engadir-carro shadow-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEngadirCategoria">
            <i class="bi bi-plus-lg me-2"></i>Engadir Categoría
        </button>
    </div>

    <div class="table-responsive shadow-sm rounded border bg-white p-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="borde-superior">
                <tr class="texto-verde">
                    <th class="py-3">ID</th>
                    <th class="py-3">Nome</th>
                    <th class="py-3">Descrición</th>
                    <th class="py-3 text-center">Accións</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categorias)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Non hai categorías rexistradas no sistema.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td class="fw-bold texto-verde">#<?php echo $c["id"]; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($c["nome"]); ?></td>
                            <td><?php echo htmlspecialchars($c["descripcion"]); ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria<?php echo $c["id"]; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="index.php?c=admin&a=borrarCategoria" method="POST" class="d-inline" onsubmit="return confirm('Estás seguro de que desexas borrar esta categoría?');">
                                        <input type="hidden" name="id" value="<?php echo $c["id"]; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditarCategoria<?php echo $c["id"]; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header fondo-verde text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-pencil-square me-2"></i>Editar Categoría #<?php echo $c["id"]; ?>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="index.php?c=admin&a=actualizarCategoria" method="POST">
                                        <div class="modal-body bg-white">
                                            <input type="hidden" name="id" value="<?php echo $c["id"]; ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold texto-verde">Nome</label>
                                                <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($c["nome"]); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold texto-verde">Descrición</label>
                                                <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($c["descripcion"]); ?></textarea>
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
</div>

<div class="modal fade" id="modalEngadirCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header fondo-verde text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Engadir Nova Categoría
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?c=admin&a=gardarCategoria" method="POST">
                <div class="modal-body bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-bold texto-verde">Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold texto-verde">Descrición</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-engadir-carro rounded-pill px-4">Engadir Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>