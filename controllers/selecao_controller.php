<?php
require_once '../config.php'; // conecta com o banco
session_start();

$id_usr = $_SESSION['id_usr']; // pega o id do usuario logado
$usuario = new Usuario(); // Cria um instancia da classe Usuario
$usuario->setId($id_usr); // seta o id do usuario
$usuario->load();

$especie = new especie();

$pokemons = $usuario->PegarMeusPokemons();

foreach ($pokemons as $pokemon) {

    $especie->setId($pokemon['id_esp']);
    $especie->load();

    echo "<div style='background-color:{$usuario->getCor()}'>"; 
    echo "<h3>{$pokemon['nome_pkm']}</h3>";
    echo "<p>Espécie: {$especie->getNome()}</p>";
    echo"<p>Vida: {$pokemon['vida_atual']}/{$especie->getVida()}</p>";
    echo "</div>";
   
}