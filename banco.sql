-- Script de criação do banco do Gerenciador de Tarefas
-- Importe este arquivo no phpMyAdmin (aba "Importar") ou pelo terminal: mysql -u root < banco.sql
CREATE DATABASE IF NOT EXISTS gerenciador_tarefas CHARACTER SET utf8mb4;
USE gerenciador_tarefas;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL, -- não pode haver 2 contas com o mesmo e-mail
    senha VARCHAR(255) NOT NULL         -- guarda o HASH da senha, nunca a senha pura
);

-- Cada matéria pertence a um usuário (assim um usuário não mexe nas matérias do outro)
CREATE TABLE IF NOT EXISTS materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_materia VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela que relaciona usuário e matéria através das tarefas
CREATE TABLE IF NOT EXISTS tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    materia_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    data_entrega DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pendente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE
);
-- Sem dados de teste: crie o primeiro usuário pela tela de cadastro (a senha precisa ser salva com hash).
