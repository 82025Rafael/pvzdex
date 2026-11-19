<?php
session_start(); 
// iniciando/retomando sessão 
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
     <h1>Login</h1> <br>
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