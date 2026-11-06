<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>

    <link rel="stylesheet" href="menuIni.css">

</head>
<body>
<div class="menuzin">
    <h1>Cadastro</h1>
        <form action="..\models\cadastro_model.php" method="POST">
            <label ><h3> Nome </h3> </label>
            <input type="text" id="nome" name="nome_usr" required maxlenght="100">

            <br><br>
            <label > <h3> Senha </h3> </label>
            <input type="password" id="senha" name="senha" required maxlenght="20">

            <br><br>
            <label> <b> Cor Favorita </b> </label>
            <input type="color" id="cor_favorita" name="cor">

            <br><br>
            <input type="submit" class="botaoIni" value="Cadastrar">
        </form>
</div>
</body>
</html>