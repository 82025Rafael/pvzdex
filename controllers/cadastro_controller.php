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

//Insere Dispaervilha
$pokemon->insert($id_usr,'Disparervilha', 11, 5, 6);

//Insere Paparazzumbi

$pokemon->insert($id_usr,'Paparazzumbi', 35, 11, 20);

//Insere Girassol

$pokemon->insert($id_usr,'Girassol', 21, 9, 10)
//Redireciona para o index
header('Location: ../views/index.php'); // volta pro index
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






