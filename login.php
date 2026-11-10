<?php
session_start();
require_once 'db.php';

// Se já está logado, vai direto para a página principal
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email !== '' && $senha !== '') {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // E-mail existe E a senha confere com o hash?
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true); // novo ID de sessão ao logar (evita session fixation)
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header('Location: index.php');
            exit;
        } else {
            $mensagem = "E-mail ou senha incorretos!";
        }
    } else {
        $mensagem = "Preencha todos os campos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="centro">
    <main class="cartao">
        <h2>Acessar o Sistema</h2>
        <?php if ($mensagem !== ''): ?>
            <p class="aviso erro"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label>E-mail</label>
            <input type="email" name="email" required>
            <label>Senha</label>
            <input type="password" name="senha" required>
            <button type="submit">Entrar</button>
            <p>Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
        </form>
    </main>
</body>
</html>
