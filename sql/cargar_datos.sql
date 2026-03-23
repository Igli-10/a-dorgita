-- 1. Inserir Categorías
INSERT INTO categorias (nome) VALUES 
('Ferretería'), 
('Alimentación'), 
('Fogar');

-- 2. Inserir Produtos
INSERT INTO productos (nome, descripcion, precio, stock, imagen, id_categoria) VALUES 
('Caixa de ferramentas Tritón', '45 pezas de aceiro cromo-vanadio.', 180.00, 10, 'caixa_ferramentas.png', 1),
('Aceite de Oliva Virxe Extra', 'Aceite O Noso 1L, extracción en frío.', 9.50, 50, 'aceite_oliva_virxe.png', 2),
('Tostadora Moulinex', 'Compacta, 2 ranuras, estilo aceiro.', 35.00, 15, 'tostadora.png', 3),
('Xogo de Chaves Fixas', '8 pezas con soporte para colgar.', 24.95, 20, 'xogo_chaves.png', 1);

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