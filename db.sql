-- ============================================
-- Script de Criação do Banco de Dados
-- Sistema de Pedidos - Dispensa Escolar
-- ============================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS dispensa_escolar 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE dispensa_escolar;

-- ============================================
-- Tabela: pedidos
-- Armazena informações principais dos pedidos
-- ============================================
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante VARCHAR(255) NOT NULL COMMENT 'Nome do solicitante',
    data_pedido DATE NOT NULL COMMENT 'Data do pedido',
    unidade VARCHAR(255) NOT NULL COMMENT 'Unidade/setor que fez o pedido',
    observacoes TEXT NULL COMMENT 'Observações adicionais',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação do registro',
    INDEX idx_solicitante (solicitante),
    INDEX idx_data_pedido (data_pedido),
    INDEX idx_unidade (unidade),
    INDEX idx_data_criacao (data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: pedido_itens
-- Armazena os itens de cada pedido
-- ============================================
CREATE TABLE IF NOT EXISTS pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL COMMENT 'ID do pedido (FK)',
    nome_item VARCHAR(255) NOT NULL COMMENT 'Nome identificador do item',
    label_item VARCHAR(255) NOT NULL COMMENT 'Label descritivo do item',
    quantidade VARCHAR(100) NOT NULL COMMENT 'Quantidade solicitada',
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pedido_id (pedido_id),
    INDEX idx_nome_item (nome_item)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Dados de exemplo (opcional - remover em produção)
-- ============================================

-- Inserir pedido de exemplo
INSERT INTO pedidos (solicitante, data_pedido, unidade, observacoes) VALUES 
('Maria Silva', '2025-10-07', 'Copa Central', 'Pedido urgente para evento');

-- Obter ID do último pedido
SET @pedido_id = LAST_INSERT_ID();

-- Inserir itens de exemplo
INSERT INTO pedido_itens (pedido_id, nome_item, label_item, quantidade) VALUES
(@pedido_id, 'detergente', 'Detergente (Ipê)', '2 CX'),
(@pedido_id, 'sabao_po', 'Sabão em Pó', '3 UNID'),
(@pedido_id, 'feijao', 'Feijão', '1 FARDO'),
(@pedido_id, 'acucar', 'Açúcar', '1 FARDO');

-- ============================================
-- Views úteis (opcional)
-- ============================================

-- View para relatório completo de pedidos
CREATE OR REPLACE VIEW vw_pedidos_completo AS
SELECT 
    p.id AS pedido_id,
    p.solicitante,
    p.data_pedido,
    p.unidade,
    p.observacoes,
    p.data_criacao,
    pi.nome_item,
    pi.label_item,
    pi.quantidade
FROM pedidos p
LEFT JOIN pedido_itens pi ON p.id = pi.pedido_id
ORDER BY p.id DESC, pi.id ASC;

-- View para resumo de pedidos por unidade
CREATE OR REPLACE VIEW vw_resumo_unidade AS
SELECT 
    unidade,
    COUNT(DISTINCT id) AS total_pedidos,
    MIN(data_pedido) AS primeiro_pedido,
    MAX(data_pedido) AS ultimo_pedido
FROM pedidos
GROUP BY unidade
ORDER BY total_pedidos DESC;

-- View para itens mais solicitados
CREATE OR REPLACE VIEW vw_itens_populares AS
SELECT 
    label_item,
    COUNT(*) AS vezes_solicitado
FROM pedido_itens
GROUP BY label_item
ORDER BY vezes_solicitado DESC
LIMIT 20;

-- ============================================
-- Verificação das tabelas criadas
-- ============================================
SHOW TABLES;

-- Estrutura da tabela pedidos
DESCRIBE pedidos;

-- Estrutura da tabela pedido_itens
DESCRIBE pedido_itens;

-- ============================================
-- Consulta de teste
-- ============================================
SELECT 
    'Banco de dados criado com sucesso!' AS status,
    COUNT(*) AS total_pedidos 
FROM pedidos;