<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: cadastrar-usuario.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

require_once 'C:/xampp/htdocs/antigo/src/conexao-bd.php';

    $real  = trim($_POST['nomeReal'] ?? '');
    $user  = trim($_POST['nomeUsuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$real || !$user || !$email || !$senha) {
        header('Location: cadastrar-usuario.php?erro=campos');
        exit;
    }

    $sql = "INSERT INTO tbUsuario (nomeReal, nomeUsuario, email, senha, perfil) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$real, $user, $email, password_hash($senha, PASSWORD_DEFAULT), 'User']);

    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>FootNews – Cadastro</title>
    <link rel="stylesheet" href="css/cadastrar-usuario.css">
</head>
<body>
    <div class="login-box">
        <h1>CADASTRAR</h1>

        <form action="cadastrar-usuario.php" method="post">
            <input type="text" name="nomeReal" placeholder="Nome Completo" required>
            <input type="text" name="nomeUsuario" placeholder="Nome de Usuário" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>

            <button type="submit">CADASTRAR</button>
        </form>
        <?php if (isset($_GET['erro']) && $_GET['erro'] === 'campos'): ?>
            <p style="color: #ff5252;">Preencha todos os campos!</p>
        <?php endif; ?>

        <p>Já tem uma conta? <a href="login.php">Entrar</a></p>
    </div>
</body>
</html>