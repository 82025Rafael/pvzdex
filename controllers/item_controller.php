<?php
session_start();
require_once '../config.php';


$item = new Item();

$usr = new Usuario();
$usr->setId($_SESSION['id_usr']);
$usr->load();

$pkm = new Pokemon();
$pkm->setId(1); // Sei la de onde vem isso ID_PKM
$pkm->load();

$item->setId(9); // sei la de onde vem isso ID_ITEM
$item->load();

$vent = new Inventario();
$vent->setItem($item->getId());
$vent->setUsr($usr->getId());
$vent->load();

$esp = new Especie();
$esp->setId($pkm->getEsp());
$esp->load();

switch($item->getId()){

        case 1:
            $pkm->setVida($pkm->getVida() + 6);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida()); }
        
        break;
        
        case 2:        
            $pkm->setVida($pkm->getVida() + 12);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida());}
        
        break;
        case 3:  
            $pkm->setVida($pkm->getVida() + 45);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida()); }
        
        break;
        case 4:       
           $pkm->setVida($esp->getVida());$vent->setQuant(0);
        
        break;
        case 5:
            if($pkm->getCond() == 1) {$pkm->setCond(null); }
        break;
        case 6:
            if($pkm->getCond() == 5) {$pkm->setCond(null); }
        break;
        case 7:
            if($pkm->getCond() == 2) {$pkm->setCond(null); }
        break;
        case 8:
            if($pkm->getCond() == 3) {$pkm->setCond(null); }
        break;
        case 9:
            if($pkm->getCond() == 4) {$pkm->setCond(null); }
        break;








}

$vent->setQuant($vent->getQuant()-1);
$vent->update();
// CRIAR METODO UPDATE POKEMON E IMPLEMENTAR EVOLUÇôes






?>