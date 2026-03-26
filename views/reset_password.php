<?php /* Vista do formulario para establecer unha nova contrasinal tras validar o token */ ?>
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="p-5 rounded shadow-sm border caixa-filtros">
                <h2 class="fw-bold texto-principal text-center mb-4">Nova contrasinal</h2>

                <?php /* Amoso o erro de validación se existe */ ?>
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php /* Formulario para introducir e confirmar a nova clave; inclúo o token nun campo oculto */ ?>
                <form action="index.php?c=recuperar&a=resetear" method="POST">
                    <?php /* Paso o token de forma segura para identificar a solicitude de reset */ ?>
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($tokenPlano, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="mb-3">
                        <label for="nova_contrasinal" class="form-label fw-bold texto-principal">Nova clave</label>
                        <input type="password" class="form-control" id="nova_contrasinal" name="nova_contrasinal">
                    </div>

                    <div class="mb-4">
                        <label for="repite_contrasinal" class="form-label fw-bold texto-principal">Repite a clave</label>
                        <input type="password" class="form-control" id="repite_contrasinal" name="repite_contrasinal">
                    </div>

                    <button type="submit" class="btn btn-engadir-carro w-100 py-2">Gardar nova clave</button>
                </form>
            </div>
        </div>
    </div>
</div>
