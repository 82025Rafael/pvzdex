<?php
session_start();
require_once "../config.php";

$pokemons = $_POST['pokemon_ids'] ?? [];
$esp = new Especie();

if(!empty($pokemons) && !isset($_SESSION['pkm'])){

            $_SESSION['pkm'] = $pokemons;
            $_SESSION['turno'] = 0;
            $_SESSION['atual'] = 0;


            header("location:../views/batalha/batalha.php");
} else if(isset($_SESSION['pkm']))
{
    $_SESSION['turno']++;







 header("location:../views/batalha/batalha.php");
}
else{
    header("location:../views/selecao/selecao.php");
    exit;
}