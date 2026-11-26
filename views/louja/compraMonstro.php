<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Minion</title>
    <link rel="stylesheet" href="lojinha.css">
</head>
<body class="tema-pokemons">
 <audio src="../../Música/Laura Shigihara - Choose Your Seeds.mp3" autoplay loop></audio>

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

<div class="tela-compra">
    <h2>Comprar <?=$esp->getNome();?>?</h2>

    <div class="compra-content">
        
        <!-- Imagem à Esquerda -->
        <div class="fotoDele">
            <img src='../<?= $esp->getFoto()?>' alt="<?=$esp->getNome();?>">
        </div>

        <!-- Opções à Direita -->
        <div class="Opcoes">
            <form action='../../controllers/compraMonstro_controller.php' method="POST">
                
                <label><b>Apelido (Opcional)</b></label><br>
                <input type="text" name="nome_pkm" value="<?=$esp->getNome()?>">
                
                <div class="preco-tela">
                    Preço: <?= $esp->getPreco(); ?>
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