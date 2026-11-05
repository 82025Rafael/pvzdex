
<?php   session_start();  
    if($_SESSION['logado'] == false)
        { header("location:login.php"); }  ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plants vs Zombies</title>
    
    <link rel="stylesheet" href="menuIni.css">
    
</head>
<body>

<div class = "menuzin"> 
    
    <h1>Plants Vs. Zombies</h1>
    <h2>Só que Pokémon</h2>

 <br>

    <h2> Bem-vindo(a), <?= $_SESSION['nome_usr'];?>!! </h2> <br>


    Aperte para começar <br>
    <button type='button' class="botaoIni" href='menuPoke.php'> JOGAR </button>
    <br>
    <br>

    Aperte para trocar de sessão <br> 
    <button type='button' class = "botaoIni"> <a href='login.php' > LOGIN </a> </button>

</div>
  
</body>
</html>


