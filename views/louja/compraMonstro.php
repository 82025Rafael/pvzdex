<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Minion</title>

    <link rel=stylesheet href=lojinha.css>
</head>
<body>
    
<?php
require_once '../../config.php';
session_start();



$esp = new especie();
$tipo = new tipo();
$pkm = new pokemon();
$atk = new ataque();

$esp->setId($_GET['compra']);
$esp->load();

$_SESSION['id_esp_compra'] = $esp->getId();

?>

<div>

<h2> Comprar <?=$esp->getNome();?> ? </h2>

    <div class="fotoDele">

    <img src='../<?= $esp->getFoto()?>'>

    </div>

    <div class="Opcoes">

            <form action='../../controllers/compraMonstro_controller.php' method=POST>

            <label> <b> Apelido (Opcional)</b></label> <br>
            <input type=text name="nome_pkm" value="<?=$esp->getNome()?>"> <br>
            <?= $esp->getPreco();?> <br>
            <?= $_SESSION['erro_compra'] ?? ''; ?>
            <button type=submit> Comprar </button>


            </form>
    </div>



</div>





</body>
</html>