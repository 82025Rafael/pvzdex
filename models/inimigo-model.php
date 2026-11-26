<?php

class Inimigo extends Usuario{

private int $id_inim;
private string $txt_entra;
private string $txt_mata;
private string $txt_morre;

// GETTERS


public function getInim()
{return $this->id_inim;}

public function getEntra()
{return $this->txt_entra;}

public function getMata()
{return $this->txt_mata;}

public function getMorre()
{return $this->txt_morre;}

// SETTERS

public function setInim(int $id_inim)
{$this->id_inim = $id_inim;}

public function setEntra(string $txt_entra)
{$this->txt_entra = $txt_entra;}

public function setMata(string $txt_mata)
{$this->txt_mata = $txt_mata;}

public function setMorre(string $txt_morre)
{$this->txt_morre = $txt_morre;}

public function loadInim(){ // CARREGAR DADOS DO INIMIGO FROM BANCO

$pdo = connect();
$sql = "select txt_entra, txt_mata, txt_morre from inimigo where id_inim = :id"; // RECUPERANDO DADOS A PARTIR DO ID
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $this->id_inim
]);
$inim = $stmt->fetch(); // ENFIA TUDO NUM VETOR

//var_dump($inim);

$this->setEntra($inim['txt_entra']); // SETANDO OQ FOI RECUPERADO
$this->setMata($inim['txt_mata']);
$this->setMorre($inim['txt_morre']);
} // ======================================================================================================


}


?>