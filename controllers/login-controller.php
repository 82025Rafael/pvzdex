<?php
    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome = htmlspecialchars($_POST['nome']);   
    $senha = htmlspecialchars($_POST['senha']);
try{
    
$usuario = get_usr($nome, $senha);
if(empty($usuario)) {
    echo "Usuário inexistente.";
} else {
    session_start();
    $_SESSION['logado'] = true;
    header("Location: ../views/index.php");
}
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}

 