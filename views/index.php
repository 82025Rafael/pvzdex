
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plants vs Zombies</title>
    
</head>
<body>
    <h1>Bem vindo ao jogo!</h1>

    <?php
    
session_start();

if($_SESSION['logado'] == true)
    { echo "

    Aperte para começar <br>
    <button type='button' href='menuPoke.php'> JOGAR </button>
    <br>
    <br>

    Aperte para trocar de sessão <br> 
    <button type='button'> <a href='login.php' > LOGIN </a> </button>
    
    ";
    }
else{
    echo "Aperte para entrar na sessão <br> <button type='button'> <a href='login.php' > LOGIN </a> </button>";}


    ?>
</body>
</html>


