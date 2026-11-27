<?php

class ItemCura extends Item{

private $quant_cura; // Itens que curam ou vida ou uma condição
private $id_cond;

public function getCura()
{return $this->quant_cura;}

public function getCond()
{return $this->id_cond;}

public function setCura($quant_cura)
{$this->quant_cura = $quant_cura;}


public function setCond($id_cond)
{$this->id_cond = $id_cond;}


public function loadCura(){

$pdo = connect();
$sql = "select quant_cura, id_cond from cura where id_item = :id"; // RECUPERANDO DADOS A PARTIR DO ID
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $this->id_item
]);
$cura = $stmt->fetch(); // ENFIA TUDO NUM VETOR

//var_dump($cura);

$this->setCura($cura['quant_cura']); // SETANDO OQ FOI RECUPERADO
$this->setCond($cura['id_cond']);

}





}


?>