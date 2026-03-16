<?php include "../includes/header.php" ?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="p-5 rounded shadow-sm border caixa-filtros">

                <div class="text-center mb-4">
                    <h2 class="fw-bold texto-principal">Iniciar Sesión</h2>
                    <p>Benvido de novo A Dorgita</p>
                </div>

                <form>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold texto-principal">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="contrasinal" class="form-label fw-bold texto-principal">Contrasinal</label>
                        <input type="password" class="form-control" id="contrasinal" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="lembrarme">
                            <label class="form-check-label small texto-principal" for="lembrarme">Lembrarme</label>
                        </div>

                        <a href="#" class="small text-decoration-none fw-bold texto-laranxa">Esqueceches o contrasinal?</a>

                    </div>

                    <button type="submit" class="btn btn-engadir-carro w-100 py-2 fs-5 mb-3">Acceder</button>

                    <div class="text-center mt-3">
                        <span class="small">Aínda non tes conta?</span>
                        <a href="/a-dorgita/views/rexistro.php" class="text-decoration-none fw-bold  ms-1">Rexístrate aquí</a>
                    </div>



                </form>

            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php" ?>