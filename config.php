<?php
try{
    $dsn = 'mysql:host=localhost;"dbname=db"';
    $user = 'root';
    $senha = '';
    $db = new PDO($dsn, $user, $senha);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}