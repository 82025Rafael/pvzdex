<?php
session_start();
require_once "../config.php";

$pokemons = $_POST['pokemon_ids'] ?? [];

$ini = new Inimigo();
$ini->setId(1);
$inimigo = $ini->pegarMeusPokemons();

$esp = new Especie();

if(!empty($pokemons) && !isset($_SESSION['pkm'])){

            $_SESSION['pkm'] = $pokemons;
            $_SESSION['ini'] = $inimigo[0]['id_pkm'];
            $_SESSION['turno'] = 0;
            $_SESSION['atual'] = 0;
            $_SESSION['atual_inimigo'] = 0;

            header("location:../views/batalha/batalha.php");
            exit;
} else if(isset($_SESSION['pkm']))
{
    $_SESSION['turno']++;







 header("location:../views/batalha/batalha.php");
 exit;
}
else{
    header("location:../views/selecao/selecao.php");
    exit;
}