<?php
session_start();
require_once '../../config.php';

$item = new Item();
$usr = new Usuario();
$usr->setId($_SESSION['id_usr']);
$usr->load();

/**
 * Converte valores legados para UTF-8 e os protege para uso no HTML.
 */
function textoLojaItensHtml($valor) {
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

/**
 * Usa a mesma montagem de caminho da loja de Pokémon.
 * No banco, foto já vem como ../ImagensPVZ/itens/...
 * Como esta página está em views/loja/, acrescentamos mais um ../,
 * formando ../../ImagensPVZ/itens/... (caminho até a raiz do projeto).
 * Não mudamos maiúsculas/minúsculas: o banco e a pasta usam "itens".
 */
function caminhoFotoItemLoja($foto) {
    $foto = trim(str_replace('\\', '/', (string) $foto));

    if ($foto === '') {
        return '';
    }

    // URLs externas, data URLs e caminhos absolutos não precisam de prefixo.
    if (preg_match('~^(?:https?:)?//|^data:|^/~i', $foto)) {
        return $foto;
    }

    // Mesmo padrão utilizado em lojapokemons.php: ../ + valor do banco.
    return '../' . $foto;
}

/**
 * Renderiza uma linha com até cinco itens, usando a mesma estrutura
 * type-row da loja de Pokémon para manter os cards alinhados.
 */
function renderLinhaItensLoja($inicio, $fim, $item) {
    echo '<div class="type-row">';

    for ($idItem = (int) $inicio; $idItem <= (int) $fim; $idItem++) {
        $item->setId($idItem);
        $item->load();

        $id = (int) $item->getId();
        $nome = textoLojaItensHtml($item->getNome());
        $descricao = textoLojaItensHtml($item->getDesc());
        $foto = textoLojaItensHtml(caminhoFotoItemLoja($item->getFoto()));
        $preco = textoLojaItensHtml($item->getPreco());

        echo '<div class="shop-item">';
        echo '  <div class="tooltip">' . $descricao . '.</div>';

        if ($foto !== '') {
            echo '  <img src="' . $foto . '" alt="' . $nome . '" class="item-img">';
        } else {
            echo '  <div class="item-img" role="img" aria-label="Imagem não cadastrada"></div>';
        }

        echo '  <h3 class="item-name">' . $nome . '</h3>';
        echo '  <p class="item-price">$ ' . $preco . '</p>';
        echo '  <div class="buy-btn"><a href="compraItem.php?compra=' . $id . '">Comprar</a></div>';
        echo '</div>';
    }

    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazar do Rux - Itens</title>
    <link rel="stylesheet" href="lojinha.css">
</head>
<body class="loja tema-itens">
    <audio src="../../Música/Laura Shigihara - Cerebrawl.mp3" autoplay loop></audio>

    <a href="lojainicial.php" class="back-btn">⬅ Voltar</a>

    <div class="money-box">
        💰 $ <span id="player-money"><?= $usr->getDinheiro() ?? '5000' ?></span>
    </div>

    <div class="shop-container">
        <h2>🧪 Itens do Rux 🌻</h2>
        <h3 id="category-title">Itens de Cura</h3>

        <div class="carousel-wrapper">
            <button id="prev-btn" class="arrow-btn" onclick="changePage(-1)"></button>

            <div class="pages-container">
                <!-- PÁGINA 1: duas linhas de cinco itens -->
                <div class="page active">
                    <?php
                    renderLinhaItensLoja(1, 5, $item);
                    renderLinhaItensLoja(6, 10, $item);
                    ?>
                </div>

                <!-- PÁGINA 2: apenas uma linha -->
                <div class="page page-single-row">
                    <?php renderLinhaItensLoja(11, 12, $item); ?>
                </div>

                <!-- PÁGINA 3: duas linhas de cinco itens -->
                <div class="page">
                    <?php
                    renderLinhaItensLoja(13, 17, $item);
                    renderLinhaItensLoja(18, 22, $item);
                    ?>
                </div>

                <!-- PÁGINA 4: apenas uma linha -->
                <div class="page page-single-row">
                    <?php renderLinhaItensLoja(23, 25, $item); ?>
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
            categoryTitle.innerText = currentPageIndex <= 1
                ? 'Itens de Cura'
                : 'Itens de Evolução';
        }

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
