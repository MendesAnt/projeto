<?php
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Usuario.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

$repo = new UsuarioRepositorio($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}

// Recebe dados do POST com tratamento para evitar "undefined index"
$id           = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
$nomeReal     = trim($_POST['nomeReal'] ?? '');
$nomeUsuario  = trim($_POST['nomeUsuario'] ?? '');
$perfil       = trim($_POST['perfil'] ?? 'usuario');
$email        = trim($_POST['email'] ?? '');
$senha        = $_POST['senha'] ?? '';

// Validações básicas: campos obrigatórios
if ($nomeReal === '' || $email === '' || (!$id && $senha === '')) {
    // Se estiver editando, mantém o id no GET para o formulário
    $redirectUrl = 'form.php' . ($id ? '?id=' . $id . '&erro=campos' : '?erro=campos');
    header('Location: ' . $redirectUrl);
    exit;
}

// Valida perfil (apenas 'usuario' ou 'Admin')
if (!in_array($perfil, ['usuario', 'Admin'], true)) {
    $perfil = 'usuario';
}

if ($id) {
    // Edição
    $existente = $repo->buscar($id);
    if (!$existente) {
        header('Location: listar.php?erro=inexistente');
        exit;
    }

    // Se senha vazia, mantém senha atual (hash)
    if ($senha === '') {
        $senhaParaObjeto = $existente->getSenha();
    } else {
        // Senha em texto plano, será convertida para hash no repositório
        $senhaParaObjeto = $senha;
    }

    $usuario = new Usuario($id, $nomeReal, $nomeUsuario, $perfil, $email, $senhaParaObjeto);
    $repo->atualizar($usuario);
    header('Location: listar.php?ok=1');
    exit;
} else {
    // Novo usuário
    $usuario = new Usuario(null, $nomeReal, $nomeUsuario, $perfil, $email, $senha);
    $repo->salvar($usuario);
    header('Location: listar.php?novo=1');
    exit;
}
