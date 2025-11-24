<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Noticia.php';
require_once __DIR__ . '/../src/Repositorio/NoticiaRepositorio.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $repositorio = new NoticiaRepositorio($pdo);
    $repositorio->deletar($id);
}

header('Location: admin.php');
exit;
