// Captura o envío do formulario de engadir produtos e evita recargar a páxina (AJAX)
document.addEventListener('submit', function(e) {
    // Se o formulario é de engadir
    if (e.target && e.target.closest('form[action*="a=engadir"]')) {
        e.preventDefault(); // Evita que a páxina recargue
        
        const form = e.target;
        const formData = new FormData(form);

        // Envío vía Fetch
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' } // sinala que é AJAX
        })
        .then(response => response.text())
        .then(html => {
            // Mostro o offcanvas
            const offcanvasElement = document.getElementById('offcanvasCart');
            const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement, {
                backdrop: false, // permite seguir clicando na páxina
                scroll: true     // permite scroll mentres está aberto
            });
            bsOffcanvas.show();

            // Actualizo o contido do carrito
            document.getElementById('cart-content').innerHTML = html;

            // Sincronización: se estamos na vista de carro completo, recargamos a páxina
            if (window.location.href.includes('c=carro&a=index')) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Erro engadir produto:', error));
    }
});


// Captura clicks en sumar, restar ou eliminar produtos dentro do carrito. Funciona para calquera elemento con data-action="sumar/restar/eliminar"
document.addEventListener('click', function(e) {
    const actionBtn = e.target.closest('.cart-action[data-action]');
    if (!actionBtn) return;

    e.preventDefault(); // evita recargar

    const action = actionBtn.dataset.action;
    const url = actionBtn.href;

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(html => {
        // Actualizo o fragmento do carrito
        document.getElementById('cart-content').innerHTML = html;

        // Sincronización: se estamos na vista de carro completo, recargamos a páxina
        if (window.location.href.includes('c=carro&a=index')) {
            window.location.reload();
        }
    })
    .catch(error => console.error(`Erro ${action} produto:`, error));
});

// Función reutilizable para actualizar o contido do carrito Podese chamar despois de engadir, sumar ou restar sen recargar
function actualizarContidoLateral() {
    fetch('index.php?c=carro&a=get_fragment', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('cart-content').innerHTML = html;
    })
    .catch(error => console.error('Erro ao actualizar carrito:', error));
}