<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Noticia.php';
require_once __DIR__ . '/../src/Repositorio/NoticiaRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Categoria.php';
require_once __DIR__ . '/../src/Repositorio/CategoriaRepositorio.php';

use App\Modelo\Noticia;

$repositorio = new NoticiaRepositorio($pdo);
$repo_categorias = new CategoriaRepositorio($pdo);
$listagemCategorias = $repo_categorias->buscarTodos();

// cria objeto vazio (evita erro quando for cadastro novo)
$noticia = new Noticia(null, '', '', '', new DateTime(), null);

if (!empty($_GET['id'])) {
    $noticia = $repositorio->buscar((int)$_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $noticia->getIdNoticia() ? 'Editar Notícia' : 'Nova Notícia' ?></title>
    <link rel="stylesheet" href="../css/style-form-noticia.css">
</head>
<body>
    <h1><?= $noticia->getIdNoticia() ? 'Editar Notícia' : 'Nova Notícia' ?></h1>

    <form action="salvar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $noticia->getIdNoticia() ?>">

        <label>Título:</label><br>
        <input type="text" name="nome" 
               value="<?= htmlspecialchars(method_exists($noticia, 'getNome') ? $noticia->getNome() : $noticia->getNome()) ?>" 
               required><br><br>

        <label>Fonte:</label><br>
        <input type="text" name="fonte" value="<?= htmlspecialchars($noticia->getFonte()) ?>" required><br><br>

        <label>Conteúdo:</label><br>
        <textarea name="conteudo" rows="5" cols="40" required><?= htmlspecialchars($noticia->getConteudo()) ?></textarea><br><br>

        <label for="categoria">Categoria</label>
<select name="categoria_id" id="categoria" required>
    <option value="">Escolha uma categoria</option>
    <?php foreach ($listagemCategorias as $cat): ?>
        <option value="<?= $cat->getId() ?>"
            <?= ($noticia->getCategoriaId() == $cat->getId()) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat->getCategoria()) ?>
        </option>
    <?php endforeach; ?>
</select>

        <label>Data da Notícia:</label><br>
        <input 
            type="date" 
            name="dataNoticia" 
            value="<?= htmlspecialchars($noticia->getDataNoticia() instanceof DateTime ? $noticia->getDataNoticia()->format('Y-m-d') : '') ?>" 
            required
        ><br><br>

        <label>Imagem:</label><br>
        <input type="file" name="imagem"><br>
        <?php if ($noticia->getImagem()): ?>
            <img src="../uploads/<?= htmlspecialchars($noticia->getImagem()) ?>" width="100" alt="Imagem da notícia">
        <?php endif; ?>
        <br><br>

        <button type="submit">Salvar</button>
    </form>

    <br>
    <a href="admin.php">Voltar</a>
</body>
</html>
