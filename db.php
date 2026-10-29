<?php

// Configurações do banco de dados:
// $host: Onde tá o MySQL? No nosso caso é 'localhost' (nossa própria máquina).
// $dbname: Nome do banco que a gente criou lá no banco.sql ('gerenciador_tarefas').
// $user: Usuário padrão do XAMPP (por padrão é 'root').
// $pass: Senha padrão do XAMPP (por padrão vem vazia '').

// PDO (PHP Data Objects):
// É a ferramenta do PHP feita pra conversar com o banco de dados com segurança.

$host = 'localhost';
$dbname = 'gerenciador_tarefas';
$user = 'root';
$pass = '';

try {
    // Abre a conexão com o MySQL. Se der certo, fica tudo guardado no $pdo pra gente usar no projeto.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    // Avisa pro PHP: "Se der qualquer erro de SQL, NÃO fique em silêncio! Mostra o erro na tela!"
    // A setinha (->) serve pra acessar uma função de dentro do $pdo (tipo apertar um botão no controle remoto).
    // setAttribute(): função pra mudar uma regra/configuração do PDO.
    // ATTR_ERRMODE e ERRMODE_EXCEPTION: ativa o modo alarme pra avisar onde deu erro.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Se a conexão falhar, cai direto aqui no catch.
    // O $e guarda as informações do erro.
    // getMessage(): pega só o texto da bronca que o MySQL deu pra gente entender o que houve.
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
