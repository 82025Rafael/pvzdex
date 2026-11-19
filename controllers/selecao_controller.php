<?php
require_once '../config.php'; // conecta com o banco
session_start();

$id_usr = $_SESSION['id_usr']; // pega o id do usuario logado
$usuario = new Usuario(); // Cria um instancia da classe Usuario
$usuario->setId($id_usr); // seta o id do usuario
$usuario->load();

$especie = new especie();

$_SESSION['pokemons'] = ;
$_SESSION['inventario'] = $usuario->pegarMeuInventario();

header("location:../views/selecao/selecao.php");
