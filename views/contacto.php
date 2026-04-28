<div class="container py-5 mt-5">
    <!-- Nesta vista ensino os datos de contacto e un formulario de mensaxe -->
    <div class="row g-5 justify-content-center align-items-center">

        <div class="col-lg-5">
            <h2 class="fw-bold texto-principal mb-4">Fálanos de ti</h2>
            <p class="mb-4 fs-4">¿Tes algunha dúbida sobre os nosos produtos ou necesitas axuda co teu pedido? Estamos aquí para axudarche</p>

            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-geo-alt fs-4 texto-verde me-3"></i>
                <span class="texto-principal">Praza Campo das Nenas, 13, A Silva, Cerceda</span>
            </div>

            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-envelope fs-4 texto-verde me-3"></i>
                <span class="texto-principal">contacto@adorgita.com</span>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="p-5 rounded shadow-sm border caixa-filtros">
                <form method="POST" action="index.php?c=contacto&a=enviar">
                    <div class="mb-3">
                        <label for="nome" class="form-label fw-bold texto-principal">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold texto-principal">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="mensaxe" class="form-label fw-bold texto-principal">Mensaxe</label>
                        <textarea class="form-control" id="mensaxe" name="mensaxe" rows="5" required></textarea> 
                    </div>

                    <button type="submit" class="btn btn-engadir-carro w-100 py-2 fs-5">Enviar mensaxe</button>
                
                </form>

            </div>
        </div>



    </div>
</div>