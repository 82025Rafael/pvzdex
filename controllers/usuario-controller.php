<?php
if(isset($_POST['nome']) && isset($_POST['senha']) && isset($_POST['cor'])) {
    $nome = htmlspecialchars($_POST['nome']);
    $senha = htmlspecialchars($_POST['senha']);
    $cor = htmlspecialchars($_POST['cor']);
    }
    else {
        echo "Por favor, preencha todos os campos.";
        header("Location: cadastro.php");
    }
try{
function cadastro($nome, $senha, $cor_favorita) {
    echo "Usuário cadastrado com sucesso!";
}
catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}






/* setcookie("cor_favorita", $cor, time() + (86400 * 30), "/"); // Expira em 30 dias
    session_start();
    $usuario = new usuario($db);
    
    $usuarios_validos = $usuario->get_list();  