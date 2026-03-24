<div class="container py-5 mt-5">
    
    <div class="d-flex gap-3 mb-4">
        <a href="index.php?c=admin&a=pedidos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-box-seam me-2"></i>Xestión de Pedidos
        </a>
        <a href="index.php?c=admin&a=productos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-grid me-2"></i>Xestión de Produtos
        </a>
        <a href="index.php?c=admin&a=categorias" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-tags me-2"></i>Xestión de Categorías
        </a>
        <a href="index.php?c=admin&a=usuarios" class="btn btn-engadir-carro shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-people me-2"></i>Xestión de Usuarios
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold texto-verde mb-0">
            <i class="bi bi-people-fill me-2"></i>Xestión de Usuarios
        </h2>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form action="index.php" method="GET" class="d-flex gap-2">
                <input type="hidden" name="c" value="admin">
                <input type="hidden" name="a" value="usuarios">

                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0 texto-verde">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="busca" class="form-control border-start-0 ps-0"
                           placeholder="Buscar por nome ou email..."
                           value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
                    <button type="submit" class="btn btn-engadir-carro px-4">Buscar</button>
                </div>

                <?php if (!empty($_GET['busca'])): ?>
                    <a href="index.php?c=admin&a=usuarios" class="btn btn-outline-secondary d-flex align-items-center rounded-pill px-3 shadow-sm">
                        <i class="bi bi-x-lg me-1"></i>Limpar
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded border bg-white p-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="borde-superior">
                <tr class="texto-verde">
                    <th class="py-3">ID</th>
                    <th class="py-3">Nome</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">Rol</th>
                    <th class="py-3 text-center">Accións</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Non hai usuarios rexistrados no sistema.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="fw-bold texto-verde">#<?php echo $u->getId(); ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($u->getNome()); ?></td>
                            <td><?php echo htmlspecialchars($u->getEmail()); ?></td>
                            <td>
                                <?php if ($u->getRol() === 'admin'): ?>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">Administrador</span>
                                <?php else: ?>
                                    <span class="badge px-3 py-2 rounded-pill" style="background-color: #1B4332;">Cliente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="index.php?c=admin&a=cambiarRolAdmin" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="id" value="<?php echo $u->getId(); ?>">
                                        <select name="rol" class="form-select form-select-sm shadow-sm" style="width: 140px;">
                                            <option value="cliente" <?php if ($u->getRol() == "cliente") echo "selected"; ?>>Cliente</option>
                                            <option value="admin" <?php if ($u->getRol() == "admin") echo "selected"; ?>>Admin</option>
                                        </select>
                                        <button type="submit" class="btn seguir-comprando btn-sm px-3 shadow-sm">Cambiar Rol</button>
                                    </form>
                                    <form action="index.php?c=admin&a=borrarUsuarioAdmin" method="POST" class="d-inline" onsubmit="return confirm('Estás seguro de que desexas borrar este usuario para sempre?');">
                                        <input type="hidden" name="id" value="<?php echo $u->getId(); ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>