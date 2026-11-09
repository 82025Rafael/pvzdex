<?php
session_start();
require_once '../config.php';

$usuario = new usuario();
        $usuario->setId((int) $_SESSION['id_usr']);
        $usuario->load();
        
$pokemons = $usuario->pegarMeusPokemons();