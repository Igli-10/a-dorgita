<?php include "../includes/header.php"; ?>

<div class="container py-5 mt-5">
    <h2 class="fw-bold texto-principal mb-4">O teu carro</h2>

    <div class="row g-4">

        <div class="col-lg-8">
            <div class="table-responsive">

                <table class="table align-middle">
                    <thead class="texto-principal">
                        <tr>
                            <th class="ps-4 py-3">Produto</th>
                            <th class="py-3">Prezo</th>
                            <th class="py-3">Cantidade</th>
                            <th class="py-3 text-end pe-4">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center">
                                    <img src="/a-dorgita/public/img/tostadora.png" alt="Tostadora" class="me-3 rounded border p-1 img-carro">
                                    <div>
                                        <h6 class="mb-0 fw-bold texto-principal">Tostadora "Moulinex"</h6>
                                        <span class="small">Ref: 123</span>
                                        <br>
                                        <a href="#" class="text-danger small text-decoration-none mt-1 d-inline-block">Eliminar</a>
                                    </div>
                                </div>
                            </td>
                            <td class="texto-principal fw-bold">35.00 €</td>
                            <td>
                                <div class="input-group input-group-sm ancho-cantidade-carro">
                                    <button class="btn btn-cantidade">-</button>
                                    <input type="text" class="form-control text-center input-cantidade" value="1" readonly>
                                    <button class="btn btn-cantidade">+</button>
                                </div>
                            </td>
                            <td class="text-end pe-4 fw-bold texto-principal">35.00 €</td>
                        </tr>

                        <tr>
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center">
                                    <img src="/a-dorgita/public/img/aceite_oliva_virxe.png" alt="Aceite de Oliva" class="me-3 rounded border p-1 img-carro">
                                    <div>
                                        <h6 class="mb-0 fw-bold texto-principal">Aceite de Oliva Virxe Extra</h6>
                                        <span class="small">Ref: 124</span>
                                        <br>
                                        <a href="#" class="text-danger small text-decoration-none mt-1 d-inline-block">Eliminar</a>
                                    </div>
                                </div>
                            </td>
                            <td class="texto-principal fw-bold">9.50 €</td>
                            <td>
                                <div class="input-group input-group-sm ancho-cantidade-carro">
                                    <button class="btn btn-cantidade">-</button>
                                    <input type="text" class="form-control text-center input-cantidade" value="2" readonly>
                                    <button class="btn btn-cantidade">+</button>
                                </div>
                            </td>
                            <td class="text-end pe-4 fw-bold texto-principal">19.00 €</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="col-lg-4">
            <div class="p-4 rounded shadow-sm border caixa-filtros">
                <h5 class="fw-bold texto-principal mb-4">Resumo do pedido</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span class="fw-bold">54€</span>
                </div>

                  <div class="d-flex justify-content-between mb-2">
                    <span>Gastos de envio</span>
                    <span class="text-success fw-bold">Gratis</span>
                </div>
                
                <hr class="my-3">

                <div class="d-flex justify-content-between mb-2">
                    <span class="fs-5 fw-bold texto-verde">Total</span>
                    <span class="texto-principal fw-bold">54€</span>
                </div>

                <button class="btn btn-engadir-carro w-100">Finalizar compra</button>
                
                <a href="/a-dorgita/index.php" class="btn btn-link w-100 text-decoration-none mt-2 small text-center d-block seguir-comprando" >Seguir mercando</a>
            </div>
        </div>

    </div>
</div>


<?php include "../includes/footer.php"; ?>