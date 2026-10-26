<?php require_once '../config.php'; // conectando com o banco

    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome_usr = htmlspecialchars($_POST['nome_usr']);   
    $senha = htmlspecialchars($_POST['senha']);

try{
    
$sql = "SELECT (nome_usr, senha) from usuario where (nome_usr = $nome_usr, senha = $senha)"; // SQL SELECT tudo do usuario
$stmt = $pdo->prepare($sql); // prepara e executa o SQL
$stmt->execute();

$resultado = $stmt->fetchall(); //enfiando tudo no Resultado
} catch(PDOException $e) {
    echo "Erro ao verificar cadastro do usuário: " . $e->getMessage();
}

try{

$sql = "SELECT (id_usr) from usuario where (nome_usr = $nome_usr, senha = $senha)"; // PROCURANDO O ID DELE
$stmt = $pdo->prepare($sql); // prepara e executa o SQL
$stmt->execute();
$id = $stmt->fetchall(); //enfiando tudo no ID
}catch(PDOException $e) {
    echo "Erro ao verificar cadastro do usuário: " . $e->getMessage();
}

if (empty($resultado))
    {
        header("location:../views/login.php");
    }
else
    {
        
        $_SESSION['logado'] = true;
        $_SESSION['id_usr'] = $id;
        header("location:index.php");
    }

 