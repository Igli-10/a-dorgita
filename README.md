# A Dorgita - Plataforma de Comercio Electrónico Local

Este proxecto consiste no desenvolvemento dunha aplicación web e-commerce destinada á dixitalización e modernización da actividade comercial da tenda tradicional **"A Dorgita"**, situada na Silva, no concello de Cerceda (A Coruña). O sistema centraliza nun único almacén de datos unha zona pública de catálogo para clientes e un panel de control privado para a xestión integral do negocio.

##  Características Principais

### Zona Pública (Interface de Usuario)
* **Catálogo Dinámico:** Navegación por categorías con ferramentas de filtrado por rango de prezos.
* **Buscador Asíncrono (AJAX):** Caixa de procura intelixente que ofrece suxestións con imaxes en tempo real mentres o usuario escribe.
* **Carro da Compra Lateral:** Engadido e actualización dinámica de produtos mediante un panel *offcanvas* asíncrono.
* **Lista de Favoritos:** Espacio persoal para gardar e xestionar artigos de interese sem necesidade de recargar a páxina.
* **Autenticación Segura:** Sistema de rexistro, inicio de sesión e módulo de recuperación de contrasinais mediante token por correo electrónico.
* **Perfil Privado:** Historial completo de pedidos co seu estado en tempo real e funcionalidade para subir unha foto de perfil.
* **Sistema de Reseñas:** Valoracións con comentarios e puntuacións de 1 a 5 estrelas para os produtos adquiridos.

### Zona Privada (Panel de Administración)
* **Dashboard de Control:** Resumo visual con estatísticas clave sobre produtos esgotados, pedidos pendentes e artigos máis vendidos.
* **Xestión de Catálogo (CRUD):** Ferramentas completas para crear, editar, visualizar e eliminar produtos e categorías.
* **Xestión de Pedidos:** Control de fluxos comerciais, alteración de estados de envío e actualización automática de stock.
* **Algoritmo de Stock Automatizado:** O sistema de forma autónoma desconta o inventario coas compras e devolve as unidades correspondentes ao almacén se un pedido se marca como 'cancelado'.
* **Control de Usuarios:** Listaxe de contas rexistradas con capacidade para alterar roles en tempo real.

## 🛠️ Tecnoloxías e Librarías Empregadas

* **Back-end:** PHP (Versión 8.2.12)
* **Base de Datos:** MySQL / MariaDB (Versión 10.4.32)
* **Front-end:** HTML5, CSS3, JavaScript Nativo (AJAX) e Bootstrap (Versión 5.3.8)
* **Arquitectura:** Patrón de deseño Modelo-Vista-Controlador (MVC) e Data Access Object (DAO)
* **Seguridade:** Conexión PDO con sentenzas preparadas e cifrado de contrasinais co algoritmo HASH BCRYPT
* **Librarías Externas:**
  * **PHPMailer (Versión 5.5):** Xestión e envío seguro de correos electrónicos para o fluxo de recuperación de claves.
  * **FPDF (Versión 1.86):** Xeración automatizada en servidor de facturas en formato PDF para cada pedido.

## Estrutura do Proxecto (Patrón MVC)

```plaintext
a-dorgita/
├── app/
│   ├── controllers/      # Controladores da lóxica de negocio (Ex: ContactoController)
│   ├── models/           # Entidades e Clases de Acceso a Datos (DAOs)
│   └── views/            # Vistas estruturadas en ficheiros PHP e subcarpetas
│       ├── partials/     # Fragmentos de interface reutilizables
│       └── contacto.php  # Vista do formulario autónomo de contacto
├── config/
│   ├── database.php      # Clase de conexão estática PDO
│   └── inicializador.php # Script de montaxe automática da base de datos
├── includes/
│   ├── header.php        # Barra de navegación corporativa superior (Verde Dorgita)
│   └── footer.php        # Peche de páxina legal institucional
├── public/
│   ├── css/
│   │   ├── bootstrap.min.css # Framework de deseño responsivo
│   │   └── estilos.css       # Guía de estilos personalizada (Times New Roman, Nextgal Red)
│   ├── js/
│   │   └── buscador.js       # Captura de eventos e lóxica asíncrona AJAX
│   └── img/                  # Almacén de imaxes de produtos e fotos de usuario
├── sql/
│   └── a_dorgita.sql     # Script de estrutura relacional e inserts de proba
└── index.php             # Enrutador principal (Front Controller) da aplicación
