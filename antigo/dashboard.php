<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
require "src/conexao-bd.php";
require "src/Modelo/Usuario.php";
require "src/Repositorio/UsuarioRepositorio.php";

$usuarioRepo = new UsuarioRepositorio($pdo);
$usuario = $usuarioRepo->buscarTodos();

$usuarioLogado = $_SESSION['usuario'] ?? null;
function pode(string $perm): bool
{
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema</title>
    <link rel="stylesheet" href="css/dashboard-style.css">
</head>
<body class="pagina-dashboard">

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
      <p>Dashboard</p>
      <a href="categoria/listar.php">Categorias</a>
      <a href="noticia/admin.php">Noticias</a>
      <a href="usuario/listar.php">Usuários</a>
      <a href="index.php">Pag Principal</a>

    </nav>
</header>

<main class="dashboard">
    <h1 class="titulo-dashboard">Dashboard</h1>
    <section class="cards-container">
        <?php if (pode('categorias.listar')): ?>
            <a class="card card-categorias" href="categoria/listar.php">
                <h2>Categorias</h2>
                <p>Listar e gerenciar categorias.</p>
            </a>
        <?php endif; ?>

        <?php if (pode('noticias.listar')): ?>
            <a class="card card-noticias" href="noticia/admin.php">
                <h2>Notícias</h2>
                <p>Listar e gerenciar notícias.</p>
            </a>
        <?php endif; ?>

        <?php if (pode('usuarios.listar')): ?>
            <a class="card card-usuarios" href="usuario/listar.php">
                <h2>Usuários</h2>
                <p>Gerenciar e cadastrar usuários.</p>
            </a>
        <?php endif; ?>
    </section>
</main>

</body>
</html>
