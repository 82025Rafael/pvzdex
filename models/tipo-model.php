<?php
class Tipo{

    private string $nome_tipo;
    private int $id_tipo;
    private int $f1;
    private int $f2;
    private int $w1;
    private int $w2;

    // GETTERS

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


    // SETTERS 

    public function setNome(string $nome){
        $this->nome_tipo = $nome;
    } 
    public function setId(int $id){
        $this->id_tipo = $id;
    }
    public function setForcas(int $f1, int $f2){
        $this->f1 = $f1;
        $this->f2 = $f2;
    }
    public function setFraquezas(int $w1, int $w2){
        $this->w1 = $w1;
        $this->w2 = $w2;
    }    

    public function load(){ // CARREGAR DADOS DO TIPO FROM BANCO

    $pdo = connect();
    $sql = "select nome_tipo, f1, f2, w1, w2 from tipo where id_tipo = :id"; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_tipo
    ]);
    $tipo = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($tipo);
    
    $this->setNome($tipo['nome_tipo']); // SETANDO OQ FOI RECUPERADO
    $this->setForcas($tipo['f1'], $tipo['f2']);
    $this->setFraquezas($tipo['w1'], $tipo[w2]);

    } // ======================================================================================================
}