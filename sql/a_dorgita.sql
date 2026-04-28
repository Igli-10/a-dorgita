CREATE DATABASE IF NOT EXISTS a_dorgita;
USE a_dorgita;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descripcion VARCHAR(500)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasinal VARCHAR(250) NOT NULL,
    rol ENUM('admin', 'cliente'),
    foto_perfil VARCHAR(255) DEFAULT 'default.png'
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descripcion VARCHAR(500),
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    imagen VARCHAR(200),
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'pendente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE detalles_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    cantidade INT NOT NULL,
    prezo_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

CREATE TABLE IF NOT EXISTS recuperacion_contrasinal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    caduca_en DATETIME NOT NULL,
    usado TINYINT(1) NOT NULL DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_usuario (id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS favoritos (
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resenas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    comentario TEXT,
    puntuacion INT CHECK(puntuacion BETWEEN 1 AND 5),
    data_resena DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensaxe TEXT NOT NULL,
    data_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 1. Inserir Categorías
INSERT INTO categorias (nome, descripcion) VALUES 
('Ferretería', 'Produtos e ferramentas para reparación, bricolaxe e mantemento.'), 
('Alimentación', 'Artigos de consumo diario e produtos de despensa.'), 
('Fogar', 'Electrodomésticos e accesorios para o fogar.');

-- 2. Inserir Produtos
INSERT INTO productos (nome, descripcion, precio, stock, imagen, id_categoria) VALUES 
('Caixa de ferramentas Tritón', '45 pezas de aceiro cromo-vanadio.', 180.00, 10, 'caixa_ferramentas.png', 1),
('Aceite de Oliva Virxe Extra', 'Aceite O Noso 1L, extracción en frío.', 9.50, 50, 'aceite_oliva_virxe.png', 2),
('Tostadora Moulinex', 'Compacta, 2 ranuras, estilo aceiro.', 35.00, 15, 'tostadora.png', 3),
('Xogo de Chaves Fixas', '8 pezas con soporte para colgar.', 24.95, 20, 'xogo_chaves.png', 1),
('Martelo Profesional', 'Cabeza de aceiro temperado e mango ergonómico.', 14.90, 30, 'martelo_profesional.png', 1),
('Destornillador Phillips', 'Punta imantada para parafusos de estrela.', 6.20, 45, 'destornillador_phillips.png', 1),
('Taladro Eléctrico 650W', 'Velocidade variable e maletín incluído.', 79.99, 12, 'taladro_electrico.png', 1),
('Silicona Universal', 'Selado resistente para interior e exterior.', 5.80, 60, 'silicona_universal.png', 1),
('Arroz Redondo 1kg', 'Ideal para receitas tradicionais.', 2.30, 80, 'arroz_redondo.png', 2),
('Pasta Espagueti 500g', 'Sémola de trigo duro, cocción 9 minutos.', 1.45, 90, 'pasta_espagueti.png', 2),
('Leite Enteiro 1L', 'Leite galego UHT.', 1.10, 120, 'leite_entero.png', 2),
('Conserva de Atún', 'Pack de 3 latas en aceite vexetal.', 3.95, 70, 'conserva_atun.png', 2),
('Galletas Mariñeiras', 'Textura crocante, formato familiar.', 2.75, 55, 'galletas_marineiras.png', 2),
('Microondas 20L', '700W con función desconxelado.', 89.00, 10, 'microondas.png', 3),
('Cafeteira Italiana', 'Capacidade para 6 cuncas.', 21.50, 22, 'cafeteira_italiana.png', 3),
('Aspiradora Compacta', 'Potente e lixeira para uso diario.', 109.00, 9, 'aspiradora_compacta.png', 3),
('Lámpada de Mesa LED', 'Baixo consumo e luz cálida.', 18.90, 35, 'lampada_mesa_led.png', 3),
('Plancha de Vapor', 'Base cerámica antiaherente.', 42.00, 17, 'plancha_vapor.png', 3),
('Broca para metal 6mm', 'Aceiro rápido HSS para perforación precisa.', 3.40, 65, 'broca_metal_6mm.png', 1),
('Cinta americana reforzada', 'Alta adherencia para reparacións rápidas.', 4.95, 50, 'cinta_americana.png', 1),
('Azucre branco 1kg', 'Granulado fino para cociña e repostaría.', 1.65, 85, 'azucre_branco.png', 2),
('Sal mariña fina 1kg', 'Sal refinada de uso alimentario.', 0.95, 100, 'sal_marina_fina.png', 2),
('Robot de cociña básico', '3 velocidades e vaso de 1.5L.', 129.00, 8, 'robot_cocina.png', 3),
('Sartén antiadherente 24cm', 'Revestimento resistente e mango frío.', 19.90, 28, 'sarten_antiadherente.png', 3),
('Chave inglesa axustable', 'Apertura máxima de 30 mm.', 11.75, 40, 'chave_inglesa_axustable.png', 1);

-- 3. Inserir Usuarios
INSERT INTO usuarios (nome, email, contrasinal, rol, foto_perfil) VALUES 
('Carlos Cliente', 'carlos@gmail.com', '$2y$10$5xgACm45hH7Y6SAOZLf5/uyIwLBBgC.55aHuIe6V66JJ4PXmntqHC', 'cliente', 'default.png'),
('Admin Dorgita', 'admin@adorgita.com', '$2y$10$foRz/BZ0h7Jy3/CFMeKDs.zNptDYed8mrCQz12p9zCWgmyYWVtvOG', 'admin', 'default.png'),
('Lucía Silva', 'lucia@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 'default.png'),
('Marcos Pérez', 'marcos@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 'default.png'),
('Elena Varela', 'elena@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 'default.png');

-- 4. Inserir Pedidos
INSERT INTO pedidos (id_usuario, total, estado) VALUES (1, 180.00, 'enviado');
INSERT INTO detalles_pedido (id_pedido, id_producto, cantidade, prezo_unitario) VALUES (1, 1, 1, 180.00);

INSERT INTO pedidos (id_usuario, total, estado) VALUES (1, 54.00, 'pendente');
INSERT INTO detalles_pedido (id_pedido, id_producto, cantidade, prezo_unitario) VALUES (2, 2, 2, 9.50);
INSERT INTO detalles_pedido (id_pedido, id_producto, cantidade, prezo_unitario) VALUES (2, 3, 1, 35.00);

-- 5. Inserir algunhas reseñas de proba para Carlos (ID 1)
INSERT INTO resenas (id_producto, id_usuario, puntuacion, comentario) VALUES 
-- Ferramentas
(1, 1, 5, 'Excelente calidade, moi robusta.'),
(4, 3, 4, 'Cumpre a súa función perfectamente.'),
(5, 4, 5, 'Un imprescindible no fogar.'),
(6, 1, 3, 'Está ben, pero o mango resbala un pouco.'),
(7, 5, 5, 'Potencia incrible, moi contento.'),
(19, 3, 4, 'Bo material, non se despuntan fácil.'),
(20, 4, 4, 'Adhírese con moita forza.'),
(25, 1, 5, 'Robusta e cómoda de usar.'),

-- Alimentación
(2, 3, 5, 'Sabor auténtico, repetiría sen dúbida.'),
(9, 4, 4, 'Queda moi solto, ideal para paella.'),
(10, 5, 4, 'Boa textura, non se pasa de cocción.'),
(11, 1, 5, 'O mellor leite que probamos.'),
(12, 3, 3, 'Están ben, pero esperaba un pouco máis de calidade.'),
(13, 4, 5, 'Crocantes e naturais, encántanme.'),
(21, 5, 4, 'O prezo é competitivo para a cantidade que trae.'),
(22, 1, 4, 'Sal fina de boa calidade.'),

-- Fogar
(3, 4, 5, 'Dourado uniforme, moi rápida.'),
(14, 5, 4, 'Fácil de usar e moi intuitivo.'),
(15, 1, 5, 'O café sae cun aroma marabilloso.'),
(16, 3, 5, 'Pesa pouco e aspira moi ben.'),
(17, 4, 4, 'Luz moi agradable, non cansa a vista.'),
(18, 5, 3, 'Cumpre, pero o cable podería ser máis longo.'),
(23, 1, 4, 'Axúdame moito coas receitas de repostaría.'),
(24, 3, 5, 'Non se pega nada, moi fácil de limpar.');