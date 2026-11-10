<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main class="conteudo">
        <h2>Meu Perfil</h2>
        <p id="aviso" class="aviso" hidden></p>
        <section class="cartao">
            <form id="form-perfil">
                <label>Nome</label>
                <input type="text" id="nome" maxlength="100" required>
                <label>E-mail</label>
                <input type="email" id="email" maxlength="100" required>
                <label>Nova senha (deixe em branco para manter a atual)</label>
                <input type="password" id="senha" minlength="6">
                <button type="submit">Salvar alterações</button>
            </form>
            <hr>
            <button class="perigo" id="btn-excluir">Excluir minha conta</button>
        </section>
    </main>

    <script src="assets/app.js"></script>
    <script>
        const $ = id => document.getElementById(id);

        api('api/usuario.php').then(u => { $('nome').value = u.nome; $('email').value = u.email; })
            .catch(e => mostrarAviso(e.message, false));

        $('form-perfil').addEventListener('submit', async e => {
            e.preventDefault();
            try {
                const r = await api('api/usuario.php', 'PUT',
                    { nome: $('nome').value, email: $('email').value, senha: $('senha').value });
                mostrarAviso(r.mensagem); $('senha').value = '';
            } catch (err) { mostrarAviso(err.message, false); }
        });

        $('btn-excluir').addEventListener('click', async () => {
            if (!confirm('Excluir sua conta e TODAS as suas tarefas e matérias? Isso não pode ser desfeito.')) return;
            try {
                await api('api/usuario.php', 'DELETE');
                location.href = 'login.php';
            } catch (err) { mostrarAviso(err.message, false); }
        });
    </script>
</body>
</html>
