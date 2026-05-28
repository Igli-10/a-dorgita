# A Dorgita - Tenda Online (Proxecto DAW)

Este é o repositorio do meu proxecto de fin de ciclo superior de Desenvolvemento de Aplicacións Web (DAW) [1]. Fixen unha aplicación web completa para dixitalizar unha tenda tradicional da miña zona chamada "A Dorgita", que está situada na aldea da Silva, en Cerceda [2]. A idea era construír un e-commerce real que conectase a tenda física de toda a vida co mundo dixital.

## Que fai a aplicación?

A web ten dúas partes separadas. Por un lado está a tenda pública onde os clientes poden mercar [3]. Ten un buscador con AJAX que vai mostrando os resultados en tempo real e un catálogo onde se pode filtrar por prezo ou categoría [3, 4]. Tamén programei un carro da compra asíncrono, o que significa que podes ir engadindo cousas dende un panel lateral sen que a páxina teña que recargar [4, 5]. Calquera persoa pode crearse unha conta, recuperar o seu contrasinal se o esquece, deixar reseñas de 1 a 5 estrelas nos produtos e revisar o historial dos seus pedidos no seu perfil privado [5-7]. Ademais, ao rematar unha compra, o sistema xera automaticamente a factura do cliente en formato PDF [5].

Por outro lado, programei un panel de administración protexido ao que só acceden as contas con rol de administrador [8, 9]. Dende aí pódense engadir, modificar ou borrar produtos e categorías moi facilmente [10]. O que máis me gusta desta parte é que o stock da tenda é totalmente automático [11]. Cando un cliente fai unha compra, o inventario descóntase só da base de datos. E se desde o panel de control eu decido cancelar un pedido porque houbo algún problema, o sistema encárgase automaticamente de devolver esas unidades ao stock dispoñible para que non haxa erros no almacén [11, 12].

## Como está programado?

Para non ter un código caótico, estruturei todo o proxecto baixo o patrón MVC (Modelo-Vista-Controlador) [13]. 

- No frontend usei HTML5, CSS3, Javascript nativo para as peticións AJAX e o framework Bootstrap (5.3.8) para que a web se vexa perfectamente dende o móbil [14, 15].
- O backend está feito en PHP (8.2.12) e a base de datos é MySQL [15, 16].
- A nivel de seguridade, blindei o acceso á base de datos usando obxectos PDO con sentenzas preparadas, evitando así inxeccións SQL, e encriptei todos os contrasinais [16].
- A maiores utilicei dúas librarías externas: FPDF para poder xerar as facturas en PDF, e PHPMailer para automatizar os correos [16, 17].

## Como probalo no teu ordenador

Se queres clonar o proxecto e darlle unha volta, o proceso de instalación é súper sinxelo porque automaticei a base de datos. 

1. O único que necesitas é ter instalado XAMPP (ou calquera servidor local) e acender os servizos de Apache e MySQL [18].
2. Abre a túa terminal dentro da carpeta `htdocs` do teu XAMPP e descarga o proxecto con este comando: `git clone https://github.com/Igli-10/a-dorgita.git` [18, 19].
3. Non tes que ir a phpMyAdmin para importar bases de datos a man nin nada diso. Simplemente arranca a aplicación abrindo o navegador na ruta `http://localhost/a-dorgita`. Cando carga por primeira vez, o propio código encárgase de construír as táboas en MySQL e de meter todos os datos de proba automaticamente. 
