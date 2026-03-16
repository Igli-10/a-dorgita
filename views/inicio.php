<div class="container-fluid fondo-laranxa">
    <div class="row justify-content-center text-center">
        <div class="col-md-8 py-5 my-5">
            <h1 class="display-3 fw-bold mb-3">Benvidos A Dorgita</h1>
            <p class=" fs-4 mb-4">Dende 1978, a túa tenda de confianza na Silva</p>
            <a href="#" class="btn btn-catalogo ">VER CATÁLOGO</a>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $p): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-1" style="background-color: #FFFFFF;">
                                <img src="public/img/<?php echo htmlspecialchars($p->getImagen()); ?>" class="card-img-top p-4" alt="<?php echo htmlspecialchars($p->getNome()); ?>">

                                <div class="card-body d-flex flex-column text-center">
                                    <h5 class="card-title fw-bold texto-principal desc-tarjeta"><?php echo htmlspecialchars($p->getNome()); ?></h5>
                                    <p class="card-text small"><?php echo htmlspecialchars($p->getDescripcion()); ?></p>

                                    <div class="mt-auto">
                                        <p class="fw-bold fs-5 texto-dorgita"><?php echo $p->getPrecio(); ?> €</p>

                                        <a href="index.php?c=producto&a=obter&id=<?php echo $p->getId(); ?>" class="btn btn-engadir-carro">VER DETALLES</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="fs-5">Non se atoparon produtos dispoñibles.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-3 ps-lg-5">
            <div class="p-4 rounded shadow-sm border border-1 caixa-filtros">
                <form action="index.php" method="GET">

                    <input type="hidden" name="c" value="producto">
                    <input type="hidden" name="a" value="index">

                    <h5 class="fw-bold mb-4 texto-dorgita">Categorías</h5>
                    <select name="cat" class="form-select mb-4">
                        <option value="">Todas as categorías</option>
                        <option value="1" <?php echo (isset($_GET['cat']) && $_GET['cat'] == 1) ? 'selected' : ''; ?>>Ferretería</option>
                        <option value="2" <?php echo (isset($_GET['cat']) && $_GET['cat'] == 2) ? 'selected' : ''; ?>>Alimentación</option>
                        <option value="3" <?php echo (isset($_GET['cat']) && $_GET['cat'] == 3) ? 'selected' : ''; ?>>Fogar</option>
                    </select>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3 texto-dorgita">Prezo</h5>
                    <input type="range" name="max_prezo" class="form-range" id="rangoPrezo" min="0" max="1000" step="5" value="<?php echo $_GET['max_prezo'] ?? '1000'; ?>">
                    <div class="d-flex justify-content-between small text-muted mt-2">
                        <span>0€</span>
                        <span id="valorPrezo" class="fw-bold"><?php echo $_GET['max_prezo'] ?? '1000'; ?> €</span>
                        
                        <span>1000€</span>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-engadir-carro w-100 py-2 fs-5">Filtrar</button>
                    </div>

            </div>
            </form>
        </div>
        <script>
            const inputRango = document.getElementById('rangoPrezo');
            const displayPrezo = document.getElementById('valorPrezo');

            inputRango.addEventListener('input', function() {
                displayPrezo.textContent = this.value+" €";
            });
        </script>
    </div>
</div>