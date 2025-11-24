<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Noticia.php';
require_once __DIR__ . '/../src/Repositorio/NoticiaRepositorio.php';

use App\Modelo\Noticia;

$repositorio = new NoticiaRepositorio($pdo);

$id            = !empty($_POST['id']) ? (int)$_POST['id'] : null;
$nome          = $_POST['nome'];
$conteudo      = $_POST['conteudo'];
$fonte         = $_POST['fonte'];
$dataNoticia   = $_POST['dataNoticia'] ?? date('Y-m-d');
$categoriaId   = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null; // ← FALTAVA
$imagem        = null;

// Upload da imagem
if (!empty($_FILES['imagem']['name'])) {
    $nomeImg = uniqid() . '-' . basename($_FILES['imagem']['name']);
    $caminho = __DIR__ . '/../uploads/' . $nomeImg;
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
        $imagem = $nomeImg;
    }
}

// Se for edição e NÃO enviou nova imagem, mantém a antiga
if ($id && empty($imagem)) {
    $imgAtual = $repositorio->buscar($id)->getImagem();
    $imagem   = $imgAtual ?: 'logo-footnews.png';
}

// Agora enviamos 8 argumentos (id, nome, conteudo, fonte, data, imagem, categoriaId, categoriaNome)
$noticia = new Noticia(
    $id,
    $nome,
    $conteudo,
    $fonte,
    new DateTime($dataNoticia),
    $imagem,
    $categoriaId
);

// Persiste
if ($id) {
    $repositorio->atualizar($noticia);
} else {
    $repositorio->salvar($noticia);
}

header('Location: admin.php');
exit;