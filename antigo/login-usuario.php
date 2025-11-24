<?php 
    session_start();
    $erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login-usuario.css">
</head>
<body>
  <div class="login-container">
    <div class="logo">
      <img src="img/icon-bola.png" alt="bola de futebol">
      <p>FOOT NEWS</p>
    </div>
    <h2>ENTRAR</h2>

    <form action="autenticar.php" method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <div class="password-container">
        <input type="password" name="senha" id="senha" class="campo-senha" placeholder="Senha" required>
        <img src="img/icon-olho-senha.png" alt="Mostrar senha" class="ver-senha" onclick="verSenha()">
      </div>
      <button type="submit" class="btn">ENTRAR</button>
    </form>

    <?php if ($erro): ?>
      <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <p class="signup">Não possui uma conta? <a href="usuario/cadastrar-usuario.php">CADASTRE-SE</a></p>
  </div>

</body>
</html>