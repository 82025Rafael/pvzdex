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

//Insere Dispaervilha

//Insere Paparazzumbi
$sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, id_atk1,id_atk2) values (:id, 'Paparazzumbi', 34, 11, 12 )";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_usr]);
//Insere Girassol
$sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, id_atk1,id_atk2) values (:id,  'Girassol', 20, 9, 10)";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_usr]);
//Redireciona para o index
header('Location: ../views/index.php'); // volta pro index
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






