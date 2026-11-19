<?php

require_once '../config.php'; // conectando com o banco
session_start();

    $nome_usr = htmlspecialchars($_POST['nome_usr']);   
    $senha = htmlspecialchars($_POST['senha']);
    $cor_favorita = htmlspecialchars($_POST['cor']);

    $usuario = new usuario(); // CRIANDO CLASSE DO USUARIO
    $usuario->setNome($nome_usr);

    if($usuario->SeraQueExisteNome()) // ESSE USUARIO JA EXISTE COM ESSE NOME????
        {header('location:../views/cadastro.php');
        exit;}


 $destino = "../uploads/"; // PRA ONDE AS FOTOS VAO SER UPADAS
    if (!is_dir($destino)) // SERA QUE EXISTE O DESTINO?
        {mkdir($destino, 0777, true);} // CRIA

    if(isset($_FILES["arquivo"])){ // FEZ UPLOAD?
    $arquivo = $_FILES["arquivo"];

    $arquivo;
     ; // AONDE ELE ESTARÁ NO FIM
    $extensao = strtolower(pathinfo($arquivo["name"], PATHINFO_EXTENSION)); // 

    $NomeArquivo = uniqid() . "." . $extensao;
    $caminho = $destino . $NomeArquivo;

    $tamMax = 4 * 1024 * 1024; // 4 MB

    try{
        if($arquivo["size"] > $tamMax)
        throw new Exception("Arquivo Muito Grande!");
    }
    catch(Exception $e)
    {
        $_SESSION['erroCadastro'] = $e->getMessage();
        header("location:../views/cadastro.php");
        exit;
    }
    
    $extensoesPermitidas = ["png", "jpg", "gif"];

    try{

    if(in_array($extensao, $extensoesPermitidas))
        {
          move_uploaded_file($arquivo['tmp_name'], $caminho);

        }
    else{
        throw new Exception("Tipo de arquivo inválido");
       
    }    
    }
    catch(Exception $e)
    {
        $_SESSION['erroCadastro'] = $e->getMessage();
        header("location:../views/cadastro.php");
         exit;
    }
     // VOLTANDO A EXCESSAO
   
    
    }

    $usuario->setFoto($caminho);



try{

    $usuario->setSenha($senha);
    $usuario->setCor($cor_favorita);
    $usuario->cadastroUsr();

// CRIAÇÃO DOS POKEMONS INICIAIS -------------------

$id_usr = $usuario->getID(); // PEGANDO O ID RECEM INSERIDO
$pokemon = new pokemon();
$especie = new especie();

//Insere Disparervilha

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






