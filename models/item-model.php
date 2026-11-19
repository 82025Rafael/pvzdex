<?php

class item{

private string $nome_item;
private int $id_item;
private int $preco_item;
private string $desc_item;
private string $foto;
private int $id_usr;

// GETTERS

public function getNome()
{return $this->nome_item;}


public function getId()
{return $this->id_item;}


public function getPreco()
{return $this->preco_item;}


public function getDesc()
{return $this->desc_item;}


public function getFoto()
{return $this->foto;}

// SETTERS

public function setNome(string $nome_item)
{$this->nome_item = $nome_item;}


public function setId(int $id_item)
{$this->id_item = $id_item;}


public function setPreco(int $preco_item)
{$this->preco_item = $preco_item;}


public function setDesc(string $desc_item)
{$this->desc_item = $desc_item;}


public function setFoto(string $foto)
{$this->foto = $foto;}


 public function load(){ // CARREGAR DADOS DO ITEM FROM BANCO

    $pdo = connect();
    $sql = "select nome_item, preco_item, desc_item, foto, id_usr from item left join inventario v on item.id_item = v.id_item where item.id_item = :id" ; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_item
    ]);
    $item = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($item);
    
    $this->setNome($item['nome_item']); // SETANDO OQ FOI RECUPERADO
    $this->setPreco($item['preco_item']); 
    $this->setDesc($item['desc_item']);
    $this->setFoto($item['foto']); 



    } // ======================================================================================================
}



?>