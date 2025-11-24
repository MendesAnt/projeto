<?php

require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Usuario.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

if (!isset($_POST['id']) || empty($_POST['id'])) {
    header("Location: listar.php?erro=id_invalido");
    exit;
}

$id = (int) $_POST['id'];

$usuarioRepositorio = new UsuarioRepositorio($pdo);
$usuarioRepositorio->deletar($id);

header("Location: listar.php");
exit;
