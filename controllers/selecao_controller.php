<?php
require_once __DIR__ . '/../config.php';
session_start();

if (empty($_SESSION['logado']) || empty($_SESSION['id_usr'])) {
    header('Location: ../views/login.php');
    exit;
}

$usuario = new usuario();
$usuario->setId((int) $_SESSION['id_usr']);

$pokemons = $usuario->pegarMeusPokemons();
$inventario = $usuario->pegarMeuInventario();

require __DIR__ . '/../views/selecao/selecao.php';
