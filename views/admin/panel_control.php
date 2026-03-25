<div class="container py-5 mt-5">
    
    <!-- Teño os botóns para moverme polo panel -->
    <div class="d-flex gap-3 mb-4">
        <a href="index.php?c=admin&a=panelControl" class="btn btn-engadir-carro shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-speedometer2 me-2"></i>Panel de control
        </a>
        <a href="index.php?c=admin&a=pedidos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-box-seam me-2"></i>Xestión de Pedidos
        </a>
        <a href="index.php?c=admin&a=productos" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-grid me-2"></i>Xestión de Produtos
        </a>
        <a href="index.php?c=admin&a=categorias" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-tags me-2"></i>Xestión de Categorías
        </a>
        <a href="index.php?c=admin&a=usuarios" class="btn btn-admin-verde shadow-sm px-4 rounded-pill text-white">
            <i class="bi bi-people me-2"></i>Xestión de Usuarios
        </a>
    </div>

    <!-- Explico de forma rápida que se ve nesta páxina -->
    <div class="panel-control-resumo rounded-4 p-4 p-lg-5 mb-4 shadow-sm">
        <h2 class="fw-bold texto-verde mb-2">
            <i class="bi bi-speedometer2 me-2"></i>Resumo da tenda
        </h2>
        <p class="mb-0 text-muted">
            Vista xeral do estado actual da tenda con acceso directo ás seccións principais do panel.
        </p>
    </div>

    <!-- Vense os 4 datos principais da tenda -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 panel-control-card panel-control-card-verde">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="small text-muted text-uppercase fw-bold">Pedidos pendentes</div>
                        <span class="panel-control-icon panel-control-icon-laranxa">
                            <i class="bi bi-hourglass-split"></i>
                        </span>
                    </div>
                    <div class="display-6 fw-bold texto-verde mb-2"><?php echo $pedidosPendientes; ?></div>
                    <a href="index.php?c=admin&a=pedidos" class="text-decoration-none fw-bold panel-control-link-stock">Ver pedidos</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 panel-control-card panel-control-card-laranxa">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="small text-muted text-uppercase fw-bold">Produtos esgotados</div>
                        <span class="panel-control-icon panel-control-icon-verde">
                            <i class="bi bi-exclamation-triangle"></i>
                        </span>
                    </div>
                    <div class="display-6 fw-bold mb-2 panel-control-texto-laranxa"><?php echo $productosAgotados; ?></div>
                    <a href="index.php?c=admin&a=productos" class="text-decoration-none fw-bold panel-control-link-acento">Revisar stock</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 panel-control-card panel-control-card-verde">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="small text-muted text-uppercase fw-bold">Total produtos</div>
                        <span class="panel-control-icon panel-control-icon-verde">
                            <i class="bi bi-grid"></i>
                        </span>
                    </div>
                    <div class="display-6 fw-bold texto-verde mb-2"><?php echo $totalProductos; ?></div>
                    <a href="index.php?c=admin&a=productos" class="text-decoration-none fw-bold panel-control-link-stock">Ver produtos</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 panel-control-card panel-control-card-laranxa">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="small text-muted text-uppercase fw-bold">Usuarios rexistrados</div>
                        <span class="panel-control-icon panel-control-icon-laranxa">
                            <i class="bi bi-people"></i>
                        </span>
                    </div>
                    <div class="display-6 fw-bold mb-2 panel-control-texto-laranxa"><?php echo $totalUsuarios; ?></div>
                    <a href="index.php?c=admin&a=usuarios" class="text-decoration-none fw-bold panel-control-link-acento">Ver usuarios</a>
                    
                </div>
            </div>
        </div>
    </div>

    <?php
    // Preparo os números para que os gráficos saian ben.
    $totalPedidosSeg = max(1, (int)$totalPedidos);
    $totalProductosSeg = max(1, (int)$totalProductos);
    $maxVolume = max(1, (int)$totalPedidos, (int)$totalProductos, (int)$totalUsuarios);
    $porcentaxePendentes = min(100, round(($pedidosPendientes / $totalPedidosSeg) * 100));
    $porcentaxeEsgotados = min(100, round(($productosAgotados / $totalProductosSeg) * 100));
    $totalVolume = max(1, (int)$totalPedidos + (int)$totalProductos + (int)$totalUsuarios);
    $porcPedidos = round(((int)$totalPedidos / $totalVolume) * 100);
    $porcProdutos = round(((int)$totalProductos / $totalVolume) * 100);
    $porcUsuarios = max(0, 100 - $porcPedidos - $porcProdutos);

    $circ = 276.46;
    $segPedidos = ($totalPedidos / $totalVolume) * $circ;
    $segProdutos = ($totalProductos / $totalVolume) * $circ;
    $segUsuarios = $circ - $segPedidos - $segProdutos;
    $offsetProdutos = -$segPedidos;
    $offsetUsuarios = -($segPedidos + $segProdutos);
    ?>

    <!-- Poño dous gráficos sinxelos para ver os datos mellor -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 panel-graficos-card h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold texto-verde mb-4">
                        <i class="bi bi-bar-chart-line me-2"></i>Gráfico de barras
                    </h5>

                    <div class="panel-grafico-item">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Pedidos pendentes</span>
                            <span><?php echo $pedidosPendientes; ?>/<?php echo $totalPedidos; ?> (<?php echo $porcentaxePendentes; ?>%)</span>
                        </div>
                        <progress class="panel-progress panel-progress-laranxa" value="<?php echo $pedidosPendientes; ?>" max="<?php echo $totalPedidosSeg; ?>"></progress>
                    </div>

                    <div class="panel-grafico-item">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Produtos esgotados</span>
                            <span><?php echo $productosAgotados; ?>/<?php echo $totalProductos; ?> (<?php echo $porcentaxeEsgotados; ?>%)</span>
                        </div>
                        <progress class="panel-progress panel-progress-verde" value="<?php echo $productosAgotados; ?>" max="<?php echo $totalProductosSeg; ?>"></progress>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 panel-graficos-card h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold texto-verde mb-4">
                        <i class="bi bi-pie-chart me-2"></i>Gráfico circular
                    </h5>

                    <div class="panel-donut-wrap">
                        <svg class="panel-donut-chart" viewBox="0 0 120 120" role="img" aria-label="Distribución entre pedidos, produtos e usuarios">
                            <circle cx="60" cy="60" r="44" class="panel-donut-track"></circle>
                            <circle cx="60" cy="60" r="44" class="panel-donut-seg panel-donut-pedidos" stroke-dasharray="<?php echo $segPedidos; ?> <?php echo $circ; ?>"></circle>
                            <circle cx="60" cy="60" r="44" class="panel-donut-seg panel-donut-produtos" stroke-dasharray="<?php echo $segProdutos; ?> <?php echo $circ; ?>" stroke-dashoffset="<?php echo $offsetProdutos; ?>"></circle>
                            <circle cx="60" cy="60" r="44" class="panel-donut-seg panel-donut-usuarios" stroke-dasharray="<?php echo $segUsuarios; ?> <?php echo $circ; ?>" stroke-dashoffset="<?php echo $offsetUsuarios; ?>"></circle>
                        </svg>
                    </div>

                    <div class="d-flex flex-wrap gap-3 mt-3 small">
                        <span class="panel-legend-item"><i class="bi bi-circle-fill panel-legend-dot panel-legend-verde"></i>Pedidos: <?php echo $totalPedidos; ?> (<?php echo $porcPedidos; ?>%)</span>
                        <span class="panel-legend-item"><i class="bi bi-circle-fill panel-legend-dot panel-legend-laranxa"></i>Produtos: <?php echo $totalProductos; ?> (<?php echo $porcProdutos; ?>%)</span>
                        <span class="panel-legend-item"><i class="bi bi-circle-fill panel-legend-dot panel-legend-verde-suave"></i>Usuarios: <?php echo $totalUsuarios; ?> (<?php echo $porcUsuarios; ?>%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Este botón leva de volta á tenda -->
    <div class="mt-4 text-center">
        <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda px-4 rounded-pill">
            <i class="bi bi-arrow-left me-2"></i>Saír do panel
        </a>
    </div>
</div>