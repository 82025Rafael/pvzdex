<?php require_once '../config.php'; // conectando com o banco

session_start(); // iniciando/retomando sessão

    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome_usr = $_POST['nome_usr'];   
    $senha = $_POST['senha'];

try{
    
$sql = "SELECT id_usr, nome_usr, senha from usuario where nome_usr = :n and senha = :s"; // SQL SELECT tudo do usuario
$stmt = $pdo->prepare($sql); // prepara e executa o SQL
$stmt->execute([

    ':n' => $nome_usr,
    ':s' => $senha
]);

$usuario = $stmt->fetch();  //enfiando tudo no Resultado
} catch(PDOException $e) {
    echo "Erro ao verificar cadastro do usuário: " . $e->getMessage();
}

if ($usuario)
    {
        $_SESSION['logado'] = true;
        $_SESSION['id_usr'] = $usuario['id_usr'];
        unset($_SESSION['erroLogin']);
        header("location:../views/index.php");
    }
else
    {
        $_SESSION['erroLogin'] = "Senha ou Usuário incorretos.";
        header("location:../views/login.php");
        
    }

  