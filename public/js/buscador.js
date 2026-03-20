// Xestión de suxestións de búsqueda en tempo real

document.addEventListener('DOMContentLoaded', () => {
    const inputBusca = document.querySelector('input[name="q"]');
    const box = document.getElementById('suxestions-box');

    // Verificamos que os elementos existan na páxina para evitar erros de execución
    if (!inputBusca || !box) return;

    // Escoitamos cada vez que o usuario escribe ou borra unha letra
    inputBusca.addEventListener('input', function(e) {
        const termo = e.target.value.trim();

        // Só iniciamos a busca se o usuario escribiu polo menos 2 caracteres
        if (termo.length < 2) {
            box.style.display = 'none';
            return;
        }

        // Chamada AJAX ao controlador de produtos (acción suxerir)
        fetch(`index.php?c=producto&a=suxerir&q=${encodeURIComponent(termo)}`)
            .then(response => {
                if (!response.ok) throw new Error('Erro na resposta do servidor');
                return response.json();
            })
            .then(data => {
                // Se o servidor devolve produtos que coinciden
                if (data.length > 0) {
                    box.innerHTML = data.map((p, index) => {
                        // Lóxica para non poñer liña divisoria no último elemento
                        const isLast = index === data.length - 1;
                        const borderClass = isLast ? '' : 'border-bottom';
                        
                        
                        //  href apunta agora a 'a=obter&id=...' para ir directo ao produto
                        return `
                        <a href="index.php?c=producto&a=obter&id=${p.id}" 
                           class="list-group-item list-group-item-action py-3 d-flex align-items-center gap-2 ${borderClass}">
                            
                            <img src="/a-dorgita/public/img/${p.imagen || 'placeholder.png'}" 
                                 alt="${p.nome}" 
                                 class="suxestion-img rounded border shadow-sm">
                            
                            <div class="ms-2">
                                <div class="texto-buscador text-dark fw-bold">${p.nome}</div>
                                ${p.descripcion ? 
                                    `<div class="small text-muted text-truncate" style="max-width: 200px;">
                                        ${p.descripcion}
                                     </div>` 
                                    : ''
                                }
                            </div>
                        </a>`;
                    }).join('');
                    
                    // Amosamos o cadro de suxestións
                    box.style.display = 'block';
                } else {
                    // Se non hai resultados, amosamos unha mensaxe informativa
                    box.innerHTML = '<div class="list-group-item py-3 text-muted text-center">Non hai suxestións para esta busca</div>';
                    box.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Erro na suxestión AJAX:', error);
                box.style.display = 'none';
            });
    });

    // Pechamos o cadro de suxestións se o usuario fai clic fóra do buscador
    document.addEventListener('click', (e) => {
        if (!inputBusca.contains(e.target) && !box.contains(e.target)) {
            box.style.display = 'none';
        }
    });
});