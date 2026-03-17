<div class="container py-5 mt-5">
    <?php if (empty($carro)): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-8 col-lg-6">
                <div class="p-5 rounded shadow-sm border caixa-filtros" >
                    <h2 class="fw-bold texto-principal mb-3">O teu carro</h2>
                    <p class="fs-5 mb-4">Aínda non engadiches ningun produto</p>
                    <a href="index.php?c=producto&a=index" class="fondo-laranxa px-4 py-2 rounded d-inline-block text-decoration-none">DESCUBRIR PRODUTOS</a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <h2 class="fw-bold texto-principal mb-3">O teu carro</h2>
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table align-middle" >
                        <thead class="texto-principal">
                            <tr>
                                <th class="ps-4 py-3">Produto</th>
                                <th class="py-3">Prezo</th>
                                <th class="py-3">Cantidade</th>
                                <th class="py-3 text-end pe-4">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($carro as $id => $item): 
                                $subtotal_producto = $item["precio"] * $item['cantidade']; ?>
                            <tr>
                                <td class="ps-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <img src="public/img/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" class="me-3 rounded shadow-sm border p-1 img-carro" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0 fw-bold texto-principal"><?php echo htmlspecialchars($item['nome']); ?></h6>
                                            <span class="small">Ref: <?php echo htmlspecialchars($id); ?></span>
                                            <br>
                                            <a href="index.php?c=carro&a=eliminar&id=<?php echo $id; ?>" class="text-danger small text-decoration-none mt-1 d-inline-block">Eliminar</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="texto-principal fw-bold"><?php echo number_format($item['precio'], 2); ?> €</td>
                                <td>
                                    <div class="input-group input-group-sm ancho-cantidade-carro" style="width: 100px;">
                                        <a href="index.php?c=carro&a=restar&id=<?php echo $id; ?>" class="btn btn-cantidade text-decoration-none d-flex align-items-center justify-content-center">-</a>
                                        <input type="text" class="form-control text-center input-cantidade" value="<?php echo $item['cantidade']; ?>" readonly>
                                        <a href="index.php?c=carro&a=engadir&id=<?php echo $id; ?>" class="btn btn-cantidade text-decoration-none d-flex align-items-center justify-content-center">+</a>
                                    </div>
                                </td>
                                <td class="text-end pe-4 fw-bold fs-5 texto-principal"><?php echo number_format($subtotal_producto, 2); ?> €</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="p-4 rounded shadow-sm border caixa-filtros" >
                    <h5 class="fw-bold texto-principal mb-4">Resumo do pedido</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold fs-5"><?php echo number_format($total, 2); ?> €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Gastos de envio</span>
                        <span class="text-success fw-bold">Gratis</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="fs-5 fw-bold texto-verde">Total</span>
                        <span class="texto-principal fs-4 fw-bold"><?php echo number_format($total, 2); ?> €</span>
                    </div>

                    <button class="btn btn-engadir-carro w-100 mt-3">Finalizar compra</button>
                    <a href="index.php?c=producto&a=index" class="btn btn-link w-100 text-decoration-none mt-2 small text-center d-block seguir-comprando">Seguir mercando</a>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>