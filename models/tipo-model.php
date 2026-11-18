<?php
class tipo{
    private string $nome_tipo;
    private int $id_tipo;
    private tipo $f1;
    private tipo $f2;
    private tipo $w1;
    private tipo $w2;

    public function getNome(){
        return $this->nome_tipo;
    }
    public function getId(){
        return $this->id_tipo;
    }
    public function getForcas(){
        return [$this->f1, $this->f2];
    }
    public function getFraquezas(){
        return [$this->w1, $this->w2];
    }
    public function setNome($nome){
        $this->nome_tipo = $nome;
    } 
    public function setId($id){
        $this->id_tipo = $id;
    }
    public function setForcas($f1, $f2){
        $this->f1 = $f1;
        $this->f2 = $f2;
    }
    public function setFraquezas($w1, $w2){
        $this->w1 = $w1;
        $this->w2 = $w2;
    }
}