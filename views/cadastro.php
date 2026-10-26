<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <h1>Cadastro</h1>
    <form action="..\controllers\cadastro_controller.php" method="POST">
        <label >Nome:</label>
        <input type="text" id="nome" name="nome_usr" required>

        <br><br>
        <label >Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <br><br>
        <label>Cor Favorita:</label>
        <input type="color" id="cor_favorita" name="cor">

        <br><br>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>