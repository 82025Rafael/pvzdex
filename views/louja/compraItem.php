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



$item = new item();

$item->setId($_GET['compra']);
$item->load();

$_SESSION['id_item_compra'] = $item->getId();

?>

<div>

<h2> Comprar <?=$item->getNome();?> ? </h2>

    <div class="fotoDele">

    <img src='../<?= $item->getFoto()?>'>

    </div>

    <div class="Opcoes">

            <form action='../../controllers/compraItem_controller.php' method=POST>

            <label> <b> Selecionar Quantidade</b></label> <br>
            <input type=number min=1 name="quant_item" value="1"> <br>
            <?= $item->getPreco();?> Cada <br>
            <?= $_SESSION['erro_compra'] ?? ''; ?>
            <button type=submit> Comprar </button>


            </form>
    </div>



</div>





</body>
</html>