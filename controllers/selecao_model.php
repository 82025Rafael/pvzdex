<?php
require_once '../config.php';
session_start();
$user = $_SESSION['id_usr'];
$sql = "SELECT * FROM pokemon WHERE id_usr = :user"; // SQL SELECT tudo do usuario
$stmt = $pdo->prepare($sql); 
$stmt->execute([':user' => $user]);

$pokemons = $stmt->fetch(PDO::FETCH_ASSOC); // Retorna os dados em formato JSON