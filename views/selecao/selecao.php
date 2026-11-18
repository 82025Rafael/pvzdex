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
    <div id="inventario">
    <div>
        <?php 
        foreach ($inventario as $item) {
            echo "<div class='item-card'>";
            echo "<h3>{$item['nome_item']}</h3>";
            echo "<p>Quantidade: {$item['quantidade_item']}</p>";
            echo "</div>";
    }
    ?></div>    
</div>
    <div id="pokemons">
        <div>
    <?php 
    foreach ($pokemons as $pokemon) {
    echo "<div class='pokemon-card'>"; 
    echo "<h3>{$pokemon['nome_pkm']}</h3>";
    echo "<p>Espécie: {$pokemon['nome_esp']}</p>";
    echo "<p>Vida: {$pokemon['vida_restante_pkm']}/{$pokemon['vida_total_pkm']}</p>";
    echo "</div>";
    }?>
    </div>
    </div>

</body>
</html>
