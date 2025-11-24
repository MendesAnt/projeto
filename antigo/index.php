<?php
require "src/conexao-bd.php";
require "src/Modelo/Noticia.php";
require "src/Repositorio/NoticiaRepositorio.php";

$noticiaRepo = new NoticiaRepositorio($pdo);
$listaNoticias = $noticiaRepo->buscarTodas();
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/index-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="img/icone-footnews.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>FootNews - Notícias</title>
</head>
<body>
<main>
    <section class="container-banner">
        <div class="container-texto-banner">
            <img src="img/logo-footnews.png" class="logo" alt="logo-footnews">
            <a href="dashboard.php" class="btn-dashboard">Dashboard</a>

        </div>
    </section>

    <h2>Notícias do Mundo da Bola</h2>

    <section class="container-noticias">
        <div class="container-noticias-titulo">
            <h3>Últimas publicadas</h3>
            <img class="ornaments" src="img/ornamento-bola.png" alt="ornaments">
        </div>

        <div class="container-noticias-cards">
            <?php foreach ($listaNoticias as $noticia): ?>
                <div class="container-card">
                    <div class="container-foto">
                        <img src="<?= $noticia->getImagemDiretorio() ?>" alt="<?= htmlspecialchars($noticia->getNome()) ?>">
                    </div>
                    <p class="titulo"><?= htmlspecialchars($noticia->getNome()) ?></p>
                    <p class="fonte">Fonte: <?= htmlspecialchars($noticia->getFonte()) ?></p>
                    <p class="conteudo"><?= nl2br(htmlspecialchars($noticia->getConteudo())) ?></p>
                    <p class="categoria"><?= htmlspecialchars($noticia->getCategoria()) ?></p>
                    <p class ="data"><?= htmlspecialchars($noticia->getDataNoticia()->format("d/m/Y"))?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</body>
</html>