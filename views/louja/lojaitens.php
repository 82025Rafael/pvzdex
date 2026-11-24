<?php
session_start();
require_once '../../config.php';


$item = new Item();
$usr = new Usuario();
$usr->setId($_SESSION['id_usr']);
$usr->load();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazar do Rux - Itens</title>
    <link rel="stylesheet" href="lojinha.css">
</head>
<body>

    <a href="lojainicial.php" class="back-btn">⬅ Voltar</a>

    <div class="shop-container">
        <h2>Itens do Rux </h2>
        <h3 id="category-title">Itens de Cura</h3>
        
        <div class="carousel-wrapper">
            <button id="prev-btn" class="arrow-btn" onclick="changePage(-1)"></button>

            <div class="pages-container">
                <!-- PÁGINA 1 -->
                <div class="page active">
                    
                    <?php
                    $id_item = 1;

                    for($id_item = 1; $id_item < 11; $id_item++) {
                        $item->setId($id_item);
                        $item->load();

                        echo' <div class="shop-item">';
                        echo' <div class="tooltip">' . $item->getDesc() . '.</div>';
                        echo' <img src="../' . $item->getFoto() .'" alt="' . $item->getNome() . '" class="item-img">';
                        echo' <h3 class="item-name">' . $item->getNome() . '</h3>';
                        echo' <p class="item-price">$' . $item->getPreco() . '</p>';
                        echo' <button class="buy-btn" > <a href=compraItem.php?compra='. $item->getId().'>Comprar </a></button>';
                        echo' </div>';
                    }


                    ?>

                    
                </div>
                                  
                <!-- PÁGINA 2 -->
                <div class="page">
                    <?php

                    for($id_item = 11; $id_item < 13; $id_item++) {
                        $item->setId($id_item);
                        $item->load();

                        echo' <div class="shop-item">';
                        echo' <div class="tooltip">' . $item->getDesc() . '.</div>';
                        echo' <img src="../' . $item->getFoto() .'" alt="' . $item->getNome() . '" class="item-img">';
                        echo' <h3 class="item-name">' . $item->getNome() . '</h3>';
                        echo' <p class="item-price">$' . $item->getPreco() . '</p>';
                        echo' <button class="buy-btn" > <a href=compraItem.php?compra='. $item->getId().'>Comprar </a></button>';
                        echo' </div>';
                    }


                    ?>
                </div>

                <!-- PÁGINA 3 -->
                <div class="page">
                    <?php

                    for($id_item = 13; $id_item < 23; $id_item++) {
                        $item->setId($id_item);
                        $item->load();

                        echo' <div class="shop-item">';
                        echo' <div class="tooltip">' . $item->getDesc() . '.</div>';
                        echo' <img src="../' . $item->getFoto() .'" alt="' . $item->getNome() . '" class="item-img">';
                        echo' <h3 class="item-name">' . $item->getNome() . '</h3>';
                        echo' <p class="item-price">$' . $item->getPreco() . '</p>';
                        echo' <button class="buy-btn" > <a href=compraItem.php?compra='. $item->getId().'>Comprar </a></button>';
                        echo' </div>';
                    }


                    ?>
                </div>

                <!-- PÁGINA 4 -->
                <div class="page">
                    <?php

                    for($id_item = 23; $id_item < 26; $id_item++) {
                        $item->setId($id_item);
                        $item->load();

                        echo' <div class="shop-item">';
                        echo' <div class="tooltip">' . $item->getDesc() . '.</div>';
                        echo' <img src="../' . $item->getFoto() .'" alt="' . $item->getNome() . '" class="item-img">';
                        echo' <h3 class="item-name">' . $item->getNome() . '</h3>';
                        echo' <p class="item-price">$' . $item->getPreco() . '</p>';
                        echo' <button class="buy-btn" > <a href=compraItem.php?compra='. $item->getId().'>Comprar </a></button>';
                        echo' </div>';
                    }


                    ?>
                </div>

            </div>

            <button id="next-btn" class="arrow-btn" onclick="changePage(1)"></button>
        </div>

        <div class="page-indicator">
            Página <span id="current-page">1</span> de 4
        </div>
    </div>

    <script>
        let currentPageIndex = 0;
        const pages = document.querySelectorAll('.page');
        const pageIndicator = document.getElementById('current-page');
        const categoryTitle = document.getElementById('category-title');

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

            if (currentPageIndex === 0 || currentPageIndex === 1) {
                categoryTitle.innerText = "Itens de Cura";
            } else {
                categoryTitle.innerText = "Itens de Evolução";
            }
        }

        function comprarItem(nome, preco) {
            alert(`Você tentou comprar: ${nome}\nPreço: $${preco}`);
        }
    </script>
</body>
</html>