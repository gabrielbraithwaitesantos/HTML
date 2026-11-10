# Gerenciador de Tarefas (TDE 2 – Programação Web)

Aplicação web para organizar tarefas por matéria. HTML/CSS + JavaScript (fetch/JSON) no front, PHP (PDO + sessões) no back e MySQL.

## Requisitos
- XAMPP (Apache + MySQL/MariaDB) com PHP 7.4 ou superior

## Instalação
1. Copie a pasta `gerenciador_tarefas` para `C:\xampp\htdocs\` (ou `/opt/lampp/htdocs/`).
2. Abra o XAMPP e inicie **Apache** e **MySQL**.
3. Acesse `http://localhost/phpmyadmin`, clique em **Importar** e selecione o arquivo `banco.sql` (ele cria o banco `gerenciador_tarefas` e as tabelas).
4. Se o seu MySQL tiver usuário/senha diferentes de `root` / (vazio), ajuste o arquivo `db.php`.
5. Acesse `http://localhost/gerenciador_tarefas/cadastro.php`.

## Como testar
1. Cadastre um usuário em `cadastro.php` e faça login.
2. Em **Gerenciar**, cadastre uma matéria e depois uma tarefa.
3. Em **Tarefas**, filtre, altere o status, edite ou exclua.
4. Em **Perfil**, edite os dados ou exclua a conta.
5. Clique em **Sair** e tente abrir `index.php` direto: deve voltar ao login.

## Estrutura
- `banco.sql` – criação do banco e tabelas (usuarios, materias, tarefas, com chaves estrangeiras)
- `db.php` – conexão PDO · `auth.php` – proteção por sessão · `logout.php` – encerra a sessão
- `cadastro.php`, `login.php` – autenticação (senha com `password_hash`/`password_verify`)
- `index.php` (listagem), `gerenciar.php` (CRUD de tarefas e matérias), `perfil.php` (CRUD do usuário)
- `api/` – endpoints JSON (`tarefas.php`, `materias.php`, `usuario.php`)
- `assets/` – `app.js` (fetch) e `style.css`

## Relacionamentos
`usuarios 1—N materias`, `usuarios 1—N tarefas`, `materias 1—N tarefas`. A listagem de tarefas usa `JOIN` com `materias`, e todas as consultas com dados do usuário usam prepared statements.
