<?php
// filepath: c:\Users\Jair\Documents\projetosweb\projeto-final\produtos.php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}
$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
  header('Location: login.php');
  exit;
}
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Categoria.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";
require "../src/Modelo/Usuario.php";
require "../src/Repositorio/UsuarioRepositorio.php";

$categoriaRepositorio = new CategoriaRepositorio($pdo);
$categorias = $categoriaRepositorio->buscarTodos();

$usuarioRepo = new UsuarioRepositorio($pdo);
$usuario = $usuarioRepo->buscarTodos();

$usuarioLogado = $_SESSION['usuario'] ?? null;
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../css/categoria-style.css">
  
  <title>Granato - Categorias</title>
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
        echo htmlspecialchars($usuarioLogado); 
    }
?>
</span>

    </div>
    
    <nav class="menu-adm">
      <a href="../dashboard.php">Dashboard</a>
      <p>Categorias</p>
      <a href="../noticia/admin.php">Noticias</a>
      <a href="../usuario/listar.php">Usuários</a>
      <a href="../index.php">Pag Principal</a>

    </nav>
</header>
    <div class="container-admin-banner">
      <a href="dashboard.php">
        <img src="../img/logo-footnews.png" alt="Foot news" class="logo-admin">
      </a>
    </div>


  </header>
  <main>
    <h2>Lista de Categorias</h2>
    <section class="container-table">
      <table>
        <thead>
          <tr>
            <th>Id</th>
            <th>Categoria</th>
            <th colspan="2">Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categorias as $categoria): ?>
            <tr>
              <td><?= htmlspecialchars($categoria->getId())?></td>
              <td><?= htmlspecialchars($categoria->getCategoria()) ?></td>
              <td><a class="botao-editar" href="form.php?id=<?= $categoria->getId() ?>">Editar</a></td>
              <td>
                <form action="excluir.php" method="post">
                  <input type="hidden" name="id" value="<?= $categoria->getId() ?>">
                  <input type="submit" class="botao-excluir" value="Excluir">
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <a class="botao-cadastrar" href="form.php">Cadastrar categoria</a>
    </section>
  </main>
</body>

</html>