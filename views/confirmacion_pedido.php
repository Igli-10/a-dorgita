<div class="container py-5 mt-5">
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
                    <strong class="fs-3 texto-laranxa">#<?php echo $id_pedido; ?></strong>
                </div>

                <p class="text-muted">Recibirás un correo electrónico cos detalles do envío e a factura en breve.</p>
                
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda px-4">Volver á tenda</a>
                    <a href="index.php?c=usuario&a=perfil" class="btn btn-engadir-carro px-4 ">Ver os meus pedidos</a>
                </div>
            </div>
        </div>
    </div>
</div>