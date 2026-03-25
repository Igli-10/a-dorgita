<div class="container py-5 mt-5">
    <!-- Nesta vista confirmo que o pedido foi creado correctamente -->
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="p-5 rounded shadow-sm border caixa-filtros">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
                </div>
                <h2 class="fw-bold texto-principal">¡Grazas pola túa compra!</h2>
                <p class="fs-5 mt-3">O teu pedido foi rexistrado correctamente.</p>
                
                <div class="alert alert-light border my-4">
                    <span class="d-block mb-2">Número de pedido:</span>
                    <strong class="fs-3 texto-laranxa">#<?php echo htmlspecialchars((string)$id_pedido); ?></strong>
                </div>

                <div class="alert alert-light border mb-4">
                    <span class="d-block">Estado inicial: <strong class="texto-principal">Pendente</strong></span>
                </div>

                <p class="text-muted">Garda este número para calquera consulta sobre o pedido.</p>
                
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda px-4"><i class="bi bi-house"></i> Volver á tenda</a>
                    <a href="index.php?c=usuario&a=perfil" class="btn btn-engadir-carro px-4 "><i class="bi bi-bag-check"></i> Ver os meus pedidos</a>
                    <a href="index.php?c=carro&a=descargarFactura&id=<?php echo urlencode((string)$id_pedido); ?>" class="btn btn-factura-pdf px-4">
                        <i class="bi bi-file-earmark-pdf"></i> Factura PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>