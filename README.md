--------------------------------------------------------------------------------
# 🛒 A Dorgita - Plataforma de Comercio Electrónico Local

Este proxecto consiste no desenvolvemento dunha aplicación web e-commerce destinada á dixitalización e modernización da actividade comercial da tenda tradicional **"A Dorgita"**, situada na Silva, no concello de Cerceda (A Coruña) [2, 4]. 

O sistema centraliza nun único almacén de datos unha zona pública de catálogo para clientes e un panel de control privado para a xestión integral do negocio [2]. Foi desenvolvido por **Alejandro Iglesias Santos** como Proxecto de Desenvolvemento de Aplicacións Web (DAW) [1, 5].

---

## 🚀 Características Principais

### 🛍️ Zona Pública (Interface de Usuario)
* **Catálogo Dinámico:** Navegación por categorías con ferramentas de filtrado por rango de prezos [6].
* **Buscador Asíncrono (AJAX):** Caixa de procura intelixente que ofrece suxestións con imaxes en tempo real mentres o usuario escribe [6].
* **Carro da Compra Lateral:** Engadido e actualización dinámica de produtos mediante un panel *offcanvas* asíncrono, sen abandonar a páxina [6].
* **Lista de Favoritos:** Espazo persoal para gardar e xestionar artigos de interese sen necesidade de recargar a páxina [6].
* **Autenticación Segura:** Sistema de rexistro, inicio de sesión e módulo de recuperación de contrasinais mediante token por correo electrónico [6].
* **Perfil Privado:** Historial completo de pedidos co seu estado en tempo real e funcionalidade para subir unha foto de perfil [6].
* **Sistema de Reseñas:** Valoracións con comentarios e puntuacións de 1 a 5 estrelas para os produtos adquiridos [6].

### ⚙️ Zona Privada (Panel de Administración)
* **Dashboard de Control:** Resumo visual con estatísticas clave sobre produtos esgotados, pedidos pendentes e artigos máis vendidos [7].
* **Xestión de Catálogo (CRUD):** Ferramentas completas para crear, editar, visualizar e eliminar produtos e categorías [7].
* **Xestión de Pedidos:** Control de fluxos comerciais, alteración de estados de envío e actualización automática de stock [7].
* **Algoritmo de Stock Automatizado:** O sistema de forma autónoma desconta o inventario coas compras e devolve as unidades correspondentes ao almacén se un pedido se marca como 'cancelado' [7].
* **Control de Usuarios:** Listaxe de contas rexistradas con capacidade para alterar roles (Cliente / Administrador) en tempo real [7].

---

## 🛠️ Tecnoloxías e Librarías Empregadas

Este proxecto foi construído empregando as seguintes tecnoloxías e versións:

* **Back-end:** PHP (Versión 8.2.12) [3].
* **Base de Datos:** MySQL / MariaDB (Versión 10.4.32) [3].
* **Front-end:** HTML5, CSS3, JavaScript Nativo (AJAX) e Bootstrap (Versión 5.3.8) [3].
* **Arquitectura:** Patrón de deseño Modelo-Vista-Controlador (MVC) e Data Access Object (DAO) [3].
* **Seguridade:** Conexión PDO con sentenzas preparadas (bloqueo de inxeccións SQL) e cifrado de contrasinais co algoritmo HASH BCRYPT [3].

**Librarías Externas:**
* **PHPMailer (Versión 5.5):** Xestión e envío seguro de correos electrónicos para o fluxo de recuperación de claves [3].
* **FPDF (Versión 1.86):** Xeración automatizada no servidor de facturas en formato PDF para cada pedido finalizado [3].

---

## 📂 Estrutura do Proxecto (Patrón MVC)

A aplicación separa estritamente a lóxica de negocio, o acceso a datos e a interface visual seguindo o patrón MVC, o que garante un código limpo, doado de manter e preparado para futuras escalabilidades [8, 9].

---

## 💻 Instalación e Despregamento Local

Para executar este proxecto no teu equipo local, segue estes pasos:

1. **Requisitos previos:** Asegúrate de ter instalado un servidor local como **XAMPP (Versión 3.3.0 recomendada)** que inclúa Apache e MySQL [10, 11].
2. **Clonar o repositorio:** 
   Abre a túa terminal e clona este proxecto no directorio raíz do teu servidor web (por exemplo, `htdocs` en XAMPP):
   ```bash
   git clone https://github.com/Igli-10/a-dorgita.git
Base de datos:
Abre o panel de control de XAMPP e inicia os servizos de Apache e MySQL
.
Accede a phpMyAdmin (normalmente en http://localhost/phpmyadmin)
.
Crea unha base de datos nova.
Importa o ficheiro .sql incluído no repositorio para xerar todas as táboas e datos de proba (usuarios, produtos, categorías)
.
Configuración da conexión:
Revisa o ficheiro de configuración da base de datos (dentro da estrutura MVC) e asegúrate de que as credenciais (usuario, contrasinal e nome da base de datos) coinciden coas do teu contorno local
.
Execución:
Abre o teu navegador web e accede a http://localhost/a-dorgita para ver a tenda en funcionamento.

--------------------------------------------------------------------------------
📄 Propiedade Intelectual e Licenza
Copyright © 2026. Todos os dereitos reservados para Alejandro Iglesias Santos e "A Dorgita"
. A documentación do proxecto atópase baixo a Licenza Creative Commons Atribución-NonComercial-CompartirIgual (CC BY-NC-SA)
.