<?php
// 1. INICIA A SESSÃO (deve ser a primeira linha do PHP)
session_start();

require_once 'db.php';
$mensagem = '';

// 2. Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (!empty($email) && !empty($senha)) {

        // 3. Busca o usuário no banco pelo e-mail
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        // Pega os dados do usuário encontrado
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. VERIFICAÇÃO: E-mail existe E a senha bate com o hash?
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Salva na SESSÃO para lembrar quem está logado
            $_SESSION['usuario_id']   = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            // Redireciona para a página principal
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
</head>
<body>

    <h2>Acessar o Sistema</h2>

    <?php if (!empty($mensagem)): ?>
        <p><strong><?php echo $mensagem; ?></strong></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div>
            <label>E-mail:</label><br>
            <input type="email" name="email" required>
        </div>
        <br>

        <div>
            <label>Senha:</label><br>
            <input type="password" name="senha" required>
        </div>
        <br>

        <button type="submit">Entrar</button>

        <p>Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
    </form>

</body>
</html>