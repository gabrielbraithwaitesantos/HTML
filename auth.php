<?php
// Incluir este arquivo no topo de toda página/API que exige login.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Funções de apoio para as APIs (resposta e leitura de JSON)
function json_out($dados, $codigo = 200) {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}
function ler_json() {
    $d = json_decode(file_get_contents('php://input'), true);
    return is_array($d) ? $d : [];
}

// Sem usuário na sessão = não autenticado
if (!isset($_SESSION['usuario_id'])) {
    if (defined('EH_API')) {
        json_out(['erro' => 'Sessão expirada. Faça login novamente.'], 401);
    }
    header('Location: login.php');
    exit;
}
