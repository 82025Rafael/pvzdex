<?php

class ItemEvo extends Item{

private int $id_esp;

public function getEsp()
{return $this->id_esp;}

public function setEsp(int $id_esp)
{$this->id_esp = $id_esp;}

public function loadEvo()
{
    $pdo = connect();
$sql = "select id_esp from evolucao where id_item = :id"; // RECUPERANDO DADOS A PARTIR DO ID
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $this->id_item
]);
$evo = $stmt->fetch(); // ENFIA TUDO NUM VETOR

//var_dump($usuario);

$this->setEsp($evo['id_esp']); // SETANDO OQ FOI RECUPERADO
}




}

?>