<?php
try{
    $stmt=$conexion->query("SELECT * FROM produtos");
    $produtos=$stmt->fetchAll(); // Gardamos o resultado da consulta no array produtos
}catch(PDOException $e){
    $produtos=[];
    echo "Erro: ". $e->getMessage();
}

?>

<div class="container-fluid fondo-laranxa">
    <div class="row justify-content-center text-center">
        <div class="col-md-8 py-5 my-5">
            <h1 class="display-3 fw-bold mb-3">Benvidos A Dorgita</h1>
            <p class=" fs-4 mb-4">Dende 1978, a túa tenda de confianza na Silva</p>
            <a href="#" class="btn btn-catalogo ">VER CATÁLOGO</a>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <?php foreach ($produtos as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-1" style="background-color: #FFFFFF;">
                            <img src="public/img/<?php echo $p['imagen']; ?>" class="card-img-top p-4" alt="<?php echo $p['nome']; ?>">
                            <div class="card-body d-flex flex-column text-center">
                                <h5 class="card-title fw-bold texto-principal desc-tarjeta"><?php echo $p['nome']; ?></h5>
                                <p class="card-text small"><?php echo $p['descripcion']; ?></p>

                                <div class="mt-auto">
                                    <p class="fw-bold fs-5 texto-dorgita"><?php echo $p['precio']; ?>€</p>
                                    <a href="#" class="btn btn-engadir-carro">ENGADIR AO CARRO</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-3 ps-lg-5">
            <div class="p-4 rounded shadow-sm border border-1 caixa-filtros">
                <h5 class="fw-bold mb-4 texto-dorgita">Categorías</h5>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <a href="#" class="enlace-filtro">Ferretería </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="enlace-filtro"> Alimentación </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="enlace-filtro"> Fogar </a>
                    </li>
                </ul>

                <hr class="my-4">

                <h5 class="fw-bold mb-3 texto-dorgita">Prezo</h5>
                <input type="range" class="form-range" min="0" max="20000">
                <div class="d-flex justify-content-between small text-muted mt-2">
                    <span>0€</span>
                    <span>20.000€</span>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-engadir-carro w-100 py-2 fs-5">Filtrar</button>
                </div>

            </div>
        </div>
    </div>
</div>