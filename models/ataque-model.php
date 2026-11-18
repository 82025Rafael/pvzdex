<?php
class ataque{
    private int $id_atk;
    private string $nome;

    private tipo $ipo;

    private float $mult_dano;
    private int $priori;    //prioridade do ataque, quanto menor, mas rapido ele é
    private string $desc;   //descrição do ataque
    
    private condicao $condicao;
    private float $chance_condicao;

    public function getId(){
        return $this->id_atk;
    }
    public function getNome(){
        return $this->nome;
    }
    public function getTipo(){
        return $this->ipo;
    }
    public function getMultDano(){
        return $this->mult_dano;
    }
    public function getPrioridade(){
        return $this->priori;
    }
    public function getDesc(){
        return $this->desc;
    }
    public function getCondicao(){
        return $this->condicao;
    }
    public function getChanceCondicao(){
        return $this->chance_condicao;
    }

    public function setId($id_atk){
        $this->id_atk = $id_atk;
    }
    public function setNome($nome){
        $this->nome = $nome;
    }
    public function setTipo(tipo $tipo){
        $this->ipo = $tipo;
    }
    public function setDano($dano){
        $this->mult_dano = $mult_dano;
    }
    public function setPriori($priori){
        $this->priori = $priori;
    }
    public function setDesc($desc){
        $this->desc = $desc;
    }
    public function setCond($cond){
        $this->condicao = $cond;
    }
    public function setChance($chance_cond){
        $this->chance_condicao = $chance_condicao;
    }


}