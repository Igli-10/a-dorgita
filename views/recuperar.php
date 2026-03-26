<?php /* Vista do formulario de recuperación de contrasinal */ ?>
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="p-5 rounded shadow-sm border caixa-filtros">
                <h2 class="fw-bold texto-principal text-center mb-4">Recuperar contrasinal</h2>

                <?php /* Amoso a mensaxe de aviso se existe na sesión e elimínoa despois */ ?>
                <?php if (isset($_SESSION['mensaxe_aviso'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <?php
                        echo $_SESSION['mensaxe_aviso'];
                        unset($_SESSION['mensaxe_aviso']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php /* Formulario onde o usuario introduce o seu correo para recibir a ligazón de reset */ ?>
                <form action="index.php?c=recuperar&a=solicitar" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold texto-principal">Correo electrónico</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                    <button type="submit" class="btn btn-engadir-carro w-100 py-2">Enviar ligazón</button>
                </form>

                <?php /* Ligazón para volver á páxina de login */ ?>
                <div class="text-center mt-3">
                    <a href="index.php?c=usuario&a=login" class="text-decoration-none">Volver ao login</a>
                </div>
            </div>
        </div>
    </div>
</div>
