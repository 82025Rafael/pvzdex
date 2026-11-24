<?php
session_start();
require_once '../../config.php';

unset($_SESSION['erro_compra']);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazar do Rux - Minions</title>
    <link rel="stylesheet" href="../louja/lojinha.css">
</head>
<body class="tema-pokemons">

    <a href="lojainicial.php" class="back-btn">⬅ Voltar</a>

    <div class="shop-container">
        <h2> Monstros do Rux </h2>
        <h3 id="category-title">Plantas - Explosivo & Crescida</h3>
        
        <div class="carousel-wrapper">
            <button class="arrow-btn" onclick="changePage(-1)">&#10094;</button>

            <div class="pages-container">

            <?php 
            
            $esp = new especie();
            $tipo = new tipo();

            

            ?>

                <!-- PÁGINA 1 -->
                <div class="page active">

                    <?php 
                        
                        $esp->setTipo(2);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }

                        $esp->setTipo(3);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }

                    ?>
                </div>
                                  
                <!-- PÁGINA 2 -->
                <div class="page">
                    <?php
                    $esp->setTipo(4);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }

                        $esp->setTipo(1);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }
                        ?>
                </div>

                <!-- PÁGINA 3 -->
                <div class="page">
                    <?php 
                    $esp->setTipo(5);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }
                    
                    ?>
                </div>

                <!-- PÁGINA 4 -->
                <div class="page">
                    <?php 

                        $esp->setTipo(7);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }

                        $esp->setTipo(8);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }

                    ?>
                </div>

                <!-- PÁGINA 5 -->
                <div class="page">
                    <?php 

                        $esp->setTipo(6);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }

                        $esp->setTipo(10);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }

                    ?>
                </div>

                <!-- PÁGINA 6 -->
                <div class="page">
                    <?php 
                    $esp->setTipo(9);
                        $tipo->setId($esp->getTipo());
                        $tipo->load();
                        $lista = $esp->TodosDoTipo();
                        foreach($lista as $pkm) {
                        
                        $esp->setId($pkm['id_esp']);    
                        $esp->load();
                                echo'<div class="shop-item">';
                                echo'<div class="tooltip">Tipo: ' . $tipo->getNome() . ' <br>ATK:' . $esp->getForca() . ' | HP: ' . $esp->getVida() .' | Vel: ' . $esp->getVeloc(). '</div>';
                                echo'      <img src="../' . $esp->getFoto() . '" alt=" '. $esp->getNome() . ' . " class="item-img">';
                                echo'        <h3 class="item-name">'. $esp->getNome() .'</h3>';
                                echo'        <p class="item-price">'. $esp->getPreco()   .'</p>';
                                echo'        <button class="buy-btn" > <a href=compraMonstro.php?compra='. $esp->getId().'>Recrutar </a></button>';
                                echo'   </div>';
                        }
                    
                    ?>
                </div>

            </div>

            <button class="arrow-btn" onclick="changePage(1)">&#10095;</button>
        </div>

        <div class="page-indicator">
            Página <span id="current-page">1</span> de 6
        </div>
    </div>

    <script>
        let currentPageIndex = 0;
        const pages = document.querySelectorAll('.page');
        const pageIndicator = document.getElementById('current-page');
        const categoryTitle = document.getElementById('category-title');
        
        const titles = [
            "Plantas - Explosivo & Crescida",
            "Plantas - Esperto & Protetor",
            "Plantas - Solar",
            "Zumbis - Miolástico & Doidão",
            "Zumbis - Feroz & Furtivo",
            "Zumbis - Vigoroso"
        ];

        function changePage(direction) {
            pages[currentPageIndex].classList.remove('active');
            currentPageIndex += direction;
           

            if (currentPageIndex >= pages.length) {
                currentPageIndex = 0;
            } else if (currentPageIndex < 0) {
                currentPageIndex = pages.length - 1;
              
            }

            pages[currentPageIndex].classList.add('active');
            pageIndicator.innerText = currentPageIndex + 1;
            
            categoryTitle.innerText = titles[currentPageIndex];
            
        }

        function comprarItem(nome, preco) {
            alert(`Você recrutou: ${nome}\nCusto: $${preco}`);
        }
    </script>
</body>
</html>