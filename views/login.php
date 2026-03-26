<div class="container py-5 mt-5">
    <!-- Nesta vista permito iniciar sesión co correo e o contrasinal -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="p-5 rounded shadow-sm border caixa-filtros">
                <div class="text-center mb-4">

                    <!-- Amoso avisos temporais gardados na sesión -->
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

                    <h2 class="fw-bold texto-principal">Iniciar Sesión</h2>
                </div>

                <!-- Se hai erro de acceso, amósoo antes do formulario -->
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo $erro; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Este formulario envía os datos ao controlador de login -->
                <form action="/a-dorgita/index.php?c=usuario&a=login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold texto-principal">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="contrasinal" class="form-label fw-bold texto-principal">Contrasinal</label>
                        <input type="password" class="form-control" id="contrasinal" name="contrasinal" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="lembrarme" name="lembrarme">
                            <label class="form-check-label small texto-principal" for="lembrarme">Lembrarme</label>
                        </div>
                        <a href="index.php?c=recuperar&a=solicitar" class="small text-decoration-none fw-bold texto-laranxa">Esqueceches o contrasinal?</a>
                    </div>

                    <button type="submit" class="btn btn-engadir-carro w-100 py-2 fs-5 mb-3">Acceder</button>

                    <div class="text-center mt-3">
                        <span class="small">Aínda non tes conta?</span>
                        <a href="index.php?c=usuario&a=rexistrar" class="text-decoration-none fw-bold ms-1">Rexístrate aquí</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>