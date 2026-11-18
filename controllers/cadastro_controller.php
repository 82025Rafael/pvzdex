<?php

require_once '../config.php'; // conectando com o banco

    $nome_usr = htmlspecialchars($_POST['nome_usr']);   
    $senha = htmlspecialchars($_POST['senha']);
    $cor_favorita = htmlspecialchars($_POST['cor']);

    $usuario = new usuario(); // CRIANDO CLASSE DO USUARIO
    $usuario->setNome($nome_usr);

    if($usuario->SeraQueExisteNome())
        {header('location:../views/cadastro.php');}
try{

    $usuario->setSenha($senha);
    $usuario->setCor($cor_favorita);
    $usuario->cadastroUsr();

// CRIAÇÃO DOS POKEMONS INICIAIS -------------------

$id_usr = $usuario->getID(); // PEGANDO O ID RECEM INSERIDO
$pokemon = new pokemon();
$especie = new especie();

//Insere Dispaervilha

$pokemon->setUsr($id_usr);

$especie->setId(11);
$especie->load();

$pokemon->setEsp($especie->getId());
$pokemon->setNome($especie->getNome());
$pokemon->setAtk1(5);
$pokemon->setAtk2(6);
$pokemon->setVida($especie->getVida());

$pokemon->insert();

//Insere Paparazzumbi


$especie->setId(35);
$especie->load();

$pokemon->setEsp($especie->getId());
$pokemon->setNome($especie->getNome());
$pokemon->setAtk1(11);
$pokemon->setAtk2(20);
$pokemon->setVida($especie->getVida());

$pokemon->insert();

//Insere Girassol

$especie->setId(21);
$especie->load();

$pokemon->setEsp($especie->getId());
$pokemon->setNome($especie->getNome());
$pokemon->setAtk1(9);
$pokemon->setAtk2(10);
$pokemon->setVida($especie->getVida());

$pokemon->insert();
//Redireciona para o index
header('Location: ../views/index.php'); // volta pro index
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






