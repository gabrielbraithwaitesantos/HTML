<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Tarefas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main class="conteudo">
        <h2>Minhas Tarefas</h2>
        <p id="aviso" class="aviso" hidden></p>

        <div class="barra">
            <label>Filtrar por status:
                <select id="filtro">
                    <option value="">Todos</option>
                    <option>Pendente</option>
                    <option>Em andamento</option>
                    <option>Concluída</option>
                </select>
            </label>
            <a class="botao" href="gerenciar.php">+ Nova tarefa</a>
        </div>

        <div class="rolagem">
            <table>
                <thead>
                    <tr><th>Título</th><th>Matéria</th><th>Entrega</th><th>Status</th><th>Ações</th></tr>
                </thead>
                <tbody id="lista"></tbody>
            </table>
        </div>
    </main>

    <script src="assets/app.js"></script>
    <script>
        let tarefas = [];
        const STATUS = ['Pendente', 'Em andamento', 'Concluída'];

        async function carregar() {
            try {
                tarefas = await api('api/tarefas.php');
                desenhar();
            } catch (e) { mostrarAviso(e.message, false); }
        }

        function desenhar() {
            const filtro = document.getElementById('filtro').value;
            const visiveis = tarefas.filter(t => !filtro || t.status === filtro);
            const corpo = document.getElementById('lista');
            if (visiveis.length === 0) {
                corpo.innerHTML = '<tr><td colspan="5">Nenhuma tarefa encontrada.</td></tr>';
                return;
            }
            corpo.innerHTML = visiveis.map(t => `
                <tr>
                    <td><strong>${esc(t.titulo)}</strong><br><small>${esc(t.descricao)}</small></td>
                    <td>${esc(t.nome_materia)} (${esc(t.codigo)})</td>
                    <td>${formatarData(t.data_entrega)}</td>
                    <td>
                        <select onchange="mudarStatus(${t.id}, this.value)">
                            ${STATUS.map(s => `<option ${s === t.status ? 'selected' : ''}>${s}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <a class="botao pequeno" href="gerenciar.php?editar=${t.id}">Editar</a>
                        <button class="pequeno perigo" onclick="excluir(${t.id})">Excluir</button>
                    </td>
                </tr>`).join('');
        }

        async function mudarStatus(id, status) {
            try {
                const r = await api('api/tarefas.php', 'PUT', { id, status });
                mostrarAviso(r.mensagem);
                carregar();
            } catch (e) { mostrarAviso(e.message, false); }
        }

        async function excluir(id) {
            if (!confirm('Excluir esta tarefa?')) return;
            try {
                const r = await api('api/tarefas.php', 'DELETE', { id });
                mostrarAviso(r.mensagem);
                carregar();
            } catch (e) { mostrarAviso(e.message, false); }
        }

        document.getElementById('filtro').addEventListener('change', desenhar);
        carregar();
    </script>
</body>
</html>
