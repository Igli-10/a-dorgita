<?php
$productos = [
    [
        'titulo' => "Caixa de ferramentas 'Tritón'",
        'descripcion' => "45 pezas de aceiro cromo-vanadio.",
        'prezo' => "18.000 €",
        'imaxe' => "public/img/caixa_ferramentas.png"
    ],
    [
        'titulo' => "Aceite de Oliva Virxe Extra",
        'descripcion' => "Aceite 'O Noso' 1L, extracción en frío.",
        'prezo' => "9.50 €",
        'imaxe' => "public/img/aceite_oliva_virxe.png"
    ],
    [
        'titulo' => "Tostadora 'Moulinex'",
        'descripcion' => "Compacta, 2 ranuras, estilo aceiro.",
        'prezo' => "35.00 €",
        'imaxe' => "public/img/tostadora.png"
    ]
];

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
                <?php foreach ($productos as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-1" style="background-color: #FFFFFF;">
                            <img src="<?php echo $p['imaxe']; ?>" class="card-img-top p-4" alt="Producto">
                            <div class="card-body d-flex flex-column text-center">
                                <h5 class="card-title fw-bold texto-principal"><?php echo $p['titulo']; ?></h5>
                                <p class="card-text small"><?php echo $p['descripcion']; ?></p>

                                <div class="mt-auto">
                                    <p class="fw-bold fs-5 texto-dorgita"><?php echo $p['prezo']; ?></p>
                                    <a href="#" class="btn btn-engadir-carro">ENGADIR AO CARRO</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-3">

        </div>
    </div>
</div>