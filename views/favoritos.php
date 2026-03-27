<div class="container py-5 mt-5">
    <!-- Cabeceira coa navegación de volta ao catálogo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-heart-fill text-danger"></i> Os meus Favoritos</h2>
        <a href="index.php?c=producto&a=index" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver ao catálogo
        </a>
    </div>

    <!-- Amoso mensaxe informativa se o usuario non ten favoritos gardados -->
    <?php if (empty($meusFavoritos)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-emoji-frown display-4"></i>
            <p class="mt-3">Aínda non tes ningún produto gardado como favorito.</p>
            <a href="index.php?c=producto&a=index" class="btn btn-engadir-carro">Explorar tenda</a>
        </div>
    <?php else: ?>
        <!-- Mostro as tarxetas de cada produto favorito -->
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($meusFavoritos as $p): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border caixa-filtros">
                        <img src="public/img/<?php echo htmlspecialchars($p['imagen'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top p-3" alt="<?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?>" style="height: 200px; object-fit: contain;">

                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold texto-principal text-truncate"><?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="card-text fw-bold fs-5 texto-dorgita"><?php echo number_format($p['precio'], 2); ?> €</p>
                        </div>

                        <!-- Accións: engadir ao carro ou quitar de favoritos -->
                        <div class="card-footer bg-white border-0 d-grid gap-2 pb-3">
                            <a href="index.php?c=carro&a=engadir&id=<?php echo (int)$p['id']; ?>" class="btn btn-engadir-carro">
                                <i class="bi bi-cart-plus"></i> Engadir ao carro
                            </a>
                            <a href="index.php?c=producto&a=toggleFavorito&id=<?php echo (int)$p['id']; ?>&accion=quitar" class="btn btn-sm btn-link text-danger text-decoration-none">
                                <i class="bi bi-trash"></i> Quitar dos meus favoritos
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
