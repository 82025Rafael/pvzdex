<?php
class Ataque{

    private int $id_atk;
    private string $nome;

    private int $id_tipo;

    private float $mult_dano;
    private int $priori;    //prioridade do ataque, quanto menor, mas rapido ele é
    private string $desc;   //descrição do ataque
    
    private $id_cond;
    private  $chance_cond;


    // GETTERS

    public function getId(){
        return $this->id_atk;
    }
    public function getNome(){
        return $this->nome;
    }
    public function getTipo(){
        return $this->id_tipo;
    }
    public function getDano(){
        return $this->mult_dano;
    }
    public function getPriori(){
        return $this->priori;
    }
    public function getDesc(){
        return $this->desc;
    }
    public function getCond(){
        return $this->id_cond;
    }
    public function getChance(){
        return $this->chance_cond;
    }


    // SETTERS

    public function setId(int $id_atk){
        $this->id_atk = $id_atk;
    }
    public function setNome(string $nome){
        $this->nome = $nome;
    }
    public function setTipo(int $id_tipo){
        $this->id_tipo = $id_tipo;
    }
    public function setDano(float $mult_dano){
        $this->mult_dano = $mult_dano;
    }
    public function setPriori(int $priori){
        $this->priori = $priori;
    }
    public function setDesc(string $desc){
        $this->desc = $desc;
    }
    public function setCond( $id_cond){
        $this->id_cond = $id_cond;
    }
    public function setChance($chance_cond){
        $this->chance_cond = $chance_cond;
    }

    public function load(){ // CARREGAR DADOS DO ATAQUE FROM BANCO

    $pdo = connect();
    $sql = "select nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond from ataque where id_atk = :id"; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_atk
    ]);
    $atk = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($atk);
    
    $this->setNome($atk['nome_atk']); // SETANDO OQ FOI RECUPERADO
    $this->setTipo($atk['id_tipo']); 
    $this->setDano($atk['mult_dano']); 
    $this->setPriori($atk['priori']); 
    $this->setDesc($atk['desc_atk']);
    $this->setCond($atk['id_cond']);
    $this->setChance($atk['chance_cond']); 

    } // FIM LOAD ======================================================================================================


}