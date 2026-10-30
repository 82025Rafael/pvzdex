<?php // conectando com o banco
try{
    $dsn = "mysql:host=localhost;dbname=pvzpokemon";
    $user = "root";
    $senha = '';
    $pdo = new PDO($dsn, $user, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}

?>