<?php
    require_once '../config.php'; // conectando com o banco
session_start(); 
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    $usr = new Usuario();
    $usr->setId((int) $_SESSION['id_usr']);
    $usr->load();

    $mensagem = "Usuário deslogado: {$usr->getNome()}";
    $arquivoLog = __DIR__ . '/../logs/registro.log';
    date_default_timezone_set('America/Sao_Paulo');
    $dataHora = date("Y-m-d H:i:s");
    $entrada = "[$dataHora] = $mensagem" . PHP_EOL;
    file_put_contents($arquivoLog, $entrada, FILE_APPEND);
}
// Iniciando/retomando sessão
$_SESSION['logado'] = false;
$_SESSION['id_usr'] = '';

 
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="menuIni.css">

</head>
<body>


<div class="menuzin">

    <form action="../controllers/login_controller.php" method="POST">
     <h1 style= "text-decoration:underline">Login</h1> <br>
        <label><h2>Usuário </h2></label>
        <input type="text" id="nome_usr" name="nome_usr" required>
                <br><br>
        <label> <h2>Senha </h2></label>
        <input type="password" id="senha" name="senha" required>
                <br><br> <?= $_SESSION['erroLogin'] ?? ''?>
        <button type="submit" class = "botaoIni">Login</button> <br> 

        <br> Não possui conta? Registre-se aqui!
        <a href='cadastro.php' class = "botaoLogin">Cadastrar-se </a>

    </form>

</div>

</body>
</html>