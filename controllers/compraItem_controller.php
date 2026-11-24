<?php
session_start();
require_once '../config.php';

$quant_item = htmlspecialchars($_POST['quant_item']);

$vent = new inventario();
$item = new item();
$usr = new usuario();


$item->setId($_SESSION['id_item_compra']);
$item->load();

$usr->setId($_SESSION['id_usr']);
$usr->load();
$vent->setQuant($quant_item);

$money = $usr->getDinheiro();

try{
    try{

        if($money >=  $item->getPreco() * $vent->getQuant())
            {
                //SETANDO DADOS DO POKEMON
                    
                    $vent->setItem($item->getId());
                    $vent->setUsr($usr->getId());

                    $usr->setDinheiro($money  - $item->getPreco() * $vent->getQuant());
                    $vent->insert();
                    $usr->updateBD();
                    header("location: ../views/louja/lojaitens.php");
                    unset($_SESSION['erro_compra']);
            }
        else
            {throw new Pokemon_Exception("Saldo Insuficiente!");}
    }
    catch(Pokemon_Exception $e)
    {
        $_SESSION['erro_compra'] = $e->getMessage();
        header("location: ../views/louja/compraItem.php?compra=" . $_SESSION['id_item_compra']);
        
    }
}
catch(PDOException $e)
{echo "Erro ao realizar compra:" . $e->getMessage();}









?>
