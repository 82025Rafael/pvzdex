<?php
    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome = htmlspecialchars($_POST['nome']);   
    $senha = htmlspecialchars($_POST['senha']);
try{
    
$usuario = get_usr($nome, $senha);
if(empty($usuario)) {
    echo "Usuário inexistente.";
    header("Location: ../views/login.html");
} else {
    session_start();
    $_SESSION['logado'] = true;
    header("Location: ../views/index.html");
}
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}

 