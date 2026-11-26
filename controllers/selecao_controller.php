<?php
require_once __DIR__ . '/../config.php';
session_start();

if (empty($_SESSION['logado']) || empty($_SESSION['id_usr'])) {
    header('Location: ../views/login.php');
    exit;
}

$usuario = new usuario();
$usuario->setId((int) $_SESSION['id_usr']);
$usuario->load();

$pokemons = $usuario->pegarMeusPokemons();
$inventario = $usuario->pegarMeuInventario();

// Verificador de se o ULTIMO INIMIGO ja foi vencido pelo usuario
$stmt = $pdo->query("SELECT MAX(id_inim) as max_inim FROM inimigo");
$max_inim = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM log l
    JOIN usuario u ON l.nome_inim = u.nome_usr
    WHERE l.nome_usr = :nome_usr AND u.id_usr = :max_inim AND l.ganhou_perdeu = 1
");
$stmt->execute([':nome_usr' => $usuario->getNome(), ':max_inim' => $max_inim]);
$venceuJogo = $stmt->fetchColumn() > 0;

?>
