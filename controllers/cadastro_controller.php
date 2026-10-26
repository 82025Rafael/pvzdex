<?php

require_once '../config.php'; // conectando com o banco


    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome_usr = htmlspecialchars($_POST['nome_usr']);   
    $senha = htmlspecialchars($_POST['senha']);
    $cor = htmlspecialchars($_POST['cor']);
try{
$sql = "insert into usuario (nome_usr, senha, cor_favorita) values (:n, :s, :c)"; // INSERT SQL
$stmt = $pdo->prepare($sql); // Substituindo os valores provisorios

$stmt->execute([

        ':n' => $nome_usr,
        ':s' => $senha,
        ':c' => $cor

]);

 {
    echo "Usuário cadastrado com sucesso!";
}
header('Location: ../views/index.php');
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






