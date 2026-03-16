

<div class="container py-5 mt-5">
    <div class="row g-5">
        <div class="col-md-6 text-center">
            <div class="p-4 rounded shadow-sm border border-1 caixa-filtros">
               <img src="public/img/<?php echo htmlspecialchars($prod->getImagen()); ?>" 
                     class="img-fluid" 
                     alt="<?php echo htmlspecialchars($prod->getNome()); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="index.php?c=producto&a=index" class="enlace-filtro p-0">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($prod->getNome()); ?></li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold texto-dorgita mb-3"><?php echo htmlspecialchars($prod->getNome());?></h1>
            <p class="fs-3 fw-bold texto-principal mb-4"><?php echo htmlspecialchars($prod->getPrecio());?> €</p>
            
            <div class="mb-4">
                <h6 class="fw-bold texto-dorgita">Descrición:</h6>
                <p class="text-muted"><?php echo htmlspecialchars($prod->getDescripcion());?></p>
            </div>

            <hr class="my-4">

            <div class="d-flex align-items-center gap-3">
                <div class="input-group ancho-cantidade-produto" >
                    <button class="btn btn-cantidade" type="button">-</button>
                    <input type="text" class="form-control text-center" value="1" readonly>
                    <button class="btn btn-cantidade" type="button">+</button>
                </div>
                <button class="btn btn-engadir-carro btn-lg px-5 w-100">
                    Engadir ao carro
                </button>
            </div>
        </div>
    </div>
</div>
