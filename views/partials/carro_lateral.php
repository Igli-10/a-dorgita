<div class="cart-items">
    <?php if (empty($carro)): ?>
        <div class="text-center py-5">
            <p class="text-muted">O teu carro está baleiro.</p>
            <a href="index.php?c=producto&a=index" class="btn btn-catalogo" style="background-color: #1B4332;">Descubrir</a>
        </div>
    <?php else: ?>
        <?php foreach ($carro as $id => $item):
            $subtotal = $item['precio'] * $item['cantidade']; ?>
            <div class="d-flex align-items-center mb-3 p-2 border-bottom caixa-filtros">
                <img src="/a-dorgita/public/img/<?php echo htmlspecialchars($item['imagen']); ?>"
                    alt="<?php echo htmlspecialchars($item['nome']); ?>"
                    class="img-carro rounded border">

                <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0 fw-bold texto-principal" style="font-size: 0.9rem;">
                        <?php echo htmlspecialchars($item['nome']); ?>
                    </h6>
                    <small class="text-muted">
                        <?php echo $item['cantidade']; ?> x <?php echo number_format($item['precio'], 2); ?> €
                    </small>
                </div>

                <div class="text-end">
                    <span class="fw-bold d-block texto-principal"><?php echo number_format($subtotal, 2); ?> €</span>

                    <a href="index.php?c=carro&a=eliminar&id=<?php echo $id; ?>"
                        class="text-danger small cart-action"
                        data-action="eliminar">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>

            </div>
        <?php endforeach; ?>

        <div class="mt-4 p-3 rounded shadow-sm border">
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-bold">Total:</span>
                <span class="fs-5 fw-bold texto-verde"><?php echo number_format($total, 2); ?> €</span>
            </div>
            <a href="index.php?c=carro&a=index" class="btn seguir-comprando w-100 mb-2">Ver carro completo</a>
             <a href="index.php?c=carro&a=finalizar" class="btn btn-engadir-carro w-100 ">Finalizar compra</a>
        </div>
    <?php endif; ?>
</div>