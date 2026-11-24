<?php
$pokemons = $_POST['pokemon_ids'] ?? [];

if($pokemons !== []){
    foreach ($pokemons as $pokemon) {
    echo $pokemon . "<br>";
}

} else {
    header("location:../views/selecao/selecao.php");
}