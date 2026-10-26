<?php
    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome = htmlspecialchars($_POST['nome']);   
    $senha = htmlspecialchars($_POST['senha']);
    $cor = htmlspecialchars($_POST['cor']);
try{
function cadastro($nome, $senha, $cor_favorita) {
    echo "Usuário cadastrado com sucesso!";
}
header('Location: ../views/index.php');
exit;
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






