<!-- Aqui tem só o HTML por enquanto, o PHP vamos colocar depois -->

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
</head>

<body>
    <h2>Criar Nova Conta</h2>

    <!-- Sim, comentário em HTML é com essa tag esquisita mesmo kkk -->
    <!-- Formulário de cadastro: -->
    <!-- method="POST": envia os dados escondidos no corpo da requisição (muito mais seguro pra senhas do que GET, que mostra tudo na URL) -->
    <form action="cadastro.php" method="POST">

        <div>
            <label>Nome: </label><br>
            <!-- name="nome": MUITO IMPORTANTE! É o nome que o PHP usa pra capturar o que foi digitado ($_POST['nome']) -->
            <!-- required: impede de enviar o formulário se o campo estiver vazio -->
            <input type="text" name="nome" required>
        </div>
        <br>

        <div>
            <label>E-mail: </label><br>
            <!-- name="email": nome que o PHP usa pra pegar o e-mail digitado ($_POST['email']) -->
            <input type="email" name="email" required>
        </div>
        <br>

        <div>
            <label>Senha: </label><br>
            <!-- type="password": troca as letras por bolinhas pra ninguém em volta ver a senha digitada -->
            <!-- name="senha": nome que o PHP usa pra capturar a senha ($_POST['senha']) -->
            <input type="password" name="senha" required>
        </div>
        <br>

        <!-- type="submit": botão que dispara o envio do formulário pro arquivo do action (cadastro.php) -->
        <button type="submit">Cadastrar</button>

        <p>Já tem uma conta? <a href="login.php">Faça Login Aqui</a></p>
    </form>
</body>

</html>
