<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Noticia.php';
require_once __DIR__ . '/../src/Repositorio/NoticiaRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/CategoriaRepositorio.php';
require __DIR__ . '/../src/Modelo/Usuario.php';
require __DIR__ . '/../src/Modelo/Categoria.php';
require __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

use App\Modelo\Noticia;

$repositorio = new NoticiaRepositorio($pdo);
$usuarioRepo = new UsuarioRepositorio($pdo);
$usuario = $usuarioRepo->buscarTodos();
$usuarioLogado = $_SESSION['usuario'] ?? null;

$repo_categorias = new CategoriaRepositorio($pdo);
$listagemCategorias = $repo_categorias->buscarTodos();

// Paginação e ordenação
$pagina  = isset($_GET['pagina'])  ? max(1, (int)$_GET['pagina'])  : 1;
$ordem   = isset($_GET['ordem'])   && in_array($_GET['ordem'],   ['idNoticia','nome','fonte','dataNoticia']) ? $_GET['ordem']   : 'dataNoticia';
$direcao = isset($_GET['direcao']) && in_array($_GET['direcao'], ['ASC','DESC'])                            ? $_GET['direcao'] : 'DESC';
$itens   = 10;

$total        = $repositorio->totalRegistros();
$totalPaginas = max(1, ceil($total / $itens));
$pagina       = min($pagina, $totalPaginas);

$noticias = $repositorio->buscarTodasComFiltro($ordem, $direcao, $pagina, $itens);

function buscarNomeCategoria($id, $categorias)
{
    foreach ($categorias as $categoria) {
        if ($categoria->getId() == $id) {
            return $categoria->getCategoria();
        }
    }
    return 'Sem categoria';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin - Notícias</title>
    <link rel="stylesheet" href="../css/style-admin.css">
    <style>
        /* Paginação */
        .paginacao {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin: 30px 0 10px;
            font-size: 14px;
        }
        .paginacao a {
            background: #181818;
            color: #e7e7e7;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .paginacao a:hover {
            background: #00c853;
            color: #000;
        }
        /* Ordenação */
        thead a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }
        thead a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header class="container-admin">
    <div class="topo-direita">
        <span>Bem-vindo, 
        <?php 
            if (is_object($usuario) && ($usuario->getNomeUsuario() !== null)) {
                echo htmlspecialchars($usuario->getNomeUsuario());
            } else {
                echo htmlspecialchars($usuarioLogado);
            }
        ?>
        </span>
    </div>
    <nav class="menu-adm">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../categoria/listar.php">Categorias</a>
        <p>Noticias</p>
        <a href="../usuario/listar.php">Usuários</a>
        <a href="../index.php">Pag Principal</a>
    </nav>
</header>

<a href="form.php" class="btn">Nova Notícia</a>

<table class="tabela">
    <thead>
        <tr>
            <th><a href="?ordem=idNoticia&direcao=<?= $ordem==='idNoticia' && $direcao==='ASC' ? 'DESC' : 'ASC' ?>">ID</a></th>
            <th><a href="?ordem=nome&direcao=<?=       $ordem==='nome'       && $direcao==='ASC' ? 'DESC' : 'ASC' ?>">Título</a></th>
            <th><a href="?ordem=fonte&direcao=<?=      $ordem==='fonte'      && $direcao==='ASC' ? 'DESC' : 'ASC' ?>">Fonte</a></th>
            <th><a href="?ordem=dataNoticia&direcao=<?=$ordem==='dataNoticia'&& $direcao==='ASC' ? 'DESC' : 'ASC' ?>">Data</a></th>
            <th>Categoria</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($noticias as $noticia): ?>
            <tr>
                <td><?= htmlspecialchars((string)$noticia->getIdNoticia()) ?></td>
                <td><?= htmlspecialchars($noticia->getNome()) ?></td>
                <td><?= htmlspecialchars($noticia->getFonte()) ?></td>
                <td><?= $noticia->getDataNoticia()->format('d/m/Y') ?></td>
                <td><?= htmlspecialchars(buscarNomeCategoria($noticia->getCategoriaId(), $listagemCategorias)) ?></td>
                <td>
                    <?php if ($noticia->getImagem()): ?>
                        <img src="../uploads/<?= htmlspecialchars($noticia->getImagem()) ?>" width="80">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="form.php?id=<?= $noticia->getIdNoticia() ?>" class="icone" title="Editar">Editar</a> |
                    <a href="excluir.php?id=<?= $noticia->getIdNoticia() ?>" onclick="return confirm('Excluir esta notícia?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form action="gerador-pdf.php" method="post" style="display:inline;">
    <input type="submit" class="botao-cadastrar" value="Baixar Relatório">
</form>

<!-- Paginação -->
<nav class="paginacao">
    <?php if ($pagina > 1): ?>
        <a href="?pagina=<?= $pagina - 1 ?>&ordem=<?= $ordem ?>&direcao=<?= $direcao ?>">‹ Anterior</a>
    <?php endif; ?>

    <span>Página <?= $pagina ?> / <?= $totalPaginas ?></span>

    <?php if ($pagina < $totalPaginas): ?>
        <a href="?pagina=<?= $pagina + 1 ?>&ordem=<?= $ordem ?>&direcao=<?= $direcao ?>">Próxima ›</a>
    <?php endif; ?>
</nav>

</body>
</html>