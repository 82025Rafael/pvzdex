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
// CRIAÇÃO DOS POKEMONS INICIAIS -------------------
$sql = "Select id_usr from usuario where nome_usr = :n"; // Select do id do usuario
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':n' => $nome_usr
]);
$id_usr = $stmt->fetch(PDO::FETCH_ASSOC); // Retorna o id do usuario como array associativo
$id_usr = $id_usr['id_usr']; // extrai apenas o valor do id

//Insere Dispaervilha
$sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, id_atk1,id_atk2) values (:id, 'Disparervilha', 11, 5, 6 )";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_usr]);
//Insere Paparazzumbi
$sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, id_atk1,id_atk2) values (:id, 'Paparazzumbi', 34, 11, 12 )";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_usr]);
//Insere Girassol
$sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, id_atk1,id_atk2) values (:id,  'Girassol', 20, 9, 10)";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_usr]);
 {
    echo "Usuário cadastrado com sucesso!";
}
//header('Location: ../views/index.php');
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






