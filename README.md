# carrinho-compras

SQL para inserir produtos no carrinho

INSERT INTO pedidos
(id, user_id, status, created_at, updated_at)
VALUES (1, 1, 'RE', now(), now());

INSERT INTO pedido_produtos
(id, pedido_id, produto_id, status, valor, desconto, cupom_desconto_id, created_at, updated_at)
VALUES (1, 1, 2, 'RE', 30.00, 0, NULL, now(), now())