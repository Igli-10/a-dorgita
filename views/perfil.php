<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="p-5 rounded shadow-sm border caixa-filtros mb-4 bg-white">
                <div class="text-center mb-4">
                    <?php if (isset($_SESSION['mensaxe_aviso'])): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php
                            echo $_SESSION['mensaxe_aviso'];
                            unset($_SESSION['mensaxe_aviso']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <h2 class="fw-bold">O meu perfil</h2>
                    <p>Xestiona os teus datos personais</p>
                    <div class="mt-3">
                        <i class="bi bi-person-circle  foto-perfil fs-1 "></i>
                    </div>
                </div>
            </div>

            <div class="card border-0 p-4 mb-4 shadow-sm bg-white">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold texto-verde">
                        <i class="bi bi-person-fill me-2"></i>Nome completo
                    </div>
                    <div class="col-sm-8"><?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?></div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold texto-verde">
                        <i class="bi bi-envelope-fill me-2"></i>Correo electrónico
                    </div>
                    <div class="col-sm-8"><?php echo htmlspecialchars($_SESSION['usuario']['email']); ?></div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold texto-verde">
                        <i class="bi bi-shield-fill me-2"></i>Rol da conta
                    </div>
                    <div class="col-sm-8 d-flex align-items-center gap-3 flex-wrap">
                        <span class="badge <?php echo ($_SESSION['usuario']['rol'] === 'admin') ? 'bg-danger' : 'bg-primary'; ?> px-3 py-2 rounded-pill">
                            <?php echo strtoupper(htmlspecialchars($_SESSION['usuario']['rol'])); ?>
                        </span>

                        <?php if ($_SESSION['usuario']['email'] === 'admin@adorgita.com'): ?>
                            <a href="index.php?c=usuario&a=cambiarRol" class="btn btn-engadir-carro btn-sm rounded-pill shadow-sm">
                                <i class="bi bi-arrow-left-right me-1"></i>
                                Cambiar a modo <?php echo ($_SESSION['usuario']['rol'] === 'admin') ? 'Cliente' : 'Admin'; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <a href="index.php?c=admin&a=pedidos" class="btn boton-volver-tenda btn-sm rounded-pill shadow-sm px-4">
                                <i class="bi bi-speedometer2 me-1"></i>
                                Ir ao panel de control
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card border-0 p-4 mb-5 shadow-sm bg-white">
                <button class="btn w-100 text-start d-flex justify-content-between align-items-center fw-bold texto-principal p-0 border-0 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePedidos" aria-expanded="false" aria-controls="collapsePedidos">
                    <span class="fs-4"><i class="bi bi-box-seam me-2"></i>Historial de pedidos</span>
                    <i class="bi bi-chevron-down fs-4 icono-flecha"></i>
                </button>

                <div class="collapse mt-4" id="collapsePedidos">
                    <?php if (empty($pedidos_completos)): ?>
                        <div class="alert alert-light border text-center">
                            Aínda non realizaches ningún pedido.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="texto-verde">
                                    <tr>
                                        <th class="py-3">Pedido</th>
                                        <th class="py-3">Data</th>
                                        <th class="py-3">Produtos</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3 text-end">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pedidos_completos as $pedido): ?>
                                        <tr>
                                            <td class="py-3 fw-bold texto-verde">#<?php echo htmlspecialchars($pedido['pedido']->getId()); ?></td>
                                            <td class="py-3 fw-bold"><?php echo date('d/m/Y', strtotime($pedido['pedido']->getDataPedido())); ?></td>
                                            <td class="py-3">
                                                <ul class="list-unstyled mb-0 small">
                                                    <?php foreach ($pedido['detalles'] as $detalle): ?>
                                                        <li>
                                                            <span class="fw-bold"><?php echo $detalle['cantidade']; ?>x</span>
                                                            <?php echo htmlspecialchars($detalle['nome']); ?>
                                                            <span class="text-muted">(<?php echo number_format($detalle['prezo_unitario'], 2); ?> €)</span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>
                                            <td class="py-3 fw-bold"><?php echo number_format($pedido['pedido']->getTotal(), 2); ?> €</td>
                                            <td class="py-3 text-end">
                                                <?php
                                                $estado = strtolower($pedido['pedido']->getEstado());
                                                $clase_estado = ($estado === 'pendente') ? 'bg-warning text-dark' : 'bg-success';
                                                ?>
                                                <span class="badge <?php echo $clase_estado; ?>"><?php echo htmlspecialchars($pedido['pedido']->getEstado()); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="d-flex justify-content-center gap-3 mb-5">
                <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda fs-5 px-4 rounded-pill">
                    <i class="bi bi-shop me-2"></i>Volver á tenda
                </a>

                <a href="index.php?c=usuario&a=logout" class="btn btn-danger fs-5 px-4 rounded-pill">
                    <i class="bi bi-box-arrow-right me-2"></i>Pechar sesión
                </a>
            </div>
        </div>
    </div>
</div>