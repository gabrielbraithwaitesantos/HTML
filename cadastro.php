<?php
require_once 'db.php';
$mensagem = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? ''; // senha sem trim: espaços podem ser propositais

    if ($nome === '' || $email === '' || $senha === '') {
        $mensagem = "Preencha todos os campos!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido!";
    } elseif (strlen($senha) < 6) {
        $mensagem = "A senha deve ter pelo menos 6 caracteres!";
    } else {
        // Verifica se o e-mail já existe (prepared statement)
        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt_check->execute([':email' => $email]);

        if ($stmt_check->rowCount() > 0) {
            $mensagem = "Este e-mail já está cadastrado!";
        } else {
            // Nunca salvar senha pura: password_hash gera o hash (bcrypt)
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha_hash]);
            $mensagem = "Usuário cadastrado com sucesso! Já pode fazer login.";
            $sucesso = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="centro">
    <main class="cartao">
        <h2>Criar Nova Conta</h2>
        <?php if ($mensagem !== ''): ?>
            <p class="aviso <?php echo $sucesso ? 'ok' : 'erro'; ?>"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
        <form action="cadastro.php" method="POST">
            <label>Nome</label>
            <input type="text" name="nome" maxlength="100" required>
            <label>E-mail</label>
            <input type="email" name="email" maxlength="100" required>
            <label>Senha (mínimo 6 caracteres)</label>
            <input type="password" name="senha" minlength="6" required>
            <button type="submit">Cadastrar</button>
            <p>Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
        </form>
    </main>
</body>
</html>
