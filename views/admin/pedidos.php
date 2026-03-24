<div class="container py-5 mt-5">
    
    <div class="d-flex gap-3 mb-4">
        <a href="index.php?c=admin&a=pedidos" class="btn btn-engadir-carro shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-box-seam me-2"></i>Xestión de Pedidos
        </a>
        <a href="index.php?c=admin&a=productos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-grid me-2"></i>Xestión de Produtos
        </a>
        <a href="index.php?c=admin&a=categorias" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-tags me-2"></i>Xestión de Categorías
        </a>
    </div>

    <h2 class="fw-bold texto-verde mb-4">
        <i class="bi bi-gear-fill me-2"></i>Xestión de Pedidos
    </h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <form action="index.php" method="GET" class="d-flex gap-2">
                <input type="hidden" name="c" value="admin">
                <input type="hidden" name="a" value="pedidos">
                
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0 texto-verde">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="busca" class="form-control border-start-0 ps-0" 
                           placeholder="Buscar por ID ou nome de cliente..." 
                           value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
                    <button type="submit" class="btn btn-engadir-carro px-4">Buscar</button>
                </div>

                <?php if (!empty($_GET['busca'])): ?>
                    <a href="index.php?c=admin&a=pedidos" class="btn btn-outline-secondary d-flex align-items-center rounded-pill px-3 shadow-sm">
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
                    <th class="py-3">Cliente</th>
                    <th class="py-3">Data</th>
                    <th class="py-3">Total</th>
                    <th class="py-3">Estado Actual</th>
                    <th class="py-3 text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos_completos)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Non hai pedidos rexistrados no sistema.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos_completos as $item): ?>
                        <?php $p = $item["pedido"]; ?>
                        <?php
                        $dataPedido = $p["data"] ?? $p["data_pedido"] ?? null;
                        $dataFormateada = $dataPedido ? date("d/m/Y H:i", strtotime($dataPedido)) : "-";
                        ?>
                        <tr>
                            <td class="fw-bold texto-verde">#<?php echo $p["id"]; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($p["nome_usuario"]); ?></td>
                            <td><?php echo $dataFormateada; ?></td>
                            <td class="fw-bold"><?php echo number_format($p["total"], 2); ?> €</td>
                            <td>
                                <?php
                                $estado = strtolower($p["estado"]);
                                $clase_badge = "bg-secondary";
                                if ($estado === "pendente") $clase_badge = "bg-warning text-dark";
                                if ($estado === "enviado") $clase_badge = "bg-info text-dark";
                                if ($estado === "entregado") $clase_badge = "bg-success";
                                if ($estado === "cancelado") $clase_badge = "bg-danger";
                                ?>
                                <span class="badge <?php echo $clase_badge; ?> px-3 py-2 rounded-pill">
                                    <?php echo ucfirst($estado); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-outline-dark btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPedido<?php echo $p["id"]; ?>">
                                        <i class="bi bi-eye"></i> Detalles
                                    </button>
                                    <form action="index.php?c=admin&a=cambiarEstado" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="id" value="<?php echo $p["id"]; ?>">
                                        <select name="estado" class="form-select form-select-sm shadow-sm" style="width: 140px;">
                                            <option value="pendente" <?php if ($estado == "pendente") echo "selected"; ?>>Pendente</option>
                                            <option value="enviado" <?php if ($estado == "enviado") echo "selected"; ?>>Enviado</option>
                                            <option value="entregado" <?php if ($estado == "entregado") echo "selected"; ?>>Entregado</option>
                                            <option value="cancelado" <?php if ($estado == "cancelado") echo "selected"; ?>>Cancelado</option>
                                        </select>
                                        <button type="submit" class="btn seguir-comprando btn-sm px-3 shadow-sm">Actualizar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalPedido<?php echo $p["id"]; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header fondo-verde text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-box-seam me-2"></i>Detalles do Pedido #<?php echo $p["id"]; ?>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body bg-white">
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($item["detalles"] as $detalle): ?>
                                                <li class="list-group-item d-flex align-items-center py-3 px-0">
                                                    <img src="public/img/<?php echo htmlspecialchars($detalle["imagen"]); ?>" 
                                                         alt="<?php echo htmlspecialchars($detalle["nome"]); ?>" 
                                                         class="imaxe-miniatura-pedido me-3">
                                                    
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($detalle["nome"]); ?></h6>
                                                        <small class="text-muted">Cantidade: <span class="texto-verde fw-bold"><?php echo $detalle["cantidade"]; ?></span></small>
                                                    </div>
                                                    
                                                    <div class="text-end">
                                                        <span class="fw-bold texto-verde"><?php echo number_format($detalle["prezo_unitario"], 2); ?> €</span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <span class="fw-bold fs-5">Total do pedido:</span>
                                            <span class="fs-4 fw-bold texto-principal"><?php echo number_format($p["total"], 2); ?> €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-center">
        <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda px-4 rounded-pill">
            <i class="bi bi-arrow-left me-2"></i>Saír do Panel
        </a>
    </div>
</div>