<?php
session_start();
require_once '../../config.php';

$usr = new Usuario();
$usr->setId($_SESSION['id_usr']);
$usr->load();

$esp = new Especie();
$item = new Item();

$atk[0] = new Ataque();
$atk[1] = new Ataque();

$pkm = new Pokemon();
$tipo = new Tipo();


$pkm->setId($_SESSION['pkm'][$_SESSION['atual']]);
$pkm->load();

$esp->setId($pkm->getEsp());
$esp->load();

$tipo->setId($esp->getTipo());
$tipo->load();

$atk[0]->setId($esp->getAtk1());
$atk[0]->load();


$atk[1]->setId($esp->getAtk2());
$atk[1]->load();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batalha!</title>
</head>
<body>



<div style="border: 1px solid red; width: 30vw; height: 50vh;" class ="voces"> 
<img style="width: 75%;" src = "../<?php

    if($_SESSION['turno'] == 0)
       {echo $usr->getFoto();}
    else
        {echo $esp->getFoto();}?>">
    </div> <br>
    <a href="../../controllers/batalha_controller.php"> Continuar </a> <?php if($_SESSION['turno'] > 0) {echo $atk[0]->getNome() . " " . $atk[1]->getNome();} ?>
</body>
</html>