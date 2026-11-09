
<?php  
 session_start();

 if($_SESSION['logado'] == false)
  { header("location:login.php"); }  
        
  require_once '../config.php';   
        
        $usuario = new usuario();
        $usuario->setId((int) $_SESSION['id_usr']);
        $usuario->load();
        
        
        
        
        ?>

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

 

    <h3> Bem-vindo(a), <?= $usuario->getNome();?>!! </h3> <br>

    
                    

    Aperte para começar <br><br>
    <button type='button' class="botaoIni" href='menuPoke.php'> JOGAR </button>
    <br>

     <br> Se esse não é você, clique aqui 
   <a href='login.php' class = "botaoLogin">Trocar de sessão </a>

</div>
  
</body>
</html>


