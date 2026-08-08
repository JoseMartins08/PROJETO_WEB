<?php
    if (!isset($base)) $base = '';

    $pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD Mundo</title>
        <link rel="stylesheet" href="<?= $base ?>css/style.css">
    </head>
    <body>
        <header>
            <h1>Crud Mundo</h1>
            <p>Sistema de gerenciamento de continentes, países, cidades e governantes</p>
        </header>
        <nav>
            <a href="<?= $base ?>index.php" class="<?= $pagina_atual == 'index.php' ? 'ativo' : '' ?>">Início</a>
            <a href="<?= $base ?>paginas/continentes.php" class="<?= $pagina_atual == 'continentes.php' ? 'ativo' : '' ?>">Continentes</a>
            <a href="<?= $base ?>paginas/paises.php" class="<?= $pagina_atual == 'paises.php' ? 'ativo' : '' ?>">Países</a>
            <a href="<?= $base ?>paginas/cidades.php" class="<?= $pagina_atual == 'cidades.php' ? 'ativo' : '' ?>">Cidades</a>
            <a href="<?= $base ?>paginas/governantes.php" class="<?= $pagina_atual == 'governantes.php' ? 'ativo' : '' ?>">Governantes</a>
        </nav>
        <main>