<?php

require_once '../config.php'; // conectando com o banco
    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome_usr = htmlspecialchars($_POST['nome_usr']);   
    $senha = htmlspecialchars($_POST['senha']);
    $cor = htmlspecialchars($_POST['cor']);

    $usuario = new usuario(); // CRIANDO CLASSE DO USUARIO
 try{
     $usuario->setNome($nome_usr);

$sql = "select nome_usr from usuario where nome_usr = :n"; // VERIFICANDO SE USUARIO JA EXISTE
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':n' => $nome_usr
]);
$teste = $stmt->fetch();

if(!empty($teste))
    {throw new Exception("Nome de Usuário já existente!");}

}
catch(Exception $e){

    $_SESSION['erroCadastro'] = $e->getMessage();

}



try{

    $usuario->setSenha($senha);
    $usuario->setCorfav($cor_favorita);

    $usuario->cadastroUsr();

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
//Redireciona para o index
header('Location: ../views/index.php'); // volta pro index
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






