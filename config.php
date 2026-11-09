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


function connect(){
    $pdo = null;
    try{
        $dsn = "mysql:host=localhost;dbname=pvzpokemon";
        $user = "root";
        $senha = '';
        $pdo = new PDO($dsn, $user, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
    }
    return $pdo;
}

spl_autoload_register(function ($className) {
    $file = __DIR__ . '/models/' . $className . '-model.php';
    if (file_exists($file)) {
        require $file; // ou require $file
    }
});

?>