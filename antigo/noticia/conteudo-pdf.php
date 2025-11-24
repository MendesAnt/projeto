<?php
require "../src/conexao-bd.php";
require "../src/Modelo/Noticia.php";
require "../src/Repositorio/NoticiaRepositorio.php";

date_default_timezone_set('America/Sao_Paulo'); // ajuste conforme sua timezone
$rodapeDataHora = date('d/m/Y H:i');

$noticiaRepositorio = new NoticiaRepositorio($pdo);
$noticias = $noticiaRepositorio->buscarTodas();

$imagePath = '../img/logo-footnews.png';
$imageData = base64_encode(file_get_contents($imagePath));
$imageSrc = 'data:image/jpeg;base64,' . $imageData;


?>
<head>
    <meta charset="UTF-8">

<style>
    body,
    table,
    th,
    td,
    h3 {
        font-family: Arial, Helvetica, sans-serif;
    }

    table {
        width: 90%;
        margin: auto 0;
    }

    table,
    th,
    td {
        border: 1px solid #000;
        
    }

    table th {
        padding: 11px 0 11px;
        font-weight: bold;
        font-size: 14px;
        text-align: left;
        padding: 8px;
    }

    table tr {
        border: 1px solid #000;
    }

    table td {
        font-size: 12px;
        padding: 8px;
    }

    h3 {
        text-align: center;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }

    .container-admin-banner h1 {
        margin-top: 40px;
        font-size: 30px;
    }

    .pdf-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        text-align: center;
        font-size: 12px;
        color: #444;
        border-top: 1px solid #ddd;
        padding-top: 6px;
    }

    /* Dá margem inferior para que o conteúdo da tabela não fique sobre o rodapé */
    body {
        margin-bottom: 50px;
        margin-top: 0;
    }

    .pdf-img {
        width: 100px;
    }
</style>
</head>
<img src="<?= $imageSrc ?>" class="pdf-img" alt="logo-footnews">


<h3>Listagem de Noticias</h3>

<table>
    <thead>
        <tr>
            <th>Noticia</th>
            <th>Conteúdo</th>
            <th>Fonte</th>
            <th>Data Noticia</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($noticias as $noticia): ?>
            <tr>
                <td><?= $noticia->getNome() ?></td>
                <td><?= $noticia->getConteudo() ?></td>
                <td><?= $noticia->getFonte() ?></td>
                <td><?= $noticia->getDataNoticia()->format('d/m/Y') ?></td>
            </tr>
        <?php endforeach; ?>


    </tbody>
</table>

<div class="pdf-footer">
    Gerado em: <?= htmlspecialchars($rodapeDataHora) ?>
</div>