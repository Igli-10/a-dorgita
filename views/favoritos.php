<div class="container py-5 mt-5">
    <!-- Cabeceira coa navegación de volta ao catálogo usando o teu botón corporativo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold texto-principal"><i class="bi bi-heart-fill text-danger"></i> Os meus Favoritos</h2>
        <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left"></i> Volver ao catálogo
        </a>
    </div>

    <!-- Amoso mensaxe informativa co estilo limpo da túa web se non hai favoritos -->
    <?php if (empty($meusFavoritos)): ?>
        <div class="p-5 rounded shadow-sm border bg-white text-center py-5">
            <i class="bi bi-emoji-frown display-4 texto-verde"></i>
            <p class="mt-3 fs-5 texto-principal">Aínda non tes ningún produto gardado como favorito.</p>
            <a href="index.php?c=producto&a=index" class="btn btn-engadir-carro px-4 rounded-pill">Explorar tenda</a>
        </div>
    <?php else: ?>
        <!-- Mostro as tarxetas de cada produto favorito -->
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($meusFavoritos as $p): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border caixa-filtros bg-white">
                        <!-- Corrixida a ruta engadindo a barra inicial correspondente -->
                        <img src="/a-dorgita/public/img/<?php echo htmlspecialchars($p['imagen'], ENT_QUOTES, 'UTF-8'); ?>" 
                             class="card-img-top p-3" 
                             alt="<?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?>" 
                             style="height: 200px; object-fit: contain;">

                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold texto-principal text-truncate"><?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <!-- Corrixido texto-dorgita por texto-verde que é a túa clase real en CSS -->
                            <p class="card-text fw-bold fs-5 texto-verde"><?php echo number_format($p['precio'], 2); ?> €</p>
                        </div>

                        <!-- Accións: engadir ao carro co teu estilo de botón ou quitar de favoritos -->
                        <div class="card-footer bg-white border-0 d-grid gap-2 pb-3">
                            <form action="index.php?c=carro&a=engadir" method="POST" class="m-0">
                                <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                                <input type="hidden" name="cantidade" value="1">
                                <button type="submit" class="btn btn-engadir-carro w-100 rounded-pill">
                                    <i class="bi bi-cart-plus"></i> Engadir ao carro
                                </button>
                            </form>
                            <a href="index.php?c=producto&a=toggleFavorito&id=<?php echo (int)$p['id']; ?>&accion=quitar" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold mt-1">
                                <i class="bi bi-trash"></i> Quitar dos meus favoritos
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>