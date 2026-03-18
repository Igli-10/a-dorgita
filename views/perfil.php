<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="p-5 rounded shadow-sm border caixa-filtros">
                <div class="text-center mb-4">
                    <h2 class="fw-bold texto-principal">O meu perfil</h2>
                    <p>Xestiona os teus datos personais</p>
                    <div class="mt-3">
                        <i class="bi bi-person-circle foto-perfil"></i>
                    </div>
                </div>
            </div>

            <div class="card border-0 p-4 mb-4">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold texto-verde">
                        <i class="bi bi-person-fill me-2"></i>Nome completo
                    </div>
                    <div class="col-sm-8"><?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?></div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold texto-verde">
                        <i class="bi bi-envelope-fill me-2"></i>Correo electrónico
                    </div>
                    <div class="col-sm-8"><?php echo htmlspecialchars($_SESSION['usuario']['email']); ?></div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold texto-verde">
                        <i class="bi bi-shield-fill me-2"></i>Rol da conta
                    </div>
                    <div class="col-sm-8"><?php echo htmlspecialchars($_SESSION['usuario']['rol']); ?></div>
                </div>

            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="index.php?c=producto&a=index" class="btn boton-volver-tenda fs-5 px-4 rounded-pill">
                    <i class="bi bi-shop me-2"></i>Volver á tenda
                </a>

                <a href="index.php?c=usuario&a=logout" class="btn btn-danger fs-5 px-4 rounded-pill">
                    <i class="bi bi-box-arrow-right me-2"></i>Pechar sesión
                </a>

            </div>
        </div>
    </div>
</div>