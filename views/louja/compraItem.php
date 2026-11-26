<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Item</title>
    <link rel="stylesheet" href="lojinha.css">
</head>
<body>
 <audio src="../../Música/Laura Shigihara - Choose Your Seeds.mp3" autoplay loop></audio>

<?php
require_once '../../config.php';
session_start();

// 1. Puxa as informações do usuário para mostrar o dinheiro
$usr = new Usuario();
$usr->setId($_SESSION['id_usr']);
$usr->load();

$item = new item();
$item->setId($_GET['compra']);
$item->load();

$_SESSION['id_item_compra'] = $item->getId();
?>

<!-- 2. Botão de Voltar (aponta de volta para a lista de itens) -->
<a href="lojaitens.php" class="back-btn">⬅ Voltar</a>

<!-- 3. Caixa de Dinheiro -->
<div class="money-box">
    💰 $ <span id="player-money"><?= $usr->getDinheiro() ?? '5000' ?></span>
</div>

<div class="tela-compra">
    <h2>Comprar <?=$item->getNome();?>?</h2>

    <div class="compra-content">
        
        <!-- Imagem à Esquerda -->
        <div class="fotoDele">
            <img src='../<?= $item->getFoto()?>' alt="<?=$item->getNome();?>">
        </div>

        <!-- Opções à Direita -->
        <div class="Opcoes">
            <form action='../../controllers/compraItem_controller.php' method="POST">
                
                <label><b>Selecionar Quantidade</b></label><br>
                <input type="number" min="1" name="quant_item" value="1">
                
                <div class="preco-tela">
                    Preço: <?= $item->getPreco(); ?> Cada
                </div>
                
                <?php if(isset($_SESSION['erro_compra'])): ?>
                    <div class="erro"><?= $_SESSION['erro_compra']; ?></div>
                <?php endif; ?>

                <button type="submit">Confirmar Compra</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>