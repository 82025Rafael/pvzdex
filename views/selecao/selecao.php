<?php 
   session_start(); 
    $pokemons = $_SESSION['pokemons'] ?? []; // Pega os pokemons da sessão, se existir
    $inventario = $_SESSION['inventario'] ?? []; // Pega o inventario da sessão, se existir
    echo $_SESSION['pokemons']
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
    <div id="inventario">
        <?php
        if (!empty($inventario)) {
            foreach ($inventario as $item) {
                $nome = $item['nome_item'] ?? 'Item';
                $quantidade = $item['quantidade_item'] ?? 0;

                echo "<div class='item-card'>";
                echo "<h3>" . htmlspecialchars((string) $nome) . "</h3>";
                echo "<p>Quantidade: " . htmlspecialchars((string) $quantidade) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum item no inventário.</p>";
        }
        ?>
    </div>
    <?php
    if (!empty($pokemons)) {
        foreach ($pokemons as $pokemon) {
            echo "<div class='pokemon-card'>";
            echo "<h3>" . htmlspecialchars((string) ($pokemon['nome_pkm'] ?? '')) . "</h3>";
            echo "<p>Espécie: " . htmlspecialchars((string) ($pokemon['nome_esp'] ?? '')) . "</p>";
            echo "<p>Vida: " . htmlspecialchars((string) ($pokemon['vida_restante_pkm'] ?? 0)) . "/" . htmlspecialchars((string) ($pokemon['vida_total_pkm'] ?? 0)) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum Pokémon encontrado na sessão.</p>";
    }
    ?>

</body>
</html>
