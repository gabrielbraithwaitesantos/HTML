<header class="topo">
    <strong>Gerenciador de Tarefas</strong>
    <nav>
        <a href="index.php">Tarefas</a>
        <a href="gerenciar.php">Gerenciar</a>
        <a href="perfil.php">Perfil</a>
        <a href="logout.php">Sair (<?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>)</a>
    </nav>
</header>
