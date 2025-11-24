<?php
require __DIR__ . '/../src/conexao-bd.php';
require __DIR__ . '/../src/Modelo/Usuario.php';
require __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);
$usuario = null;

if (!empty($_GET['id'])) {
    $usuario = $repo->buscar((int)$_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $usuario ? 'Editar Usuário' : 'Novo Usuário' ?></title>
    <link rel="stylesheet" href="../css/style-form-usuario.css">
</head>
<body>
    <h1><?= $usuario ? 'Editar Usuário' : 'Novo Usuário' ?></h1>

    <form action="salvar.php" method="post">
        <input type="hidden" name="id" value="<?= $usuario ? $usuario->getId() : '' ?>">

        <label>Nome Real:</label><br>
        <input type="text" name="nomeReal" value="<?= $usuario ? htmlspecialchars($usuario->getNomeReal()) : '' ?>" required><br><br>

        <label>Nome de Usuário:</label><br>
        <input type="text" name="nomeUsuario" value="<?= $usuario ? htmlspecialchars($usuario->getNomeUsuario()) : '' ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $usuario ? htmlspecialchars($usuario->getEmail()) : '' ?>" required><br><br>

        <label>Perfil:</label><br>
        <select name="perfil">
            <option value="User" <?= $usuario && $usuario->getPerfil() === 'User' ? 'selected' : '' ?>>User</option>
            <option value="Admin" <?= $usuario && $usuario->getPerfil() === 'Admin' ? 'selected' : '' ?>>Admin</option>
        </select><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" placeholder="<?= $usuario ? 'Deixe em branco para manter' : '' ?>"><br><br>

        <button type="submit">Salvar</button>
    </form>

    <br>
    <a href="listar.php">Voltar</a>
</body>
</html>
