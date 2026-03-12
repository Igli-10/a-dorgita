<?php include "../includes/header.php"; ?>

<div class="container py-5 mt-5">
    <div class="row g-5">
        <div class="col-md-6 text-center">
            <div class="p-4 rounded shadow-sm border border-1 caixa-filtros">
               <img src="/a-dorgita/public/img/tostadora.png" class="img-fluid" alt="Producto">
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../index.php" class="enlace-filtro p-0">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tostadora 'Moulinex'</li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold texto-dorgita mb-3">Tostadora 'Moulinex'</h1>
            <p class="fs-3 fw-bold texto-principal mb-4">35.00 €</p>
            
            <div class="mb-4">
                <h6 class="fw-bold texto-dorgita">Descrición:</h6>
                <p class="text-muted">
                    Compacta, 2 ranuras, estilo aceiro. Deseño elegante que encaixa en calquera cociña moderna. 
                    Ideal para pans de diferentes grosores grazas ao seu sistema de centrado automático.
                </p>
            </div>

            <hr class="my-4">

            <div class="d-flex align-items-center gap-3">
                <div class="input-group" style="width: 140px;">
                    <button class="btn btn-cantidade" type="button">-</button>
                    <input type="text" class="form-control text-center input-cantidade" value="1" readonly>
                    <button class="btn btn-cantidade" type="button">+</button>
                </div>
                <button class="btn btn-engadir-carro btn-lg px-5 w-100">
                    Engadir ao carro
                </button>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>