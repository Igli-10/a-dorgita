<div class="container py-5 mt-5">
    <h2 class="fw-bold texto-verde mb-4">
        <i class="bi bi-gear-fill me-2"></i>Xestión de Pedidos
    </h2>

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
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Non hai pedidos rexistrados no sistema.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td class="fw-bold texto-verde">#<?php echo $p["id"]; ?></td>
                            
                            <td class="fw-bold"><?php echo htmlspecialchars($p["nome_usuario"]); ?></td>
                            
                            <td><?php echo date("d/m/Y H:i", strtotime($p["data"])); ?></td>
                            
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
                                <form action="index.php?c=admin&a=cambiarEstado" method="POST" class="d-flex justify-content-center gap-2">
                                    <input type="hidden" name="id" value="<?php echo $p["id"]; ?>">
                                    
                                    <select name="estado" class="form-select form-select-sm shadow-sm" style="width: 140px;">
                                        <option value="pendente" <?php if($estado == "pendente") echo "selected"; ?>>Pendente</option>
                                        <option value="enviado" <?php if($estado == "enviado") echo "selected"; ?>>Enviado</option>
                                        <option value="entregado" <?php if($estado == "entregado") echo "selected"; ?>>Entregado</option>
                                        <option value="cancelado" <?php if($estado == "cancelado") echo "selected"; ?>>Cancelado</option>
                                    </select>
                                    
                                    <button type="submit" class="btn seguir-comprando btn-sm px-3 shadow-sm">
                                        Actualizar
                                    </button>
                                </form>
                            </td>
                        </tr>
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