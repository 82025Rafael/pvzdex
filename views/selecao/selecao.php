<?php 
    session_start(); 

    $raizProjeto = dirname(__DIR__, 2);
    chdir($raizProjeto . DIRECTORY_SEPARATOR . 'models');
    require_once $raizProjeto . DIRECTORY_SEPARATOR . 'config.php'; // conecta com o banco
    $id_usr = $_SESSION['id_usr']; // pega o id do usuario logado
    $usuario = new Usuario(); // Cria um instancia da classe Usuario
    $usuario->setId($id_usr); // seta o id do usuario
    $usuario->load();
    $pokemons = $usuario->PegarMeusPokemons(); // Pega os pokemons da sessão, se existir
    $inventario = $_SESSION['inventario'] ?? []; // Pega o inventario da sessão, se existir
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Pokemons</title>
    <link rel="stylesheet" href="selecao.css">

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
        var_dump($pokemon); // Debugging line to check the contents of $pokemon
    echo "<div class='pokemon-card'>";  
    echo "<h3>" . htmlspecialchars($pokemon['nome_pkm'] ?? '') . "</h3>";
    echo "<p>Espécie: " . htmlspecialchars($pokemon['nome_esp'] ?? '') . "</p>";
    $vidaAtual = $pokemon['vida_atual'] ?? null;
    $vidaMax = $pokemon['vida_max'] ?? null;
    if ($vidaAtual !== null && $vidaMax !== null) {
        echo "<p>Vida: " . htmlspecialchars($vidaAtual) . "/" . htmlspecialchars($vidaMax) . "</p>";
    } else {
        echo "<p>Vida: não informada</p>";
    }
    echo "</div>";
    }?>
    </div>
    </div>

</body>
</html>
