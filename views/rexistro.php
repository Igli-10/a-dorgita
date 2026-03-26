<div class="container py-5 mt-5">
    <!-- Nesta vista permito crear unha conta nova -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="p-5 rounded shadow-sm border caixa-filtros">
                <div class="text-center mb-4">
                    <h2 class="fw-bold texto-principal">Crear Conta</h2>
                    <p>Únete A Dorgita</p>
                </div>

                <!-- Amoso o erro de validación se existe -->
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i><br>
                        <?php echo $erro; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Este formulario envía os datos de rexistro ao controlador -->
                <form action="/a-dorgita/index.php?c=usuario&a=rexistrar" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label fw-bold texto-principal">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold texto-principal">Correo electrónico</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="contrasinal" class="form-label fw-bold texto-principal">Contrasinal</label>
                        <input type="password" class="form-control" id="contrasinal" name="contrasinal">
                    </div>

                    <div class="mb-4">
                        <!-- Campo para repetir a clave e confirmar que coincide -->
                        <label for="contrasinal2" class="form-label fw-bold texto-principal">Repetir Contrasinal</label>
                        <input type="password" class="form-control" id="contrasinal2" name="contrasinal2">
                    </div>

                    <button type="submit" class="btn btn-engadir-carro w-100 py-2 fs-5 mb-3">Rexistrarse</button>

                    <!-- Ligazón para usuarios que xa teñen conta -->
                    <div class="text-center mt-3">
                        <span class="small">Xa tes unha conta?</span>
                        <a href="index.php?c=usuario&a=login" class="text-decoration-none fw-bold ms-1">Inicia Sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>