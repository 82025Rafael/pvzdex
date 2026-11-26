<?php  
 session_start();

 if(!isset($_SESSION['logado']) || $_SESSION['logado'] == false)
  { header("location:login.php"); exit; }  
        
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
    <title>Plants Vs. Zombies</title>
    
    <link rel="stylesheet" href="menuIni.css">

</head>
<body>
    <audio src="../Música/Laura Shigihara - Crazy Dave (Intro Theme).mp3" autoplay loop></audio>

<div class = "menuzin"> 
    
                    <h1><img src='../ImagensPVZ/logo.png' width=100%></h1>
                    <h2> <u> <i> Só que Pokémon </i> </u>  </h2> <br>

 

    <h3> Bem-vindo(a), <?= $usuario->getNome();?>!! </h3> <br>
    <img class="fotoPerfil" src="<?= htmlspecialchars($usuario->getFoto(), ENT_QUOTES, 'UTF-8'); ?>" width="250" height="250"> <br>

    
                    

    Aperte para começar <br><br>
    <a href='selecao/selecao.php'><button type='button' class="botaoIni"> JOGAR </button></a>
    <br>

     <br> Se esse não é você, clique aqui 
   <a href='login.php' class = "botaoLogin">Trocar de sessão </a>

</div>
  
</body>
</html>


