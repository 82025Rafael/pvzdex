<?php
require_once '../config.php'; // conecta com o banco
session_start();

$id_usr = $_SESSION['id_usr']; // pega o id do usuario logado
$usuario = new Usuario(); // Cria um instancia da classe Usuario
$usuario->setId($id_usr); // seta o id do usuario

$pokemons = $usuario->PegarMeusPokemons();

foreach ($pokemons as $pokemon) {
    echo "<div class='pokemon-card'>"; 
    echo "<h3>{$pokemon['nome_pkm']}</h3>";
    echo "<p>Espécie: {$pokemon['nome_esp']}</p>";
    echo "<p>Vida: {$pokemon['vida_restante_pkm']}/{$pokemon['vida_total_pkm']}</p>";
    echo "</div>";
}