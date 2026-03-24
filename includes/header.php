<!DOCTYPE html>
<html lang="gl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Dorgita</title>
    <link rel="stylesheet" href="/a-dorgita/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/a-dorgita/public/css/estilos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="/a-dorgita/public/js/buscador.js" defer></script>
    <link rel="icon" type="image/png" href="/a-dorgita/public/img/logo_favicon.png" alt="Favicon">

</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-dark fondo-verde">
        <div class="container-fluid d-flex align-items-center">

            <div style="width: 25%;">
                <a class="navbar-brand d-flex align-items-center" href="/a-dorgita/index.php">
                    <img src="/a-dorgita/public/img/logo.png" width="50" height="50" class="me-2" alt="Logo">
                    A Dorgita
                </a>
            </div>

            <div class="flex-grow-1 d-flex justify-content-center">
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="#">Alimentación</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Ferretería</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Fogar</a></li>
                    <li class="nav-item"><a class="nav-link" href="/a-dorgita/views/contacto.php">Contacto</a></li>
                </ul>
            </div>

            <div style="width: 25%;" class="d-flex justify-content-end">
                <ul class="nav">
                    <form action="index.php" method="get" class="d-flex align-content-center me-2">
                        <input type="hidden" name="c" value="producto">
                        <input type="hidden" name="a" value="index">

                        <div class="input-group input-group-sm position-relative" style="width: 150px;">

                            <input type="text" name="q" id="input-busca"
                                class="form-control rounded-pill ps-3 texto-buscador input-buscador"
                                placeholder="Buscar..." autocomplete="off"
                                value="<?php echo isset($_GET["q"]) ? htmlspecialchars($_GET["q"]) : " "; ?>">

                            <button class="btn btn-lupa" type="submit">
                                <i class="bi bi-search"></i>
                            </button>

                            <div id="suxestions-box" class="caixa-suxestions list-group"></div>
                        
                        </div>
                    </form>

                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart"><i class="bi bi-cart3"></i></a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-check-fill me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm bg-white">
                                <li><a class="dropdown-item texto-verde" href="index.php?c=usuario&a=perfil"><i class="bi bi-person me-2"></i>O meu perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="index.php?c=usuario&a=logout"><i class="bi bi-box-arrow-right me-2"></i>Pechar sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?c=usuario&a=login"><i class="bi bi-person"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold texto-verde" id="offcanvasCartLabel">O teu carro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>


            <div class="offcanvas-body" id="cart-content">
                <?php
                // Uso directamente a sesión para evitar o "salto máxico"
                $carro_actual = $_SESSION['carro'] ?? [];

                if (!empty($carro_actual)):
                    $total = 0;
                    // Re-calculo para asegurar que a vista teña o dato
                    foreach ($carro_actual as $item) {
                        $total += $item['precio'] * $item['cantidade'];
                    }
                    // Paso a variable $carro que espera o teu fragmento
                    $carro = $carro_actual;
                    include __DIR__ . '/../views/partials/carro_lateral.php';
                else: ?>
                    <div class="text-center py-5">
                        <p class="text-muted">O teu carro está baleiro.</p>
                        <a href="index.php?c=producto&a=index" class="btn btn-catalogo seguir-comprando">Descubrir produtos</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>