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
('Martelo Profesional', 'Cabeza de aceiro temperado e mango ergonómico.', 14.90, 30, 'caixa_ferramentas.png', 1),
('Destornillador Phillips', 'Punta imantada para parafusos de estrela.', 6.20, 45, 'xogo_chaves.png', 1),
('Taladro Eléctrico 650W', 'Velocidade variable e maletín incluído.', 79.99, 12, 'caixa_ferramentas.png', 1),
('Silicona Universal', 'Selado resistente para interior e exterior.', 5.80, 60, 'xogo_chaves.png', 1),
('Arroz Redondo 1kg', 'Ideal para receitas tradicionais.', 2.30, 80, 'aceite_oliva_virxe.png', 2),
('Pasta Espagueti 500g', 'Sémola de trigo duro, cocción 9 minutos.', 1.45, 90, 'aceite_oliva_virxe.png', 2),
('Leite Entero 1L', 'Leite galego UHT.', 1.10, 120, 'aceite_oliva_virxe.png', 2),
('Conserva de Atún', 'Pack de 3 latas en aceite vexetal.', 3.95, 70, 'aceite_oliva_virxe.png', 2),
('Galletas Mariñeiras', 'Textura crocante, formato familiar.', 2.75, 55, 'aceite_oliva_virxe.png', 2),
('Microondas 20L', '700W con función desconxelado.', 89.00, 10, 'tostadora.png', 3),
('Cafeteira Italiana', 'Capacidade para 6 cuncas.', 21.50, 22, 'tostadora.png', 3),
('Aspiradora Compacta', 'Potente e lixeira para uso diario.', 109.00, 9, 'tostadora.png', 3),
('Lámpada de Mesa LED', 'Baixo consumo e luz cálida.', 18.90, 35, 'tostadora.png', 3),
('Plancha de Vapor', 'Base cerámica antiaherente.', 42.00, 17, 'tostadora.png', 3),
('Broca para metal 6mm', 'Aceiro rápido HSS para perforación precisa.', 3.40, 65, 'xogo_chaves.png', 1),
('Cinta americana reforzada', 'Alta adherencia para reparacións rápidas.', 4.95, 50, 'caixa_ferramentas.png', 1),
('Azucre branco 1kg', 'Granulado fino para cociña e repostaría.', 1.65, 85, 'aceite_oliva_virxe.png', 2),
('Sal mariña fina 1kg', 'Sal refinada de uso alimentario.', 0.95, 100, 'aceite_oliva_virxe.png', 2),
('Robot de cociña básico', '3 velocidades e vaso de 1.5L.', 129.00, 8, 'tostadora.png', 3),
('Sartén antiadherente 24cm', 'Revestimento resistente e mango frío.', 19.90, 28, 'tostadora.png', 3),
('Chave inglesa axustable', 'Apertura máxima de 30 mm.', 11.75, 40, 'xogo_chaves.png', 1);

-- 3. Inserir Usuarios (O ID xérase solo)
INSERT INTO usuarios (nome, email, contrasinal, rol) VALUES 
('Carlos Cliente', 'carlos@gmail.com', '$2y$10$5xgACm45hH7Y6SAOZLf5/uyIwLBBgC.55aHuIe6V66JJ4PXmntqHC', 'cliente'),
('Admin Dorgita', 'admin@adorgita.com', '$2y$10$foRz/BZ0h7Jy3/CFMeKDs.zNptDYed8mrCQz12p9zCWgmyYWVtvOG', 'admin');

-- 4. Inserir Pedidos
-- Pedido 1 para Carlos (ID 1)
INSERT INTO pedidos (id_usuario, total, estado) VALUES (1, 180.00, 'enviado');
-- Detalle para o pedido 1 (Produto 1)
INSERT INTO detalles_pedido (id_pedido, id_producto, cantidade, prezo_unitario) VALUES (1, 1, 1, 180.00);

-- Pedido 2 para Carlos (ID 1)
INSERT INTO pedidos (id_usuario, total, estado) VALUES (1, 54.00, 'pendente');
-- Detalles para o pedido 2 (Produtos 2 e 3)
INSERT INTO detalles_pedido (id_pedido, id_producto, cantidade, prezo_unitario) VALUES (2, 2, 2, 9.50);
INSERT INTO detalles_pedido (id_pedido, id_producto, cantidade, prezo_unitario) VALUES (2, 3, 1, 35.00);