<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <h1>Cadastro</h1>
    <form action="..\controllers\usuario-controller.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <label for="cor_favorita">Cor Favorita:</label>
        <input type="color" id="cor_favorita" name="cor">
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>