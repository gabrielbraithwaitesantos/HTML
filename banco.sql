CREATE DATABASE IF NOT EXISTS gerenciador_tarefas;
-- usar o if not exists para tipo boas praticas pq vai que ja tem tlgd, espero q vcs realmente leam -- os comentarios pq literalmente to me esforçando
-- tudo oque estiver abaixo vai ser dentro do banco de dados gerenciador_tarefas 
USE  gerenciador_tarefas;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- O AUTO INCREMENT é para o mysql gerar numeros do id sozinho a cada cadastro, sla so pra eu lembrar mesmo
    -- 100 seria os caracteres e sim to criando e vou ficar comentando dps nos tira galera e o mysql nao guarda todas as letras q digitas aqui nos 100, o 100 seria um limite de segurança
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    -- unique é pra ser untipo nao pode ter 2 pessoas com o mesmo email
    senha VARCHAR(255) NOT NULL

);

CREATE TABLE IF NOT EXISTS materias(
    id iNT AUTO_INCREMENT PRIMARY KEY,
    nome_materia VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    materia_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT, -- text n precisa definir um numero grandão
    data_entrega DATE NOT NULL, -- date é padrao americano
    status VARCHAR(20) DEFAULT 'Pendente',
    -- é pra colocar ja como pendente tlgd pq vc criou agr ne kkkkk seria bom se a gente criasse e ja tivesse concluido né mas fazer oq, imagina ter rust pendente e ja n ter mais, coisa boa

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE

);


-- teste:

INSERT INTO usuarios (nome, email, senha) VALUES
('Gabriel lindo', 'gabriellindo@hotrodrigo.com', '123456');

INSERT INTO materias (nome_materia, codigo) VALUES
('sla', 'SEILA001'),
('RUST', 'BD002');

INSERT INTO tarefas (usuario_id, materia_id, titulo, descricao, data_entrega) VALUES
(1, 1, 'Entregar infelizmente rust', 'Perder horas e não aprender nada', '2026-02-26');


