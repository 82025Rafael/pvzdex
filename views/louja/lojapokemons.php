<?php
session_start();
require_once '../../config.php';

unset($_SESSION['erro_compra']);

$usr = new Usuario();
$usr->setId($_SESSION['id_usr']);
$usr->load();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazar do Rux - Minions</title>
    <link rel="stylesheet" href="lojinha.css">
</head>
<body class="loja tema-pokemons">
 <audio src="../../Música/Laura Shigihara - Cerebrawl.mp3" autoplay loop></audio>

    <a href="lojainicial.php" class="back-btn">⬅ Voltar</a>

    <!-- Caixa de Dinheiro puxando do PHP -->
    <div class="money-box">
        💰 $ <span id="player-money"><?= $usr->getDinheiro() ?? '5000' ?></span>
    </div>

    <div class="shop-container">
        <h2> Monstros do Rux </h2>
        <h3 id="category-title">Plantas - Explosivo & Crescida</h3>
        
        <div class="carousel-wrapper">
            <button id="prev-btn" class="arrow-btn" onclick="changePage(-1)"></button>

            <div class="pages-container">

            <?php
            $esp = new especie();
            $tipo = new tipo();

            /**
             * Renderiza no máximo cinco espécies de um único tipo.
             * A validação após load() evita que registros de outro tipo,
             * eventualmente retornados pela consulta, sejam repetidos na linha.
             */
            /**
             * Converte texto legado para UTF-8 antes de escapar o HTML.
             * Isso evita que nomes com acentos inválidos desapareçam quando
             * htmlspecialchars recebe bytes que não são UTF-8 válido.
             */
            function textoLojaHtml($valor) {
                $texto = (string) $valor;

                if (preg_match('//u', $texto) !== 1) {
                    if (function_exists('mb_convert_encoding')) {
                        $texto = mb_convert_encoding($texto, 'UTF-8', 'Windows-1252');
                    } elseif (function_exists('iconv')) {
                        $convertido = @iconv('Windows-1252', 'UTF-8//IGNORE', $texto);
                        if ($convertido !== false) {
                            $texto = $convertido;
                        }
                    }
                }

                return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }

            function renderTipoNaLoja($idTipo, $esp, $tipo) {
                $idTipo = (int) $idTipo;
                $esp->setTipo($idTipo);
                $tipo->setId($idTipo);
                $tipo->load();

                $lista = $esp->TodosDoTipo();
                $idsExibidos = [];
                $quantidade = 0;

                echo '<div class="type-row">';

                foreach ($lista as $pkm) {
                    if ($quantidade >= 5) {
                        break;
                    }

                    $idEspecie = (int) $pkm['id_esp'];
                    if (isset($idsExibidos[$idEspecie])) {
                        continue;
                    }

                    $esp->setId($idEspecie);
                    $esp->load();

                    // Garante que a linha contenha somente o tipo solicitado.
                    if ((int) $esp->getTipo() !== $idTipo) {
                        continue;
                    }

                    $idsExibidos[$idEspecie] = true;
                    $nome = textoLojaHtml($esp->getNome());
                    $foto = textoLojaHtml($esp->getFoto());
                    $nomeTipo = textoLojaHtml($tipo->getNome());
                    $forca = textoLojaHtml($esp->getForca());
                    $vida = textoLojaHtml($esp->getVida());
                    $velocidade = textoLojaHtml($esp->getVeloc());
                    $preco = textoLojaHtml($esp->getPreco());
                    $id = (int) $esp->getId();

                    echo '<div class="shop-item">';
                    echo '  <div class="tooltip">Tipo: ' . $nomeTipo . '<br>ATK: ' . $forca . ' | HP: ' . $vida . ' | Vel: ' . $velocidade . '</div>';
                    echo '  <img src="../' . $foto . '" alt="' . $nome . '" class="item-img">';
                    echo '  <h3 class="item-name">' . $nome . '</h3>';
                    echo '  <p class="item-price">$ ' . $preco . '</p>';
                    echo '  <div class="buy-btn"><a href="compraMonstro.php?compra=' . $id . '">Recrutar</a></div>';
                    echo '</div>';

                    $quantidade++;
                }

                echo '</div>';
            }
            ?>

                <!-- PÁGINA 1 -->
                <div class="page active">
                    
                    <?php
                        renderTipoNaLoja(2, $esp, $tipo);
                        renderTipoNaLoja(3, $esp, $tipo);
                    ?>
                </div>
                                  
                <!-- PÁGINA 2 -->
                <div class="page">
                    
                    <?php
                        renderTipoNaLoja(4, $esp, $tipo);
                        renderTipoNaLoja(1, $esp, $tipo);
                    ?>
                </div>

                <!-- PÁGINA 3 -->
                <div class="page page-single-row">
                    
                    <?php
                        renderTipoNaLoja(5, $esp, $tipo);
                    ?>
                </div>

                <!-- PÁGINA 4 -->
                <div class="page">
                    
                    <?php
                        renderTipoNaLoja(7, $esp, $tipo);
                        renderTipoNaLoja(8, $esp, $tipo);
                    ?>
                </div>

                <!-- PÁGINA 5 -->
                <div class="page">
                    
                    <?php
                        renderTipoNaLoja(6, $esp, $tipo);
                        renderTipoNaLoja(10, $esp, $tipo);
                    ?>
                </div>

                <!-- PÁGINA 6 -->
                <div class="page page-single-row">
                    
                    <?php
                        renderTipoNaLoja(9, $esp, $tipo);
                    ?>
                </div>

            </div>

            <button id="next-btn" class="arrow-btn" onclick="changePage(1)"></button>
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

        // Navegação pelas setas do teclado
        document.addEventListener('keydown', function (event) {
            const elemento = event.target;
            const digitando = elemento && (
                elemento.isContentEditable ||
                ['INPUT', 'TEXTAREA', 'SELECT'].includes(elemento.tagName)
            );

            if (digitando || event.altKey || event.ctrlKey || event.metaKey) return;

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                changePage(-1);
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                changePage(1);
            }
        });
    </script>
</body>
</html>