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
        if($pkm->getVida() > 0)
            {$pkm->setVida($pkm->getVida() + 6);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida()); }
            }
        break;
        
        case 2:
        if($pkm->getVida() > 0){        
            $pkm->setVida($pkm->getVida() + 12);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida());}
        }
        break;
        case 3:  
        if($pkm->getVida() > 0){
            $pkm->setVida($pkm->getVida() + 45);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida()); }
        }
        break;
        case 4:       
        if($pkm->getVida() > 0)
        {
            $pkm->setVida($esp->getVida());$vent->setQuant(0);
        }
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
        case 10:
            $pkm->setCond(null);
        break;
        case 11:

        if($pkm->getVida() > 0)
        {$pkm->setVida($esp->getVida());}
        $pkm->setCond(null);
    
        break;
        case 12:
        if($pkm->getVida() == 0)
        {
            $pkm->setVida($esp->getVida()/2);
        }
        break;
        case 13:
            if($pkm->getEsp() == 11)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 14:
            if($pkm->getEsp() == 18)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 15:
            if($pkm->getEsp() == 9)
                {$pkm->setEsp($esp->getEvo());}
        break;
        
        case 16:
            if($pkm->getEsp() == 17)
                {$pkm->setEsp($esp->getEvo());}
        break;
        
        case 17:
            if($pkm->getEsp() == 21)
                {$pkm->setEsp($esp->getEvo());}
        break;
        
        case 18:
            if($pkm->getEsp() == 8)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 19:
            if($pkm->getEsp() == 47)
                {$pkm->setEsp($esp->getEvo()-3);}
        break;
        case 20:
            if($pkm->getEsp() == 47)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 21:
            if($pkm->getEsp() == 32)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 22:
            if($pkm->getEsp() == 38)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 23:
            if($pkm->getEsp() == 35)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 24:
            if($pkm->getEsp() == 41)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 25:
            if($pkm->getEsp() == 26)
                {$pkm->setEsp($esp->getEvo());}
        break;




}

$vent->setQuant($vent->getQuant()-1);

if($vent->getQuant() >= 0) { $vent->setQuant(0); $vent->delete();}

$vent->update();
// CRIAR METODO UPDATE POKEMON E IMPLEMENTAR EVOLUÇôes






?>