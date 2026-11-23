<?php
session_start();
require_once '../config.php';

$nome_pkm = htmlspecialchars($_POST['nome_pkm']);

$pkm = new pokemon();
$esp = new especie();
$usr = new usuario();


$esp->setId($_SESSION['id_esp_compra']);
$esp->load();

$usr->setId($_SESSION['id_usr']);
$usr->load();

$money = $usr->getDinheiro();

try{
    try{

        if($money >=  $esp->getPreco())
            {
                //SETANDO DADOS DO POKEMON
                    $pkm->setNome($nome_pkm);
                    $pkm->setEsp($esp->getId());
                    $pkm->setUsr($usr->getId());
                    $pkm->setVida($esp->getVida());


                    $usr->setDinheiro($money  - $esp->getPreco());
                    $pkm->insert();
                    $usr->updateBD();
            }
        else
            {throw new Pokemon_Exception("Saldo Insuficiente!");}
    }
    catch(Pokemon_Exception $e)
    {
        $_SESISON['erro_compra'] = $e->getMessage();
        echo $_SESISON['erro_compra'];
    }
}
catch(PDOException $e)
{echo "Erro ao realizar compra:" . $e->getMessage();}









?>
