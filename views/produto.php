<div class="container py-5 mt-5">
    <div class="row g-5">
        <div class="col-md-6 text-center">
            <div class="p-4 rounded shadow-sm border border-1 caixa-filtros">
                <img src="public/img/<?php echo htmlspecialchars($prod->getImagen()); ?>"
                    class="img-fluid"
                    alt="<?php echo htmlspecialchars($prod->getNome()); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="index.php?c=producto&a=index" class="enlace-filtro p-0">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($prod->getNome()); ?></li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold texto-dorgita mb-3"><?php echo htmlspecialchars($prod->getNome()); ?></h1>
            <p class="fs-3 fw-bold texto-principal mb-4"><?php echo htmlspecialchars($prod->getPrecio()); ?> €</p>

            <div class="mb-4">
                <h6 class="fw-bold texto-dorgita">Descrición:</h6>
                <p class="text-muted"><?php echo htmlspecialchars($prod->getDescripcion()); ?></p>
            </div>

            <hr class="my-4">

            <?php 
                // Miramos se este produto xa está no carro para amosar aviso persistente
                $id_actual = $prod->getId();
                $unidades_no_carro = $_SESSION['carro'][$id_actual]['cantidade'] ?? 0;
                $stock_maximo = $prod->getStock();
            ?>

            <?php if ($unidades_no_carro >= $stock_maximo && $stock_maximo > 0): ?>
                <div class="alert alert-info py-2 small mb-3">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Xa tes o máximo de unidades dispoñibles no teu carro.
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['erro_stock'])): ?>
                <div class="alert alert-danger py-2 small mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php 
                        echo $_SESSION['erro_stock']; 
                        unset($_SESSION['erro_stock']); 
                    ?>
                </div>
            <?php endif; ?>

            <form action="index.php?c=carro&a=engadir" method="POST">
                <input type="hidden" name="id" value="<?php echo $prod->getId(); ?>">

                <div class="d-flex align-items-center gap-3">
                    <div class="input-group ancho-cantidade-produto" style="width: 140px;">
                        <button class="btn btn-cantidade" type="button" onclick="cambiarCantidade(-1)" <?php echo ($prod->getStock() <= 0) ? 'disabled' : ''; ?>>-</button>

                        <input type="number" name="cantidade" id="cantidade-input" class="form-control text-center fw-bold" value="<?php echo ($prod->getStock() > 0) ? '1' : '0'; ?>" min="<?php echo ($prod->getStock() > 0) ? '1' : '0'; ?>" max="<?php echo $prod->getStock(); ?>" oninput="validarStock(this, <?php echo $prod->getStock(); ?>)" readonly>

                        <button class="btn btn-cantidade" type="button" onclick="cambiarCantidade(1, <?php echo $prod->getStock(); ?>)" <?php echo ($prod->getStock() <= 0) ? 'disabled' : ''; ?>>+</button>
                    </div>

                    <?php if ($prod->getStock() > 0): ?>
                        <button type="submit" class="btn btn-engadir-carro btn-lg px-5 w-100">
                            Engadir ao carro
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-lg px-5 w-100" disabled>
                            Sen existencias
                        </button>
                    <?php endif; ?>
                </div>
                <small class="<?php echo ($prod->getStock() <= 0) ? 'text-danger' : 'text-muted'; ?> mt-2 d-block">
                    <?php if ($prod->getStock() > 0): ?>
                        Stock dispoñible: <?php echo $prod->getStock(); ?> unidades
                    <?php else: ?>
                        <i class="bi bi-x-circle me-1"></i>Actualmente fóra de stock
                    <?php endif; ?>
                </small>
            </form>
        </div>
    </div>
</div>

<script>
    function cambiarCantidade(valor, stockMax) {
        const input = document.getElementById('cantidade-input');

        // Obtemos canto hai xa no carro dende un atributo que meteremos no input
        const noCarro = parseInt(input.getAttribute('data-no-carro')) || 0;

        let actual = parseInt(input.value);
        let nova = actual + valor;

        // Validacións de seguridade no cliente
        if (nova < 1) nova = 1;
        if (stockMax && nova > stockMax) nova = stockMax;

        input.value = nova;
    }

    // Esta función controla o que o usuario escribe directamente
    function validarStock(input, stockMax) {
        let valor = parseInt(input.value);

        // Se escribe algo que non é un número ou menor que 1
        if (isNaN(valor) || valor < 1) {
            input.value = 1;
        }
        // Se intenta escribir máis do stock dispoñible
        else if (valor > stockMax) {
            input.value = stockMax;
        }
    }
</script>