<?php
session_start(); 
if (isset($_SESSION['erroLogin'])) {echo $_SESSION['erroLogin'];}// iniciando/retomando sessão ?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

</head>
<body>
    <form action="../models/login_model.php" method="POST">
    <label>Usuário:</label> 
    <input type="text" id="nome_usr" name="nome_usr" required>
            <br><br>
    <label>Senha:</label>
    <input type="password" id="senha" name="senha" required>
            <br><br>
    <button type="submit">Login</button>
    <p>Não possui login? Clique <a href="cadastro.php">aqui</a> para cadastrar!</p>
</form>
</body>
</html>


<?php
$_SESSION['logado'] = false;
$_SESSION['id_usr'] = '';
?>