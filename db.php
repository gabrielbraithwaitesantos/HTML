<?php
// Conexão com o MySQL usando PDO. Ajuste usuário/senha se o seu XAMPP for diferente.
$host = 'localhost';
$dbname = 'gerenciador_tarefas';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Modo exceção: qualquer erro de SQL vira uma exceção que podemos tratar
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
