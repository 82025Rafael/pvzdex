<?php 
   session_start(); 
    $pokemons = $_SESSION['pokemons'] ?? []; // Pega os pokemons da sessão, se existir
    $inventario = $_SESSION['inventario'] ?? []; // Pega o inventario da sessão, se existir
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Pokemons</title>
</head>
<body>
    <h1>Selecione seu time!</h1>
    <p>Escolha 6 Pokemons para sua equipe:</p>

    <div>Equipe selecionada</div>
    <div id="pokemons">
        <?php
        if (!empty($pokemons)) {
            foreach ($pokemons as $pokemon) {
                echo "<div class='pokemon-card'>";
                echo "<h3>" . $pokemon['nome_pkm']  . "</h3>";
                echo "<p>Espécie: " . $pokemon['nome_esp'] . "</p>";
            echo "<p>Vida: " .$pokemon['vida_restante_pkm'] . "/" . $pokemon['vida_total_pkm'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum Pokémon encontrado na sessão.</p>";
    }
    ?></div>

    <div id="inventario">
        <?php
        if (!empty($inventario)) {
            foreach ($inventario as $item) {
                $nome = $item['nome_item'] ?? 'Item';
                $quantidade = $item['quantidade_item'] ?? 0;

                echo "<div class='item-card'>";
                echo "<h3>" . $nome . "</h3>";
                echo "<p>Quantidade: " . $quantidade . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum item no inventário.</p>";
        }
        ?>
    </div>
</body>
</html>
