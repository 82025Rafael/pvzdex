<?php
class condicao{
    private int $id_cond;
    private string $nome_cond;
    private string $efeito;

    public function getId(){
        return $this->id_cond;
        }
    public function getNome(){
        return $this->nome_cond;
    }
    public function getEfeito(){
        return $this->efeito;
    }

    public function setId(int $id_cond){
        $this->id_cond = $id_cond;
    }
    public function setNome(string $nome_cond){
        $this->nome_cond = $nome_cond;
    }
    public function setEfeito(string $efeito){
        $this->efeito = $efeito;
    }

    public function load(){ // CARREGAR DADOS DA CONDICAO FROM BANCO

    $pdo = connect();
    $sql = "select nome_cond, efeito from condicao where id_cond = :id"; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_cond
    ]);
    $tipo = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($tipo);
    
    $this->setNome($tipo['nome_cond']); // SETANDO OQ FOI RECUPERADO
    $this->setEfeito($tipo['efeito']);

    } // ======================================================================================================

}