<?php

// Traz a conexão com o banco de dados que fizemos no db.php.
// require_once significa: traz o arquivo obrigatoriamente, mas se já carregou antes, não duplica.
require_once 'db.php';

// Variável para guardar avisos de sucesso ou de erro.
// Começa vazia pra não dar erro no PHP se a pessoa só abriu a página e ainda não clicou em nada.
$mensagem = '';

// Verifica se o formulário foi enviado pelo botão (método POST).
// $_SERVER é uma variável pronta do PHP com dados do acesso.
// REQUEST_METHOD guarda 'GET' (só abriu a página) ou 'POST' (clicou no botão de enviar).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // $_POST: pega o que foi digitado em cada campo pelo atributo name="..." do HTML.
    // trim(): limpa espaços extras no começo e no fim (ex: " gabriel " vira "gabriel").
    // Na senha NÃO usamos trim(), porque se a pessoa colocou espaço de propósito na senha dela, temos que respeitar!
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // empty(): confere se algum dos campos ficou em branco
    if (!empty($nome) && !empty($email) && !empty($senha)) {
        //essa parte de email me quebrou mt n vou mentir, ainda n entendi 100% n vou mentir, temos q ver isso e na real essas linhas como um todos é meio complicado, é validação tlgd ai é fd

        // Verifica se o e-mail já existe no banco:
        $sql_check = "SELECT id FROM usuarios WHERE email = :email";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':email' => $email]);

        // rowCount() > 0 significa que achou alguém com esse e-mail
        if ($stmt_check->rowCount() > 0) {
            $mensagem = "Este e-mail já está cadastrado!";
        } else {
            // Criptografa a senha antes de salvar no banco (NUNCA salve senha pura!).
            // PASSWORD_DEFAULT: manda o PHP usar o algoritmo de criptografia mais recomendado hoje (atualmente o BCRYPT).
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Prepara o comando SQL com segurança (Prepared Statement pra evitar invasão por SQL Injection).
            // :nome, :email, :senha são "lacunas/etiquetas" que o MySQL reserva antes de receber os valores reais.
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $pdo->prepare($sql);

            // Executa o comando preenchendo as lacunas com os dados reais e grava no MySQL.
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha_hash
            ]);

            $mensagem = "Usuário cadastrado com sucesso!";
        }

    } else {
        // Se faltou preencher algo, avisa na hora:
        $mensagem = "Preencha todos os campos!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
</head>

<body>
    <h2>Criar Nova Conta</h2>

    <!-- Se tiver mensagem (de sucesso ou erro), exibe aqui na tela -->
    <?php if (!empty($mensagem)): ?>
        <p><strong><?php echo $mensagem; ?></strong></p>
    <?php endif; ?>

    <!-- Formulário de cadastro: -->
    <!-- method="POST": envia os dados escondidos na requisição (muito mais seguro pra senhas do que GET) -->
    <form action="cadastro.php" method="POST">

        <div>
            <label>Nome: </label><br>
            <!-- name="nome": o PHP usa esse nome pra capturar no $_POST['nome'] -->
            <!-- required: impede de enviar o formulário se o campo estiver vazio -->
            <input type="text" name="nome" required>
        </div>
        <br>

        <div>
            <label>E-mail: </label><br>
            <!-- name="email": o PHP usa pra capturar no $_POST['email'] -->
            <input type="email" name="email" required>
        </div>
        <br>

        <div>
            <label>Senha: </label><br>
            <!-- type="password": troca as letras por bolinhas pra ninguém ver a senha digitada -->
            <!-- name="senha": o PHP usa pra capturar no $_POST['senha'] -->
            <input type="password" name="senha" required>
        </div>
        <br>

        <!-- type="submit": botão que dispara o envio do formulário pro arquivo do action (cadastro.php) -->
        <button type="submit">Cadastrar</button>

        <p>Já tem uma conta? <a href="login.php">Faça Login Aqui</a></p>
    </form>
</body>

</html>