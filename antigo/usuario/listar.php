<?php
require __DIR__ . '/../src/conexao-bd.php';
require __DIR__ . '/../src/Modelo/Usuario.php';
require __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);
$usuarios = $repo->buscarTodos();
$usuario = $repo->buscarTodos();

$usuarioLogado = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin – Usuários</title>
    <link rel="stylesheet" href="../css/usuario-style.css">
</head>
<body>
<header class="container-admin">
    <div class="topo-direita">
<span>Bem-vindo, 
<?php 
    if (is_object($usuario) && ($usuario->getNomeUsuario() !== null)) {
        $nomeUsuario = $usuario->getNomeUsuario();
        echo htmlspecialchars($nomeUsuario);
    } else {
        echo htmlspecialchars($usuarioLogado); // assume string
    }
?>
</span>
    </div>
    
    <nav class="menu-adm">
      <a href="../dashboard.php">Dashboard</a>
      <a href="../categoria/listar.php">Categorias</a>
      <a href="../noticia/admin.php">Noticias</a>
      <p>Usuários</p>
      <a href="../index.php">Pag Principal</a>

    </nav>
</header>
    <h1>Usuários cadastrados</h1>

    <a href="form.php">Novo Usuário</a><br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome Real</th>
            <th>Usuário</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u->getId() ?></td>
                <td><?= htmlspecialchars($u->getNomeReal()) ?></td>
                <td><?= htmlspecialchars($u->getNomeUsuario()) ?></td>
                <td><?= htmlspecialchars($u->getEmail()) ?></td>
                <td><?= htmlspecialchars($u->getPerfil()) ?></td>
                <td>
        <a href="form.php?id=<?= $u->getId() ?>">Editar</a> |
        <form action="excluir.php" method="post" style="display:inline">
            <input type="hidden" name="id" value="<?= $u->getId() ?>">
            <button type="submit" onclick="return confirm('Deseja excluir este usuário?')">Excluir</button>
        </form>

                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
