<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main class="conteudo">
        <h2>Gerenciar</h2>
        <p id="aviso" class="aviso" hidden></p>

        <div class="duas-colunas">
            <!-- TAREFAS: criar e editar -->
            <section class="cartao">
                <h3 id="titulo-tarefa">Nova tarefa</h3>
                <form id="form-tarefa">
                    <input type="hidden" id="t-id">
                    <label>Título</label>
                    <input type="text" id="t-titulo" maxlength="150" required>
                    <label>Descrição</label>
                    <textarea id="t-descricao" rows="3"></textarea>
                    <label>Matéria</label>
                    <select id="t-materia" required></select>
                    <label>Data de entrega</label>
                    <input type="date" id="t-data" required>
                    <label>Status</label>
                    <select id="t-status">
                        <option>Pendente</option>
                        <option>Em andamento</option>
                        <option>Concluída</option>
                    </select>
                    <button type="submit">Salvar tarefa</button>
                    <button type="button" class="secundario" onclick="limparTarefa()">Limpar</button>
                </form>
            </section>

            <!-- MATÉRIAS: criar, editar, excluir -->
            <section class="cartao">
                <h3 id="titulo-materia">Nova matéria</h3>
                <form id="form-materia">
                    <input type="hidden" id="m-id">
                    <label>Nome da matéria</label>
                    <input type="text" id="m-nome" maxlength="100" required>
                    <label>Código</label>
                    <input type="text" id="m-codigo" maxlength="20" required>
                    <button type="submit">Salvar matéria</button>
                    <button type="button" class="secundario" onclick="limparMateria()">Limpar</button>
                </form>
                <h4>Minhas matérias</h4>
                <ul id="lista-materias" class="lista"></ul>
            </section>
        </div>
    </main>

    <script src="assets/app.js"></script>
    <script>
        const $ = id => document.getElementById(id);
        let materias = [];

        // ---------- MATÉRIAS ----------
        async function carregarMaterias() {
            materias = await api('api/materias.php');
            // Select usado no formulário de tarefas
            const atual = $('t-materia').value;
            $('t-materia').innerHTML = materias.length
                ? materias.map(m => `<option value="${m.id}">${esc(m.nome_materia)} (${esc(m.codigo)})</option>`).join('')
                : '<option value="">Cadastre uma matéria primeiro</option>';
            if (atual) $('t-materia').value = atual;
            // Lista com editar/excluir
            $('lista-materias').innerHTML = materias.length
                ? materias.map(m => `<li>${esc(m.nome_materia)} (${esc(m.codigo)})
                    <span><button class="pequeno" onclick="editarMateria(${m.id})">Editar</button>
                    <button class="pequeno perigo" onclick="excluirMateria(${m.id})">Excluir</button></span></li>`).join('')
                : '<li>Nenhuma matéria cadastrada.</li>';
        }

        function limparMateria() {
            $('form-materia').reset(); $('m-id').value = '';
            $('titulo-materia').textContent = 'Nova matéria';
        }
        function editarMateria(id) {
            const m = materias.find(x => x.id == id);
            $('m-id').value = m.id; $('m-nome').value = m.nome_materia; $('m-codigo').value = m.codigo;
            $('titulo-materia').textContent = 'Editar matéria';
        }
        async function excluirMateria(id) {
            if (!confirm('Excluir esta matéria? As tarefas dela também serão excluídas.')) return;
            try {
                const r = await api('api/materias.php', 'DELETE', { id });
                mostrarAviso(r.mensagem); limparMateria(); carregarMaterias();
            } catch (e) { mostrarAviso(e.message, false); }
        }
        $('form-materia').addEventListener('submit', async e => {
            e.preventDefault();
            const id = $('m-id').value;
            const corpo = { id, nome_materia: $('m-nome').value, codigo: $('m-codigo').value };
            try {
                const r = await api('api/materias.php', id ? 'PUT' : 'POST', corpo);
                mostrarAviso(r.mensagem); limparMateria(); carregarMaterias();
            } catch (err) { mostrarAviso(err.message, false); }
        });

        // ---------- TAREFAS ----------
        function limparTarefa() {
            $('form-tarefa').reset(); $('t-id').value = '';
            $('titulo-tarefa').textContent = 'Nova tarefa';
        }
        $('form-tarefa').addEventListener('submit', async e => {
            e.preventDefault();
            const id = $('t-id').value;
            const corpo = {
                id, titulo: $('t-titulo').value, descricao: $('t-descricao').value,
                materia_id: $('t-materia').value, data_entrega: $('t-data').value, status: $('t-status').value
            };
            try {
                const r = await api('api/tarefas.php', id ? 'PUT' : 'POST', corpo);
                mostrarAviso(r.mensagem); limparTarefa();
            } catch (err) { mostrarAviso(err.message, false); }
        });

        // Se veio de "Editar" na listagem (?editar=ID), preenche o formulário
        async function iniciar() {
            await carregarMaterias();
            const id = new URLSearchParams(location.search).get('editar');
            if (id) {
                try {
                    const t = await api('api/tarefas.php?id=' + encodeURIComponent(id));
                    $('t-id').value = t.id; $('t-titulo').value = t.titulo; $('t-descricao').value = t.descricao;
                    $('t-materia').value = t.materia_id; $('t-data').value = t.data_entrega; $('t-status').value = t.status;
                    $('titulo-tarefa').textContent = 'Editar tarefa';
                } catch (e) { mostrarAviso(e.message, false); }
            }
        }
        iniciar().catch(e => mostrarAviso(e.message, false));
    </script>
</body>
</html>
