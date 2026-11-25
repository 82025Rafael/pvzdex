<?php require_once '../config.php'; // conectando com o banco

session_start(); // iniciando/retomando sessão

    //convertendo caracteres especiais em entidades HTML para evitar XSS 
    //e transformando em variaveis
    $nome_usr = $_POST['nome_usr'];   
    $senha = $_POST['senha'];
    $usuario = new Usuario(); //instanciando usuario
    
    $id_usr = $usuario->login($nome_usr, $senha);
    //

    if ($id_usr)
    {
        
        unset($_SESSION['erroLogin']);
        $_SESSION['logado'] = true;
        $_SESSION['id_usr'] = $id_usr;
        header("location:../views/index.php");
        $mensagem = "Usuário logado com sucesso: {$nome_usr}";

    }
else
    {
        $_SESSION['erroLogin'] = "Senha ou Usuário incorretos."; // errou burro
        header("location:../views/login.php");
        $mensagem = "Falha ao logar: {$nome_usr}";

    }
    //Configuração do log
    $arquivoLog = "../logs/registro.log";
    date_default_timezone_set('America/Sao_Paulo');
    $dataHora = date("Y-m-d H:i:s");
    $entrada = "[$dataHora] = $mensagem" . PHP_EOL;
    file_put_contents($arquivoLog, $entrada, FILE_APPEND);