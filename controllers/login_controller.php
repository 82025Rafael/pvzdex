<?php require_once '../config.php'; // conectando com o banco

session_start(); // iniciando/retomando sessão

    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome_usr = $_POST['nome_usr'];   
    $senha = $_POST['senha'];
    $usuario = new Usuario(); //instanciando usuario

    $id_usr = $usuario->login($nome_usr, $senha);
    
    if ($id_usr)
    {
        
        unset($_SESSION['erroLogin']);
        $_SESSION['logado'] = true;
        $_SESSION['id_usr'] = $id_usr;
        header("location:../views/index.php");
    }
else
    {
        $_SESSION['erroLogin'] = "Senha ou Usuário incorretos."; // errou burro
        header("location:../views/login.php");
        
    }