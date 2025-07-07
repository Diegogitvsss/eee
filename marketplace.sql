-- Banco de dados atualizado para tela de cadastro de produto do vendedor

CREATE DATABASE IF NOT EXISTS marketplace DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE marketplace;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('comprador','vendedor','ambos') NOT NULL DEFAULT 'comprador',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    categoria VARCHAR(100),
    estoque INT DEFAULT 0,
    metodo_pagamento VARCHAR(200),
    rastreamento VARCHAR(100),
    imagem VARCHAR(255),
    id_vendedor INT,
    peso DECIMAL(8,2) DEFAULT 0.0,
    dimensoes VARCHAR(40),
    envio VARCHAR(40),
    garantia VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_vendedor) REFERENCES usuarios(id)
);

-- Outras tabelas podem ser adicionadas conforme o crescimento do projeto,
-- como pedidos, lojas, avaliações, endereços, etc.

select * from produtos;